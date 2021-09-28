<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\forms\mall\mch;

use app\core\response\ApiCode;
use app\models\Model;
use app\models\Store;
use app\plugins\mch\models\Mch;

class MchEditForm extends Model
{
    public $id;
    public $realname;
    public $wechat;
    public $mobile;
    public $address;
    public $mch_common_cat_id;
    public $name;
    public $logo;
    public $bg_pic_url;
    public $service_mobile;
    public $latitude_longitude;
    public $description;
    public $scope;

    public $is_open;
    public $open_type;
    public $week_list;
    public $time_list;
    public $is_auto_open;
    public $auto_open_time;


    public function rules()
    {
        return [
            [['mch_common_cat_id', 'address',], 'required'],
            [['mch_common_cat_id', 'id', 'is_open', 'open_type', 'is_auto_open'], 'integer'],
            [['mobile', 'logo', 'latitude_longitude',
                'description', 'scope', 'service_mobile'], 'string', 'max' => 255],
            [['realname', 'wechat', 'name', 'auto_open_time'], 'string', 'max' => 65],
            [['bg_pic_url', 'week_list', 'time_list'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->checkData();
            $mch = Mch::findOne([
                'id' => $this->id,
                'is_delete' => 0
            ]);

            if (!$mch) {
                throw new \Exception('店铺不存在');
            }

            $mch->realname = $this->realname;
            $mch->mobile = $this->mobile;
            $mch->mch_common_cat_id = $this->mch_common_cat_id;
            $mch->wechat = $this->wechat;
            $res = $mch->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($mch));
            }

            $store = Store::findOne(['mch_id' => $mch->id]);
            if (!$store) {
                throw new \Exception('商户店铺信息不存在');
            }
            $store->name = $this->name;
            $store->address = $this->address;
            $store->cover_url = $this->logo;
            $store->pic_url = \Yii::$app->serializer->encode($this->bg_pic_url);
            $store->mobile = $this->service_mobile;
            $store->description = $this->description;
            $store->scope = $this->scope;
            $store->longitude = $this->latitude_longitude[1];
            $store->latitude = $this->latitude_longitude[0];
            $store->extra_attributes = json_encode([
                'is_open' => $this->is_open ?: 1,
                'open_type' => $this->open_type ?: 1,
                'week_list' => $this->week_list ? $this->week_list : [],
                'time_list' => $this->time_list ? $this->time_list : [],
                'is_auto_open' => $this->is_auto_open ?: 1,
                'auto_open_time' => $this->auto_open_time ?: '',
            ]);

            $res = $store->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($store));
            }

            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    private function checkData()
    {
        if ($this->is_open == 1 && $this->open_type == 2 && empty($this->week_list)) {
            throw new \Exception('请选择营业星期');
        }
            
        if ($this->latitude_longitude) {
            $arr = explode(',', $this->latitude_longitude);
            if (count($arr) != 2) {
                throw new \Exception('请输入正确的经纬度');
            }
            $this->latitude_longitude = $arr;
        }
    }
}
