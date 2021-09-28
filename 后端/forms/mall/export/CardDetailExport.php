<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: jack_guo
 */

namespace app\forms\mall\export;

use app\core\CsvExport;

class CardDetailExport extends BaseExport
{
    public function fieldsList()
    {
        return [
            [
                'key' => 'card_id',
                'value' => '卡券ID',
            ],
            [
                'key' => 'card_name',
                'value' => '卡券名称',
            ],
            [
                'key' => 'clerk_time',
                'value' => '核销时间',
            ],
            [
                'key' => 'clerk_id',
                'value' => '核销员ID',
            ],
            [
                'key' => 'clerk_name',
                'value' => '核销员名称',
            ],
            [
                'key' => 'clerk_store_name',
                'value' => '核销门店',
            ],
            [
                'key' => 'clerk_number',
                'value' => '核销次数',
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
        return '卡券核销统计';
    }

    protected function transform($list)
    {
        $newList = [];
        $arr = [];

        foreach ($list as $key => $item) {
            foreach ($item['clerkLog'] as $key => $clerkLog) {
                $arr['card_id'] = $item['card_id'];
                $arr['card_name'] = $item['name'];
                $arr['clerk_time'] = $clerkLog['clerked_at'];
                $arr['clerk_id'] = $clerkLog['clerk_id'];
                $arr['clerk_name'] = $clerkLog['user']['nickname'];
                $arr['clerk_store_name'] = $clerkLog['store']['name'];
                $arr['clerk_number'] = $clerkLog['use_number'];

                $newList[] = $arr;
            }
        }
        $this->dataList = $newList;
    }
}
