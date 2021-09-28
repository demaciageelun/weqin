<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/11/1
 * Time: 10:37
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
?>

<style>
    #app-shipping-rules .el-radio {
        width: 22%;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }
</style>
<template id="app-shipping-rules">
    <div class="app-shipping-rules">
        <el-tag @close="deleteValue" v-if="value"
                :key="value.name"
                :disable-transitions="true"
                style="margin-right: 10px;"
                closable>
            {{value.name}}
        </el-tag>
        <el-button type="button" size="mini" @click="open">选择包邮规则</el-button>
        <el-dialog title="选择包邮规则" :visible.sync="dialog" width="30%">
            <div class="input-item">
                <el-input size="small" placeholder="请输入包邮名称" v-model="search.ssBY" clearable>
                    <el-button slot="append" icon="el-icon-search"></el-button>
                </el-input>
            </div>
            <el-card shadow="never" flex="dir:left" style="flex-wrap: wrap" v-loading="loading">
                <el-scrollbar style="height: 500px">
                    <el-radio-group v-if="BYlist && BYlist.length" flex="dir:top" v-model="checked">
                        <el-radio style="padding: 10px;" v-for="item in BYlist"
                                  :label="item" :key="item.id">{{item.name}}{{item.id !== 0 ? '(' + item.text +' )' :
                            ''}}
                        </el-radio>
                    </el-radio-group>
                    <div v-else style="width: 520px;color:#999999" flex="cross:center main:center">暂无搜索结果</div>
                </el-scrollbar>
            </el-card>
            <div slot="footer" class="dialog-footer">
                <el-button @click="cancel">取 消</el-button>
                <el-button type="primary" @click="confirm">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    Vue.component('app-shipping-rules', {
        template: '#app-shipping-rules',
        props: {
            value: {
                type: Array | Object,
                default: function() {
                    return {}
                }
            },
            title: String,
            url: String,
        },
        data() {
            return {
                dialog: false,
                list: [],
                checked: {},
                loading: false,
                search: {
                    ssBY: ''
                },
            };
        },
        computed: {
            BYlist() {
                if (this.search.ssBY) {
                    return this.list.filter(item => {
                        return item.name.indexOf(this.search.ssBY) !== -1;
                    });
                } else {
                    return this.list;
                }
            },
        },
        methods: {
            handleCurrentChange() {},
            open() {
                this.dialog = true;
                this.loadData();
            },
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: this.url
                    }
                }).then(response => {
                    this.loading = false;
                    if (response.data.code == 0) {
                        this.list = response.data.data.list;
                    } else {
                        this.$message.error(response.data.msg);
                    }
                }).catch(response => {
                    console.log(response);
                });
            },
            cancel() {
                this.dialog = false;
                this.checked = {};
            },
            confirm() {
                this.$emit('selected', this.checked);
                this.cancel();
            },
            deleteValue() {
                this.$emit('selected', null);
            }
        }
    });
</script>
