<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
Yii::$app->loadViewComponent('app-dialog-template');
?>
<style>
    .app-permissions-setting .check-title {
        background-color: #F3F5F6;
        width: 100%;
        padding: 0 20px;
    }

    .app-permissions-setting .check-list {
        display: flex;
        flex-wrap: wrap;
        padding: 0 20px;
    }

    .app-permissions-setting .check-list .el-checkbox {
        width: 145px;
    }

    .app-permissions-setting .el-checkbox {
        height: 50px;
        line-height: 50px;
    }

    .app-permissions-setting .window {
        border: 1px solid #EBEEF5;
    }

    .app-permissions-setting .check-title .el-checkbox__label {
        font-size: 16px;
    }
</style>

<template id="app-permissions-setting">
    <div class="app-permissions-setting">
        <el-button :loading="loading" @click="edit" size="small" :type="buttonType" @click="batchSetting">{{permissionText()}}</el-button>
        <el-dialog
            @close="dialogClose"
            :title="titleText"
            :visible.sync="dialogVisible"
            width="50%"
            @click="dialogVisible = false">
        <div class="window">
            <el-checkbox class="check-title" :indeterminate="mallIndeterminate" v-model="checkMall"
                         @change="handleCheckMallChange">基础权限
            </el-checkbox>
            <el-checkbox-group class="check-list" v-model="checkedMallPermissions" @change="handleCheckedMallChange">
                <el-checkbox v-for="item in permissions.mall" :label="item.name" :key="item.id">
                    {{item.display_name}}
                </el-checkbox>
            </el-checkbox-group>
            <el-checkbox class="check-title" :indeterminate="pluginsIndeterminate" v-model="checkPlugins"
                         @change="handleCheckPluginsChange">插件权限
            </el-checkbox>
            <el-checkbox-group class="check-list" v-model="checkedPluginsPermissions"
                               @change="handleCheckedPluginsChange">
                <el-checkbox v-for="item in permissions.plugins" :label="item.name" :key="item.id">
                    {{item.display_name}}
                </el-checkbox>
            </el-checkbox-group>
            <template v-if="storageShow()">
                <el-checkbox class="check-title" :indeterminate="storageIndeterminate()"
                             v-model="checkStorage"
                             @change="storageCheckAll">上传权限
                </el-checkbox>
                <el-checkbox-group class="check-list" v-model="secondary_permissions.attachment"
                                   @change="storageCheck">
                    <el-checkbox v-for="(item, key) in storage" :label="key" :key="item">
                        {{item}}
                    </el-checkbox>
                </el-checkbox-group>
            </template>
            <template v-if="templateShow()">
                <div class="check-title" style="height: 50px;line-height: 50px">模板权限</div>
                <div style="padding: 10px 20px;">
                    <div style="margin-right: 10px;">显示权限</div>
                    <div flex>
                        <el-button size="mini" type="text" style="margin-right: 10px;" @click="show = true"
                                   v-if="secondary_permissions.template.is_all == 0">添加模板</el-button>
                        <el-checkbox v-model="secondary_permissions.template.is_all"
                                     true-label="1" false-label="0">全部选择</el-checkbox>
                    </div>
                    <div v-if="secondary_permissions.template.is_all == 0">
                        <el-tag v-for="(item, key) in secondary_permissions.template.list" :key="key"
                                closable :disable-transitions="true" style="margin-right: 5px;margin-bottom: 5px"
                                @close="templateDel(key)">{{item.name}}
                        </el-tag>
                    </div>
                    <div style="margin-right: 10px;margin-top: 10px;">使用权限</div>
                    <div flex>
                        <el-button size="mini" type="text" style="margin-right: 10px;" @click="show_use = true"
                                   v-if="secondary_permissions.template.use_all == 0">添加模板</el-button>
                        <el-checkbox v-model="secondary_permissions.template.use_all"
                                     true-label="1" false-label="0">全部选择</el-checkbox>
                    </div>
                    <div v-if="secondary_permissions.template.use_all == 0">
                        <el-tag v-for="(item, key) in secondary_permissions.template.use_list" :key="key"
                                closable :disable-transitions="true" style="margin-right: 5px;margin-bottom: 5px"
                                @close="useTemplateDel(key)">{{item.name}}
                        </el-tag>
                    </div>
                </div>
                <app-dialog-template :show="show" @selected="templateSelected" :status="1"
                                     :selected="templateList"></app-dialog-template>
                <app-dialog-template :show="show_use" @selected="useTemplateSelected" :status="0"
                                     :selected="useTemplateList"></app-dialog-template>
            </template>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button v-if="cancelShow" size="small" @click="dialogVisible = false">取 消</el-button>
            <el-button size="small" :loading="btnLoading" type="primary" @click="updatePermission">{{submitText}}</el-button>
        </span>
    </el-dialog>
    </div>
