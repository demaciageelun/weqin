<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

Yii::$app->loadViewComponent('admin/app-export-dialog-admin');
?>

<style>
    /*.el-card__header {
        height: 60px;
        line-height: 60px;
        padding: 0 20px;
    }*/

    .date-select {
        margin-right: 0!important;
        color: #909399;
    }

    .date-select .el-input__inner {
        background-color: #F5F7FA;
        width: 120px;
        border-right: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        color: #909399;
    }
    .date-picker{
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

<div id="app" v-cloak>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>订单列表</span>
            <span style="float: right;">
                <el-button id="app-order-download" size="small" @click="$navigate({r:'admin/app-manage/file'})">下载中心</el-button>
                <app-export-dialog-admin
                    :field_list='export_list' 
                    :params="search"
                    action_url="admin/app-manage/order">
                </app-export-dialog-admin>
            </span>
        </div>
        <div flex="dir:left" style="margin-bottom: 15px;">
            <div style="width: 400px;" class="item-box label" class="show-search-icon">
                <el-input size="small" v-model="search.keyword" placeholder="请输入搜索内容"  clearable
                          @clear="toSearch"
                          @keyup.enter.native="toSearch">
                    <el-select style="width: 120px" slot="prepend" v-model="search.keyword_1">
                        <el-option v-for="item in selectList" :key="item.value"
                                   :label="item.name"
                                   :value="item.value">
                        </el-option>
                    </el-select>
                </el-input>
            </div>

            <div style="display: inherit;margin-left: 10px;">
                <el-select class="item-box date-select" size="small" v-model="search.date_type" placeholder="请选择">
                <el-option label="下单时间" value="created_time"></el-option>
                </el-select>
                <el-date-picker
                        class="item-box date-picker"
                        size="small"
                        @change="changeTime"
                        v-model="search.time"
                        type="datetimerange"
                        value-format="yyyy-MM-dd HH:mm:ss"
                        range-separator="至"
                        start-placeholder="开始日期"
                        end-placeholder="结束日期">
                </el-date-picker>
            </div>
        </div>
        <!-- <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane v-for="(item, index) in tabs" :key="index" :label="item.name"
                         :name="item.value"></el-tab-pane>
        </el-tabs> -->
        <el-table
            v-loading="loading"
            :data="list"
            border
            style="width: 100%">
            <el-table-column
                    prop="order_no"
                    width="245"
                    label="订单号">
            </el-table-column>
            <el-table-column
                    prop="nickname"
                    width="150"
                    label="用户">
            </el-table-column>
            <el-table-column
                    prop="app_name"
                    width="180"
                    label="应用名称">
            </el-table-column>
            <el-table-column
                    prop="pay_price"
                    width="180"
                    label="支付价格">
            </el-table-column>
            <el-table-column
                    prop="status"
                    label="状态">
            </el-table-column>
        </el-table>
        <div style="text-align: right;margin: 20px 0;">
            <el-pagination @current-change="pagination" background layout="prev, pager, next, jumper"
                           :page-count="pageCount">
            </el-pagination>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
            	list: [],
                export_list: [],
            	loading: false,
                activeName: '0',
                search: {
                    time: [],
                    date_start: '',
                    date_end: '',
                    keyword_1: 'order_no',
                    keyword: '',
                    date_type: 'created_time',
                    status: '0'
                },
                tabs: [
                    {name: '全部', value: '0'},
                    {name: '待付款', value: '1'},
                    {name: '已完成', value: '2'},
                ],
                selectList: [
                    {name: '订单号', value: 'order_no'},
                    {name: '用户', value: 'nickname'},
                    {name: '应用名称', value: 'app_name'},
                ],
                pageCount: 0,
                page: 1,
            };
        },
        methods: {
            handleClick(e) {
                this.search.status = e.name;
                this.toSearch()
            },
            // 日期搜索
            changeTime() {
                if (this.search.time) {
                    this.search.date_start = this.search.time[0];
                    this.search.date_end = this.search.time[1];
                } else {
                    this.search.date_start = null;
                    this.search.date_end = null;
                }
                this.toSearch();
            },
            toSearch() {
                this.getList();
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/app-manage/order',
                        page: this.page,
                        search: JSON.stringify(this.search)
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pageCount = e.data.data.pagination.page_count;
                        this.export_list = e.data.data.export_list;
                    }
                }).catch(() => {
                    this.loading = false;
                });
            }
        },
        mounted: function () {
            this.getList();
            let html = document.getElementById('app-order-download')
            localStorage.setItem('_APP_ORDER_DOWNLOAD_PARAMS', JSON.stringify(html.getBoundingClientRect()));
        }
    });
</script>
