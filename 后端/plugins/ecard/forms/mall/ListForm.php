<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2020/3/11
 * Time: 14:19
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace app\plugins\ecard\forms\mall;


use app\forms\common\ecard\CommonEcard;
use app\forms\mall\export\CommonExport;
use app\models\EcardData;
use app\plugins\ecard\forms\Model;
use app\models\Ecard;
use app\models\EcardOptions;
use yii\helpers\Json;

/**
 * Class ListForm
 * @package app\plugins\ecard\forms\mall
 * @property Ecard $ecard
 * @property CommonEcard $commonEcard
 */
class ListForm extends Model
{
    public $keyword;
    public $date_start;
    public $date_end;
    public $ecard_id;
    public $status;
    public $page;
    public $flag;

    private $commonEcard;

    public function rules()
    {
        return [
            [['ecard_id', 'status', 'page'], 'integer'],
            [['keyword', 'date_start', 'date_end', 'flag'], 'trim'],
            [['keyword', 'date_start', 'date_end', 'flag'], 'string'],
            [['status'], 'default', 'value' => -1],
            [['flag'], 'default', 'value' => '']
        ];
    }

    public function init()
    {
        parent::init();
        $this->commonEcard = CommonEcard::getCommon();
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $ecard = $this->commonEcard->getEcardArray($this->commonEcard->getEcard($this->ecard_id));
            $newList = [];
            /* @var EcardOptions[] $list */
            $query = $this->getData($ecard);
            $query->page($pagination, 20, $this->page);
            $list = $query->all();
            foreach ($list as $item) {
                if (!isset($newList[$item->token])) {
                    $newList[$item->token] = [
                        'count' => 1,
                        'created_at' => $item->created_at,
                        'is_sales' => $item->is_sales,
                        'is_delete' => $item->is_delete,
                        'token' => $item->token,
                    ];
                }
                $data = Json::decode($item->value, true);
                foreach ($data as $value) {
                    $newList[$item->token]['key' . $newList[$item->token]['count']] = $value['value'];
                    $newList[$item->token]['count']++;
                }
            }
            $newList = array_values($newList);
            return $this->success([
                'list' => $newList,
                'pagination' => $pagination,
                'ecard' => $ecard
            ]);
        } catch (\Exception $exception) {
            return $this->fail(['msg' => $exception->getMessage()]);
        }
    }

    protected function getData($ecard)
    {
        $first = $ecard['key'];
        $pagination = null;
        /* @var EcardOptions[] $list */
        $token = null;
        $query = EcardOptions::find()
            ->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'ecard_id' => $ecard['id']])
            ->keyword($this->status != -1, ['is_sales' => $this->status])
            ->keyword($this->date_start, ['>=', 'created_at', $this->date_start])
            ->keyword($this->date_end, ['<=', 'created_at', $this->date_end]);
        if ($this->keyword !== '') {
            $token = EcardData::find()
                ->where(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'ecard_id' => $ecard['id']])
                ->keyword($this->keyword !== '', ['and', ['key' => $first], ['like', 'value', $this->keyword]])
                ->select('token');
        }
        $query = $query->keyword($token && !empty($token), ['token' => $token])
            ->orderBy(['is_sales' => SORT_ASC, 'id' => SORT_DESC]);
        return $query;
    }

    public function export()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $ecard = $this->commonEcard->getEcard($this->ecard_id);
            $model = new ExportForm();
            $model->ecard = $ecard;
            $model->ecardOptions = [];
            $model->exportData();
        } catch (\Exception $exception) {
            return $this->fail(['msg' => $exception]);
        }
    }
    
    public function newExport()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if ($this->flag == 'EXPORT') {
            $ecard = $this->commonEcard->getEcard($this->ecard_id);
            // 导出数据
            $queueId = CommonExport::handle([
                'export_class' => NewExportForm::className(),
                'params' => [
                    'ecard' => $ecard,
                    'query' => $this->getData($this->commonEcard->getEcardArray($ecard))
                ]
            ]);
            return $this->success([
                'queue_id' => $queueId
            ]);
        } else {
            $this->export();
        }
    }
}
