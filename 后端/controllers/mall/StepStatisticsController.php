<?php


namespace app\controllers\mall;


class StepStatisticsController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            $plugin = \Yii::$app->plugin->getPlugin('step');
            $form = $plugin->getApi();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->search());
        } else {
            return $this->render('index');
        }
    }

    public function actionEx()
    {
        if (\Yii::$app->request->isAjax) {
            $plugin = \Yii::$app->plugin->getPlugin('step');
            $form = $plugin->getApi();
            $form->attributes = \Yii::$app->request->get();
            $form->attributes = \Yii::$app->request->post();
            return $this->asJson($form->ex_search());
        } else {
            return $this->render('ex');
        }
    }
}