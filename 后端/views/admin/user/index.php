<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .action-box .el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        display: inline-block;
        width: 250px;
        margin-right: 10px;
        margin-bottom: 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        margin: 0;
    }

    .el-tooltip__popper {
        max-width: 200px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0;">
        <div class="table-body">
            <div>
                <el-select @change="search" style="width: 120px;" size="small" v-model="type" placeholder="请选择">
                    <el-option
                      v-for="item in options"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value">
                    </el-option>
                </el-select>
                <div class="input-item">
                    <el-input @keyup.enter.native="search" size="small" placeholder="请输入用户名、手机号或备注" clearable v-model="keyword" clearable @clear='getList'>
                        <el-button slot="append" @click="search" icon="el-icon-search"></el-button>
                    </el-input>
                </div>
                <el-button size="small" type="primary" @click="batchSettingUserGroup">批量设置用户组</el-button>
            </div>
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    @selection-change="handleSelectionChange"
                    style="width: 100%">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        width="120"
                        prop="username"
                        label="账户">
                    <template slot-scope="scope">
                        <div flex="dir:top">
                            <span>{{scope.row.username}}</span>
                            <span style="color: #999999;">{{scope.row.mobile}}</span>
                            <el-tooltip v-if="scope.row.adminInfo.remark.length > 16" class="item" effect="dark" :content="scope.row.adminInfo.remark" placement="bottom">
                                <span style="color: #999999;">{{scope.row.remark}}</span>
                            </el-tooltip>
                            <span v-else style="color: #999999;">{{scope.row.adminInfo.remark}}</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="user_group_name"
                        width="150"
                        label="用户组">
                </el-table-column>
                <el-table-column
                        prop="permissions_group_name"
                        width="150"
                        label="权限套餐">
                </el-table-column>
                <el-table-column
                        prop="subjoin_permissions_number"
                        width="80"
                        label="附加权限">
                    <template slot-scope="scope">
                        <a href="#" style="text-decoration:none; color: #409EFF;" @click="edit(scope.row.id)">{{scope.row.subjoin_permissions_number}}</a>
                    </template>
                </el-table-column>
                <el-table-column
                        prop=""
                        label="可创建小程序数量">
                    <template slot-scope="scope">
                        <span v-if="scope.row.adminInfo.app_max_count == -1">无限制</span>
                        <span v-else>{{scope.row.adminInfo.app_max_count}}</span>
                    </template>
                </el-table-column>
                <el-table-column
                        label="已创建小程序数量">
                    <template slot-scope="scope">
                        <a href="#" style="text-decoration:none; color: #409EFF;" @click="toMallList(scope.row)">{{scope.row.create_app_count}}</a>
                    </template>
                </el-table-column>
                <el-table-column
                        width="240"
                        label="有效期">
                        <template slot-scope="scope">
                            <el-tag v-if="scope.row.expired_type == '未到期'" type="success">未到期</el-tag>
                            <el-tag v-if="scope.row.expired_type == '已到期'" type="warning">已到期</el-tag>
                            <span>{{scope.row.adminInfo.expired_at == '0000-00-00 00:00:00' ? '永久' : scope.row.adminInfo.expired_at}}</span>
                        </template>
                </el-table-column>
                <el-table-column
                        prop="created_at"
                        width="180"
                        label="创建日期">
                </el-table-column>
                <el-table-column
                        fixed="right"
                        label="操作"
                        width="220">
                    <template slot-scope="scope">
                        <div class="action-box">
                            <el-button @click="edit(scope.row.id)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                    <img src="statics/img/mall/edit.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button @click="editPassword(scope.row.id)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="修改密码" placement="top">
                                    <img src="statics/img/mall/change.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button @click="destroy(scope.row.id)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                    <img src="statics/img/mall/del.png" alt="">
                                </el-tooltip>
                            </el-button>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <el-dialog
              title="批量设置用户组"
              :visible.sync="batchUserGroup.visible"
              width="30%">
                <el-form @submit.native.prevent :model="batchUserGroup.form" :rules="batchUserGroup.rules" ref="ruleForm" label-width="100px">
                  <el-form-item label="用户组名称" prop="user_group_id">
                    <el-autocomplete size="small" v-model="batchUserGroup.form.user_group_name" value-key="name" :fetch-suggestions="querySearchAsync" placeholder="请输入用户组名" @select="selectUserGroup"></el-autocomplete>
                  </el-form-item>
                </el-form>
              <span slot="footer" class="dialog-footer">
                <el-button size="small" @click="batchUserGroup.visible = false">取 消</el-button>
                <el-button :loading="batchUserGroup.btnLoading" size="small" type="primary" @click="batchSubmit('ruleForm')">确 定</el-button>
              </span>
            </el-dialog>

            <div style="text-align: right;margin: 20px 0;">
                <el-pagination
                        v-if="isPageShow"
                        @current-change="pagination"
                        background
                        layout="prev, pager, next"
                        :current-page.sync="page"
                        :page-count="pageCount">
                </el-pagination>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                list: [],
                listLoading: false,
                page: 2,
                pageCount: 0,
                keyword: '',
                type: '全部',

                multipleSelection: [],

                options: [{
                  value: '全部',
                  label: '全部'
                }, {
                  value: '未到期',
                  label: '未到期'
                },{
                  value: '已到期',
                  label: '已到期'
                }],
                isPageShow: false,
                batchUserGroup: {
                    visible: false,
                    btnLoading: false,
                    form: {
                        user_group_id: null,
                        user_group_name: ''
                    },
                    rules: {
                      user_group_id: [
                        { required: true, message: '请选择用户组', trigger: 'change' },
                      ],
                    }
                }
            };
        },
        methods: {
            search() {
                console.log(1)
                this.page = 1;
                this.getList();
            },

            formatExpired(row, column) {
                if (row.adminInfo.expired_at !== '0000-00-00 00:00:00') {
                    return row.adminInfo.expired_at;
                }
                return '永久'
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'admin/user/index',
                        page: self.page,
                        keyword: self.keyword,
                        type: self.type
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code === 0) {
                        self.list = e.data.data.list;
                        self.pageCount = e.data.data.pagination.page_count;
                        self.page = e.data.data.pagination.current_page;
                        self.list.forEach(function(item){
                            if(item.adminInfo.remark.length > 16) {
                                item.remark = item.adminInfo.remark.slice(0,16) + '...'
                            }
                            
                        })
                    }
                    self.isPageShow = true;
                }).catch(e => {
                    console.log(e);
                });
            },
            edit(id) {
                navigateTo({
                    r: 'admin/user/edit',
                    id: id,
                    page: this.page
                });
            },
            destroy(id) {
                let self = this;
                self.$confirm('删除该用户, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'admin/user/destroy',
                        },
                        method: 'post',
                        data: {
                            id: id,
                        }
                    }).then(e => {
                        if (e.data.code === 0) {
                            self.$message.success(e.data.msg);
                            self.getList();
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info('已取消删除')
                });
            },
            editPassword(id) {
                let self = this;
                self.$prompt('请输入新密码', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    inputPattern: /\S+/,
                    inputErrorMessage: '请输入新密码',
                    inputType: 'password',
                }).then(({value}) => {
                    request({
                        params: {
                            r: 'admin/user/edit-password',
                        },
                        method: 'post',
                        data: {
                            id: id,
                            password: value
                        }
                    }).then(e => {
                        if (e.data.code === 0) {
                            self.$message.success(e.data.msg);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {

                });
            },
            toMallList(row) {
                navigateTo({
                    r: 'admin/mall/index',
                    _layout: 'admin',
                    user_id: row.id,
                });
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            batchSettingUserGroup() {
                if (this.multipleSelection.length == 0) {
                    this.$message.warning('请先选择账号');
                    return false;
                }
                this.batchUserGroup.visible = true;
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
                this.batchUserGroup.form.user_group_id = row.id
                this.batchUserGroup.form.user_group_name = row.name
            },
            batchSubmit(formName) {
                let self = this;
                self.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.batchUserGroup.btnLoading = true;
                        let chooseList = [];
                        self.multipleSelection.forEach(function(item) {
                            chooseList.push(item.id)
                        })
                        request({
                            params: {
                                r: 'admin/user/batch-setting-user-group',
                            },
                            method: 'post',
                            data: {
                                user_group_id: self.batchUserGroup.form.user_group_id,
                                choose_list: chooseList
                            },
                        }).then(e => {
                            self.batchUserGroup.btnLoading = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                self.batchUserGroup.visible = false;
                                self.getList();
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
            }
        },
        mounted: function () {
            this.page = getQuery('page') ? getQuery('page') : 1;
            this.getList();
        }
    });
</script>
