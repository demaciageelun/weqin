<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\controllers\admin;


use app\core\response\ApiCode;
use app\forms\admin\MessageRemindSettingForm;
use app\forms\admin\SmsCaptchaForm;
use app\forms\admin\user\BatchPermissionForm;
use app\forms\admin\user\PermissionsGroupEditForm;
use app\forms\admin\user\PermissionsGroupForm;
use app\forms\admin\user\RegisterAuditForm;
use app\forms\admin\user\UserBindForm;
use app\forms\admin\user\UserEditForm;
use app\forms\admin\user\UserForm;
use app\forms\admin\user\UserGroupEditForm;
use app\forms\admin\user\UserGroupForm;
use app\forms\common\CommonAuth;
use app\forms\common\CommonUser;
use app\forms\common\attachment\CommonAttachment;
use app\models\User;

class UserController extends AdminController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
            } else {
                $form = new UserForm();
                $form->attributes = \Yii::$app->request->get();

                return $this->asJson($form->getList());
            }
        } else {
            return $this->render('index');
        }
    }

    /**
     * 管理员用户信息
     * @return \yii\web\Response
     */
    public function actionUser()
    {
        /* @var User $user */
        $user = User::find()->where(['id' => \Yii::$app->user->id, 'is_delete' => 0])->with('identity')->one();

        if (!$user) {
            $logout = \Yii::$app->user->logout();
            return [
                'code' => ApiCode::CODE_NOT_LOGIN,
                'msg' => '请求成功'
            ];
        }
        $adminInfo = CommonUser::getAdminInfo();

        $newUser = [
            'nickname' => $user->nickname,
            'mobile' => $user->mobile,
            'app_max_count' => $adminInfo->app_max_count == -1 ? '无限制' : $adminInfo->app_max_count,
            'username' => $user->username,
            'identity' => $user->identity->toArray()
        ];

        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'user' => $newUser,
                'admin_info' => $adminInfo,
                'expired_data' => $this->getExpiredData($adminInfo)
            ]
        ]);
    }

    private function getExpiredData($adminInfo)
    {
        $messageRemind = new MessageRemindSettingForm();
        $newDay = (strtotime($adminInfo->expired_at) - time()) / (24 * 60 * 60);
        if ($newDay <= 0) {
            $newDay = 0;
        } elseif ($newDay > 0 && $newDay <= 1) {
            $newDay = 1;
        } else {
            $newDay = floor($newDay);
        }

        $setting = $messageRemind->getSetting();

        $data = [
            'is_show_message' => false,
            'expired_day' => $newDay,
            'expired_at' => $adminInfo->expired_at,
            'id' => $adminInfo->id
        ];

        if ($setting['status'] == 1 && $adminInfo->expired_at != '0000-00-00 00:00:00' && $adminInfo->expired_at > date('Y-m-d H:i:s') && $newDay < $setting['day']) {
            $data['is_show_message'] = true;
            $data = array_merge($data, $setting);
        }

        return $data;
    }

    /**
     * 账户编辑
     * @return array|string|\yii\web\Response
     */
    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $formData = json_decode(\Yii::$app->request->post('form'), true);
                $form = new UserEditForm();
                $form->user_id = isset($formData['id']) ? $formData['id'] : '';
                $form->attributes = $formData['adminInfo'];
                $form->attributes = $formData;
                $form->page = \Yii::$app->request->post('page');
                $form->isCheckExpired = \Yii::$app->request->post('isCheckExpired');
                $form->isAppMaxCount = \Yii::$app->request->post('isAppMaxCount');
                $res = $form->save();

                return $res;
            } else {
                $form = new UserForm();
                $form->attributes = \Yii::$app->request->get();
                $detail = $form->getDetail();

                return $this->asJson($detail);
            }
        } else {
            return $this->render('edit');
        }
    }

    /**
     * 账户绑定
     * @return array|string|\yii\web\Response
     */
    public function actionBind()
    {
        if (\Yii::$app->request->isAjax) {
            $post = \Yii::$app->request->post();
            $form = new UserBindForm();
            $form->attributes = $post;
            $res = $form->bind();

            return $res;
        } else {
            return $this->render('bind');
        }
    }

    /**
     * 管理员账号解绑
     * @return \yii\web\Response
     */
    public function actionDestroyBind()
    {
        $form = new UserForm();
        $form->id = \Yii::$app->request->post('id');
        $res = $form->destroy_bind();

        return $this->asJson($res);
    }

    /**
     * 管理员账号删除
     * @return \yii\web\Response
     */
    public function actionDestroy()
    {
        $form = new UserForm();
        $form->id = \Yii::$app->request->post('id');
        $res = $form->destroy();

        return $this->asJson($res);
    }

    /**
     * 修改密码
     * @return \yii\web\Response
     */
    public function actionEditPassword()
    {
        $form = new UserForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->editPassword();

        return $this->asJson($res);
    }

    /**
     * 当前账号修改密码
     * @return \yii\web\Response
     */
    public function actionAdminEditPassword()
    {
        $form = new UserForm();
        $form->attributes = \Yii::$app->request->post();
        $form->id = \Yii::$app->user->id;
        $res = $form->editPassword();

        return $this->asJson($res);
    }

    /**
     * 注册账号审核列表
     */
    public function actionRegister()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
            } else {
                $form = new RegisterAuditForm();
                $form->attributes = \Yii::$app->request->get();
                $list = $form->getList();

                return $this->asJson($list);
            }
        } else {
            return $this->render('register');
        }
    }

    /**
     * 账号审核
     * @return \yii\web\Response
     */
    public function actionRegisterAudit()
    {
        $form = new RegisterAuditForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->audit());
    }

    /**
     * 注册审核账号删除
     */
    public function actionRegisterDestroy()
    {
        $form = new RegisterAuditForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->destroy();

        return $this->asJson($res);
    }


    public function actionPermissions()
    {
        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'permissions' => CommonAuth::getPermissionsList(),
                'storage' => CommonAttachment::getCommon()->getStorage()
            ]
        ]);
    }

    public function actionMe()
    {
        return $this->render('me');
    }

    public function actionCloudAccount()
    {
        $userNum = CommonAuth::getChildrenNum();
        $res = \Yii::$app->cloud->auth->getAuthInfo();

        $accountNum = $res['host']['account_num'];
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'account_num' => $accountNum,
                'user_num' => $userNum
            ]
        ];
    }

    public function actionUpdateMobile()
    {
        $form = new SmsCaptchaForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->updateMobile());
    }

    /**
     * 批量设置账户权限
     * @return \yii\web\Response
     */
    public function actionBatchPermission()
    {
        $form = new BatchPermissionForm();
        $form->formData = \Yii::$app->request->post('form');

        return $this->asJson($form->save());
    }

    public function actionSaveGlobalPermission()
    {
        $form = new BatchPermissionForm();
        $form->globalData = \Yii::$app->request->post('globalData');

        return $this->asJson($form->saveGlobalPermission());
    }

    public function actionGetGlobalPermission()
    {
        $form = new BatchPermissionForm();

        return $this->asJson($form->getGlobalPermission());
    }

    public function actionUserGroup()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new UserGroupForm();
            $form->attributes = \Yii::$app->request->get();
            $res = $form->getList();

            return $this->asJson($res);
        }

        return $this->render('user-group');
    }

    public function actionUserGroupEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new UserGroupEditForm();
                $form->attributes = \Yii::$app->request->post();
                $res = $form->save();

                return $this->asJson($res);
            } else {
                $form = new UserGroupForm();
                $form->attributes = \Yii::$app->request->get();
                $res = $form->getDetail();

                return $this->asJson($res);
            }
        }

        return $this->render('user-group-edit');
    }

    public function actionUserGroupDestroy()
    {
        $form = new UserGroupForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->destroy();

        return $this->asJson($res);
    }

    public function actionPermissionsGroup()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new PermissionsGroupForm();
            $form->attributes = \Yii::$app->request->get();
            $res = $form->getList();

            return $this->asJson($res);
        }

        return $this->render('permissions-group');
    }

    public function actionPermissionsGroupEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new PermissionsGroupEditForm();
                $form->attributes = \Yii::$app->request->post();
                $res = $form->save();

                return $this->asJson($res);
            } else {
                $form = new PermissionsGroupForm();
                $form->attributes = \Yii::$app->request->get();
                $res = $form->getDetail();

                return $this->asJson($res);
            }
        }

        return $this->render('permissions-group-edit');
    }

    public function actionPermissionsGroupDestroy()
    {
        $form = new PermissionsGroupForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->destroy();

        return $this->asJson($res);
    }

    public function actionBatchSettingUserGroup()
    {
        $form = new BatchPermissionForm();
        $form->attributes = \Yii::$app->request->post();

        return $this->asJson($form->batchSettingUserGroup());
    }
}
