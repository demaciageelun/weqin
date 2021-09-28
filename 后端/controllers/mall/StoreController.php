<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

namespace app\controllers\mall;


use app\forms\mall\store\StoreEditForm;
use app\forms\mall\store\StoreForm;

class StoreController extends MallController
{
    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
            } else {
                $form = new StoreForm();
                $form->attributes = \Yii::$app->request->get();
                $list = $form->getList();

                return $this->asJson($list);
            }
        } else {
            return $this->render('index');
        }
    }

    /**
     * 添加、编辑
     * @return string|\yii\web\Response
     */
    public function actionEdit()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = new StoreEditForm();
                $form->attributes = \Yii::$app->request->post('form');
                $res = $form->save();

                return $this->asJson($res);
            } else {
                $form = new StoreForm();
                $form->attributes = \Yii::$app->request->get();
                $detail = $form->getDetail();

                return $this->asJson($detail);
            }
        } else {
            return $this->render('edit');
        }
    }

    public function actionSwitchStatus(){
        if (\Yii::$app->request->isPost) {
            $form = new StoreForm();
            $form->attributes = \Yii::$app->request->post();
            $res = $form->switchStatus();

            return $this->asJson($res);
        }
    }
    /**
     * 删除
     * @return \yii\web\Response
     */
    public function actionDestroy()
    {
        $form = new StoreForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->destroy();

        return $this->asJson($res);
    }

    /**
     * 默认门店
     */
    public function actionSwitchDefault()
    {
        $form = new StoreForm();
        $form->attributes = \Yii::$app->request->post();
        $res = $form->switchDefault();

        return $this->asJson($res);
    }
}
