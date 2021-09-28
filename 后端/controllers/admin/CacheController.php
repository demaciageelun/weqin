<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2019/3/15
 * Time: 11:01
 */

namespace app\controllers\admin;


use app\core\response\ApiCode;
use app\jobs\ClearCacheJob;

class CacheController extends AdminController
{
    public function actionClean()
    {
        if (\Yii::$app->request->isAjax) {
            if (\Yii::$app->request->isPost) {
                $form = \Yii::$app->request->post();
                $job = new ClearCacheJob();
                if (isset($form['data']) && $form['data'] == 'true') {
                    $job->data = true;
                }
                if (isset($form['file']) && $form['file'] == 'true') {
                    $job->file = true;
                }
                if (isset($form['update']) && $form['update'] == 'true') {
                    $job->update = true;
                }
                \Yii::$app->queue3->delay(0)->push($job);
                $job->execute(\Yii::$app->queue3);
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '清理成功。',
                    'data' => $form,
                ];
            }
        } else {
            return $this->render('clean');
        }
    }
}
