<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\core\CsvExport;

class BargainStatisticsExport extends BaseExport
{

    public function fieldsList()
    {
        return [
            [
                'key' => 'name',
                'value' => '商品名称',
            ],
            [
                'key' => 'attr_groups',
                'value' => '规格',
            ],
            [
                'key' => 'min_price',
                'value' => '活动价',
            ],
            [
                'key' => 'initiator',
                'value' => '发起人数',
            ],
            [
                'key' => 'participant',
                'value' => '参与人数',
            ],
            [
                'key' => 'min_price_goods',
                'value' => '砍到最低商品数',
            ],
            [
                'key' => 'underway',
                'value' => '进行中活动数',
            ],
            [
                'key' => 'success',
                'value' => '成功活动数',
            ],
            [
                'key' => 'fail',
                'value' => '失败活动数',
            ],
            [
                'key' => 'payment_people',
                'value' => '支付人数',
            ],
            [
                'key' => 'payment_num',
                'value' => '支付件数',
            ],
            [
                'key' => 'payment_amount',
                'value' => '支付金额',
            ],
            [
                'key' => 'status',
                'value' => '状态',
            ],
        ];
    }

    public function export($query = null)
    {
        $query = $this->query;

        $fieldsKeyList = [];
        foreach ($this->fieldsList() as $item) {
            $fieldsKeyList[] = $item['key'];
        }
        $this->fieldsKeyList = $fieldsKeyList;
        
        $this->exportAction($query, ['is_array' => true]);

        return true;
    }

    public function getFileName()
    {
        return '砍价统计';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        $number = 1;
        foreach ($list as $key => $item) {
            $item['attr_groups'] = $this->attr_groups($item['attr_groups']);
            $item['min_price'] = floatval($item['min_price']);
            $item['initiator'] = intval($item['initiator']);
            $item['participant'] = intval($item['participant']);
            $item['min_price_goods'] = intval($item['min_price_goods']);
            $item['underway'] = intval($item['underway']);
            $item['success'] = intval($item['success']);
            $item['fail'] = intval($item['fail']);
            $item['payment_people'] = intval($item['payment_people']);
            $item['payment_num'] = intval($item['payment_num']);
            $item['payment_amount'] = floatval($item['payment_amount']);

            $arr = array_merge($arr, $item);

            $newList[] = $arr;
        }
        $this->dataList = $newList;
    }

    protected function attr_groups($value)
    {
        $value = json_decode($value, true);
        if (is_array($value)) {
            $attr = '';
            foreach ($value as $v) {
                $attr .= $v['attr_group_name'] . ':' . $v['attr_list'][0]['attr_name'] . ' ';
            }
        }
        return $attr;
    }
}
