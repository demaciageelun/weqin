<?php
/**
 * @copyright ©2019 浙江禾匠信息科技
 * Created by PhpStorm.
 * User: Andy - Wangjie
 * Date: 2020/3/23
 * Time: 16:52
 */


namespace app\forms\mall\export;

use app\core\CsvExport;
use app\models\CoreFile;
use app\models\ShareCash;
use app\models\ShareSetting;
use app\models\Store;
use app\models\User;
use app\plugins\mch\models\MchCash;

class FinanceCashExport extends BaseExport
{
    public function fieldsList()
    {
        return [
            [
                'key' => 'platform',
                'value' => '所属平台',
            ],
            [
                'key' => 'order_no',
                'value' => '订单号',
            ],
            [
                'key' => 'nickname',
                'value' => '昵称',
            ],
            [
                'key' => 'cash_price',
                'value' => '提现金额',
            ],
            [
                'key' => 'apply_at',
                'value' => '申请日期',
            ],
            [
                'key' => 'bank_name',
                'value' => '银行名称',
            ],
            [
                'key' => 'account',
                'value' => '打款账号',
            ],
            [
                'key' => 'real_name',
                'value' => '真实姓名',
            ],
            [
                'key' => 'status',
                'value' => '状态',
            ],
            [
                'key' => 'pay_type',
                'value' => '打款方式',
            ],
            [
                'key' => 'pay_time',
                'value' => '打款时间',
            ],
            [
                'key' => 'model_text',
                'value' => '提现类型',
            ],
            [
                'key' => 'shop_name',
                'value' => '商户信息'
            ]
        ];
    }

    public function export($query = null)
    {
        $list = $this->query->orderBy(['status' => SORT_ASC, 'created_at' => SORT_DESC])->all();
        $newList = [];
        /* @var ShareCash[] $list */
        foreach ($list as $item) {
            if ($item['model'] == 'mch') {
                $serviceCharge = 0;
                $extra = \Yii::$app->serializer->decode($item['extra']);
                $extra['name'] = $extra['nickname'] ?? '';
                $extra['mobile'] = $extra['account'] ?? '';
                $extra['bank_name'] = $extra['bank_name'] ?? '';
                if ($item['type'] == 'wx') {
                    $item['type'] = 'wechat';
                }
                $item['status'] = $this->parseMchStatus($item);
                $mchCash = MchCash::findOne($item['id']);
                $store = Store::findOne(['mch_id' => $mchCash->mch_id]);
                $extra['shop_name'] = $store->name;
            } else {
                $serviceCharge = round($item['price'] * $item['service_charge'] / 100, 2);
                $extra = \Yii::$app->serializer->decode($item['extra']);
            }
            $cashType = [
                'share' => '分销商提现',
                'bonus' => '团队分红提现',
                'stock' => '股东分红提现',
                'region' => '区域代理提现',
                'mch' => '多商户提现'
            ];
            $cashTypeText = isset($cashType[$item['model']]) ? $cashType[$item['model']] : '未知状态' . $item['model'];
            $newItem = [
                'id' => $item['id'],
                'order_no' => $item['order_no'],
                'pay_type' => ShareSetting::PAY_TYPE_LIST[$item['type']],
                'type' => $item['type'],
                'status' => $item['status'],
                'status_text' => $this->getStatusText($item['status']),
                'user' => [
                    'name' => $item['name'],
                    'phone' => $item['phone'],
                    'avatar' => $item['avatar'],
                    'nickname' => $item['nickname'],
                    'platform' => $item['platform'],
                    'user_id' => $item['user_id']
                ],
                'cash' => [
                    'price' => round($item['price'], 2),
                    'service_charge' => $serviceCharge,
                    'actual_price' => round($item['price'] - $serviceCharge, 2)
                ],
                'extra' => [
                    'name' => $extra['name'] ? $extra['name'] : '',
                    'mobile' => $extra['mobile'] ? $extra['mobile'] : '',
                    'bank_name' => $extra['bank_name'] ? $extra['bank_name'] : ''
                ],
                'time' => [
                    'created_at' => $item['created_at'],
                    'apply_at' => isset($extra['apply_at']) ? $extra['apply_at'] : '',
                    'remittance_at' => isset($extra['remittance_at']) ? $extra['remittance_at'] : '',
                    'reject_at' => isset($extra['reject_at']) ? $extra['reject_at'] : '',
                ],
                'content' => [
                    'apply_content' => isset($extra['apply_content']) ? $extra['apply_content'] : '',
                    'remittance_content' => isset($extra['remittance_content']) ? $extra['remittance_content'] : '',
                    'reject_content' => isset($extra['reject_content']) ? $extra['reject_content'] : '',
                ],
                'model_text' => $cashTypeText,
            ];
            if ($item['model'] == 'mch') {
                $newItem['shop_name'] = $extra['shop_name'];
            }
            $newList[] = $newItem;
        }


        \Yii::warning('导出开始');
        try {
            $list = $newList;
            $this->getFields();
            // 文件夹唯一标识
            $id = \Yii::$app->mall->id . '_' . $this->mch_id;
            // 唯一文件名称
            $fileName = sprintf('%s%s%s%s', $this->getFileName(), $id, time(), '.csv');

            $coreFile = new CoreFile();
            $coreFile->mall_id = \Yii::$app->mall->id;
            $coreFile->mch_id = $this->mch_id;
            $coreFile->file_name = $fileName;

            $this->transform($list);
            $dataList = $this->getDataList();
            (new CsvExport())->newAjaxExport($dataList, $this->fieldsNameList, $fileName, $id);

            $coreFile->status = 1;
            $coreFile->percent = 1;
            $res = $coreFile->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($coreFile));
            }

