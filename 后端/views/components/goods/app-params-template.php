<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2020 浙江禾匠信息科技有限公司
 * author: xay
 */
?>
<style>
    .custom-dialog {
        min-width: 680px;
        width: 600px;
    }

    .tpl-box {
        width: 300px;
        height: 440px;
    }

    .tpl-box:last-child:after {
        margin-left: 20px;
    }

    .tpl-box .tpl-head {
        padding: 0 20px;
        font-size: 15px;
        color: #909399;
        margin-bottom: 10px;
        line-height: 44px;
        background: #f5f7fa;
    }

    .tpl-scrollbar {
        height: calc(440px - 44px - 10px);
    }

    .tpl-scrollbar .tpl-checkbox .tpl-item {
        padding: 10px 0;
        max-width: 260px;
    }

    .tpl-scrollbar .tpl-checkbox .tpl-item .el-checkbox__label {
        white-space: normal;
    }

    .tpl-scrollbar .tpl-checkbox {
        padding: 0 20px;
    }

    .tpl-scrollbar .tpl-checkbox.active {
        background: #f0f7ff;
    }

    .tpl-scrollbar .tpl-checkbox .el-checkbox {
        display: flex;
        -webkit-align-items: center;
        align-items: center;
    }

    .el-message-box {
        width: 350px;
    }

    .el-message-box .el-message-box__content .el-message-box__status {
        top: calc(50% - 12px);
    }
</style>
<template id="app-params-template">
    <div class="app-params-template">
        <el-dialog custom-class="custom-dialog" :visible.sync="templateDialog">
            <div slot="title" style="margin-bottom: -30px">
                <span style="font-size: 18px;color:#7a7a7a">选择参数模板</span>
                <span style="font-size:14px;color:#c9c9c9;margin-left: 10px">参数模板最多支持添加{{lastMaxCount}}组</span>
            </div>
            <div flex="dir:left">
                <div class="tpl-box">
                    <div class="tpl-head">参数名选择</div>
                    <el-scrollbar class="tpl-scrollbar">
                        <el-checkbox-group v-model="parentValue">
                            <div class="tpl-checkbox"
                                 v-for="(group,index) in templateList" :key="index"
                                 :class="{'active': group.only === tplActiveBg[0]}"
                                 @click="tplActiveBg[0] = group.only">
                                <el-checkbox class="tpl-item"
                                             :label="group.only"
                                             :disabled="group.disabled"
                                             :indeterminate="false"
                                             @change="groupChange(group)"
                                             name="type">
                                    {{group.label}}
                                </el-checkbox>
                            </div>
                        </el-checkbox-group>
                    </el-scrollbar>
                </div>

                <div class="tpl-box" style="margin-left: 20px">
                    <div class="tpl-head">参数内容选择</div>
                    <el-scrollbar class="tpl-scrollbar">
                        <span v-for="(group,index) in templateList" :key="index">
                            <div v-if="group.only == tplActiveBg[0]"
                                 class="tpl-checkbox"
                                 v-for="(item,index) in group.children" :key="index"
                                 :class="{'active': item.only === tplActiveBg[1]}"
                                 @click="tplActiveBg[1] = item.only">
                                <el-checkbox-group v-model="childrenValue">
                                    <el-checkbox class="tpl-item"
                                                 :label="item.only"
                                                 :disabled="group.disabled"
                                                 :indeterminate="false"
                                                 @change="itemChange(group)"
                                                 name="type">
                                        <div>参数名：{{item.label}}</div>
                                        <div>参数值：{{item.value}}</div>
                                    </el-checkbox>
                                </el-checkbox-group>
                            </div>
                        </span>
                    </el-scrollbar>
                </div>
            </div>
            <div slot="footer" class="dialog-footer">
                <el-button size="small" type="primary" @click="templateSubmit">确定选择</el-button>
            </div>
        </el-dialog>

        <div @click="showTemplate">
            <slot></slot>
        </div>
    </div>
