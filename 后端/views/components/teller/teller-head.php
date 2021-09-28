<?php
?>
<style>
    .teller-title {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
        width: 100%;
        height: 70px;
        padding: 0 10px;
        background-color: #fff;
        font-size: 16px;
        color: #666666;
        padding-left: 20px;
    }
    .teller-mall-title {
        max-width: 420px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .teller-title.pad {
        height: 5.73%;
        font-size: 15px;
    }
    .teller-title.pad .teller-cash-info div {
        font-size: 15px;
    }
    .teller-title.pad img {
        height: 28px;
        width: 28px;
    }
    .teller-title img {
        height: 40px;
        width: 40px;
        margin-right: 20px;
    }
    .teller-cash-info div {
        font-size: 16px;
        padding: 0 30px;
        color: #3399ff;
        cursor: pointer;
    }
    .teller-cash-info div:last-of-type {
        color: #666666;
        cursor: auto;
    }
</style>
<template id="teller-head">
    <div class="teller-title" :class="pad ? 'pad':''" flex="main:justify cross:center">
        <div class="app-teller-title" flex="dir:left cross:center">
            <img v-if="mall.logo" :src="mall.logo" alt="">
            <div class="teller-mall-title">{{mall.name}}<span style="margin-left: 10px;">{{mall.store_name}}</span></div>
        </div>
        <div class="teller-cash-info" flex="dir:right cross:center">
            <div @click="loginout">退出登录</div>
            <div v-if="setting && setting.is_shifts == 1" @click="transition"  :style="{'color': cashier_info ? '#353535' : '#3399ff'}">交班</div>
            <div @click="showInfo" :style="{'color': is_cashier ? '#353535' : '#3399ff'}">个人信息</div>
            <div v-if="nickname">收银员：{{nickname}}</div>
        </div>
    </div>
</template>
<script>
    Vue.component('teller-head', {
        template: '#teller-head',
        props: {
            mall: Object,
            nickname: String,
            setting: Object,
            cashier_info: Boolean,
            is_cashier: Boolean,
            pad: Boolean
        },
        data() {
            return {

            }
        },
        methods: {
            loginout() {
                this.$emit('out', '')
            },
            transition() {
                this.$emit('change', '')
            },
            showInfo() {
                this.$emit('show', '')
            },

        }
    });
</script>
