<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/12/8
 * Time: 14:01
 */
Yii::$app->loadViewComponent('app-mail-setting');
?>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span>邮件管理（QQ邮箱）</span>
        </div>
        <app-mail-setting></app-mail-setting>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
    });
</script>
