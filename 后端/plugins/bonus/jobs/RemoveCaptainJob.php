<?php
/**
 * @copyright ©2019 浙江禾匠信息科技
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2019/7/8
 * Time: 15:14
 */


namespace app\plugins\bonus\jobs;

use app\forms\common\message\MessageService;
use app\forms\common\platform\PlatformConfig;
use app\forms\common\template\order_pay_template\RemoveIdentityInfo;
use app\forms\common\template\TemplateList;
use app\models\Mall;
use app\models\User;
use app\plugins\bonus\forms\common\CommonCaptainLog;
use app\plugins\bonus\forms\common\CommonForm;
use app\plugins\bonus\models\BonusCaptain;
use app\plugins\bonus\models\BonusCaptainLog;
use app\plugins\bonus\models\BonusCaptainRelation;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class RemoveCaptainJob extends BaseObject implements JobInterface
{
    public $user_id;
    public $mall;

    /**当前队长所有下级信息**/
    public $temps = [];

    /**@var BonusCaptain $captain * */
    public $captain;

    /**记录查询过的分销商id,避免死链**/
    public $exists = [];

    public $parent_id = 0;

    public function execute($queue)
    {
        \Yii::$app->setMall(Mall::findOne($this->mall->id));
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $parent_id = $this->parent_id ? $this->parent_id : CommonForm::findFirstCaptain($this->user_id);
            $this->end($parent_id);
            $transaction->commit();

            try {
                $user = User::findOne([
                    'id' => $this->user_id,
                    'mall_id' => $this->mall->id,
                    'is_delete' => 0
                ]);
                
                $remark = "队长解除:" . ($this->captain->reason ?? '你的队长身份已被解除');
                $this->sendTemplate($user, $remark);
                $this->sendSmsToUser($user, '队长');
            } catch (\Exception $exception) {
                \Yii::error("发送解除队长订阅消息失败");
                \Yii::error($exception);
            }

            $this->log();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::error("解除队长:" . $e->getMessage() . $e->getFile() . $e->getLine());
            CommonCaptainLog::create("解除队长" . BonusCaptainLog::BONUS_EXCEPTION, 0, [$e]);
            throw new \Exception($e->getMessage());
        };
    }

    private function end($parent_id)
    {
        if ($parent_id) {
            BonusCaptainRelation::updateAll(['captain_id' => $parent_id], ['captain_id' => $this->user_id]);
            $relation = BonusCaptainRelation::findOne(['captain_id' => $parent_id, 'user_id' => $this->user_id, 'is_delete' => 0]);
            if (empty($relation)) {
                $r = new BonusCaptainRelation();
                $r->user_id = $this->user_id;
                $r->captain_id = $parent_id;
                $r->save();
            }
            $parentCaptain = BonusCaptain::findOne(['user_id' => $parent_id, 'is_delete' => 0, 'status' => 1, 'mall_id' => $this->mall->id]);
            if ($parentCaptain) {
                $count = BonusCaptainRelation::find()->where(['captain_id' => $parent_id, 'is_delete' => 0])->groupBy(['user_id'])->count();
                $parentCaptain->all_member = $count;
                $parentCaptain->save();
            }
        } else {
            BonusCaptainRelation::deleteAll(['captain_id' => $this->user_id]);
        }
        $this->captain->all_member = 0;
        $this->captain->level = 0;
        $this->captain->apply_at = mysql_timestamp();
        $this->captain->is_delete = 1;
        $this->captain->save();
    }

    private function log()
    {
        try {
            $log = [];
            CommonCaptainLog::create(BonusCaptainLog::REMOVE_CAPTAIN, $this->user_id, $log);
        } catch (\Exception $exception) {
            \Yii::error('记录队长日志出错');
            \Yii::error($exception);
        }
    }

    public function sendTemplate($user, $remark)
    {
        try {
            TemplateList::getInstance()->getTemplateClass(RemoveIdentityInfo::TPL_NAME)->send([
                'remark' => $remark,
                'time' => date('Y-m-d H:i:s', time()),
                'user' => $user,
                'page' => 'plugins/bonus/index/index'
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
