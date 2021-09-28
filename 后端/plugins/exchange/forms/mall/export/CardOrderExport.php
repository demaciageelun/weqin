<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\plugins\exchange\forms\mall\export;

use app\core\CsvExport;
use app\core\response\ApiCode;
use app\forms\common\platform\PlatformConfig;
use app\forms\mall\export\BaseExport;
use app\models\BaseQuery\BaseActiveQuery;
use app\plugins\exchange\forms\common\CommonModel;
use app\plugins\exchange\models\ExchangeGoods;

class CardOrderExport extends BaseExport
{
    public $goods_id;

    public function fieldsList()
    {
        return [
            [
                'key' => 'order_no',
                'value' => '订单号',
            ],
            [
                'key' => 'user_id',
                'value' => '用户ID',
            ],
            [
                'key' => 'nickname',
                'value' => '用户名',
            ],
            [
                'key' => 'platform',
                'value' => '兑换渠道',
            ],
            [
                'key' => 'goods_name',
                'value' => '礼品卡',
            ],
            [
                'key' => 'library_name',
                'value' => '礼品码库',
            ],
            [
                'key' => 'code',
                'value' => '兑换码',
            ],
            [
                'key' => 'created_at',
                'value' => '购买时间',
            ],
            [
                'key' => 'status',
                'value' => '状态',
            ]
        ];
    }

    public function export($query = null)
    {
        $query = $this->query;
        $this->fieldsKeyList = array_column($this->fieldsList(), 'key');
        
        $this->exportAction($query);

        return true;
    }

    /**
     * 获取csv名称
     * @return string
     */
    public function getFileName()
    {
        $g = ExchangeGoods::find()->where([
            'goods_id' => $this->goods_id
        ])->one();
        if ($g) {
            return sprintf('%s-订单记录导出', $g->library->name);
        }
        return '礼品卡-订单记录导出';
    }

    protected function transform($list)
    {
        $newList = [];
        foreach ($list as $item) {
            CommonModel::getStatus($item->library, $item->code, $msg);
            $newList[] = [
                'order_no' => $item->order->order_no,
                'avatar' => $item->user->userInfo->avatar,
                'user_id' => $item->user->id,
                'nickname' => $item->user->nickname,
                'platform' => CommonModel::getPlatform((PlatformConfig::getInstance())->getPlatform($item->user)),
                'goods_name' => $item->goods->name,
                'library_name' => $item->library->name,
                'code' => $item->code->code,
                'created_at' => $item->created_at,
                'status' => $msg,
            ];
        }
        $this->dataList = $newList;
    }
}
