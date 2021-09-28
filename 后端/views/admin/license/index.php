<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.9ysw.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/15 9:47
 */
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .input-item {
        width: 200px;
        margin-right: 20px;
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

    #app .table-body .el-table .el-button {
        border-radius: 16px;
    }

    .create {
        height: 36px;
        line-height: 36px;
        float: right;
        color: #BCBCBC;
        margin-left: 20px;
    }

    .name {
        cursor: pointer;
        color: #49A9FF;
    }

    .el-input-group__append {
        background-color: #fff;
    }

    .mall-user-info img,.mall-user-info span {
        vertical-align: middle;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0;">
        <div class="table-body">
            <div flex="dir:left" style="margin-bottom: 20px;position: relative;">
                <div class="input-item">
                    <el-input 
                        @keyup.enter.native="search"
                        size="small"
                        placeholder="请输入域名地址搜索" 
                        type="text" 
                        clearable
                        @clear="search"
                        v-model="searchData.keyword">
                        <el-button slot="append" @click="search" icon="el-icon-search"></el-button>
                    </el-input>
                </div>
                <el-button style="position: absolute;right: 0" type="primary" size="small" @click="edit('add')">添加</el-button>
            </div>


            <el-table v-loading="listLoading" border :data="list" style="margin-bottom: 20px">
                <el-table-column prop="domain" label="域名地址"></el-table-column>
                <el-table-column prop="icp_number" label="ICP备案号"></el-table-column>
                <el-table-column label="公安联网备案">
                    <template slot-scope="scope">{{scope.row.security_address}} {{scope.row.security_number}}</template>
                </el-table-column>
                <el-table-column prop="electronic_domain" label="电子执照"></el-table-column>
                <el-table-column label="操作" width="180">
                    <template slot-scope="scope">
                        <el-button plain size="mini" type="info" @click="edit('update', scope.row)">编辑</el-button>

                        <el-popover v-model="scope.row.destroyPopoverVisible" placement="top" style="margin: 0 10px;">
                            <div style="margin-bottom: 10px">是否确认删除</div>
                            <div style="text-align: right">
                                <el-button size="mini"
                                           type="primary"
                                           @click="scope.row.destroyPopoverVisible = false">取消
                                </el-button>
                                <el-button size="mini" :loading="btnLoading" @click="destroy(scope.row)">确认</el-button>
                            </div>
                            <el-button plain size="mini" type="info" slot="reference">删除
                            </el-button>
                        </el-popover>

                    </template>
                </el-table-column>
            </el-table>

            <el-pagination
                    style="text-align: right"
                    v-if="pagination"
                    background
                    :page-size="pagination.pageSize"
                    @current-change="pageChange"
                    layout="prev, pager, next"
                    :total="pagination.totalCount">
            </el-pagination>
        </div>
    </el-card>

    <!-- 创建商城 -->
    <el-dialog 
        title="添加执照/备案信息" 
        :visible.sync="licenseDialog.visible" 
        width="45%" 
        :close-on-click-modal="false"
    >
        <el-form label-width="120px" size="small" :model="licenseDialog.form" :rules="licenseDialog.rules" ref="createLicenseForm">

            <div style="width: 120px;text-align: right;padding-right: 12px;color: #bbbbbb">网站链接</div>
            <el-form-item label="域名" prop="domain">
                <el-input style="width: 90%;" placeholder="请输入域名" type="text" size="small" v-model="licenseDialog.form.domain" autocomplete="off" ></el-input>
            </el-form-item>

            <div style="width: 120px;text-align: right;padding-right: 12px;color: #bbbbbb">ICP备案</div>
            <el-form-item label="备案号" prop="icp_number">
                <el-input style="width: 90%;" placeholder="请输入备案号" type="text" size="small" v-model="licenseDialog.form.icp_number" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="跳转链接" prop="icp_link">
                <el-input style="width: 90%;" placeholder="请输入备案号跳转链接" type="text" size="small" v-model="licenseDialog.form.icp_link" autocomplete="off"></el-input>
            </el-form-item>

            <div style="width: 120px;text-align: right;padding-right: 12px;color: #bbbbbb">联网备案</div>
            <el-form-item label="备案地" prop="security_address">
                <el-input style="width: 90%;" placeholder="例如：京公网安备" type="text" size="small" v-model="licenseDialog.form.security_address" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="备案号" prop="security_number">
                <el-input style="width: 90%;" placeholder="例如：11000020001" type="text" size="small" v-model="licenseDialog.form.security_number" autocomplete="off"></el-input>
            </el-form-item>

            <div style="width: 120px;text-align: right;padding-right: 12px;color: #bbbbbb">电子执照亮照</div>
            <el-form-item label="链接" prop="electronic_domain">
                <el-input style="width: 90%;" placeholder="请输入亮照链接" type="text" size="small" v-model="licenseDialog.form.electronic_domain" autocomplete="off"></el-input>
            </el-form-item>

            <el-form-item style="text-align: right">
                <el-button size="small" @click="licenseDialog.visible = false">取消</el-button>
                <el-button size="small" :loading="licenseDialog.submitLoading" type="primary"
                           @click="createLicense('createLicenseForm')">确定
                </el-button>
            </el-form-item>
        </el-form>
    </el-dialog>

</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                licenseDialog: {
                    visible: false,
                    submitLoading: false,
                    submitUrl: '',
                    submitType: '',
                    form: {
                        domain: '',
                        icp_number: '',
                        icp_link: '',
                        security_number: '',
                        security_address: '',
                        electronic_domain: '',
                    },
                    rules: {
                        domain: [
                            {required: true, message: '请填写域名。'},
                        ],
                        icp_number: [
                            {required: true, message: '请填写ICP备案号。'},
                        ],
                        icp_link: [
                            {required: true, message: '请填写ICP备案号链接。'},
                        ],
                    }
                },
                listLoading: false,
                btnLoading: false,
                list: [],
                pagination: null,
                searchData: {
                    keyword: ''
                },
                visible: false
            };
        },
        created() {
            this.loadList();
        },
        methods: {
            createLicense(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.licenseDialog.submitLoading = true;
                        this.$request({
                            params: {
                                r: this.licenseDialog.submitUrl,
                            },
                            method: 'post',
                            data: this.licenseDialog.form,
                        }).then(e => {
                            this.licenseDialog.submitLoading = false;
                            if (e.data.code === 0) {
                                this.licenseDialog.visible = false;
                                this.loadList()
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                    }
                });
            },
            destroy(row) {
                this.btnLoading = true;
                this.$request({
                    params: {
                        r: 'admin/license/destroy',
                    },
                    method: 'post',
                    data: {
                        id: row.id
                    },
                }).then(e => {
                    this.btnLoading = true;
                    row.destroyPopoverVisible = false;
                    if (e.data.code === 0) {
                        
                        this.loadList()
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            loadList() {
                this.listLoading = true;
                this.$request({
                    params: {
                        r: 'admin/license/index',
                        keyword: this.searchData.keyword,
                        page: this.searchData.page
                    },
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        for (let i in e.data.data.list) {
                            e.data.data.list[i].destroyPopoverVisible = false;
                        }
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    }
                }).catch(e => {
                });
            },
            search() {
                this.loadList();
            },
            pageChange(page) {
                this.searchData.page = page;
                this.loadList();
            },
            edit(type, row) {
                if (type == 'add') {
                    this.licenseDialog.submitUrl = 'admin/license/add';
                } else {
                    this.licenseDialog.submitUrl = 'admin/license/update';
                    this.licenseDialog.form = JSON.parse(JSON.stringify(row));
                }
                
                this.licenseDialog.visible = true;
            }
        }
    });
</script>
