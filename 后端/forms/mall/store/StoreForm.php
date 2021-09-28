<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\forms\mall\store;

use app\core\response\ApiCode;
use app\models\Model;
use app\models\Store;
use yii\helpers\ArrayHelper;

class StoreForm extends Model
{
    public $id;
    public $page;
    public $is_default;
    public $keyword;
    public $page_size;
    public $status;

    public function rules()
    {
        return [
            [['id', 'is_default', 'status'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['keyword',], 'string'],
            [['keyword',], 'default', 'value' => ''],
            [['page_size'], 'default', 'value' => 20],
            ['status', 'in', 'range' => [0,1]]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '门店ID',
        ];
    }
    public function switchStatus(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $store = Store::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id]);

            if (!$store) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '数据异常,该条数据不存在'
                ];
            }
            $store->status = $this->status;
            $store->save();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '切换成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $query = Store::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'mch_id' => \Yii::$app->user->identity->mch_id,
            'is_delete' => 0,
        ])->keyword($this->status, ['status' => $this->status]);


        if ($this->keyword) {
            $query->andWhere([
                'or',
                ['like', 'name', $this->keyword],
                ['like', 'id', $this->keyword],
            ]);
        }

        $list = $query->orderBy('id desc')
            ->page($pagination, $this->page_size)
            ->asArray()
            ->all();
        foreach ($list as $k => $v) {
            $list[$k]['latitude_longitude'] = $v['latitude'] . ',' . $v['longitude'];
            $list[$k]['name_id'] = sprintf('%s（%s）', $v['name'], $v['id']);
        }


        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    public function getDetail()
    {
        /** @var Store $detail */
        $detail = Store::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'id' => $this->id
        ])->one();

        if ($detail) {
            $detail = ArrayHelper::toArray($detail);
            $detail['pic_url'] = json_decode($detail['pic_url']);
            $detail['latitude_longitude'] = $detail['latitude'] . ',' . $detail['longitude'];
            if ($detail['business_hours']) {
                $time = explode('-', $detail['business_hours']);
                $detail['start_time'] = $time[0];
                $detail['end_time'] = $time[1];
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => [
                    'detail' => $detail,
                ]
            ];
        }

        return [
            'code' => ApiCode::CODE_ERROR,
            'msg' => '请求失败',
        ];
    }

    public function destroy()
    {

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $store = Store::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id]);

            if (!$store) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '数据异常,该条数据不存在'
                ];
            }

            $store->is_delete = 1;
            $res = $store->save();

            if (!$res) {
                throw new \Exception('删除失败x01');
            }


            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '删除成功',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function switchDefault()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if ($this->is_default) {
                $resultNum = Store::updateAll(['is_default' => 0], [
                    'mall_id' => \Yii::$app->mall->id,
                    'mch_id' => \Yii::$app->user->identity->mch_id,
                    'is_delete' => 0,
                    'is_default' => 1,
                ]);
            }

            $store = Store::find()->where(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id])->one();
            if (!$store) {
                throw new \Exception('数据异常,该条数据不存在');
            }

            $store->is_default = $this->is_default;
            $store->save();


            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '更新成功'
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                    'msg' => $e->getMessage()
                ]
            ];
        }
    }

    public function getAllStore()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = Store::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'mch_id' => \Yii::$app->user->identity->mch_id,
            'is_delete' => 0,
        ]);

        $list = $query->orderBy('id desc')->all();

        return $list;
    }
}
