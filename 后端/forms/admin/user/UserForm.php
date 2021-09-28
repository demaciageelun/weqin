<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\forms\admin\user;

use app\core\response\ApiCode;
use app\forms\common\attachment\CommonAttachment;
use app\forms\common\CommonAuth;
use app\models\AdminInfo;
use app\models\Mall;
use app\models\Model;
use app\models\User;
use app\models\UserIdentity;
use yii\db\ActiveQuery;

class UserForm extends Model
{
    public $page;
    public $id;
    public $password;
    public $keyword;
    public $is_super_admin = 0;
    public $type;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['password'], 'string', 'min' => 6, 'max' => 16],
            [['id', 'is_super_admin'], 'integer'],
            [['keyword', 'type'], 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'password' => '密码',
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = User::find()->alias('u')->where([
            'u.is_delete' => 0,
            'u.mall_id' => \Yii::$app->user->identity->mall_id
        ])->with(['adminInfo.userGroup.permissionsGroup', 'mall' => function ($query) {
            $query->andWhere(['is_delete' => 0,]);
        }]);

        if (!$this->is_super_admin) {
            $query->joinWith(['identity i' => function ($query) {
                $query->andWhere(['i.is_admin' => 1]);
            }]);
        }

        $isWe7 = is_we7();
        $query->joinWith(['adminInfo ad' => function ($query) use ($isWe7) {
            // 判断 独立版 微擎版
            if ($isWe7) {
                $query->andWhere(['>', 'ad.we7_user_id', 0]);
            } else {
                $query->andWhere(['ad.we7_user_id' => 0]);
            }

            if ($this->type == '未到期') {
                $query->andWhere([
                    'or',
                    ['=', 'ad.expired_at', '0000-00-00 00:00:00'],
                    ['>', 'ad.expired_at', date('Y-m-d H:i:s')],
                ]);
            } else if ($this->type == '已到期') {
                $query->andWhere([
                    'and',
                    ['!=', 'ad.expired_at', '0000-00-00 00:00:00'],
                    ['<=', 'ad.expired_at', date('Y-m-d H:i:s')],
                ]);
            }
        }]);

        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 'u.username', $this->keyword],
                ['like', 'u.mobile', $this->keyword],
                ['like', 'ad.remark', $this->keyword],
            ]);
        }

        $list = $query->page($pagination)->orderBy('created_at DESC')->asArray()->all();
        foreach ($list as &$item) {
            $item['create_app_count'] = count($item['mall']);

            if (($item['adminInfo']['expired_at'] > date('Y-m-d H:i:s')) || $item['adminInfo']['expired_at'] == '0000-00-00 00:00:00') {
                $item['expired_type'] = '未到期';
            } else {
                $item['expired_type'] = '已到期';
            }

            $item['user_group_name'] = '';
            $item['permissions_group_name'] = '';
            if ($item['adminInfo']['userGroup']) {
                $item['user_group_name'] = $item['adminInfo']['userGroup']['name'];
                $item['permissions_group_name'] = $item['adminInfo']['userGroup']['permissionsGroup']['name'];
            }

            $item['subjoin_permissions_number'] = 0;

            if ($item['adminInfo']['subjoin_permissions']) {
                $subjoinPermissions = json_decode($item['adminInfo']['subjoin_permissions'], true);
                $item['subjoin_permissions_number'] = count($subjoinPermissions['mall']) + count($subjoinPermissions['plugin']);
            }
        }
        unset($item);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    public function getDetail()
    {
        $detail = User::find()->where(['id' => $this->id])->with(['identity', 'adminInfo.userGroup'])->asArray()->one();

        if ($detail) {
            $mallPermissions = [];
            $pluginPermissions = [];

            try {
                $permissions = CommonAuth::getPermissionsList();
                $all = json_decode($detail['adminInfo']['permissions'], true);
                $newAll = array_flip($all);

                
                foreach ($permissions['mall'] as $item) {
                    if (isset($newAll[$item['name']])) {
                        $mallPermissions[] = $item['name'];
                    }
                }

                foreach ($permissions['plugins'] as $item) {
                    if (isset($newAll[$item['name']])) {
                        $pluginPermissions[] = $item['name'];
                    }
                }
            }catch(\Exception $exception) {

            }

            $detail['adminInfo']['mall_permissions'] = $mallPermissions;
            $detail['adminInfo']['plugin_permissions'] = $pluginPermissions;
            $detail['adminInfo']['secondary_permissions'] = CommonAuth::getSecondaryPermissionList($detail['adminInfo']['secondary_permissions']);

            $detail['user_group_name'] = '';
            $detail['user_group_id'] = null;
            if (isset($detail['adminInfo']['userGroup'])) {
                $detail['user_group_name'] = $detail['adminInfo']['userGroup']['name'];
                $detail['user_group_id'] = $detail['adminInfo']['userGroup']['id'];
            }

            // 附加权限
            $subjoinPermissions = [
                'mall' => [],
                'plugin' => [],
                'secondary' => [
                    'attachment' => ["1", "2", "3", "4"],
                    'template' =>  [
                        'is_all' => '0',
                        'use_all' => '0',
                        'list' => [],
                        'use_list' => [],
                    ]
                ]
            ];
            if ($detail['adminInfo']['subjoin_permissions']) {
                $subjoinPermissions = json_decode($detail['adminInfo']['subjoin_permissions'], true);
                $mallKeyName = [];
                $pluginKeyName = [];
                foreach ($permissions['mall'] as $item) {
                    $mallKeyName[$item['name']] = $item['display_name'];
                }
                foreach ($permissions['plugins'] as $item) {
                    $pluginKeyName[$item['name']] = $item['display_name'];
                }
                $subjoinPermissions['list'] = [];
                foreach ($subjoinPermissions['mall'] as $item) {
                    if (isset($mallKeyName[$item])) {
                        $subjoinPermissions['list'][] = $mallKeyName[$item];
                    }
                }
                foreach ($subjoinPermissions['plugin'] as $item) {
                    if (isset($pluginKeyName[$item])) {
                        $subjoinPermissions['list'][] = $pluginKeyName[$item];
                    }
                }
            }

            $detail['subjoin_permissions'] = $subjoinPermissions;

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $detail,
                ]
            ];
        }

        return [
            'code' => ApiCode::CODE_ERROR,
            'msg' => '请求失败',
        ];
    }

    public function destroy()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            /** @var User $user */
            $user = User::find()->where(['id' => $this->id])
                ->with('identity')->one();

            if (!$user) {
                throw new \Exception('数据异常,该条数据不存在');
            }

            if ($user->identity->is_super_admin) {
                throw new \Exception('超级管理员账号不可删除');
            }

            $user->is_delete = 1;
            $res = $user->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($user));
            }
            /** @var UserIdentity $userIdentity */
            $userIdentity = UserIdentity::find()->where(['user_id' => $user->id])->one();
            $userIdentity->is_delete = 1;
            $res = $userIdentity->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($userIdentity));
            }

            /** @var AdminInfo $adminInfo */
            $adminInfo = AdminInfo::find()->where(['user_id' => $user->id])->one();
            $adminInfo->is_delete = 1;
            $res = $adminInfo->save();

            if (!$res) {
                throw new \Exception($this->getErrorMsg($userIdentity));
            }
            
            $res = Mall::updateAll([
                'is_disable' => 1,
            ], [
                'user_id' => $user->id,
                'is_delete' => 0,
            ]);


            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '删除成功',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function destroy_bind()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
