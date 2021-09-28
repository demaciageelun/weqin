<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2020 浙江禾匠信息科技有限公司
 * author: xay
 */

namespace app\plugins\pintuan\forms\api\v2\poster;


use app\forms\api\poster\common\BaseConst;
use app\forms\api\poster\common\CommonFunc;
use app\forms\common\goods\CommonGoodsDetail;
use app\helpers\PluginHelper;
use app\models\Model;
use app\plugins\pintuan\models\Goods;
use app\plugins\pintuan\models\PintuanGoods;
use app\plugins\pintuan\models\PintuanGoodsGroups;
use app\plugins\pintuan\models\PintuanOrders;

class PosterCustomize extends Model implements BaseConst
{
    use CommonFunc;

    public function traitQrcode($class)
    {
        return [
            ['goods_id' => $class->goods->id, 'user_id' => \Yii::$app->user->id, 'id' => $class->other[0]],
            240,
            current($class->other) ? 'plugins/pt/detail/detail' : 'plugins/pt/goods/goods',
        ];
    }

    public function traitMultiMapContent()
    {
        $image = [
            'file_type' => self::TYPE_IMAGE,
            'width' => 120,
            'height' => 110,
            'left' => 0,
            'top' => 0,
            'image_url' => PluginHelper::getPluginBaseAssetsUrl('pintuan') . '/img/pt-qrcode.png',
        ];
        return [$image];
    }

    public function traitHash($model)
    {
        return array_merge(['id' => $model->goods->id, $model->poster_arr], $model->other);
    }

    public function traitPrice($model, $left, $top, $has_center, $color)
    {
        if ($group_id = $model->other[0]) {

            $ptGoods = PintuanOrders::findOne([
                'id' => $group_id
            ]);

            $group = PintuanGoodsGroups::findOne([
                'id' => $ptGoods->pintuan_goods_groups_id
            ]);
            $goods = $group->goods;
            $people_num = $ptGoods->people_num;
            $prices = array_column($goods->attr, 'price');
        } else {
            $form = new CommonGoodsDetail();
            $form->user = \Yii::$app->user->identity;
            $form->mall = \Yii::$app->mall;
            $goods = $form->getGoods($model->other[1]);
            if (empty($goods)) {
                throw new \Exception('拼团海报商品异常');
            }
            $goodsList = (new Goods())->getGoodsGroups($goods);
            if (empty($goodsList)) {
                throw new \Exception('拼团组异常');
            }

            /** @var Goods $item */
            $people_num = 0;
            foreach ($goodsList as $item) {
                if (!$people_num || $item->oneGroups->people_num < $people_num) {
                    $people_num = $item->oneGroups->people_num;
                    $prices = array_column($item->attr, 'price');
                }
            }
        }

        $team = $this->setText($people_num . '人团', $left, $top + 10, 30, $color);
        $font_path = \Yii::$app->basePath . '/web/statics/font/st-heiti-light.ttc';
        $t = any2eucjp($team['font'], 0, $font_path, $team['text']);
        $t_width = $t[2] - $t[0];


        $minPrice = min($prices);
        $maxPrice = max($prices);
        if ($maxPrice > $minPrice && $minPrice >= 0) {
            $mark_width = 28;
            $mark = $this->setText('￥', $left + $t_width + 3, $top + 10, 32, $color);
            $price = $this->setText($minPrice . '-' . $maxPrice, $left + $t_width + $mark_width, $top, 52, $color);

            $g = any2eucjp($price['font'], 0, $font_path, $price['text']);
            $g_width = $g[2] - $g[0];

            $has_center && $left = (750 - $g_width - $t_width - $mark_width) / 2;


            $team['left'] = $left;
            $mark['left'] = $left + $t_width + 3;
            $price['left'] = $mark['left'] + $mark_width;
            return [
                $team,
                $mark,
                $price,
            ];
        }
        if ($maxPrice == $minPrice && $minPrice > 0) {
            $mark_width = 28;
            $mark = $this->setText('￥', $left + $t_width + 3, $top + 10, 32, $color);
            $price = $this->setText($minPrice, $left + $t_width + $mark_width, $top, 52, $color);

            $g = any2eucjp($price['font'], 0, $font_path, $price['text']);
            $g_width = $g[2] - $g[0];

            $has_center && $left = (750 - $g_width - $t_width - $mark_width) / 2;


            $team['left'] = $left;
            $mark['left'] = $left + $t_width + 3;
            $price['left'] = $mark['left'] + $mark_width;

            return [
                $team,
                $mark,
                $price,
            ];
        }
        if ($minPrice == 0) {
            $mark = $this->setText('免费', $left + $t_width + 3, $top, 48, $color);

            $m = any2eucjp($mark['font'], 0, $font_path, $mark['text']);
            $m_width = $m[2] - $m[0];

            $has_center && $left = (750 - $m_width - $t_width) / 2;
            $team['left'] = $left;
            $mark['left'] = $left + $t_width + 3;

            return [
                $team,
                $mark
            ];
        }
        return [];
    }
}