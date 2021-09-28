<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
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

    .permissions-dialog .el-radio__label {
        display: none;
    }
</style>

<div id="app" v-cloak>
    <el-card v-loading="cardLoading" class="box-card">
        <div slot="header" class="clearfix">
            <span>{{id ? '编辑用户组' : '新建用户组'}}</span>
        </div>
        <div class="form">
            <el-form @submit.native.prevent ref="form" label-position="left" :model="form" :rules="rules" label-width="120px" size="small">
                <el-form-item label="用户组名称" prop="name">
                    <el-input  class="common-width" v-model="form.name" maxlength="15" show-word-limit></el-input>
                </el-form-item>
                <el-form-item label="权限套餐" prop="group_id">
                    <el-tag v-if="form.group_id" @close="deleteGroup" closable>{{form.group_name}}</el-tag>
                    <el-button @click="show">选择套餐</el-button>
                </el-form-item>
                <el-form-item>
                    <el-button :loading="btnLoading" class="submit-btn" type="primary" @click="store('form')">保存
                    </el-button>
                </el-form-item>
            </el-form>
        </div>

        <el-dialog class="permissions-dialog" title="选择权限套餐" :visible.sync="permissionsDialog.visible">
            <el-input clearable @clear="search(1)" @keyup.enter.native="search(1)" size="small" placeholder="根据名称搜索" v-model="permissionsDialog.keyword_value">
                <el-button @click="search(1)" slot="append">搜索</el-button>
            </el-input>
            <el-table v-loading="permissionsDialog.listLoading" :data="permissionsDialog.list">
                <el-table-column property="name" width="100">
                    <template slot-scope="scope">
                        <el-radio @change="select(scope.row)" v-model="permissionsDialog.id" :label="scope.row.id"></el-radio>
                    </template>
                </el-table-column>
                <el-table-column property="name" label="名称"></el-table-column>
            </el-table>
            <div style="text-align: center;margin-top: 20px;">
                <el-pagination
                    background
                    @current-change="search"
                    :current-page.sync="permissionsDialog.page"
                    :page-size="permissionsDialog.pagination.pageSize"
                    layout="total, prev, pager, next, jumper"
                    :total="permissionsDialog.pagination.totalCount">
                </el-pagination>
            </div>
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="permissionsDialog.visible = false">取 消</el-button>
                <el-button size="small" type="primary" @click="dialogSubmit">确 定</el-button>
            </div>
        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                btnLoading: false,
                cardLoading: false,
                form: {
                    name: '',
                    group_name: '',
                    group_id: null,
                },
                rules: {
                    name: [
                        {required: true, message: '请输入用户组名称', trigger: 'change'},
                        {min: 1, max: 15, message: '长度在 1 到 15 个字符', trigger: 'change'}
                    ],
                    group_id: [
                        {required: true, message: '请选择权限套餐组', trigger: 'change'},
                    ]
                },
                permissionsDialog: {
                    visible: false,
                    list: [],
                    keyword_value: '',
                    pagination: {},
                    listLoading: false,
                    page: 1,
                    group: {}
                },
                id: null
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
                                r: 'admin/user/user-group-edit',
                            },
                            method: 'post',
                            data: {
                                id: this.form.id,
                                name: this.form.name,
                                permissions_group_id: this.form.group_id
                            },
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'admin/user/user-group',
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
                        r: 'admin/user/user-group-edit',
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
            show() {
                this.permissionsDialog.visible = true;
                this.getPermissionsGroup();
            },
            getPermissionsGroup() {
                let self = this;
                self.permissionsDialog.listLoading = true;
                request({
                    params: {
                        r: 'admin/user/permissions-group',
                        page: this.permissionsDialog.page,
                        keyword_name: 'name',
                        keyword_value: this.permissionsDialog.keyword_value
                    },
                    method: 'get',
                }).then(e => {
                    self.permissionsDialog.listLoading = false;
                    if (e.data.code === 0) {
                        self.permissionsDialog.list = e.data.data.list;
                        self.permissionsDialog.pagination = e.data.data.pagination;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {

                });
            },
            search(page) {
                this.permissionsDialog.page = page ? page : 1;
                this.getPermissionsGroup();
            },
            select(row) {
                this.permissionsDialog.group = JSON.parse(JSON.stringify(row));
            },
            dialogSubmit() {
                this.form.group_id = this.permissionsDialog.group.id;
                this.form.group_name = this.permissionsDialog.group.name;
                this.permissionsDialog.visible = false;
            },
            deleteGroup() {
                this.form.group_id = null;
                this.form.group_name = '';
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
