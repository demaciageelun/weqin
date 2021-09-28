<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/7/22
 * Time: 16:54
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\controllers\mall;


use app\core\response\ApiCode;
use app\forms\common\attachment\CommonAttachment;
use app\forms\mall\attachment\AttachmentForm;

class AttachmentController extends MallController
{
    public function actionAttachment()
    {
        if (\Yii::$app->request->isAjax) {
            $user = \Yii::$app->user->identity;
            $common = CommonAttachment::getCommon($user, \Yii::$app->mall);
            try {
                $list = $common->getAttachmentList();
                $attachment = $common->getAttachment();
            } catch (\Exception $exception) {
                $list = [];
                $attachment = null;
            }
            $storage = $common->getStorage();
            return $this->asJson([
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'list' => $list,
                    'storageTypes' => $common->getStorageType(),
                    'storage' => $attachment ? $storage[$attachment->type] : '暂无配置',
                    'nickname' => $common->user->nickname
                ]
            ]);
        } else {
            return $this->render('attachment');
        }
    }

    public function actionAttachmentCreateStorage()
    {
        try {
            $user = \Yii::$app->user->identity;
            $common = CommonAttachment::getCommon($user, \Yii::$app->mall);
            $data = \Yii::$app->request->post();
            $res = $common->attachmentCreateStorage($data);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function actionAttachmentEnableStorage($id)
    {
        $common = CommonAttachment::getCommon(\Yii::$app->user->identity, \Yii::$app->mall);
        $common->attachmentEnableStorage($id);
        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功。',
        ]);
    }

    public function actionCreateStorageFromAccount()
    {
        $form = new AttachmentForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->save());
    }

    public function actionAccountAttachment()
    {
        if (\Yii::$app->request->isAjax) {
            $common = CommonAttachment::getCommon(\Yii::$app->mall->user);
            $list = $common->getAttachmentList();
            return $this->asJson([
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'list' => $list,
                    'storageTypes' => $common->getStorageType()
                ]
            ]);
        }
    }
}
