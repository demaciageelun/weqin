<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
Yii::$app->loadViewComponent('app-dialog-template');
Yii::$app->loadViewComponent('admin/app-permissions-setting');
?>

<style>
    .common-width {
        width: 300px;
    }

    .el-card__header {
        height: 60px;
        line-height: 60px;
        padding: 0 20px;
    }

    .el-form-item__label {
        position: relative;
        padding-left: 20px;
        color: #999999;
        font-size: 13px;
    }

    .is-required .el-form-item__label::before {
        content: '' !important;
        background-color: #ff5c5c;
        width: 6px;
        height: 6px;
        border-radius: 3px;
        position: absolute;
        top: 50%;
        margin-top: -3px;
        left: 0;
    }

    .common-width .el-input__inner {
        height: 35px;
        line-height: 35px;
        border-radius: 8px;
    }

    .form .el-form-item {
        margin-bottom: 25px;
        position: relative;
    }

    .form {
        display: flex;
        justify-content: center;
        margin-left: -60px;
        margin-top: 15px;
    }

    .show-password {
        position: absolute;
        right: -30px;
        top: 6.5px;
        height: 22px;
        width: 22px;
        display: block;
        cursor: pointer;
    }

    .permissions-list {
        width: 300px;
    }

    .permissions-item {
        height: 24px;
        line-height: 24px;
        border-radius: 12px;
        padding: 0 12px;
        margin-right: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        color: #999999;
        background-color: #F7F7F7;
        display: inline-block;
        font-size: 12px;
    }

    .permissions-item.active {
        background-color: #F5FAFF;
        color: #57ADFF;
    }

    .submit-btn {
        height: 32px;
        width: 65px;
        line-height: 32px;
        text-align: center;
        border-radius: 16px;
        padding: 0;
    }
</style>

