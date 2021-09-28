<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.9ysw.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/14 13:49
 */
?>
<style>
    .app-wechat-info {
        height: 50px;
        line-height: 50px;
        background-color: #e1f0ff;
        color: #409eff;
        width: 100%;
    }
    .app-wechat-info.position {
        position: absolute;
        left: 0;
    }
    .app-wechat-info>.text {
        padding: 0 20px 0 28px;
    }
    .placeholder {
        height: 50px;
    }
</style>
<template id="app-wechat-info">
    <div>
        <div class="app-wechat-info" :class="{position : float}" :style="{top: top + 'px'}" flex="dir:left cross:center">
            <div class="text">{{setting ? '点击查看公众号配置信息': '点此快速配置公众号'}}</div>
            <div flex="cross:center">
                <el-button @click="toWechat" type="primary" size="small">{{setting ? '立即查看' :'立即配置'}}</el-button>
            </div>
        </div>
        <div class="placeholder" v-if="float"></div>
    </div>
</template>
<script>
Vue.component('app-wechat-info', {
    template: '#app-wechat-info',
    props: {
        setting: Boolean,
        float: Boolean,
        top: {
            type: Number,
            default: 0
        },
    },
    data() {
        return {
        };
    },
    methods: {
        toWechat() {
            this.$navigate({
                r: 'mall/wechat/setting',
            });
        }
    },
});
</script>