</template>
<script>
    Vue.component('app-params-template', {
        template: '#app-params-template',
        props: {
            value: {
                type: Array,
                default: function () {
                    return [];
                }
            },
            lastMaxCount: {
                type: Number,
                default: 20,
            },
        },
        data() {
            return {
                templateDialog: false,
                templateList: [],
                only: 0,
                tplActiveBg: [-1, -1],
                parentValue: [],
                childrenValue: [],
            }
        },
        mounted() {
            this.getList();
        },
        methods: {
            showTemplate() {
                if (this.templateList.length === 0) {
                    this.emptyModel();
                } else {
                    this.parentValue = [];
                    this.childrenValue = [];
                    this.templateDialog = true;
                }
            },
            //子操作父节点
            itemChange(group) {
                let p = new Set(this.parentValue);
                let c = new Set(this.childrenValue);
                const result = group.children.some(item => {
                    return c.has(item.only)
                });
                result ? p.add(group.only) : p.delete(group.only);
                this.parentValue = Array.from(p);
            },
            //父操作子节点
            groupChange(group) {
                let p = new Set(this.parentValue);
                let c = new Set(this.childrenValue);
                const result = p.has(group.only);
                group.children.forEach(item => {
                    result ? c.add(item.only) : c.delete(item.only);
                });
                this.childrenValue = Array.from(c);
            },

            templateSubmit() {
                const self = this;
                const onlyArr = self.parentValue.concat(this.childrenValue);
                const templateList = self.templateList;

                let obj = {};
                let arrLength = 0;
                onlyArr.forEach(only => {
                    templateList.forEach(group => {
                        if (group.only === only && !obj.hasOwnProperty(only)) {
                            obj[only] = {
                                name: group.label,
                                content: [],
                            }
                        }
                        group.children.forEach(item => {
                            if (item.only === only) {
                                let content = {
                                    name: item.label,
                                    value: item.value,
                                };
                                if (obj.hasOwnProperty(item.last_only)) {
                                    obj[item.last_only].content.push(content)
                                } else {
                                    obj[item.last_only] = {
                                        name: group.label,
                                        content: [content],
                                    }
                                }
                                ++arrLength;
                            }
                        })
                    })
                })

                // 限制添加的参数组
                if (arrLength + self.value.length > this.lastMaxCount) {
                    self.$message.error("参数内容最多添加" + this.lastMaxCount + "个");
                    return;
                }
                this.$emit('submit', Object.values(obj));
                self.templateDialog = false;
            },
            emptyModel() {
                const self = this;
                const h = self.$createElement;

                function navTemplate() {
                    self.$navigate({
                        r: 'mall/goods-params-template/index'
                    }, true);
                }

                self.$msgbox({
                    title: '提示',
                    confirmButtonText: '我知道了',
                    message: h('p', {style: 'color:#666666;font-size:14px'}, [
                        h('p', null, '暂无参数模板数据'),
                        h('span', null, '请先至'),
                        h('span', {on: {click: navTemplate}, style: 'color:#3399ff;cursor:pointer'}, '商品管理-参数模板'),
                        h('span', null, '中添加'),
                    ]),
                    type: 'warning'
                });
            },
            getList() {
                request({
                    params: {
                        r: 'mall/goods-params-template/index',
                        page_size: 999
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        this.templateList = e.data.data.list.map(group => {
                            let only = this.only++;
                            let children = group.content.map(item => {
                                return {
                                    last_only: only,
                                    label: item.label,
                                    value: item.value,
                                    disabled: false,
                                    only: this.only++,
                                };
                            });
                            return {
                                id: group.id,
                                label: group.name,
                                only: only,
                                disabled: false,
                                children: children,
                            };
                        });
                    } else {
                        this.$message.error(e.data.msg);
                    }
                })
            },
        }
    });
</script>