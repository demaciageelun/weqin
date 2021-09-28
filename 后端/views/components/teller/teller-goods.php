<?php
?>
<style>
    .teller-goods.pad .teller-goods-item {
        width: 176px;
        height: 308px;
    }
    .teller-goods.pad .teller-goods-item .dialog-out {
        height: 176px;
    }
    .teller-goods.pad .teller-goods-item .el-image {
        height: 176px;
    }
    .teller-goods {
        flex-wrap: wrap;
        height: 100%;
        overflow: auto;
    }
    .teller-goods .teller-goods-item {
        width: 212px;
        height: 344px;
        background-color: #fff;
        border-radius: 16px;
        margin: 0 15px 20px 0;
        cursor: pointer;
        position: relative;
    }
    .teller-goods .teller-goods-item .dialog-out {
        position: absolute;
        width: 100%;
        height: 212px;
        border-radius: 16px;
        background-color: rgba(0,0,0,.5);
        top: 0;
        left: 0;
        z-index: 5;
    }
    .teller-goods .teller-goods-item .el-image {
        border-radius: 16px;
        width: 100%;
        height: 212px;
        flex-shrink: 0;
    }
    .teller-goods .teller-goods-item .teller-goods-info {
        padding: 10px 16px 15px;
        position: relative;
        color: #969391;
        font-size: 13px;
    }
    .teller-goods .teller-goods-item .teller-goods-info .teller-goods-name {
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        white-space: normal !important;
        color: #353535;
        height: 42px;
        margin-top: 10px;
        font-size: 16px;
    }
    .teller-goods .teller-goods-item .teller-goods-info .teller-goods-price {
        color: #ff4544;
        margin: 5px 0;
        font-size: 18px;
    }
    .teller-goods .teller-goods-item .teller-goods-info .add-teller-goods {
        width: 36px;
        height: 36px;
        position: absolute;
        right: 18px;
        bottom: 16px;
    }
</style>
<template id="teller-goods">
    <div class="teller-goods" :infinite-scroll-disabled="!list || list.length == 0" v-infinite-scroll="load" :class="pad ? 'pad': ''" flex="dir:left" :style="{'padding-top': paddingTop + 'px'}" v-if="list.length > 0">
        <div @click="toDetail(item)" class="teller-goods-item" v-for="(item,index) in list" :key="item.id">
            <el-image fit="cover" lazy :src="item.cover_pic" alt=""></el-image>
            <img class="dialog-out" v-if="item.stock == 0 || item.goods_stock == 0" src="statics/img/app/mall/plugins-out.png" alt="">
            <div class="teller-goods-info">
                <div class="teller-goods-name">{{item.name}}</div>
                <div class="teller-goods-price">{{item.price == '0.00' ? '免费' : '￥' + item.price}}</div>
                <div v-if="item.stock || item.stock == 0 || item.goods_stock || item.goods_stock == 0">库存{{item.stock || item.stock == 0 ? item.stock : item.goods_stock}}</div>
                <img v-if="item.stock > 0" class="add-teller-goods" src="statics/img/plugins/teller-add.png" alt="">
            </div>
        </div>
    </div>
    <div flex="dir:top main:center cross:center"  :style="{'padding-top': paddingTop + 'px','height': '80%'}" v-else>
        <img src="./../plugins/teller/assets/img/no-goods.png" alt="">
        <div style="color: #999;">暂无任何商品</div>
    </div>
</template>
<script>
    Vue.component('teller-goods', {
        template: '#teller-goods',
        props: {
            list: Array,
            pad: Boolean,
            name: String,
            top: {
                type: Number,
                default: 71
            }
        },
        watch: {
            name: {
                handler(newValue,oldValue) {
                    if(newValue != oldValue) {
                        this.paddingTop = this.top;
                    }
                }
            }
        },
        data() {
            return {
                paddingTop: 71
            }
        },
        created() {
            this.paddingTop = this.top;
            if(this.pad) {
                this.paddingTop = +this.top - 10;
            }
        },
        methods: {
            load() {
                if(this.list.length > 0) {
                    this.$emit('load','')
                }
            },
            toDetail(item) {
                this.$emit('click', item)
            },

        }
    });
</script>
