<?php
/**
 * ALIPAY API: alipay.trade.pay request
 *
 * @author auto create
 *
 * @since  1.0, 2018-06-14 18:10:00
 */

namespace Alipay\Request;

class AlipayTradePayRequest extends AbstractAlipayRequest
{
    /**
     * 用于在线下场景交易一次创建并支付掉
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
