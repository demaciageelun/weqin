<?php

/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2020 浙江禾匠信息科技有限公司
 * author: xay
 */

namespace app\plugins\exchange\forms\mall;

use app\core\response\ApiCode;
use app\forms\common\platform\PlatformConfig;
use app\forms\mall\export\CommonExport;
use app\forms\mall\export\jobs\ExportJob;
use app\models\Model;
use app\plugins\exchange\forms\common\CommonModel;
use app\plugins\exchange\forms\mall\export\RecordLogExport;
use app\plugins\exchange\models\ExchangeCode;

class RecordLogForm extends Model
{
    public $library_id;
    public $flag;
    public $origin;
    public $r_raffled_at;

    public $keyword;
    public $keyword_1;
    public $page;

    public function rules()
    {
        return [
            [['library_id'], 'required'],
            [['library_id', 'page'], 'integer'],
            [['r_raffled_at'], 'trim'],
            [['flag', 'origin', 'keyword', 'keyword_1'], 'string', 'max' => 100],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $query = $this->getQuery();

            if ($this->flag == "EXPORT") {
                $queueId = CommonExport::handle([
                    'export_class' => 'app\\plugins\\exchange\\forms\\mall\\export\\RecordLogExport',
                    'params' => [
                        'library_id' => $this->library_id,
                    ],
                    'model_class' => 'app\\plugins\\exchange\\forms\\mall\\RecordLogForm',
                    'function_name' => 'getQuery'
                ]);

                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '请求成功',
                    'data' => [
                        'queue_id' => $queueId
                    ]
                ];
            }
            $list = $query->page($pagination)->all();
            $newData = [];
            foreach ($list as $item) {
                $rewards = CommonModel::getFormatRewards($item['r_rewards']);

                $rewards_text = implode(',', array_unique(array_map(function ($reward) {
                    return $reward['name'];
                }, $rewards)));

                $newData[] = [
                    'code' => $item->code,
                    'user_id' => $item->user->userInfo->user_id,
                    'nickname' => $item->user->nickname,
                    'avatar' => $item->user->userInfo->avatar,
                    'origin' => $item->r_origin,
                    'platform' => (PlatformConfig::getInstance())->getPlatform($item->user),
                    'platform_icon' => (PlatformConfig::getInstance())->getPlatformIcon($item->user),
                    'platform_text' => (PlatformConfig::getInstance())->getPlatformText($item->user),
                    'rewards' => $rewards,
                    'rewards_text' => $rewards_text,
                    'r_raffled_at' => $item->r_raffled_at,
                    'order_name' => $item->name,
                    'order_mobile' => $item->mobile,
                ];
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '获取成功',
                'data' => [
                    'list' => $newData,
                    'pagination' => $pagination,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function getQuery()
    {
        $where = [
            'AND',
            ['in', 'c.status', [2, 3]],
            ['c.mall_id' => \Yii::$app->mall->id],
            ['c.library_id' => $this->library_id],
        ];
        empty($this->r_raffled_at) || array_push(
            $where,
            ['>=', 'c.r_raffled_at', current($this->r_raffled_at)],
            ['<=', 'c.r_raffled_at', next($this->r_raffled_at)]
        );

        $query = ExchangeCode::find()
            ->alias('c')
            ->where($where)
            ->keyword($this->origin, ['c.r_origin' => $this->origin])
            ->joinWith(['user u'])
            ->orderBy(['c.r_raffled_at' => SORT_DESC, 'c.id' => SORT_DESC]);

        if ($this->keyword) {
            $query->keyword($this->keyword_1 == 4, ['like', 'c.r_user_id', $this->keyword]);
            $query->keyword($this->keyword_1 == 2, ['like', 'u.nickname', $this->keyword]);
            $query->keyword($this->keyword_1 == 8, ['like', 'c.code', $this->keyword]);
        }

        return $query;
    }
}
