<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
Yii::$app->loadViewComponent('app-wechat-reply');
?>
<style>
    .form_box {
        background-color: #fff;
        padding-right: 40%;
        margin-top: 12px;
        padding-bottom: 40px;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 15px;
    }

    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <el-tabs v-model="activeName" @tab-click="tabClick">
            <el-tab-pane label="关注回复" name="first">
                <el-card shadow="never" class="form_box">
                    <app-wechat-reply v-if="form" :form="form" @update="update"></app-wechat-reply>
                </el-card>
            </el-tab-pane>
            <el-tab-pane label="关键词回复" name="two">
                <el-card shadow="never">
                    <el-button type="primary" size="small"
                               @click="$navigate({r:'mall/wechat/keyword-rule'})">新增关键词回复
                    </el-button>
                    <el-table v-loading="listLoading" border :data="list" style="width: 100%;margin-top: 15px">
                        <el-table-column prop="name" label="规则名称"></el-table-column>
                        <el-table-column prop="keyword" label="关键词"></el-table-column>
                        <el-table-column prop="reply" label="内容"></el-table-column>
                        <el-table-column label="操作" width="280px">
                            <template slot-scope="scope">
                                <el-button size="small" type="text" @click="edit(scope.row.id)" circle>
                                    <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                        <img src="statics/img/mall/edit.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button size="small" type="text" @click="destroy(scope.row.id)" circle>
                                    <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                        <img src="statics/img/mall/del.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <div flex="dir:right" v-if="pagination" style="margin-top: 20px;">
                        <el-pagination
                            background
                            hide-on-single-page
                           :page-size="pagination.pageSize"
                            @current-change="pageChange"
                            layout="prev, pager, next, jumper"
                           :total="pagination.total_count">
                        </el-pagination>
                    </div>
                </el-card>
            </el-tab-pane>
        </el-tabs>
        <el-button v-if="activeName == 'first'" class='button-item' :loading="btnLoading" @click="submit" type="primary" size="small">保存</el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                activeName: 'first',
                text: '',
                video_url: '',
                voice_url: '',
                pic_url: '',
                cardLoading: false,
                btnLoading: false,
                listLoading: false,
                form: null,
                pagination: {},
                list: [],
                page: 1,
                valid: true,
            };
        },
        methods: {
            destroy(id) {
                this.$confirm('确认删除该条关键词回复规则?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                request({
                    params: {
                        r: 'mall/wechat/operate'
                    },
                    data: {
                        id: id,
                        type: 'keyword_reply',
                        operate: 'delete'
                    },
                    method: 'post'
                }).then(e => {
                    this.btnLoading = false;
                    if (e.data.code == 0) {
                        this.list = [];
                        this.getList(this.page);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.$message.error(e.data.msg);
                    this.btnLoading = false;
                });
                }).catch(() => {
                });
            },
            pageChange(page) {
                this.page = page;
                this.list = [];
                this.getList(page)
            },
            edit(id) {
                navigateTo({
                    r:'mall/wechat/keyword-rule',
                    id: id
                })
            },
            tabClick() {
                if(this.activeName == 'first') {
                    this.getDetail();
                }else {
                    this.getList();
                }
            },
            update(e) {
                this.text = e.text
                this.video_url = e.video_url
                this.voice_url = e.voice_url
                this.pic_url = e.pic_url
                this.form = e.form
                this.valid = e.valid;
            },
            submit() {
                if(this.valid) {
                    this.$confirm('是否保存回复内容?', '提示', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        this.store();
                    }).catch();
                }
            },
            store() {
                this.btnLoading = true;
                if(this.form.type == 0) {
                    this.form.content = this.text;
                }
                if(this.form.type == 1) {
                    this.form.url = this.pic_url;
                }
                if(this.form.type == 2) {
                    this.form.url = this.voice_url;
                }
                if(this.form.type == 3) {
                    this.form.url = this.video_url;
                }
                request({
                    params: {
                        r: 'mall/wechat/reply'
                    },
                    method: 'post',
                    data: this.form
                }).then(e => {
                    this.btnLoading = false;
                    if (e.data.code == 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.$message.error(e.data.msg);
                    this.btnLoading = false;
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/wechat/reply'
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.form = e.data.data;
                        if(this.form.type == 0) {
                            this.text = this.form.content;
                        }
                        if(this.form.type == 1) {
                            this.pic_url = this.form.url;
                        }
                        if(this.form.type == 2) {
                            this.voice_url = this.form.url;
                        }
                        if(this.form.type == 3) {
                            this.video_url = this.form.url;
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            getList(page) {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/wechat/keyword-rule-list',
                        page: page ? page : 1
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code == 0) {
                        self.list = e.data.data.list;
                        self.pagination = e.data.data.pagination;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.activeName = getQuery('tab') || 'first';
            this.tabClick();
        }
    });
</script>
