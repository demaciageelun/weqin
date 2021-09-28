<?php

namespace app\plugins\region\forms\common;

use app\core\payment\PaymentTransfer;
use app\forms\common\platform\PlatformConfig;
use app\models\Model;
use app\models\User;
use app\plugins\bonus\models\BonusCash;
use app\plugins\region\forms\mall\CommonForm;
use app\plugins\region\models\RegionSetting;
use yii\db\Exception;

/**
 * @property BonusCash $bonusCash
 * @property User $user
 */
class CommonRegionCash extends Model
{
    public $bonusCash;
    public $user;

    public $actual_price;
    public $setting;

    /**
     * @return mixed
     * @throws Exception
     * 打款
     */
    public function remit()
    {
        $this->user = User::find()
            ->where(['id' => $this->bonusCash->user_id, 'is_delete' => 0, 'mall_id' => $this->bonusCash->mall_id])
            ->with('userInfo')->one();
        $this->setting = RegionSetting::getList(['mall_id' => \Yii::$app->mall->id]);
        $minPrice = round($this->setting[RegionSetting::FREE_CASH_MIN], 2);
        $serviceCharge = round($this->bonusCash->price * $this->bonusCash->service_charge / 100, 2);
        if ($this->bonusCash->price >= $minPrice) {
            $serviceCharge = 0;
        }
        $this->actual_price = round($this->bonusCash->price - $serviceCharge, 2);

        $type = $this->bonusCash->type;
        if (method_exists($this, $type)) {
            return $this->$type();
        } else {
            throw new Exception('错误的提现方式', $this->bonusCash);
        }
    }

    // 微信手动打款
    private function wechat()
    {
        return true;
    }

    // 支付宝手动打款
    private function alipay()
    {
        return true;
    }

    // 银行手动打款
    private function bank()
    {
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \app\core\payment\PaymentException
     * 根据用户身份自动打款（微信/支付宝）
     */
    private function auto()
    {
        $paymentTransfer = new PaymentTransfer(
            [
                'orderNo' => $this->bonusCash->order_no,
                'amount' => round($this->actual_price, 2),
                'user' => $this->user,
                'title' => '区域代理提现',
                'transferType' => PlatformConfig::getInstance()->getPlatform($this->user, true)
            ]
        );
        \Yii::$app->payment->transfer($paymentTransfer);
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     * 打款的余额
     */
    private function balance()
    {
        $desc = "区域代理提现到余额，提现金额：{$this->bonusCash->price},提现手续费：{$this->bonusCash->service_charge}%";
        \Yii::$app->currency->setUser($this->user)->balance->add($this->actual_price, $desc);
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     * 驳回
     */
    public function reject()
    {
        $this->user = User::find()
            ->where(['id' => $this->bonusCash->user_id, 'is_delete' => 0, 'mall_id' => $this->bonusCash->mall_id])
            ->with('userInfo')->one();
        $desc = "区域代理提现退回，退回佣金：{$this->bonusCash->price}";
        CommonForm::bonusCash($this->bonusCash->user_id, $this->bonusCash->price, 1, $desc);
        return true;
    }
}
