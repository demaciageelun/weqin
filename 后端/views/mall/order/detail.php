<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.9ysw.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/29 15:59
 */
Yii::$app->loadViewComponent('app-order-detail');
?>
<div id="app" v-cloak>
    <app-order-detail></app-order-detail>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {

            };
        },
        created() {
        },
        methods: {

        }
    })
</script>