            \Yii::warning('导出结束');
        }catch(\Exception $exception) {
            \Yii::error('导出异常');
            \Yii::error($exception);

            $coreFile->status = 2;
            $coreFile->save();
        }
    }

    public function getFileName()
    {
        return '提现列表';
    }

    protected function transform($list)
    {

        $userIds = [];
        foreach ($list as $item) {
            $userIds[] = $item['user']['user_id'];
        }

        $users =  User::find()->andWhere(['id' => $userIds])->with('userInfo')->all();
        $userList = [];
        foreach ($users as $user) {
            $userList[$user->id] = $user;
        }

        $newList = [];
        $number = 1;
        foreach ($list as $item) {
            $arr = [];
            $arr['number'] = $number++;
            $arr['platform'] = $this->getPlatform($userList[$item['user']['user_id']]);
            $arr['order_no'] = $item['order_no'];
            $arr['nickname'] = $item['user']['nickname'];
            $arr['cash_price'] = (float)$item['cash']['price'];
            $arr['apply_at'] = $item['time']['apply_at'];
            $arr['bank_name'] = $item['extra']['bank_name'];
            $arr['account'] = $item['extra']['mobile'];
            $arr['real_name'] = $item['extra']['name'];
            $arr['status'] = $item['status_text'];
            $arr['pay_type'] = $item['pay_type'];
            $arr['pay_time'] = $this->getDateTime($item['time']['remittance_at']);
            $arr['model_text'] = $item['model_text'];
            $arr['shop_name'] = $item['shop_name'] ?? '';
            $newList[] = $arr;
        }

        $this->dataList = $newList;
    }

    private function getStatusText($status)
    {
        $text = ['申请中', '同意申请，待打款', '已打款', '驳回'];
        return isset($text[$status]) ? $text[$status] : '未知状态' . $status;
    }

    private function parseMchStatus($status)
    {
        if ($status['status'] == 1) {
            if ($status['transfer_status'] == 2) {
                return 3;
            } elseif ($status['transfer_status'] == 1) {
                return 2;
            }
        }
        return $status['status'];
    }
}
