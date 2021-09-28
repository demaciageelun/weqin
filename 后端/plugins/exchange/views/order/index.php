<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

Yii::$app->loadViewComponent('app-new-export-dialog');
?>
<style>
    .label {
        margin: 0 10px 0 25px;
    }

    .input-item {
        display: inline-block;
        min-width: 250px;
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

    .input-item .el-input-group__prepend {
        background-color: #fff;
    }

    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body .input-item {
        margin-left: 10px;
    }

    .table-info .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }
    .like-a {
        color: #3399ff;
        cursor: pointer;
    }
</style>
<div id="app" v-cloak>
    <el-card v-loading="listLoading" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span>订单管理</span>
        </div>
        <div class="table-body">
            <div flex="wrap:wrap cross:center" style="margin-bottom: 30px;">
                <div>礼品卡</div>
                <div class="input-item">
                    <el-input @keyup.enter.native="toSearch" size="small" placeholder="请输入礼品卡ID或名称搜索" v-model="search.goods_name" clearable @clear="toSearch">
                        <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                    </el-input>
                </div>
                <div style="margin-left: 20px;" class="input-item">
                    <el-input style="width: 360px" size="small" v-model="search.keyword" placeholder="请输入搜索内容"  clearable
                              @clear="toSearch"
                              @keyup.enter.native="toSearch">
                        <el-select style="width: 100px" slot="prepend" v-model="search.keyword_1">
                            <el-option v-for="item in selectList" :key="item.value"
                                       :label="item.name"
                                       :value="item.value">
                            </el-option>
                        </el-select>
                        <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                    </el-input>
                </div>
                <div class="item-box" flex="dir:left cross:center">
                    <div class="label">所属平台</div>
                    <el-select size="small" v-model="search.platform" @change='toSearch' class="select">
                        <el-option key="0" label="全部" value="0"></el-option>
                        <el-option key="wxapp" label="微信小程序" value="wxapp"></el-option>
                        <el-option key="aliapp" label="支付宝小程序" value="aliapp"></el-option>
                        <el-option key="ttapp" label="抖音/头条小程序" value="ttapp"></el-option>
                        <el-option key="bdapp" label="百度小程序" value="bdapp"></el-option>
                    </el-select>
                </div>
                    <app-new-export-dialog-2
                        style="margin-left: 15px"
                        text='导出报表'
                        :params="search"
                        :directly=true
                        :action_url="action_url">
                    </app-new-export-dialog-2>
                </div>
                <!-- 表格信息 -->
                <el-table class="table-info" :header-cell-style="{'background-color': '#F5F7FA','height':'80px'}" :data="list" border v-loading="listLoading">
                    <el-table-column prop="order_no" label="订单号" width="260">
                        <template slot-scope="scope">
                            <div class="like-a" @click="toOrder(scope.row)">{{scope.row.order_no}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="user_id"  width="80" label="用户ID"></el-table-column>
                    <el-table-column prop="nickname" label="下单用户">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <app-image mode="aspectFill" style="margin-right: 8px;flex-shrink: 0" :src="scope.row.avatar"></app-image>
                                <div>
                                    <div>{{scope.row.nickname}}</div>
                                    <img :src="scope.row.platform_icon" height="24" width="24" alt=""/>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="goods_name" label="礼品卡名称"></el-table-column>
                    <el-table-column prop="library_name" label="兑换码库"></el-table-column>
                    <el-table-column prop="code" label="兑换码"></el-table-column>
                    <el-table-column prop="created_at" label="购买时间" width="180"></el-table-column>
                    <el-table-column prop="msg" label="状态" width="120">
                        <template slot-scope="scope">
                            <el-tag v-if="scope.row.msg == '可用'" size="small" type="success">可用</el-tag>
                            <el-tag v-if="scope.row.msg == '已失效'" size="small" type="info">已失效</el-tag>
                            <el-tag v-if="scope.row.msg == '已兑换'" size="small" type="warning">已兑换</el-tag>
                        </template>
                    </el-table-column>
                </el-table>
                <div flex="main:right cross:center" style="margin-top: 20px;">
                    <div v-if="pagination.page_count > 0">
                        <el-pagination
                                @current-change="changePage"
                                background
                                :current-page="pagination.current_page"
                                layout="prev, pager, next, jumper"
                                :page-count="pagination.page_count">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                listLoading: false,
                keyword: '',
                platform: '0',
                action_url: 'plugin/exchange/mall/card-goods/order-log',
                checkedFields: [
                    'order_no',
                    'nickname',
                    'platform',
                    'goods_name',
                    'library_name',
                    'code',
                    'created_at',
                    'msg',
                ],
                list: [],
                selectList: [
                    {value: '4', name: '用户ID'},
                    {value: '2', name: '用户昵称'},
                    {value: '1', name: '订单号'},
                    {value: '8', name: '兑换码库'}
                ],
                pagination: {
                    page_count: 0
                },
                search: {
                    keyword_1: '4',
                    keyword: '',
                    goods_name: '',
                    platform: '0',
                    page: 1,
                }
            };
        },
        created() {
            this.getList();
        },
        methods: {
            toOrder(row) {
                console.log(row)
                this.$navigate({
                    r: 'mall/order/detail',
                    order_id: row.order_id
                },true);
            },
            changePage(page) {
                this.search.page = page;
                this.getList();
            },
            getList() {
                this.listLoading = true;

                request({
                    params: {
                        r: 'plugin/exchange/mall/card-goods/order-log',
                        page: this.search.page,
                        keyword: this.search.keyword,
                        goods_name: this.search.goods_name,
                        keyword_1: this.search.keyword_1,
                        platform: this.search.platform
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
            toSearch() {
                this.search.page = 1;
                this.getList();
            }
        }
    });
</script>
