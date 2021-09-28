<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/2/20
 * Time: 14:11
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\jobs;


use app\core\response\ApiCode;
use app\forms\common\platform\PlatformConfig;
use app\forms\common\template\TemplateSender;
use app\forms\common\template\tplmsg\BaseTemplate;
use app\models\Formid;
use app\models\Mall;
use app\models\TemplateRecord;
use app\models\User;
use app\models\UserInfo;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class TemplateSendJob extends BaseJob implements JobInterface
{
    public $user;
    public $templateTpl;
    public $templateId;
    public $page;
    public $data;
    public $token;
    public $titleStyle;
    public $dataKey;
    public $tplClass;

    /* @var TemplateSender */
    public $sender;
    private $platform;

    /**
     * @param \yii\queue\Queue $queue
     * @throws \Exception
     */
    public function execute($queue)
    {
        try {
            \Yii::warning('---订阅消息发送提醒---');
            $this->setRequest();
            $userList = $this->getUserList();
            $mall = Mall::findOne(['id' => $this->user->mall_id]);
            \Yii::$app->setMall($mall);
            $userListId = [];
            foreach ($userList as $item) {
                $userListId[] = $item->id;
            }
            $formList = $this->getFormList($userListId);

            $formIdList = [];
            $templateRecord = [];
            foreach ($userList as $item) {
                $platform = PlatformConfig::getInstance()->getPlatform($item);
                $platformList = explode('_', $platform);
                foreach ($platformList as $value) {
                    $this->platform = $value;
                    $templateRecord[] = $this->send($item, $formList, $formIdList);
                }
            }
            if (!empty($formIdList)) {
                // 模板消息发送成功相应的form_id剩余发放次数减1
                Formid::updateAll(['remains' => '`remains`-1'], ['id' => $formIdList]);
            }
            if (count($templateRecord) > 0) {
                // 批量存储发送模板消息数据
                \Yii::$app->db->createCommand()->batchInsert(
                    TemplateRecord::tableName(),
                    ['user_id', 'mall_id', 'status', 'data', 'error', 'created_at', 'token'],
                    $templateRecord
                )->execute();
            }
        } catch (\Exception $e) {
            \Yii::warning($e);
            throw $e;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function sender()
    {
        $plugin = \Yii::$app->plugin->getPlugin($this->platform);
        return $plugin->templateSender();
    }

    private function getUserList()
    {
        /* @var User[] $userList */
        if (isset($this->user)) {
            if (is_array($this->user)) {
                // 群发模板消息
                $userList = $this->user;
            } else {
                // 单发模板消息
                $userList = [$this->user];
            }
        } else {
            throw new \Exception('缺少参数user');
        }
        return $userList;
    }

    private function getFormList($userListId)
    {
        // 获取对应用户可用的form_id
        $form = Formid::find()->where(['user_id' => $userListId])
            ->andWhere(['>=', 'created_at', date('Y-m-d H:i:s', time() - 7 * 86400)])
            ->andWhere(['>', 'remains', 0])->orderBy(['created_at' => SORT_ASC])->groupBy('user_id')->all();

        $formList = [];
        /* @var Formid[] $form */
        foreach ($form as $item) {
            $formList[$item->user_id] = $item;
        }
        return $formList;
    }

    /**
     * @param User $item
     * @param $formList
     * @param $formIdList
     * @return array
     */
    private function send($item, $formList, &$formIdList)
    {
        $data = $this->data;
        if ($this->tplClass && $this->tplClass instanceof BaseTemplate) {
            $data = $this->tplClass->adaptive($this->platform);
        }
        // 发送模板消息需要的数据
        $arg = [
            'user' => $item,
            'templateId' => $this->templateId,
            'templateTpl' => $this->templateTpl,
            'page' => $this->page,
            'data' => $data,
            'dataKey' => $this->dataKey,
            'formId' => '',
            'titleStyle' => $this->titleStyle == 2 ? 'keyword1.DATA' : '',
        ];

        // 存储发送模板消息的数据
        $newItem = [
            'user_id' => $item->id,
            'mall_id' => $item->mall_id,
            'status' => 1,
            'data' => \Yii::$app->serializer->encode($arg),
            'error' => '',
            'created_at' => mysql_timestamp(),
            'token' => $this->token
        ];
        try {
            $this->sender = $this->sender();
            if ($this->sender->is_need_form_id) {
                if (!isset($formList[$item->id]) || !$formList[$item->id]) {
                    throw new \Exception('没有有效的form_id');
                }
                $form = $formList[$item->id];
                $arg['formId'] = $form->form_id;
                $formIdList[] = $form->id;
            }
            $this->sender->sendTemplate($arg);
        } catch (\Exception $e) {
            $newItem['error'] = $e->getMessage();
            $newItem['status'] = 0;
        }
        return $newItem;
    }
}