<div id="app" v-cloak>
    <el-card v-loading="cardLoading" class="box-card">
        <div slot="header" class="clearfix">
            <span v-if="isDisabled">编辑子账户信息</span>
            <span v-else>新增子账户信息</span>
            <span style="float: right;">
                当前子账户数量：{{cloudAccount.user_num}},
                最大子账户数量：{{cloudAccount.account_num == -1 ? "无限制" : cloudAccount.account_num}}
            </span>
        </div>
        <div class="form">
            <el-form ref="form" label-position="left" :model="form" :rules="rules" label-width="120px" size="small">
                <el-form-item label="用户名" prop="username">
                    <el-input :disabled="isDisabled" class="common-width" v-model="form.username"></el-input>
                </el-form-item>
                <el-form-item v-if="isShow" label="登录密码" prop="password">
                    <el-input type="password" v-if="!show_password" class="common-width"
                              v-model="form.password"></el-input>
                    <el-input type="text" v-if="show_password" class="common-width" v-model="form.password"></el-input>
                    <img class="show-password" v-if="show_password" @click="show_password = !show_password"
                         src="statics/img/admin/show.png" alt="">
                    <img class="show-password" v-if="!show_password" @click="show_password = !show_password"
                         src="statics/img/admin/hide.png" alt="">
                </el-form-item>
                <el-form-item label="手机号" prop="mobile">
                    <el-input class="common-width" v-model="form.mobile"></el-input>
                </el-form-item>
                <el-form-item label="备注">
                    <el-input type="text" class="common-width" v-model="form.adminInfo.remark"></el-input>
                </el-form-item>
                <el-form-item label="小程序数量" prop="adminInfo.app_max_count">
                    <el-input :disabled="isAppMaxCount" type="number" class="common-width"
                              v-model="form.adminInfo.app_max_count">
                    </el-input>
                    <el-checkbox v-model="isAppMaxCount" @change="appMaxCount">无限制</el-checkbox>
                    <div class="common-width" style="color:#BBBBBB;font-size: 11px;line-height: 1;margin-top: 5px">
                        此用户可以创建的小程序的数量
                    </div>
                </el-form-item>
                <el-form-item label="账户有效期" prop="adminInfo.expired_at" ref="expired_at">
                    <el-date-picker :disabled='isExpiredDisabled' class="common-width"
                                    type="datetime"
                                    value-format="yyyy-MM-dd HH:mm:ss"
                                    placeholder="选择日期"
                                    v-model="form.adminInfo.expired_at">
                    </el-date-picker>
                    <el-checkbox v-model="isCheckExpired" @change="checkExpiredAt">永久</el-checkbox>
                </el-form-item>
                <el-form-item label="用户组" prop="user_group_name">
                    <div flex="dir:left">
                        <el-autocomplete size="small" v-model="form.user_group_name" value-key="name" :fetch-suggestions="querySearchAsync" placeholder="请选择用户组" @select="selectUserGroup">
                        </el-autocomplete>
                        <app-permissions-setting
                            v-show="isShowPermissions && form.adminInfo.user_group_id == 0"
                            style="margin-left: 10px;"
                            button-text="查看历史权限"
                            title-text="查看历史权限"
                            submit-text="我知道了"
                            :cancel-show="false"
                            :mall-permissions="form.adminInfo.mall_permissions"
                            :plugin-permissions="form.adminInfo.plugin_permissions"
                            :secondary-permissions="form.adminInfo.secondary_permissions"
                        >
                        查看历史权限
                        </app-permissions-setting>
                    </div>
                </el-form-item>
                <el-form-item label="附加权限">
                    <app-permissions-setting
                        button-text="选择附加权限"
                        @submit="updateSubjoinPermissions"
                        :mall-permissions="form.subjoin_permissions.mall"
                        :plugin-permissions="form.subjoin_permissions.plugin"
                        :secondary-permissions="form.subjoin_permissions.secondary"
                    ></app-permissions-setting>
                    <div style="width: 400px;" v-if="form.subjoin_permissions.list">
                        <el-tag 
                            style="margin-right: 10px;margin-top: 10px;border-radius: 30px;height: 25px;line-height: 25px;"
                            v-for="(item, index) in form.subjoin_permissions.list" 
                            :index="index">
                            {{item}}
                        </el-tag>
                    </div>
                </el-form-item>
                <el-form-item>
                    <el-button :loading="btnLoading" class="submit-btn" type="primary" @click="store('form')">保存
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                btnLoading: false,
                cardLoading: false,
                show_password: false,
                isShow: true,//输入框是否显示
                isDisabled: false,//输入框是否禁用
                isExpiredDisabled: false,//日期选择框是否禁用
                isCheckExpired: false,//有效期永久是否勾选
                isAppMaxCount: false,//可创建小程序数量是否勾选
                cloudAccount: {},
                form: {
                    adminInfo: {
                        expired_at: '',
                        app_max_count: '',
                    },
                    identity: {},
                    user_group_name: '',
                    user_group_id: null,
                    subjoin_permissions: {
                        mall: [],
                        plugin: []
                    }
                },
                rules: {
                    username: [
                        {required: true, message: '请输入用户名', trigger: 'change'},
                        {min: 4, max: 15, message: '长度在 4 到 15 个字符', trigger: 'change'}
                    ],
                    password: [
                        {required: true, message: '请输入密码', trigger: 'change'},
                    ],
                    mobile: [
                        {required: true, message: '请输入手机号', trigger: 'change'},
                    ],
                    user_group_name: [
                        {required: true, message: '请选择用户组', trigger: 'change'},
                    ],
                    'adminInfo.expired_at': [
                        {required: true, message: '请选择账户有效期', trigger: 'change'},
                    ],
                    'adminInfo.app_max_count': [
                        {required: true, message: '请填写可创建小程序数量', trigger: 'change'},
                    ]
                },
                allIsCheck: false,
                show: false,
                show_use: false,
                isShowPermissions: false,
            };
        },
        methods: {
            store(formName) {
                let self = this;
                self.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'admin/user/edit',
                            },
                            method: 'post',
                            data: {
                                form: JSON.stringify(self.form),
                                isCheckExpired: self.isCheckExpired ? 1 : 0,
                                isAppMaxCount: self.isAppMaxCount ? 1 : 0,
                                page: getQuery('page')
                            },
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                window.location.href = e.data.data.url;
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.btnLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'admin/user/edit',
                        id: getQuery('id'),
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code === 0) {
                        self.form = e.data.data.detail;
                        if (self.form.adminInfo.expired_at == '0000-00-00 00:00:00') {
                            self.isCheckExpired = true;
                            self.isExpiredDisabled = true;
                            self.form.adminInfo.expired_at = '0000-00-00';
                        }
                        if (self.form.adminInfo.app_max_count == -1) {
                            self.isAppMaxCount = true;
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {

                });
            },
            // 账户有效期永久事件
            checkExpiredAt(value) {
                this.isCheckExpired = value;
                this.isExpiredDisabled = value;
                if (value) {
                    this.form.adminInfo.expired_at = '0000-00-00';
                } else {
                    this.form.adminInfo.expired_at = '';
                }
                this.$refs['expired_at'].clearValidate();
            },
            // 创建小程序数量无限制事件
            appMaxCount(value) {
                this.isAppMaxCount = value;
                if (value) {
                    this.form.adminInfo.app_max_count = -1;
                } else {
                    this.form.adminInfo.app_max_count = '';
                }
                this.$refs['app_max_count'].clearValidate();
            },
            getCloudAccount() {
                let self = this;
                request({
                    params: {
                        r: 'admin/user/cloud-account',
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        self.cloudAccount = e.data.data
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {

                });
            },
            querySearchAsync(query, cb) {
                let self = this;
                request({
                    params: {
                        r: 'admin/user/user-group',
                        keyword_name: 'name',
                        keyword_value: query,
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        let list = e.data.data.list;
                        let newList = [];
                        list.forEach(function(item) {
                            newList.push({
                                id: item.id.toString(),
                                name: item.name
                            })
                        })
                        cb(newList);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            selectUserGroup(row) {
                this.form.user_group_id = row.id
                this.form.user_group_name = row.name
            },
            updateSubjoinPermissions(e) {
                this.form.subjoin_permissions.mall = e.mall_permissions;
                this.form.subjoin_permissions.plugin = e.plugin_permissions;
                this.form.subjoin_permissions.secondary = e.secondary_permissions;

                let list = [];
                e.permissions.mall.forEach(function(item) {
                    list[item.name] = item.display_name
                })
                e.permissions.plugins.forEach(function(item) {
                    list[item.name] = item.display_name
                })

                let newList = [];
                e.mall_permissions.forEach(function(item, index) {
                    newList.push(list[item])
                })
                e.plugin_permissions.forEach(function(item, index) {
                    newList.push(list[item])
                })

                this.form.subjoin_permissions.list = newList;

                console.log(this.form.subjoin_permissions.list);
            }
        },
        mounted: function () {
            this.getCloudAccount();
            if (getQuery('id')) {
                this.getDetail();
                this.isShow = false;
                this.isDisabled = true;
                this.isShowPermissions = true;
            }
        }
    });
</script>
