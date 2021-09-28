<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
Yii::$app->loadViewComponent('admin/app-batch-permission');
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
        margin-left: 10px;
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
            <div flex="dir:left">
                <el-select style="width: 130px;" size="small" v-model="keyword_name" placeholder="请选择">
                    <el-option
                      v-for="item in options"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value">
                    </el-option>
                </el-select>
                <div class="input-item">
                    <el-input 
                        @keyup.enter.native="search"
                        style="width: 285px;"
                        size="small" 
                        placeholder="请输入用户组名称、用户名或手机号" 
                        clearable 
                        v-model="keyword_value" 
                        @clear='getList'
                    >
                        <el-button slot="append" @click="search" icon="el-icon-search"></el-button>
                    </el-input>
                </div>
                <div style="margin-left: 35px;">
                    <el-button size="small" type="primary" @click="edit">新建用户组</el-button>
                </div>
            </div>
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                    prop="name"
                    width="200"
                    label="用户组名称">
                </el-table-column>
                <el-table-column
                    prop="user_count"
                    width="80"
                    label="用户数">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.user_count}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="group_name"
                    width="200"
                    label="权限套餐">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.group_name}}</app-ellipsis>
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
                            <el-button @click="destroy(scope.row.id)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                    <img src="statics/img/mall/del.png" alt="">
                                </el-tooltip>
                            </el-button>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

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
                keyword_name: '',
                keyword_value: '',
                keyword_name: 'name',

                multipleSelection: [],

                options: [
                {
                  value: 'name',
                  label: '用户组名称'
                },
                {
                  value: 'username',
                  label: '用户名'
                },
                {
                  value: 'mobile',
                  label: '手机号'
                },
                ],
                isPageShow: false
            };
        },
        methods: {
            search() {
                console.log(1)
                this.page = 1;
                this.getList();
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
                        r: 'admin/user/user-group',
                        page: self.page,
                        keyword_name: self.keyword_name,
                        keyword_value: self.keyword_value,
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code === 0) {
                        self.list = e.data.data.list;
                        self.pageCount = e.data.data.pagination.page_count;
                        self.page = e.data.data.pagination.current_page;
                    }
                    self.isPageShow = true;
                }).catch(e => {
                    console.log(e);
                });
            },
            edit(id) {
                navigateTo({
                    r: 'admin/user/user-group-edit',
                    id: id,
                });
            },
            destroy(id) {
                let self = this;
                self.$confirm('删除该条数据, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'admin/user/user-group-destroy',
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
            }
        },
        mounted: function () {
            this.page = getQuery('page') ? getQuery('page') : 1;
            this.getList();
        }
    });
</script>
