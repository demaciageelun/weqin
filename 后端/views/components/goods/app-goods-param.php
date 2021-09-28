<?php
Yii::$app->loadViewComponent('goods/app-params-template');
?>
<style>

    .app-goods-param .box {
        border: 1px solid #EBEEF5;
        padding: 10px;
        width: 130%;
    }

    .app-goods-param .box .bg {
        height: 44px;
        padding: 0 10px;
        background: #f8f8f8;
    }

    .app-goods-param .box .del-img {
        height: 20px;
        width: 20px;
        cursor: pointer;
    }

    .app-goods-param .d-select {
        cursor: move;
    }
    .app-goods-param .x:before {
        content: '*';
        color: #F56C6C;
        margin-right: 4px;
    }
</style>
<template id="app-goods-param">
    <div class="app-goods-param">
        <el-form-item prop="param_name" label="参数标题">
            <el-input placeholder="请输入参数标题" v-model="value.param_name" maxlength="20" show-word-limit></el-input>
        </el-form-item>
        <el-form-item prop="param_content" label="参数内容">
            <div class="box">
                <draggable :options="{draggable:'.d-select',filter:'.d-filter',preventOnFilter:false}"
                           v-model="value.param_content">
                    <div class="d-select" v-for="(group, i) of value.param_content">
                        <div flex="dir:left cross:center" class="bg">
                            <span style="user-select:none;" class="x">参数名:</span>
                            <el-input type="text" v-model="group.key"
                                      class="d-filter"
                                      style="width:60%;margin:0 16px"></el-input>
                            <div @click="deleteItem(i)" style="margin-left: auto;line-height: 1">
                                <el-image class="del-img" src="statics/img/mall/order/del.png"></el-image>
                            </div>
                        </div>
                        <div flex="dir:left" style="padding:0 10px;margin:16px 0">
                            <span style="user-select:none;" class="x">参数值:</span>
                            <el-input type="text" v-model="group.value"
                                      class="d-filter"
                                      style="width:60%;margin:0 16px"></el-input>
                        </div>
                    </div>
                </draggable>
                <div v-if="isAddAttrGroups" flex="dir:left cross:center" class="bg">
                    <app-params-template v-model="value.param_content" :last-max-count="maxNum" @submit="makeGroup">
                        <el-button>选择参数模板</el-button>
                    </app-params-template>
                    <el-button @click="add" style="margin-left: 12px">添加参数项目</el-button>
                    <span style="padding-left: 14px;color:#c9c9c9">注：参数名最多添加{{maxNum}}个</span>
                </div>

            </div>
        </el-form-item>
    </div>
</template>
<script>
    Vue.component('app-goods-param', {
        template: '#app-goods-param',
        props: {
            value: {
                type: Object,
                default: {
                    param_name: '',
                    param_content: [],
                }
            },
        },
        data() {
            return {
                maxNum: 20,
            };
        },

        created() {

        },
        computed: {
            //添加规格组按钮是否显示
            isAddAttrGroups() {
                return this.value.param_content.length < this.maxNum;
            },
        },

        methods: {
            makeGroup(res) {
                let tempArr = [];
                for (let i of res) {
                    if (i.content && i.content.length) {
                        for (let j of i.content) {
                            tempArr.push({
                                key: j.name,
                                value: j.value,
                            })
                        }
                    }
                }
                this.value.param_content.push(...tempArr)
            },
            deleteItem(i) {
                this.value.param_content.splice(i, 1);
            },
            add() {
                try {
                    if (this.value.param_content.length > this.maxNum) {
                        throw new Error('数量超限');
                    }
                    this.value.param_content = this.value.param_content.concat({
                        'key': '',
                        'value': '',
                    })
                } catch (err) {
                    this.$message.error(err);
                }

            }
        }
    });
</script>
