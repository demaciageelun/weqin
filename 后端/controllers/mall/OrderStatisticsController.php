<?php


namespace app\controllers\mall;


use app\forms\mall\statistics\OrderForm;

class OrderStatisticsController extends MallController
{
    public function actionStore()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->searchStore());
        }
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $form = new OrderForm();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->search());
        } else {
            return $this->render('index');
        }
    }

    public function actionCats_list()
    {
        $form = new OrderForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->cats_search());
    }
    
    public function actionShop()
    {
        return $this->render('new-shop');
    }

    public function actionMch()
    {
        return $this->render('mch');
    }
}