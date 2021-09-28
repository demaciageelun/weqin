<?php


namespace app\forms\mall\statistics;


use app\core\response\ApiCode;
use app\forms\mall\export\CommonExport;
use app\forms\mall\export\IntegralStatisticsExport;
use app\forms\mall\export\jobs\ExportJob;
use app\models\IntegralLog;
use app\models\Model;
use app\models\UserInfo;

class IntegralForm extends Model
{
    public $date_start;
    public $date_end;

    public $page;
    public $limit;

    public $flag;
    public $fields;

    public $platform;

    public function rules()
    {
        return [
            [['flag', 'platform'], 'string'],
            [['page', 'limit'], 'integer'],
            [['page',], 'default', 'value' => 1],
            [['date_start', 'date_end', 'fields'], 'trim']
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $query = $this->where();

        $query->select(["DATE_FORMAT(`il`.`created_at`, '%Y-%m-%d') AS `date`,
                         COALESCE(SUM(CASE `il`.`type` WHEN 1 THEN `il`.`integral` ELSE 0 END), 0) AS in_integral,
                         COALESCE(SUM(CASE `il`.`type` WHEN 2 THEN `il`.`integral` ELSE 0 END), 0) AS out_integral"]);

        if ($this->flag == "EXPORT") {
            $new_query = clone $query;
            $new_query->groupBy('`date`')->orderBy('`date` desc');
            return $this->export($new_query);
        }

        $all_query = clone $query;
        $all_data = $all_query->asArray()->one();

        $list = $query->groupBy('`date`')
            ->orderBy('`date` desc')
            ->page($pagination)
            ->asArray()
            ->all();

        unset($all_data[0]['date']);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'pagination' => $pagination,
                'all_data' => $all_data,
                'list' => $list,
            ]
        ];
    }

    protected function where()
    {
        $query = IntegralLog::find()->alias('il')->where(['il.mall_id' => \Yii::$app->mall->id,])
            ->leftJoin(['i' => UserInfo::tableName()], 'i.user_id = il.user_id');

        //时间查询
        if ($this->date_start) {
            $query->andWhere(['>=', 'il.created_at', $this->date_start . ' 00:00:00']);
        }

        if ($this->date_end) {
            $query->andWhere(['<=', 'il.created_at', $this->date_end . ' 23:59:59']);
        }
        //平台标识查询
        if ($this->platform) {
            $query->andWhere(['i.platform' => $this->platform]);
        }
        return $query;
    }


    protected function export($query)
    {
        $queueId = CommonExport::handle([
            'export_class' => 'app\\forms\\mall\\export\\IntegralStatisticsExport',
            'params' => [
                'query' => $query
            ]
        ]);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'queue_id' => $queueId
            ]
        ];
    }
}