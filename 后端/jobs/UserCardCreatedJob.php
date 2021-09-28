<?php
/**
 * 卡卷提醒
 */

namespace app\jobs;

use app\forms\common\template\order_pay_template\AccountChangeInfo;
use app\forms\common\template\TemplateList;
use app\models\User;
use app\models\UserCard;
use yii\base\Component;
use yii\queue\JobInterface;

class UserCardCreatedJob extends BaseJob implements JobInterface
{
    public $id;

    public $mall;
    public $user_id;

    public function execute($queue)
    {
        try {
            $this->setRequest();
            $model = UserCard::find()->with('card')->where([
                'mall_id' => $this->mall->id,
                'id' => $this->id,
                'is_use' => 0,
                'is_delete' => 0,
            ])->andWhere(['>', 'end_time', mysql_timestamp()])->one();

            if (!$model) {
                throw new \Exception('卡卷已过期或已使用或已删除');
            }
            try {
                TemplateList::getInstance()->getTemplateClass(AccountChangeInfo::TPL_NAME)->send([
                    'page' => 'pages/card/index/index',
                    'user' => User::findOne($this->user_id),
                    'remark' => '[' . $model->name . ']',
                    'desc' => '卡卷即将过期，请尽快使用'
                ]);
            } catch (\Exception $exception) {
                \Yii::warning('卡卷过期提醒发送失败 => ' . $exception->getMessage());
            }
            return $model;
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
        }
    }
}
