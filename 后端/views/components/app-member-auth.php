<?php
/**
 * Created by PhpStorm
 * User: 风哀伤
 * Date: 2020/12/18
 * Time: 5:21 下午
 * @copyright: ©2020 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
?>
<template id="app-member-auth">
    <div class="app-member-auth">
        <template v-if="!showGoodsChecked">
            <el-tag v-for="(item, index) in list"
                    @close="authDelete(index)" :key="item.level"
                    :disable-transitions="true" style="margin:0 10px 10px 0;" closable>
                {{item.name}}
            </el-tag>
            <el-button type="button" size="mini" @click="showGoodsOpen">选择会员等级
            </el-button>
        </template>
        <el-checkbox v-model="showGoodsChecked" @change="defaultShowGoods">所有用户
        </el-checkbox>
        <el-dialog title="选择会员等级" :visible.sync="dialog" width="30%">
            <el-card shadow="never" style="max-height: 488px;overflow: auto">
                <el-checkbox-group v-model="tempValue" flex="dir:left" style="flex-wrap: wrap">
                    <el-checkbox v-for="item in members" :label="item"
                                 :key="item.level">{{item.name}}
                    </el-checkbox>
                </el-checkbox-group>
            </el-card>
            <div slot="footer" class="dialog-footer">
                <el-button @click="close">取 消</el-button>
                <el-button type="primary" @click="confirm">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    Vue.component('app-member-auth', {
        template: '#app-member-auth',
        props: {
            value: String,
            members: Array,
        },
        data() {
            return {
                showGoodsChecked: false,
                dialog: false,
                list: [],
                tempValue: [],
            };
        },
        methods: {
            showGoodsOpen() {
                this.dialog = true;
            },
            defaultShowGoods() {
                if (this.showGoodsChecked) {
                    this.$emit('input', '-1');
                } else {
                    this.$emit('input', '');
                }
            },
            close() {
                this.tempValue = [];
                this.dialog = false;
            },
            confirm() {
                this.$emit('input', this.reduce(this.tempValue, 'level'));
                this.close();
            },
            authDelete(index) {
                this.list.splice(index, 1);
                this.$emit('input', this.reduce(this.list, 'level'));
            },
            reduce(list, key) {
                let auth = [];
                list.forEach(item => {
                    auth.push(item[key]);
                });
                return auth.join()
            }
        },
        watch: {
            value: {
                handler(newVal, oldVal) {
                    let temp = JSON.parse(JSON.stringify(this.value));
                    this.showGoodsChecked = temp === '-1';
                },
                immediate: true
            },
            dataRange: {
                handler(newVal, oldVal) {
                    let temp = JSON.parse(JSON.stringify(this.value));
                    let arr = temp.split(',');
                    let list = [];
                    this.members.forEach(item => {
                        for (let i = 0;i < arr.length; i++) {
                            if (arr[i] == item.level) {
                                list.push(item);
                            }
                        }
                    })
                    this.list = list;
                },
                immediate: true
            }
        },
        computed: {
            dataRange() {
                let value = this.value;
                let members = this.members;
                return {
                    value,
                    members
                };
            }
        }
    });
</script>
