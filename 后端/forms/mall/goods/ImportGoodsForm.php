<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\forms\mall\goods;


use app\core\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Goods;
use app\models\GoodsAttr;
use app\models\GoodsCatRelation;
use app\models\GoodsWarehouse;
use app\models\ImportData;
use app\models\Model;
use app\models\ModelActiveRecord;
use app\models\Option;
use app\plugins\mch\models\MchGoods;

class ImportGoodsForm extends Model
{
    public $cat_ids;
    public $system_cat_ids;
    public $goods_status;
    public $file;
    public $current_num;
    public $file_path;
    public $import_data_id;


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['cat_ids', 'goods_status', 'current_num'], 'required'],
            [['file'], 'file', 'extensions' => ['csv']],
            [['file_path'], 'string'],
            [['import_data_id'], 'integer'],
            [['system_cat_ids'], 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::rules(), [
            'cat_ids' => "分类",
            'goods_status' => "商品上架状态",
            'file' => "csv文件",
        ]);
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        // 关闭日志存储
        ModelActiveRecord::$log = false;
        try {

            if (!is_array($this->cat_ids)) {
                throw new \Exception('请选择商品分类');
            }

            if ($this->current_num <= 1) {
                if (empty($_FILES) || !isset($_FILES['file'])) {
                    return [
                        'code' => 1,
                        'msg' => '请上传csv文件'
                    ];
                }

                $fileName = $_FILES['file']['name'];
                $tmpName = $_FILES['file']['tmp_name'];
                $path = \Yii::$app->basePath . '/web/temp/csv/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($ext != 'csv') {
                    return [
                        'code' => 1,
                        'msg' => '请上传csv文件'
                    ];
                }
                $file = '商品列表' . time() . '.' . $ext;
                $uploadFile = $path . $file;
                $result = move_uploaded_file($tmpName, $uploadFile);
                $importData = new ImportData();
                $importData->mall_id = \Yii::$app->mall->id;
                $importData->mch_id = \Yii::$app->user->identity->mch_id;
                $importData->user_id = \Yii::$app->user->id;
                $importData->file_name = $fileName;
            } else {
                $uploadFile = $this->file_path;
                $importData = ImportData::findOne($this->import_data_id);
            }

            $errorList = [];
            $successCount = 0;
            $errorMsg = [];
            $list = $this->read_csv($uploadFile);
            if (count($list) > 300) {
                throw new \Exception('单次最多上传300条商品数据');
            }
            $actionNum = 10;
            $minNum = $this->current_num * $actionNum - $actionNum;
            $maxNum = $this->current_num * $actionNum - 1;
            foreach ($list as $key => $item) {
                if ($key >= $minNum && $key <= $maxNum) {
                    try {                                                              
                        $this->saveGoods($item);
                        $successCount += 1;
                    } catch (\Exception $exception) {
                        $errorList[] = $item;
                        $errorItem = [];
                        $errorItem['line'] = $exception->getLine();
                        $errorItem['msg'] = $exception->getMessage();
                        $errorItem['name'] = $item['name'];
                        $errorMsg[] = $errorItem;
                    }
                    if ($key > $maxNum) {
                        break;
                    }
                }
            }

            if (count($errorList) > 0) {
                $newArr['error_msg'] = $errorMsg;
                $newArr['error_list'] = $errorList;
            } else {
                $newArr['error_msg'] = [];
                $newArr['error_list'] = [];
            }
            // 记录错误数据
            $option = CommonOption::get(Option::NAME_IMPORT_ERROR_LOG, \Yii::$app->mall->id, Option::GROUP_ADMIN);
            if (!$option) {
                $option = CommonOption::set(Option::NAME_IMPORT_ERROR_LOG, $newArr, \Yii::$app->mall->id, Option::GROUP_ADMIN);
            }
            // 追加 错误数据
            if (count($errorList) > 0 || $this->current_num <= 1) {
                if ($this->current_num > 1) {
                    $newArr['error_msg'] = array_merge($option['error_msg'], $newArr['error_msg']);
                    $newArr['error_list'] = array_merge($option['error_list'], $newArr['error_list']);
                }
                $option = CommonOption::set(Option::NAME_IMPORT_ERROR_LOG, $newArr, \Yii::$app->mall->id, Option::GROUP_ADMIN);
            }

            $importData->count = count($list);
            $importData->success_count = $importData->success_count > 0 ? $importData->success_count + $successCount : $successCount;
            $importData->error_count = count($list) - $importData->success_count;
            if (count($list) == $importData->success_count) {
                $importData->status = 3;
            } elseif (count($list) - $importData->success_count > 0) {
                $importData->status = 2;
            } else {
                $importData->status = 1;
            }
            $res = $importData->save();
            if (!$res) {
                throw new \Exception($this->getErrorMsg($importData));
            }

            $number = count($list) / $actionNum;
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '导入成功',
                'data' => [
                    'error_count' => $importData->error_count,
                    'success_count' => $importData->success_count,
                    'import_params' => [
                        'count' => count($list),
                        'num_count' => $number > 0 ? ceil($number) : 1,
                        'current_num' => (int)$this->current_num,
                        'file_path' => $uploadFile,
                        'import_data_id' => $importData->id,
                    ]
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'error' => [
                    'line' => $exception->getLine()
                ]
            ];
        }
    }


    public function read_csv($file)
    {
        try {
            setlocale(LC_ALL, 'zh_CN');//linux系统下生效
            if (!is_file($file) && !file_exists($file)) {
                throw new \Exception('csv文件错误');
            }

            $csvFile = fopen($file, 'r'); //开始读取csv文件数据
            $i = 0;//记录cvs的行
            $list = [];
            while ($file_data = fgetcsv($csvFile)) {
                $i++;
                if ($i == 1) {
                    $headers = $file_data;
                } else {
                    $newItem = [];
                    foreach ($headers as $key => $value) {
                        $newItem[$this->getNewData($value)] = $file_data[$key];
                    }
                    $list[] = $newItem;
                }
            }
            fclose($csvFile);

            $data = [];//返回的文件数据行
            foreach ($list as $key => $value) {
                $arr = [];
                $arr['name'] = $this->getNewData($value['商品名称']);
                $arr['original_price'] = $this->getNewData($value['原价']);
                $arr['cost_price'] = $this->getNewData($value['成本价']);
                $arr['detail'] = $this->getNewData($value['商品详情']);
                $arr['cover_pic'] = $this->getNewData($value['商品缩略图']);
                $arr['pic_url'] = json_decode($this->getNewData($value['商品轮播图']), true);
                $arr['video_url'] = $this->getNewData($value['商品视频']);
                $arr['unit'] = $this->getNewData($value['单位']);
                $arr['price'] = $this->getNewData($value['售价']);
                $arr['use_attr'] = $this->getNewData($value['是否使用规格']);
                $arr['attrGroups'] = json_decode($this->getNewData($value['规格组']), true);
                $arr['goods_num'] = $this->getNewData($value['商品库存']);
                $arr['virtual_sales'] = $this->getNewData($value['虚拟销量']);
                $arr['confine_count'] = $this->getNewData($value['购物数量限制']);
                $arr['pieces'] = $this->getNewData($value['单品满件包邮']);
                $arr['forehead'] = $this->getNewData($value['单品满额包邮']);
                $arr['give_integral'] = $this->getNewData($value['赠送积分']);
                $arr['give_integral_type'] = $this->getNewData($value['赠送积分类型']);
                $arr['forehead_integral'] = $this->getNewData($value['可抵扣积分']);
                $arr['forehead_integral_type'] = $this->getNewData($value['可抵扣积分类型']);
                $arr['accumulative'] = $this->getNewData($value['允许多件累计折扣']);
                $arr['sign'] = $this->getNewData($value['商品标识']);
                $arr['app_share_pic'] = $this->getNewData($value['自定义分享图片']);
                $arr['app_share_title'] = $this->getNewData($value['自定义分享标题']);
                $arr['sort'] = $this->getNewData($value['排序']);
                $arr['confine_order_count'] = $this->getNewData($value['限购订单']);
                $arr['is_area_limit'] = $this->getNewData($value['是否单独区域购买']);
                $arr['area_limit'] = json_decode($this->getNewData($value['区域限购详情']), true);
                $arr['attr'] = json_decode($this->getNewData($value['规格详情']), true);
                $arr['is_quick_shop'] = $this->getNewData($value['是否快速购买']);
                $arr['is_sell_well'] = $this->getNewData($value['是否热销']);
                $arr['is_negotiable'] = $this->getNewData($value['是否面议']);
                $arr['shipping_id'] = $this->getNewData($value['包邮规则']);

                // 多商户商品部分数据要默认值
                if (\Yii::$app->user->identity->mch_id > 0) {
                    $arr['give_integral'] = 0;
                    $arr['give_integral_type'] = 1;
                    $arr['forehead_integral'] = 0;
                    $arr['forehead_integral_type'] = 1;
                    $arr['accumulative'] = 0;
                }
                $data[] = $arr;
            }

            return $data;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getNewData($data)
    {
        // 解决乱码 IGNORE 当无法转某些特殊字符时 跳过
        $text = iconv('GBK', 'UTF-8//IGNORE', $data);
        return trim($text);
    }

    private function saveGoods($data)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $goodsWarehouse = new GoodsWarehouse();
            $goodsWarehouse->mall_id = \Yii::$app->mall->id;
            $goodsWarehouse->name = $data['name'];
            $goodsWarehouse->original_price = $data['original_price'];
            $goodsWarehouse->cost_price = $data['cost_price'];
            $goodsWarehouse->detail = $data['detail'];
            $goodsWarehouse->cover_pic = $data['cover_pic'];
            $goodsWarehouse->pic_url = \Yii::$app->serializer->encode($data['pic_url']);
            $goodsWarehouse->video_url = $data['video_url'];
            $goodsWarehouse->unit = $data['unit'];
            if (!$goodsWarehouse->save()) {
                throw new \Exception($this->getErrorMsg($goodsWarehouse));
            }

            $goods = new Goods();
            $goods->mall_id = \Yii::$app->mall->id;
            $goods->mch_id = \Yii::$app->user->identity->mch_id;
            $goods->goods_warehouse_id = $goodsWarehouse->id;
            $goods->virtual_sales = $data['virtual_sales'];
            $goods->price = $data['price'];
            $goods->use_attr = $data['use_attr'];
            $goods->attr_groups = \Yii::$app->serializer->encode($data['attrGroups']);
            $goods->app_share_title = $data['app_share_title'];
            $goods->app_share_pic = $data['app_share_pic'];

            $option = CommonOption::get(Option::NAME_MCH_MALL_SETTING, \Yii::$app->mall->id, Option::GROUP_APP);
            if (\Yii::$app->user->identity->mch_id > 0) {
                if ($option['is_goods_audit']) {
                    $goods->status = $this->goods_status ? 0 : $this->goods_status;
                } else {
                    $goods->status = $this->goods_status;
                }
            } else {
                $goods->status = $this->goods_status;
            }
            $goods->sort = $data['sort'];
            $goods->confine_count = $data['confine_count'];
            $goods->confine_order_count = $data['confine_order_count'];
            $goods->pieces = $data['pieces'];
            $goods->forehead = $data['forehead'];
            $goods->give_integral = $data['give_integral'];
            $goods->give_integral_type = $data['give_integral_type'];
            $goods->forehead_integral = $data['forehead_integral'];
            $goods->forehead_integral_type = $data['forehead_integral_type'];
            $goods->accumulative = $data['accumulative'];
            $goods->is_area_limit = $data['is_area_limit'];
            $goods->freight_id = 0;
            $goods->area_limit = \Yii::$app->serializer->encode($data['area_limit']);
            $goods->sign = \Yii::$app->user->identity->mch_id > 0 ? 'mch' : '';
            $goods->shipping_id = $data['shipping_id'];

            $newAttrData = [];
            $goodsStock = 0;
            foreach ($data['attr'] as $attrItem) {
                $newItem = [];
                $newItem[] = $attrItem['sign_id'];
                $newItem[] = $attrItem['stock'];
                $newItem[] = $attrItem['price'];
                $newItem[] = $attrItem['no'];
                $newItem[] = $attrItem['weight'];
                $newItem[] = $attrItem['pic_url'];
                $newItem[] = $attrItem['bar_code'];
                $newAttrData[] = $newItem;
                $goodsStock += $attrItem['stock'];
            }

            $goods->goods_stock = $goodsStock;
            if (!$goods->save()) {
                throw new \Exception($this->getErrorMsg($goods));
            }

            $mallGoods = new \app\models\MallGoods();
            $mallGoods->goods_id = $goods->id;
            $mallGoods->mall_id = \Yii::$app->mall->id;
            $mallGoods->is_quick_shop = $data['is_quick_shop'];
            $mallGoods->is_sell_well = $data['is_sell_well'];
            $mallGoods->is_negotiable = $data['is_negotiable'];
            if (!$mallGoods->save()) {
                throw new \Exception($this->getErrorMsg($mallGoods));
            }

            if (\Yii::$app->user->identity->mch_id > 0) {
                $mchGoods = new MchGoods();
                $mchGoods->mall_id = \Yii::$app->mall->id;
                $mchGoods->mch_id = \Yii::$app->user->identity->mch_id;
                $mchGoods->goods_id = $goods->id;
                $mchGoods->sort = $data['sort'];// TODO 这里排序需要优化
                if ($option['is_goods_audit'] == 1) {
                    $goods->status = $this->goods_status ? 0 : $this->goods_status;
                    $mchGoods->status = $this->goods_status ? 1 : 0;
                    $mchGoods->remark = $this->goods_status ? '申请上架' : '';
                } else {
                    $goods->status = $this->goods_status;
                    $mchGoods->status = $this->goods_status ? 2 : 0;
                    $mchGoods->remark = $this->goods_status ? '同意上架' : '';
                }
                $res = $mchGoods->save();
                if (!$res) {
                    throw new \Exception($this->getErrorMsg($mchGoods));
                }

                if (!$goods->save()) {
                    throw new \Exception($this->getErrorMsg($goods));
                }
            }

            // 分类
            $keys = ['goods_warehouse_id', 'cat_id'];
            $newData = [];
            foreach ($this->cat_ids as $catId) {
                $newItem = [];
                $newItem[] = $goodsWarehouse->id;
                $newItem[] = $catId;
                $newData[] = $newItem;
            }
            $res = \Yii::$app->db->createCommand()->batchInsert(GoodsCatRelation::tableName(), $keys, $newData)->execute();
            if (!$res) {
                throw new \Exception('商品分类异常');
            }

            // 商户分类
            if (\Yii::$app->user->identity->mch_id > 0) {
                if (!$this->system_cat_ids || count($this->system_cat_ids)  <= 0) {
                    throw new \Exception('请选择系统分类');
                }
                $keys = ['goods_warehouse_id', 'cat_id'];
                $newData = [];
                foreach ($this->system_cat_ids as $catId) {
                    $newItem = [];
                    $newItem[] = $goodsWarehouse->id;
                    $newItem[] = $catId;
                    $newData[] = $newItem;
                }
                $res = \Yii::$app->db->createCommand()->batchInsert(GoodsCatRelation::tableName(), $keys, $newData)->execute();
                if (!$res) {
                    throw new \Exception('商品分类异常');
                }
            }

            // 商品规格
            $keys = ['sign_id', 'stock', 'price', 'no', 'weight', 'pic_url', 'bar_code', 'goods_id'];
            foreach ($newAttrData as $key => $item) {
                $newAttrData[$key][] = $goods->id;
            }

            $res = \Yii::$app->db->createCommand()->batchInsert(GoodsAttr::tableName(), $keys, $newAttrData)->execute();
            if (!$res) {
                throw new \Exception('商品规格异常');
            }

            $transaction->commit();

            return true;
        } catch (\Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}