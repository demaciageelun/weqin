<?php
/**
 * ALIPAY API: alipay.trade.precreate request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 17:32:25
 */

namespace Alipay\Request;

class AlipayTradePrecreateRequest extends AbstractAlipayRequest
{
    /**
     * 收银员通过收银台或商户后台调用支付宝接口，生成二维码后，展示给伤脑筋户，由用户扫描二维码完成订单支付。
     * 修改路由策略到R
     **/
    private $bizContent;

    private $appCertSn;

    private $alipayRootCertSn;

    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParams['biz_content'] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }

    /**
     * @return mixed
     */
    public function getAppCertSn()
    {
        return $this->appCertSn;
    }

    /**
     * @param mixed $appCertSn
     */
    public function setAppCertSn($appCertSn): void
    {
        $this->appCertSn = $appCertSn;
        $this->apiParams['app_cert_sn'] = $appCertSn;
    }

    /**
     * @return mixed
     */
    public function getAlipayRootCertSn()
    {
        return $this->alipayRootCertSn;
    }

    /**
     * @param mixed $alipayRootCertSn
     */
    public function setAlipayRootCertSn($alipayRootCertSn): void
    {
        $this->alipayRootCertSn = $alipayRootCertSn;
        $this->apiParams['alipay_root_cert_sn'] = $alipayRootCertSn;
    }
}
