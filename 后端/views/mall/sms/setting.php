<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

$mchId = Yii::$app->user->identity->mch_id;
Yii::$app->loadViewComponent('app-sms-setting');
?>
<style>
    .el-card__body {
        background-color: #F3F3F3;
        padding: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0">
        <div slot="header">
            <div>
                <span>短信配置</span>
            </div>
        </div>
        <app-sms-setting></app-sms-setting>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                mch_id: <?= $mchId ?>,
            };
        },
    });
</script>
