<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\forms\admin\user;

use app\core\newsms\Sms;
use app\core\response\ApiCode;
use app\forms\common\CommonAdminUser;
use app\forms\common\CommonAuth;
use app\forms\common\CommonOption;
use app\models\AdminInfo;
use app\models\AdminRegister;
use app\models\Model;
use app\models\Option;
use app\models\User;
use app\models\UserIdentity;
use Overtrue\EasySms\Message;

class RegisterAuditForm extends Model
{
    public $page;
    public $id;
    public $status;
    public $keyword;

    public function rules()
    {
        return [
            [['page'], 'default', 'value' => 1],
            [['id', 'status'], 'integer'],
            [['keyword'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'status' => '审核状态'
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = AdminRegister::find()->where(['is_delete' => 0]);

        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 'username', $this->keyword],
                ['like', 'mobile', $this->keyword],
                ['like', 'name', $this->keyword],
                ['like', 'wechat_id', $this->keyword],
            ]);
        }

        $list = $query->page($pagination)->orderBy(['created_at' => SORT_DESC])->asArray()->all();


        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    public function destroy()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $adminRegister = AdminRegister::findOne($this->id);

        if (!$adminRegister) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '数据异常,该条数据不存在',
            ];
        }

        $adminRegister->is_delete = 1;
        $res = $adminRegister->save();

        if ($res) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '删除成功',
            ];
        }

        return [
            'code' => ApiCode::CODE_ERROR,
            'msg' => '删除失败',
        ];
    }

    public function audit()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $adminRegister = AdminRegister::findOne([
            'id' => $this->id,
            'is_delete' => 0,
        ]);

        if (!$adminRegister) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '数据异常,该条数据不存在',
            ];
        }
        if ($adminRegister->status != 0) {
            $statusText = $adminRegister->status == 1 ? '已通过' : '未通过';
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '该数据已被处理：审核' . $statusText
            ];
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $adminRegister->status = $this->status;
            $res = $adminRegister->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($adminRegister));
            }
            // 审核通过
            if ($this->status == 1) {
                $this->checkAuth();
                $expiredAt = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60);
                /** @var AdminInfo $adminUser */
                $adminUser = CommonAdminUser::createAdminUser([
                    'username' => $adminRegister->username,
                    'password' => $adminRegister->password,
                    'mobile' => $adminRegister->mobile,
                    'app_max_count' => 1,
                    'remark' => $adminRegister->remark,
                    'we7_user_id' => 0,
                    'expired_at' => $expiredAt,
                    'permissions' => [],
                    'secondary_permissions' => []
                ]);
                \Yii::$app->queue->delay(strtotime($expiredAt))->push(new UserUpdateJob([
                    'user_id' => $adminUser->user_id
                ]));
            }
            $transaction->commit();
            $smsError = null;
            try {
                $indSetting = CommonOption::get(Option::NAME_IND_SETTING);
                if ($indSetting) {
                    $tplKey = $adminRegister->status == 1 ? 'register_success_tpl_id' : 'register_fail_tpl_id';
                    \Yii::$app->sms->module(Sms::MODULE_ADMIN)->send($adminRegister->mobile, new Message([
                        'template' => $indSetting['ind_sms']['aliyun'][$tplKey],
                        'data' => [
                            'name' => $adminRegister->username,
                        ],
                    ]));
                }
            } catch (\Exception $exception) {
                $smsError = $exception->getMessage();
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功',
                'data' => [
                    'sms_error' => $smsError,
                ],
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

    private function checkAuth()
    {
        $res = \Yii::$app->cloud->auth->getAuthInfo();
        $userNum = CommonAuth::getChildrenNum();

        $accountNum = $res['host']['account_num'];

        // 总管理员自身不算入总数限制 -1
        if ($accountNum > -1 && $userNum >= $accountNum) {
            throw new \Exception('子账户数量超出限制');
        }
    }
}
