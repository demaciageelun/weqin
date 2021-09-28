<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2020 浙江禾匠信息科技有限公司
 * author: xay
 */
?>
<style>
    .input-item {
        width: 300px;
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

    .params-template .params-list {
        border-radius: 4px 0 4px 4px;
        border: 1px solid #DCDFE6;
        padding-top: 18px;
        margin-bottom: 12px;
        padding-right: 18px;
        position: relative;
        cursor: move;
    }

    .params-template .params-btn {
        position: absolute;
        text-align: center;
        top: 0;
        right: -35px;
        line-height: 35px;
        width: 35px;
        background: #00a0e9;
        cursor: pointer;
    }

    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body .params-name {
        background: #f3f3f3;
        color: #666666;
        display: inline-block;
        line-height: 32px;
        margin: 5px;
        border-radius: 5px;
        padding: 0 15px;
    }

    .el-scrollbar .el-scrollbar__wrap {
        max-height: 55vh;
    }
</style>
<div id="app" v-cloak class="material material-dialog">
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <el-breadcrumb separator="/" style="display: inline-block">
                    <el-breadcrumb-item>
                        <span>参数模板</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
                <div style="float: right; margin: -5px 0">
                    <el-button type="primary" @click="handleAdd" size="small">新增模板管理</el-button>
                </div>
            </div>
        </div>

        <div class="table-body">
            <!--工具条 过滤表单和新增按钮-->
            <el-col :span="24" class="toolbar" style="padding-bottom: 0px">
                <div class="input-item">
                    <el-input @keyup.enter.native="searchList"
                              size="small"
                              placeholder="请输入参数模板名称或者参数内容搜索"
                              v-model="search.keyword"
                              clearable
                              @clear="searchList">
                        <el-button slot="append" icon="el-icon-search" @click="searchList"></el-button>
                    </el-input>
                </div>
            </el-col>

            <!--列表-->
            <el-table :data="list" v-loading="listLoading" style="width: 100%;" border>
                <el-table-column prop="name" label="参数模板名称" width="300"></el-table-column>
                <el-table-column prop="content" label="参数内容">
                    <template slot-scope="scope">
                        <span v-for="(item,index) in scope.row.content" :key="index">
                            <span class="del-params-name">参数名:{{item.label}}，参数值:{{item.value}}；</span>
                        </span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" width="180" fixed="right">
                    <template slot-scope="scope">
                        <el-button @click="handleEdit(scope.$index,scope.row)" type="text" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button @click="handleDel(scope.$index,scope.row)" type="text" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <!--工具条 分页-->
            <el-col flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                        background
                        hide-on-single-page
                        layout="prev, pager, next, jumper"
                        @current-change="pageChange"
                        :page-size="pagination.pageSize"
                        :total="pagination.total_count"
                        style="float:right;margin:15px 0">
                </el-pagination>
            </el-col>
        </div>

        <el-dialog :title="title" :visible.sync="ruleFormVisible" class="params-template" top="10vh">
            <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="120px"
                     @submit.native.prevent>
                <el-form-item label="参数模板名称" prop="name" style="width: 35vw">
                    <el-input v-model="ruleForm.name" placeholder="请输入参数模板名称"></el-input>
                </el-form-item>
                <el-form-item label="参数内容" prop="content">
                    <el-scrollbar>
                        <draggable :options="{draggable:'.params-list',filter:'.d-filter',preventOnFilter:false}"
                                   @end="makeparamsGroup" :mask="false"
                                   flex="dir:left" style="flex-wrap: wrap" v-model="ruleForm.content">
                            <div v-for="(c,i) of ruleForm.content" :key="i" class="params-list">
                                <el-form-item :prop="'content.' + i + '.label'"
                                              :rules="[{required: true, message: '参数名不能为空', trigger: 'blur'}]"
                                              label="参数名" style="width: 30vw;margin-left: -40px">
                                    <el-input class="d-filter" v-model="c.label" placeholder="请输入参数名"></el-input>
                                </el-form-item>
                                <el-form-item label="参数值"
                                              :prop="'content.' + i + '.value'"
                                              :rules="[{required: true, message: '参数值不能为空', trigger: 'blur'}]"
                                              style="width: 30vw;margin-left: -40px">
                                    <el-input class="d-filter" v-model="c.value" placeholder="请输入参数值"></el-input>
                                </el-form-item>
                                <div v-if="ruleForm.content.length > 1" class="params-btn"
                                     @click="handleParamsListDel(i)">
                                    <icon class="el-icon-delete" name="123213" style="color: #FFFFFF"></icon>
                                </div>
                            </div>
                        </draggable>
                    </el-scrollbar>
                </el-form-item>
                <div style="margin-left: 35px">
                    <div style="color:#c9c9c9;font-size: 13px;margin-bottom: 10px">注：最多添加{{maxLength}}组参数内容</div>
                    <el-button v-if="ruleForm.content.length < maxLength" @click="addParams" size="small"
                               type="primary">+添加参数内容
                    </el-button>
                </div>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button size="small" @click.native="ruleFormVisible = false">取消</el-button>
                <el-button size="small" type="primary" :loading="btnLoading" @click="editSubmit">保存</el-button>
            </span>
        </el-dialog>
    </el-card>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                maxLength: 20,
                listLoading: false,
                search: {
                    keyword: '',
                },
                page: 1,
                list: [],
                pagination: {},
                btnLoading: false,
                ruleForm: {
                    name: '',
                    content: []
                },
                ruleFormVisible: false,
                rules: {
                    name: [
                        {required: true, message: '模板名称不能为空', trigger: 'blur'},
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                let preg = /[\'"\\=]/;
                                if (preg.test(value)) {
                                    callback(`规格名称不能包含\ ' " \\ =等特殊符`);
                                }
                                callback();
                            }
                        }
                    ],
                    content: [
                        {required: true, message: '参数内容不能为空', trigger: 'change'},
                        {
                            required: true, type: 'array', validator: (rule, value, callback) => {
                                callback();
                                let s = new Set();
                                let sentinel = false;
                                let preg = /[\'"\\=]/;
                                let status = value.every(item => {
                                    if (preg.test(item.label) || preg.test(item.value)) {
                                        sentinel = true;
                                    }
                                    s.add(item.label);
                                    return item.label && item.value;
                                });
                                if (sentinel) {
                                    callback(`不能包含\ ' " \\ =等特殊符`);
                                }

                                if (!status) {
                                    callback('名/值不能为空');
                                }
                                if (s.size !== value.length) {
                                    callback('参数名不能重复');
                                }
                                callback();
                            },
                        }
                    ],
                },
                title: '新增参数模板',
            };
        },
        mounted() {
            this.getList();
        },
        methods: {
            makeparamsGroup() {
                console.log(1);
            },
            addParams() {
                this.ruleForm.content.push({
                    label: '',
                    value: '',
                })
            },
            handleParamsListDel(index) {
                this.ruleForm.content.splice(index, 1);
            },
            pageChange(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            searchList() {
                this.page = 1;
                this.getList();
            },
            handleAdd() {
                this.title = '新增参数模板';
                this.ruleForm = {
                    name: '',
                    content: [{
                        label: '',
                        value: '',
                    }],
                }
                this.ruleFormVisible = true;
            },
            handleEdit(index, row) {
                this.title = '编辑参数模板';
                this.ruleForm = JSON.parse(JSON.stringify(row));
                this.ruleFormVisible = true;
            },
            editSubmit() {
                const self = this;
                self.$refs.ruleForm.validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/goods-params-template/post'
                            },
                            method: 'POST',
                            data: self.ruleForm,
                        }).then(e => {
                            self.btnLoading = false;
                            self.ruleFormVisible = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.btnLoading = false;
                        });
                    }
                });
            },
            handleDel(index, row) {
                this.$confirm('确认删除该记录吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    let para = {id: row.id};
                    request({
                        params: {
                            r: 'mall/goods-params-template/destroy'
                        },
                        data: para,
                        method: 'post'
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.list.splice(index, 1);
                            this.$message.success(e.data.msg);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    })
                })
            },
            getList() {
                const self = this;
                self.listLoading = true;
                const params = Object.assign({r: 'mall/goods-params-template/index', page: self.page}, self.search);
                request({
                    params,
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    self.pagination = e.data.data.pagination;
                }).catch(e => {
                    self.listLoading = false;
                });
            },
        }
    });
</script>
