<?php
?>
<style>
    .teller-order {
        overflow-y: auto;
        height: 100%;
    }
    .teller-order .hung-list {
        background-color: #f5f9fc;
        border-radius: 16px;
        padding: 16px;
        position: relative;
        margin-bottom: 12px;
    }
    .teller-order .hung-list .order-info {
        margin-bottom: 16px;
        color: #999999;
    }
    .teller-order .hung-list .order-info>div {
        margin-right: 46px;
    }
    .teller-order .hung-list .order-info span {
        color: #353535
    }
    .teller-order .hung-goods-item {
        width: 120px;
        border-radius: 16px;
        background-color: #fff;
        margin-bottom: 16px;
        margin-right: 10px;
        color: #353535;
    }
    .teller-order .hung-goods-item .el-image {
        width: 120px;
        height: 110px;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
    .teller-order .hung-goods-item .hung-goods-info {
        padding: 6px 10px;
        font-size: 13px;
    }
    .teller-order .hung-goods-item .hung-goods-info .hung-goods-name {
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .teller-order .hung-list .handle-hung {
        position: absolute;
        right: 27px;
        bottom: 16px;
    }
    .teller-order .hung-item {
        width: 100%;
        overflow-x: auto;
    }
</style>
<template id="teller-order">
    <div class="teller-order" v-infinite-scroll="load" :infinite-scroll-disabled="stop">
        <div v-for="(count,idx) in list" class="hung-list" :key="idx">
            <div v-if="count.order_no" class="order-info" flex="dir:left cross:center">
                <div>订单号：<span>{{count.order_no}}</span></div>
                <div v-if="count.sales_name">导购员：<span>{{count.sales_name}}</span></div>
                <div>收银员：<span>{{count.cashier_name}}</span></div>
            </div>
            <div flex="dir:left" class="hung-item">
                <div class="hung-goods-item" v-for="(item,index) in count.detail" :key="item.id">
                    <el-image fit="cover" lazy :src="item.cover_pic" alt=""></el-image>
                    <div class="hung-goods-info">
                        <div class="hung-goods-name">{{item.name}}</div>
                        <div style="color: #999999;margin: 4px 0;">x{{item.num}}</div>
                        <div style="color: #ff4544;">￥{{type == 'order' ? item.total_price : item.price}}</div>
                    </div>
                </div>
            </div>
            <div style="margin-bottom: 4px;">订单总价 <span style="color: #ff4544;">￥{{type == 'order' ? count.total_pay_price : count.total}}</span><span style="margin-left: 16px;color: #999999">等{{type == 'order' ? count.goods_count: count.list.length}}件商品</span></div>
            <div style="width: 60%;">
                <el-tag v-if="count.seller_remark || count.remark" type="warning" size="small" style="border:0;margin: 5px 0">{{ type == 'order' ?  count.seller_remark : count.remark }}</el-tag>
            </div>
            <div class="handle-hung" v-if="type == 'order'">
                <el-button size="small" @click="addRemark(idx, 'order')" round>{{count.seller_remark ? '修改备注':'添加备注'}}</el-button>
                <el-button size="small" @click="toOrderDetail(count.order_id)" round>详情</el-button>
            </div>
            <div class="handle-hung" v-if="type == 'hung'">
                <el-button size="small" @click="addCount(idx)" round>取单</el-button>
                <el-button size="small" @click="addRemark(idx)" round>{{count.remark ? '修改备注':'添加备注'}}</el-button>
                <el-button size="small" @click="delHung(idx)" round>删除</el-button>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('teller-order', {
        template: '#teller-order',
        props: {
            list: Array,
            stop: Boolean,
            type: {
                type: String,
                default: 'order'
            }
        },
        data() {
            return {

            }
        },
        methods: {
            load() {
                console.log(this.list.length)
                if(this.list.length > 0) {
                    this.$emit('load','')
                }
            },
            addCount(index) {
                this.$emit('add', index)
            },
            addRemark(index) {
                this.$emit('remark', index, this.type)
            },
            toOrderDetail(index) {
                this.$emit('click', index)
            },
            delHung(index) {
                this.$emit('change', index)
            }
        }
    });
</script>
