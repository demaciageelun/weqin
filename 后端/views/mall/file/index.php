<?php defined('YII_ENV') or exit('Access Denied'); ?>
<style>
    .set-el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0 0 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover{
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus{
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

    .table-body {
        padding: 20px;
        background-color: #fff;
    }
</style>
<div id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span>下载中心</span>
        </div>
        <div class="table-body">
            <span style="color:#606266">生成时间：</span>
            <el-date-picker
                    size="small"
                    v-model="searchData.time"
                    @change="search"
                    value-format="yyyy-MM-dd HH:mm:ss"
                    type="datetimerange"
                    range-separator="至"
                    start-placeholder="开始日期"
                    end-placeholder="结束日期">
            </el-date-picker>
            <span style="color:#606266">生成状态：</span>
            <el-select style="width: 120px;" @change="search" size="small" v-model="searchData.status" placeholder="请选择">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
            </el-select>
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="请输入文件名称搜索" v-model="searchData.keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-button size="small" @click="clearFile">删除全部</el-button>
            <el-table :data="list" highlight-current-row v-loading="listLoading" style="width: 100%;" border>
                <el-table-column prop="file_name" label="生成名称"></el-table-column>
                <el-table-column prop="created_at" label="生成时间" width="180"></el-table-column>
                <el-table-column prop="status" label="生成状态" width="120">
                    <template slot-scope="scope">
                        <span size="small" type="info" v-if="scope.row.status == 0">{{scope.row.status_text}}({{scope.row.percent}})</span>
                        <span size="small" type="success" v-if="scope.row.status == 1">{{scope.row.status_text}}</span>
                        <span size="small" type="danger" v-if="scope.row.status == 2">{{scope.row.status_text}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="200" fixed="right">
                    <template slot-scope="scope">
                        <el-button v-if="scope.row.download_url && scope.row.status == 1" class="set-el-button" size="mini" type="text" circle>
                            <a :href="scope.row.download_url">
                                <el-tooltip class="item" effect="dark" content="下载文件" placement="top">
                                    <img src="statics/img/mall/download.png" alt="">
                                </el-tooltip>
                            </a>
                        </el-button>
                        <el-button v-if="scope.row.download_url && scope.row.status == 1 || scope.row.status == 2" size="mini" type="text" circle>
                            <a @click="destroy(scope.row)">
                                <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                    <img src="statics/img/mall/del.png" alt="">
                                </el-tooltip>
                            </a>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                    hide-on-single-page
                    background
                    :page-size="pagination.pageSize"
                    :total="pagination.total_count"
                    :current-page="pagination.current_page"
                    @current-change="pageChange"
                    layout="total, prev, pager, next, jumper">
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
                searchData: {
                    keyword: '',
                    time: [],
                    status: ''
                },
                options: [
                    {
                        label: '全部',
                        value: '',
                    },
                    {
                        label: '生成中',
                        value: 0,
                    },
                    {
                        label: '已生成',
                        value: 1,
                    },
                    {
                        label: '生成异常',
                        value: 2,
                    },
                ],
                list: [],
                listLoading: false,
                pagination: {},
                page: 1,
                id: 0,
            };
        },
        directives: {
            // 注册一个局部的自定义指令 v-focus
            focus: {
                // 指令的定义
                inserted: function (el) {
                    // 聚焦元素
                    el.querySelector('input').focus()
                }
            }
        },
        methods: {
            search() {
                this.page = 1;
                this.getList();
            },
            pageChange(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },

            //删除全部
            clearFile: function (column) {
                this.$confirm('确认删除所有文件吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/file/destroy-all'
                        },
                        method: 'get'
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.search();
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                    });

                });
            },
            //删除
            destroy: function (column) {
                this.$confirm('确认删除吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/file/destroy',
                            id: column.id
                        },
                        method: 'get'
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.search();
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                    });

                });
            },
            //获取列表
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/file/index',
                        page: this.page,
                        keyword: this.searchData.keyword,
                        time: this.searchData.time,
                        status: this.searchData.status,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },
        },
        mounted() {
            this.getList();
        }
    })
</script>
