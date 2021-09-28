<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\core\response;


class ApiCode
{
    /**
     *  状态码：成功
     */
    const CODE_SUCCESS = 0;

    /**
     * 状态码：失败
     */
    const CODE_ERROR = 1;

    /**
     * 状态码：未登录
     */
    const CODE_NOT_LOGIN = -1;

    /**
     * 状态码：商城禁用
     */
    const CODE_STORE_DISABLED = -2;
    /**
     * 状态码：多商户未登录
     */
    const CODE_MCH_NOT_LOGIN = -3;
    /**
     * 状态码：未关注公众号
     */
    const CODE_WECHAT_SUBSCRIBE = 2;
}
