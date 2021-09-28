<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: xay
 */

namespace app\plugins\step\controllers\mall;

use app\plugins\Controller;
use app\forms\mall\order\OrderDestroyForm;
use app\forms\mall\order\OrderDetailForm;
use app\plugins\step\forms\mall\OrderForm;
use app\plugins\step\forms\mall\StepOrderForm;

class OrderController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new StepOrderForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->search());
        } else {
            return $this->render('index');
        }
    }

    //订单详情
    public function actionDetail()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderDetailForm();
            $form->attributes = \Yii::$app->request->get();
            return $this->asJson($form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->search());
        } else {
            return $this->render('detail');
        }
    }

    //清空回收站
    public function actionDestroyAll()
    {
        if (\Yii::$app->request->isPost) {
            $form = new OrderDestroyForm();
            return $this->asJson($form->setSign(\Yii::$app->plugin->getCurrentPlugin()->getName())->destroyAll());
        }
    }
}
