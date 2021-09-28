<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: fjt
 */
?>

<style>
    .theme-list {
        width: 60%;
    }
    .item {
        width: 127px;
        height: 61px;
        position: relative;
        border: 1px solid #e2e2e2;
        border-radius: 5px;
        margin-left: 20px;
        margin-bottom: 20px;
        overflow: hidden;
        cursor:pointer;
    }

    .item-active {
        border: 2px solid #3399ff;
    }
    .item:hover {
        margin-top: -3px;
        box-shadow: 0 4px 4px 4px #ECECEC;
    }
    .item .color {
        width: 46px;
        height: 33px;
        margin-right: 5px;
        transform-origin: 50% 50%;

        position: relative;
    }

    .color div {
        width: 25px;
        height: 25px;
        border-radius: 5px;
        position: absolute;
        top: 3.5px;
        transform: rotate(45deg);
    }
    .item .text {
        margin-left: 5px;
        font-size: 12px;
        color: #666666;
    }
    .deep {
        left: 3.5px;
    }
    .shallow {
        left: 18.5px;

    }
    .theme-show {
        height: 460px;
        width: 60%;
        margin-left: 20px;
        padding: 5px;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .theme-item {
        width: 250px;
        height: 100%;
        box-shadow: 0 10px 30px 3px #dddddd;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        position: relative;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }
    .style-tab {
        padding: 9px 25px;
        margin-bottom: 30px;
    }
    .style-label {
        text-align: right;
        width: 56px;
        margin-right: 36px;
    }
    .style-label-2 {
        width: 156px;
    }
    .diy-show {
        position: absolute;
        bottom: 6px;
        right: 9px;
        width: 178px;
        height: 25px;
    }
    .diy-show-left {
        height: 25px;
        width: 89px;
        border-top-left-radius: 30px;
        border-bottom-left-radius: 30px;
        font-size: 10px;
        text-align: center;
        line-height: 25px;
    }
    .diy-show-right {
        height: 25px;
        width: 89px;
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
        font-size: 10px;
        text-align: center;
        line-height: 25px;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;" v-loading="loading">
        <div slot="header">
            <div>
                <span>商城风格</span>
            </div>
        </div>
        <div style="background-color: #fff;padding: 20px 0;">
            <div flex="dir:left" class="style-tab">
                <div class="style-label">商城风格</div>
                <div>
                    <el-radio @change="changeStyle" v-model="mallStyle" :label="1">系统模版
                    </el-radio>
                    <el-radio @change="changeStyle" v-model="mallStyle" :label="2">自定义
                    </el-radio>
                </div>
            </div>
            <div class="theme-list" v-if="mallStyle == 1" flex="dir:left" style="flex-wrap: wrap">
                <div class="item" flex="dir:left main:center cross:center" :class="{'item-active': item.is_select}"  v-for="(item, index) in list" :key="index" v-if="item.key != 'custom'" @click="select(index)">
                    <div class="color">
                        <div class="deep" :style="{backgroundColor: item.color.secondary}"></div>
                        <div class="shallow"  :style="{backgroundColor: item.color.main}"></div>
                    </div>
                    <div class="text">{{item.name}}</div>
                </div>
            </div>
            <div v-if="mallStyle == 2" class="style-tab">
                <div flex="dir:left cross:center">
                    <div flex="dir:left cross:center">
                        <div class="style-label">主色</div>
                        <el-color-picker v-model="diy_color.main"></el-color-picker>
                    </div>
                    <div flex="dir:left cross:center">
                        <div class="style-label style-label-2">主色按钮文字颜色</div>
                        <el-color-picker v-model="diy_color.main_text"></el-color-picker>
                    </div>
                </div>
                <div flex="dir:left cross:center" style="margin-top: 30px;">
                    <div flex="dir:left cross:center">
                        <div class="style-label">辅色</div>
                        <el-color-picker v-model="diy_color.secondary"></el-color-picker>
                    </div>
                    <div flex="dir:left cross:center">
                        <div class="style-label style-label-2">辅色按钮文字颜色</div>
                        <el-color-picker v-model="diy_color.secondary_text"></el-color-picker>
                    </div>
                </div>
            </div>
            <div class="theme-show" flex="dir:left main:justify">
                <div v-if="mallStyle == 1" class="theme-item" v-for="item in pic_list" :style="{backgroundImage: `url(${item})`}"></div>
                <div v-if="mallStyle == 2" class="theme-item" v-for="(item,index) in pic_list" :style="{backgroundImage: `url(${item})`,backgroundColor: `${diy_color.main}`}">
                    <div v-if="index == 0" class="diy-show" flex="main:center cross:center">
                        <div class="diy-show-left" :style="{backgroundColor: `${diy_color.secondary}`,color: `${diy_color.secondary_text}`}">加入购物车</div>
                        <div class="diy-show-right" :style="{backgroundColor: `${diy_color.main}`,color: `${diy_color.main_text}`}">立即购买</div>
                    </div>
                </div>
            </div>
        </div>
        <el-button class="button-item" type="primary"  @click="onSubmit">保存</el-button>
    </el-card>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                mallStyle: 1,
                loading: true,
                list: [],
                timeout: -1,
                index: 0,
                pic_list: [],
                diy_color: {
                    main: '#ff4544',
                    secondary: '#f39800',
                    main_text: '#ffffff',
                    secondary_text: '#ffffff',
                },
            };
        },
        methods: {
            changeStyle(e) {
                if(e == 2) {
                    for(let item of this.list) {
                        if(item.key == 'custom') {
                            this.pic_list = item.pic_list
                        }
                    }
                }else {
                    for(let i in this.list) {
                        if(this.index == i) {
                            this.list[i].is_select = true;
                            this.pic_list = this.list[this.index].pic_list;
                        }
                    }
                }
            },
            select(index) {
                this.list.map(item => {
                    item.is_select = false;
                });
                this.index = index;
                this.list[index].is_select = true;
                this.pic_list = this.list[index].pic_list;

            },

            onSubmit() {
                this.save();
            },

            async save() {
                this.loading = true;
                try {
                    let para = {
                        theme_color: this.mallStyle == 1 ? this.list[this.index].key : 'custom',
                    }
                    if(this.mallStyle == 2) {
                        para.main = this.diy_color.main
                        para.secondary = this.diy_color.secondary
                        para.main_text = this.diy_color.main_text
                        para.secondary_text = this.diy_color.secondary_text
                    }
                    const e = await request({
                        params: {
                            r: '/mall/theme-color/index',
                        },
                        data: para,
                        method: 'post',
                    });
                    this.loading = false;
                    if (e.data.code === 0) {
                    } else {
                        this.$message.error(e.data.msg);
                    }
                } catch(e) {
                    this.$message.error(e);
                }
            }
        },
        mounted: function () {
            request({
                params: {
                    r: '/mall/theme-color/index'
                },
                method: 'get',
            }).then(e => {
                this.loading = false;
                if (e.data.code === 0) {
                    this.list = e.data.data.list;
                    this.list.map(item => {
                        if (item.is_select) {
                            if(item.key == 'custom') {
                                this.mallStyle = 2;
                                this.diy_color = item.color;
                            }
                            this.pic_list = item.pic_list;
                        }
                    })
                } else {
                    this.$message.error(e.data.msg);
                }
            }).catch(e => {
                this.$message.error(e.data.msg);
                this.loading = false;
            });
        },
    });
</script>
