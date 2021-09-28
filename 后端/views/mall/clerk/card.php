<?php defined('YII_ENV') or exit('Access Denied');
Yii::$app->loadViewComponent('app-new-export-dialog');
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .input-item {
        width: 250px;
        margin: 0 0 20px;
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

    .award-name .label-name {
        flex-shrink: 0;
        margin-right: 5px
    }

    .award-name .value-name {
        margin-right: 5px;
        margin-bottom: 5px;
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .platform-img {
        width: 24px;
        height: 24px;
        margin-right: 4px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div flex="dir:left cross:center">
                <span>核销订单</span>
                <div flex="dir:right" flex-box=1>
                    <app-new-export-dialog-2
                            :field_list='export_list'
                            action_url="mall/clerk/card"
                            :params="search">
                    </app-new-export-dialog-2>
                </div>
            </div>
        </div>
        <div class="table-body">
            <!--工具条 过滤表单和新增按钮-->
            <el-col :span="24" class="toolbar">
                <el-form size="small" :inline="true" :model="search">
                    <!-- 搜索框 -->
                    <el-form-item prop="time">
                        <span style="color:#606266">核销时间：</span>
                        <el-date-picker
                                v-model="search.time"
                                @change="searchList"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                type="datetimerange"
                                range-separator="至"
                                start-placeholder="开始日期"
                                end-placeholder="结束日期">
                        </el-date-picker>
                    </el-form-item>
                    <el-select size="small" style="width: 150px"
                        filterable
                        v-model="search.clerk_id"
                        @change="searchList" 
                        placeholder="核销员">
                        <el-option label="全部核销员" value="0"></el-option>
                        <el-option v-for="clerk_user in clerk_user_list" :label="clerk_user.name" :value="clerk_user.id"></el-option>
                    </el-select>
                    <el-form-item>
                            <el-input 
                            style="width: 350px;"
                            size="small" 
                            v-model="search.keyword" 
                            placeholder="请输入搜索内容" 
                            clearable
                            @clear="searchList"
                            @keyup.enter.native="searchList">
                            <el-select style="width: 120px" slot="prepend" v-model="search.keyword_name">
                                <el-option v-for="item in selectList" :key="item.value"
                                           :label="item.name"
                                           :value="item.value">
                                </el-option>
                            </el-select>
                        </el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <!--列表-->
            <el-table v-loading="loading" border :data="list" style="width: 100%;margin-bottom: 15px">
                <el-table-column prop="card_id" label="卡券ID" width="80"></el-table-column>
                <el-table-column prop="card_name" label="卡券名称" width="200"></el-table-column>
                <el-table-column prop="user_name" label="核销员" width="280">
                    <template slot-scope="scope">
                        <app-image mode="aspectFill" style="float: left;margin-right: 8px"
                                   :src="scope.row.clerk_user_avatar"></app-image>
                        <div flex="dir:left cross:center">
                            {{scope.row.clerk_user_name}}({{scope.row.clerk_user_id}})
                        </div>
                        <img class="platform-img" :src="scope.row.platform_icon" alt="">
                    </template>
                </el-table-column>
                <el-table-column prop="clerk_store_name" label="核销门店"></el-table-column>
                <el-table-column prop="clerk_number" label="核销次数" width="180"></el-table-column>
                <el-table-column prop="clerk_time" label="核销时间" width="180"></el-table-column>
            </el-table>

            <!--工具条 批量操作和分页-->
            <el-col :span="24" class="toolbar">
                <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="pageChange"
                        :page-size="pagination.pageSize"
                        :total="pagination.total_count"
                        style="float:right;margin-bottom:15px"
                        v-if="pagination">
                </el-pagination>
            </el-col>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                list: [],
                loading: false,
                pagination: null,
                page: 1,
                search: {
                    time: null,
                    keyword: '',
                    keyword_name: 'card_name',
                    clerk_id: '0',
                },

                export_list: [],//导出字段数据,
                selectList: [
                    {value: 'card_name', name: '卡券名称'},
                    {value: 'card_id', name: '卡券ID'}
                ],
                clerk_user_list: []
            };
        },
        methods: {
            searchList() {
                this.page = 1;
                this.getList();
            },
            pageChange(page) {
                this.page = page;
                this.getList();
            },
            getList() {
                this.loading = true;
                let param = Object.assign({r: 'mall/clerk/card'}, this.search, {page: this.page});
                request({
                    params: param,
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                        this.export_list = e.data.data.export_list;
                        this.clerk_user_list = e.data.data.clerk_user_list;
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
        },
        mounted() {
            this.getList();
        }
    })
</script>
