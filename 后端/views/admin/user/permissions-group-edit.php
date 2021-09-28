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
            <span>{{id ? '编辑权限套餐' : '新建权限套餐'}}</span>
        </div>
        <div class="form">
            <el-form @submit.native.prevent ref="form" label-position="left" :model="form" :rules="rules" label-width="120px" size="small">
                <el-form-item label="权限套餐名称" prop="name">
                    <el-input  class="common-width" v-model="form.name" maxlength="15" show-word-limit></el-input>
                </el-form-item>
                <el-form-item label="插件" prop="permissions">
                    <app-permissions-setting 
                        @submit="updatePermissions"
                        :mall-permissions="form.permissions.mall_permissions"
                        :plugin-permissions="form.permissions.plugin_permissions"
                        :secondary-permissions="form.permissions.secondary_permissions"
                    >
                    </app-permissions-setting>
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
                cloudAccount: {},
                form: {
                    name: '',
                    permissions: {
                        mall_permissions: [],
                        plugin_permissions: [],
                        secondary_permissions: {},
                    }
                },
                rules: {
                    name: [
                        {required: true, message: '请输入权限套餐名称', trigger: 'change'},
                        {min: 1, max: 15, message: '长度在 1 到 15 个字符', trigger: 'change'}
                    ],
                    permissions: [
                        {required: true, message: '请选择插件', trigger: 'change'},
                    ]
                },

                id: null
            };
        },
        methods: {
            store(formName) {
                let self = this;
                let isExist = self.checkPermissions();
                if (!isExist) {
                    return $isExist;
                }
                self.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'admin/user/permissions-group-edit',
                            },
                            method: 'post',
                            data: {
                                id: this.form.id,
                                name: this.form.name,
                                permissions: JSON.stringify(this.form.permissions)
                            },
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'admin/user/permissions-group',
                                });
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
                        r: 'admin/user/permissions-group-edit',
                        id: getQuery('id'),
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code === 0) {
                        self.form = e.data.data.group;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {

                });
            },
            updatePermissions(e) {
                this.form.permissions.mall_permissions = e.mall_permissions;
                this.form.permissions.plugin_permissions = e.plugin_permissions;
                this.form.permissions.secondary_permissions = e.secondary_permissions;
            },
            checkPermissions(){
                if (this.form.permissions.mall_permissions.length == 0 && this.form.permissions.plugin_permissions.length == 0) {
                    this.$message.warning('请选择插件');
                    return false;
                }

                return true;
            }
        },
        mounted() {
            this.id = getQuery('id');
            if (getQuery('id')) {
                this.getDetail();
            }
        }
    });
</script>