</template>

<script>
    Vue.component('app-permissions-setting', {
        template: '#app-permissions-setting',
        props: {
            mallPermissions: {
                type: Array,
                default: function() {
                    return [];
                }
            },
            pluginPermissions: {
                type: Array,
                default: function() {
                    return [];
                }
            },
            secondaryPermissions: {
                type: Object,
                default: function() {
                    return {
                        attachment: ["1", "2", "3", "4"],
                        template: {
                            is_all: '0',
                            use_all: '0',
                            list: [],
                            use_list: [],
                        }
                    }
                }
            },
            titleText: {
                type: String,
                default: '添加权限'
            },
            buttonText:  {
                type: String,
                default: '权限管理'
            },
            submitText:  {
                type: String,
                default: '保存'
            },
            cancelShow: {
                type: Boolean,
                default: true
            },
            buttonType: {
                type: String,
                default: 'primary'
            }
        },
        data() {
            return {
                loading: false,
                dialogVisible: false,
                mallIndeterminate: false,
                pluginsIndeterminate: false,
                checkMall: false,
                checkPlugins: false,
                checkedMallPermissions: [],
                checkedPluginsPermissions: [],
                permissions: {
                    mall: [],
                    plugins: [],
                },
                btnLoading: false,
                secondary_permissions: {
                    attachment: ["1", "2", "3", "4"],
                    template: {
                        is_all: '0',
                        use_all: '0',
                        list: [],
                        use_list: [],
                    }
                },
                storage: [],
                checkStorage: true,
                show: false,
                show_use: false,
            }
        },
        computed: {
            templateList() {
                if (typeof this.secondary_permissions.template == 'undefined') {
                    return [];
                }
                let list = [];
                this.secondary_permissions.template.list.forEach(item => {
                    list.push(item.id);
                });
                return list;
            },
            useTemplateList() {
                if (typeof this.secondary_permissions.template == 'undefined') {
                    return [];
                }
                let list = [];
                this.secondary_permissions.template.use_list.forEach(item => {
                    list.push(item.id);
                });
                return list;
            },
        },
        watch: {
            mallPermissions(newVal, oldVal) {
                this.checkedMallPermissions = newVal;
            },
            pluginPermissions(newVal, oldVal) {
                this.checkedPluginsPermissions = newVal;
            },
            secondaryPermissions(newVal, oldVal) {
                this.secondary_permissions = newVal;
            }
        },
        methods: {
            dialogClose(e) {

            },
            handleCheckMallChange(val) {
                let checkedArr = [];
                if (val) {
                    this.permissions.mall.forEach(function (item, index) {
                        checkedArr.push(item.name);
                    });
                }
                this.checkedMallPermissions = checkedArr;
                this.mallIndeterminate = false;
            },
            handleCheckPluginsChange(val) {
                let checkedArr = [];
                if (val) {
                    this.permissions.plugins.forEach(function (item, index) {
                        checkedArr.push(item.name);
                    });
                }
                this.checkedPluginsPermissions = checkedArr;
                this.pluginsIndeterminate = false;
            },
            handleCheckedMallChange(value) {
                let checkedCount = value.length;
                this.checkMall = checkedCount === this.permissions.mall.length;
                this.mallIndeterminate = checkedCount > 0 && checkedCount < this.permissions.mall.length;
            },
            handleCheckedPluginsChange(value) {
                let checkedCount = value.length;
                this.checkPlugins = checkedCount === this.permissions.plugins.length;
                this.pluginsIndeterminate = checkedCount > 0 && checkedCount < this.permissions.plugins.length;
            },
            getPermissions() {
                let self = this;
                self.loading = true;
                request({
                    params: {
                        r: 'admin/user/permissions',
                        id: getQuery('id'),
                    },
                    method: 'get',
                }).then(e => {
                    self.loading = false;
                    if (e.data.code === 0) {
                        self.permissions = e.data.data.permissions;
                        self.storage = e.data.data.storage;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                });
            },
            // 更新权限
            updatePermission() {
                let self = this;
                self.dialogVisible = false;
                self.$emit('submit', {
                    mall_permissions: self.checkedMallPermissions,
                    plugin_permissions: self.checkedPluginsPermissions,
                    secondary_permissions: self.secondary_permissions,
                    permissions:self.permissions
                });
                self.permissionText();

            },
            permissionText() {
                let permissions_num = this.checkedMallPermissions.length + this.checkedPluginsPermissions.length;
                let total = this.permissions.mall.length + this.permissions.plugins.length;
                let own = permissions_num ? permissions_num : 0;

                return this.buttonText + `(` + own + `/` + total + `)`;
            },
            edit() {
                let self = this;
                self.dialogVisible = true;

                self.handleCheckedMallChange(self.checkedMallPermissions);
                self.handleCheckedPluginsChange(self.checkedPluginsPermissions);
            },
            storageShow() {
                for (let i in this.checkedMallPermissions) {
                    if (this.checkedMallPermissions[i] == 'attachment') {
                        return true;
                    }
                }
                return false;
            },
            storageCheckAll() {
                let arr = [];
                for (let i in this.storage) {
                    arr.push(i);
                }
                if (arr.length == this.secondary_permissions.attachment.length) {
                    this.secondary_permissions.attachment = [];
                    this.checkStorage = false;
                } else {
                    this.secondary_permissions.attachment = arr;
                    this.checkStorage = true;
                }
            },
            storageCheck(value) {
                let arr = [];
                for (let i in this.storage) {
                    arr.push(i);
                }
                if (this.secondary_permissions.attachment.length == arr.length) {
                    this.checkStorage = true;
                }
                this.checkStorage = false;
            },
            storageIndeterminate() {
                let arr = [];
                for (let i in this.storage) {
                    arr.push(i);
                }
                if (this.secondary_permissions.attachment.length > 0 && this.secondary_permissions.attachment.length < arr.length) {
                    return true;
                }
                if (this.secondary_permissions.attachment.length == arr.length) {
                    return false;
                }
                return false;
            },
            templateShow() {
                for (let i in this.checkedPluginsPermissions) {
                    if (this.checkedPluginsPermissions[i] == 'diy') {
                        return true;
                    }
                }
                return false;
            },
            templateSelected(data) {
                this.show = false;
                if (data) {
                    this.secondary_permissions.template.list = data;
                }
            },
            templateDel(key) {
                this.secondary_permissions.template.list.splice(key, 1);
            },
            useTemplateSelected(data) {
                this.show_use = false;
                if (data) {
                    this.secondary_permissions.template.use_list = data;
                }
            },
            useTemplateDel(key) {
                this.secondary_permissions.template.use_list.splice(key, 1);
            },
        },
        created() {
            this.getPermissions();
        }
    })
</script>