//        $app_admin = false;
//        $permission_arr = \Yii::$app->role->getPermission();
//        if (!is_array($permission_arr) && $permission_arr) {
//            $app_admin = true;
//        } else {
//            foreach ($permission_arr as $value) {
//                if ($value == 'app_admin') {
//                    $app_admin = true;
//                }
//            }
//        }
//        if (!$app_admin) {
//            return [
//                'code' => ApiCode::CODE_ERROR,
//                'msg' => '无权限操作',
//            ];
//        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $userIdentity = UserIdentity::find()->where(['user_id' => $this->id])->one();
            $userIdentity->is_admin = 0;
            $res = $userIdentity->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($userIdentity));
            }

            $adminInfo = AdminInfo::find()->where(['user_id' => $this->id])->one();
            $adminInfo->is_delete = 1;
            $res = $adminInfo->save();

            if (!$res) {
                throw new \Exception($this->getErrorMsg($adminInfo));
            }

            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '解绑成功',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function editPassword()
    {
        $user = User::find()->alias('u')->where(['u.id' => $this->id, 'u.is_delete' => 0])->one();

        if (!$user) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '用户不存在',
            ];
        }

        $user->password = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $res = $user->save();

        if ($res) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '密码修改成功',
            ];
        }

        return [
            'code' => ApiCode::CODE_ERROR,
            'msg' => '密码修改失败',
        ];
    }
}
