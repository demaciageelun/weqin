<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/1/23
 * Time: 10:35
 */

namespace app\forms\mall\share;


use app\core\response\ApiCode;
use app\events\ShareMemberEvent;
use app\forms\common\CommonQrCode;
use app\forms\common\message\MessageService;
use app\forms\common\platform\PlatformConfig;
use app\forms\common\template\order_pay_template\RemoveIdentityInfo;
use app\forms\common\template\TemplateList;
use app\handlers\HandlerRegister;
use app\models\Model;
use app\models\Share;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserInfo;

class ShareForm extends Model
{
    public $id;
    public $reason;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reason'], 'string'],
            [['reason'], 'trim'],
        ];
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        /* @var Share $share */
        $share = Share::find()->with(['userInfo'])
            ->where(['id' => $this->id, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id])->one();

        if (!$share) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '分销商不存在'
            ];
        }

        $t = \Yii::$app->db->beginTransaction();
        $share->first_children = 0;
        $share->all_children = 0;
        $share->is_delete = 1;
        $share->reason = $this->reason;
        $share->delete_first_show = 0;
        $share->deleted_at = mysql_timestamp();
        if ($share->save()) {
            $userIdentity = UserIdentity::findOne(['user_id' => $share->user_id]);
            $userIdentity->is_distributor = 0;
            $parentId = $share->userInfo->parent_id;
            if ($userIdentity->save()) {
                UserInfo::updateAll(
                    ['parent_id' => 0],
                    ['or',
                        ['parent_id' => $share->user_id],
                        ['user_id' => $share->user_id]
                    ]
                );

                $t->commit();
                \Yii::$app->trigger(HandlerRegister::CHANGE_SHARE_MEMBER, new ShareMemberEvent([
                    'mall' => \Yii::$app->mall,
                    'beforeParentId' => $parentId,
                    'parentId' => 0,
                    'userId' => $share->user_id,
                    'remark' => $this->reason
                ]));

                try {
                    $user = User::findOne(['id' => $share->user_id]);
                    $this->sendTemplate($user, '分销商解除:你的分销商身份已被解除');
                    $this->sendSmsToUser($user, '分销商');
                } catch (\Exception $exception) {
                    \Yii::error("发送解除分销商模板消息失败");
                    \Yii::error($exception);
                }

                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '删除成功'
                ];
            } else {
                $t->rollBack();
                return $this->getErrorResponse($userIdentity);
            }
        } else {
            $t->rollBack();
            return $this->getErrorResponse($share);
        }
    }

    public function getQrcode()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $share = Share::findOne(['id' => $this->id, 'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id]);

            if (!$share) {
                throw new \Exception('分销商不存在');
            }
            $list = [];
            $form = new CommonQrCode();
            $platform = PlatformConfig::getInstance()->getPlatform($share->user);
            $platformList = explode('_', $platform);
            foreach ($platformList as $item) {
                $form->appPlatform = $item;
                $list[] = $form->getQrCode(['user_id' => $share->user_id])['file_path'];
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => $list
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }

    public function sendTemplate($user, $remark)
    {
        try {
            TemplateList::getInstance()->getTemplateClass(RemoveIdentityInfo::TPL_NAME)->send([
                'remark' => $remark,
                'time' => date('Y-m-d H:i:s', time()),
                'user' => $user,
                'page' => 'pages/share/index/index'
            ]);
        } catch (\Exception $exception) {
            \Yii::error('模板消息发送: ' . $exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @param $remark
     * @return $this
     * 向用户发送短信提醒
     */
    protected function sendSmsToUser($user, $remark)
    {
        try {
            \Yii::warning('----消息发送提醒----');
            if (!$user->mobile) {
                throw new \Exception('用户未绑定手机号无法发送');
            }
            $messageService = new MessageService();
            $messageService->user = $user;
            $messageService->content = [
                'mch_id' => 0,
                'args' => [$remark]
            ];
            $messageService->platform = PlatformConfig::getInstance()->getPlatform($user);
            $messageService->tplKey = RemoveIdentityInfo::TPL_NAME;
            $res = $messageService->templateSend();
        } catch (\Exception $exception) {
            \Yii::error('向用户发送短信消息失败');
            \Yii::error($exception);
        }
        return $this;
    }
}
