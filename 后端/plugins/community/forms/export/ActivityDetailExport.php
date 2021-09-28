<?php
/**
 * @copyright ©2019 浙江禾匠信息科技
 * Created by PhpStorm.
 * User: jack_guo
 * Date: 2019/7/15
 * Time: 14:48
 */

namespace app\plugins\community\forms\export;

use app\core\CsvExport;
use app\forms\mall\export\BaseExport;
use app\models\CoreFile;

class ActivityDetailExport extends BaseExport
{
    public $activity;

    public function fieldsList()
    {
        return [
            [
                'key' => 'user_id',
                'value' => '团长ID',
            ],
            [
                'key' => 'nickname',
                'value' => '团长昵称'
            ],
            [
                'key' => 'name',
                'value' => '姓名',
            ],
            [
                'key' => 'mobile',
                'value' => '手机号',
            ],
            [
                'key' => 'location',
                'value' => '所在小区',
            ],
            [
                'key' => 'detail',
                'value' => '提货地址',
            ],
            [
                'key' => 'order_price',
                'value' => '订单实付金额（元）',
            ],
            [
                'key' => 'order_num',
                'value' => '支付订单数',
            ],
            [
                'key' => 'user_num',
                'value' => '参团人数',
            ],
            [
                'key' => 'activity_status',
                'value' => '活动状态',
            ],
            [
                'key' => 'activity_name',
                'value' => '活动名称',
            ],
            [
                'key' => 'condition_text',
                'value' => '最低成团标准',
            ],
        ];
    }

    public function export($list = null)
    {
        \Yii::warning('导出开始');
        try {
            $list = $this->query;
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

    /**
     * 获取csv名称
     * @return string
     */
    public function getFileName()
    {
        $name = '【' . $this->activity['title'] ?? '未知' . '】活动详情列表';

        return $name;
    }

    protected function transform($list)
    {
        switch ($this->activity['condition']) {
            case 1:
                $condition_text = '满' . $this->activity['num'] . '人';
                break;
            case 2:
                $condition_text = '满' . $this->activity['num'] . '件';
                break;
            default:
                $condition_text = '无';
        }
        switch ($this->activity['activity_status']) {
            case 0:
                $status = '未开始';
                break;
            case 1:
                $status = '进行中';
                break;
            case 2:
                $status = '已结束';
                break;
            case 3:
                $status = '下架中';
                break;
            default:
                $status = '未知';
        }
        $newList = [];
        $number = 1;
        foreach ($list as $item) {
            $arr = [];
            $arr['number'] = $number++;
            $arr['user_id'] = $item['user_id'];
            $arr['nickname'] = $item['middleman']['nickname'];
            $arr['name'] = $item['address']['name'];
            $arr['mobile'] = $item['address']['mobile'];
            $arr['location'] = $item['address']['location'];
            $arr['detail'] = $item['address']['detail'];
            $arr['order_price'] = (float)$item['order_price'];
            $arr['order_num'] = (int)$item['order_num'];
            $arr['user_num'] = (int)$item['user_num'];
            $arr['activity_status'] = $status;
            $arr['activity_name'] = $this->activity['title'];
            $arr['condition_text'] = $condition_text;

            $newList[] = $arr;
        }

        $this->dataList = $newList;
    }
}
