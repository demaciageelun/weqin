<style>
    .app-video-model .el-radio .el-radio__label {
        display: none;
    }
</style>
<template id="app-video-model">
    <div class="app-video-model">
        <el-dialog title="选择视频" :visible.sync="videoDialog" top="10vh" width="750">
            <div class="input-item" style="margin-bottom: 12px">
                <el-input @keyup.enter.native="searchVideo" size="small" placeholder="根据名称搜索"
                          v-model="search.keyword" clearable
                          @clear="searchVideo">
                    <el-button slot="append" @click="searchVideo">搜索</el-button>
                </el-input>
            </div>
            <div flex="dir:top" v-loading="listLoading">
                <el-table :data="list" stripe style="width: 100%">
                    <el-table-column label="" width="80">
                        <template slot-scope="scope">
                            <el-radio style="padding-left: 20px" v-model="form.id" :label="scope.row.id"></el-radio>
                        </template>
                    </el-table-column>
                    <el-table-column label="名称">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <app-image :src="scope.row.pic_url"></app-image>
                                <div style="margin-left: 20px">{{scope.row.title}}</div>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!--工具条 批量操作和分页-->
            <el-col :span="24" class="toolbar">
                <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="pageChange"
                        :page-size="pagination.pageSize"
                        :total="pagination.total_count"
                        style="text-align:center;margin: 15px 0"
                        v-if="pagination">
                </el-pagination>
            </el-col>
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="videoDialog = false">取 消</el-button>
                <el-button size="small" type="primary" @click="videoConfirm">确 定</el-button>
            </div>
        </el-dialog>

        <span @click="openVideoDialog">
            <slot></slot>
        </span>
    </div>
</template>

<script>
    Vue.component('app-video-model', {
        template: '#app-video-model',
        props: {
            value: {
                type: String,
                default: () => {
                    return ""
                }
            },
        },
        data() {
            return {
                listLoading: false,
                search: {
                    keyword: ''
                },
                form: {
                    id: ''
                },
                list: [],
                videoDialog: false,
                page: 1,
                pagination: null,
            }
        },
        methods: {
            openVideoDialog() {
                if (!this.list || !this.list.length) {
                    this.getList();
                }
                this.videoDialog = true;
            },
            closeVideoDialog() {
                this.videoDialog = false;
            },
            videoConfirm() {
                let listItem = null;
                for (let item of this.list) {
                    if (item['id'] == this.form.id) {
                        listItem = item;
                        break;
                    }
                }
                this.$emit('change', listItem);
                this.$emit('input', this.form.id);
                this.closeVideoDialog();
            },
            pageChange(page) {
                this.form.id = '';
                this.page = page;
                this.getList();
            },
            searchVideo() {
                this.page = 1;
                this.getList();
            },
            getList() {
                const self = this;
                self.listLoading = true;
                request({
                    params: Object.assign({}, {
                        r: 'mall/video/index',
                        keyword: self.search.keyword,
                        page: self.page,
                    }),
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code === 0) {
                        self.pagination = e.data.data.pagination;
                        self.list = e.data.data.list;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                });
            },
        },
        mounted() {
        }
    })
</script>