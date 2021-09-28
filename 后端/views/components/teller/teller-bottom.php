<?php
?>
<style>
    .teller-bottom {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 8.59%;
        background-color: #fff;
        padding-left: 28px;
        z-index: 10;
    }
</style>
<template id="teller-bottom">
    <div class="teller-bottom" flex="cross:center" v-if="setting">
        <el-button @click="toHung" round>挂单
        </el-button>
        <el-button :type="show ? 'primary':''" class="hung-btn" @click="getOrder" round>取单
            <div v-if="length > 0" class="hung">{{length}}</div>
        </el-button>
        <el-button v-if="setting.is_add_money == 1" @click="toggleView('addMoney')" :type="add ? 'primary':''" round>加钱</el-button>
        <el-button v-if="setting.is_goods_change_price == 1" @click="toggleView('changePrice')" :type="change ? 'primary':''" round>改价</el-button>
        <el-button v-if="setting.is_member_topup == 1" @click="toggleView('addCredit')" :type="credit ? 'primary':''" round>会员充值</el-button>
    </div>
</template>
<script>
    Vue.component('teller-bottom', {
        template: '#teller-bottom',
        props: {
            setting: Object,
            length: Number,
            add: Boolean,
            change: Boolean,
            credit: Boolean,
            show: Boolean
        },
        data() {
            return {

            }
        },
        methods: {
            toHung() {
                this.$emit('to', '')
            },
            getOrder() {
                this.$emit('get', '')
            },
            toggleView(type) {
                this.$emit('click', type)
            },

        }
    });
</script>
