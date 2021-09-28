<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 16:10
 */


namespace app\controllers;


use app\forms\BdCaptchaAction;
use app\plugins\url_scheme\models\UrlScheme;
use yii\captcha\CaptchaAction;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'pic-captcha' => [
                'class' => BdCaptchaAction::class,
                'minLength' => 4,
                'maxLength' => 5,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(\Yii::$app->urlManager->createUrl(['admin/index/index']));
    }

    public function actionInstallPlugin($name)
    {
        var_dump(\Yii::$app->plugin->install($name));
    }

    public function actionScheme($id)
    {
        $model = UrlScheme::findOne($id);
        $url = '';
        if ($model) {
            $url = $model->url_scheme;
        }
        return $this->render('scheme', [
            'url' => $url
        ]);
    }
}
