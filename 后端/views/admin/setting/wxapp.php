<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2019/3/22
 * Time: 16:23
 */
?>
<style>
    .form-body {
        display: flex;
        justify-content: center;
    }

    .form-body .el-form {
        width: 550px;
        margin-top: 10px;
    }

    .url-form.form-body .el-form {
        margin-top: -10px;
    }

    .currency-width {
        width: 400px;
    }

    .currency-width .el-input__inner {
        height: 35px;
        line-height: 35px;
        border-radius: 8px;
    }

    .isAppend .el-input__inner {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .form-body .currency-width .el-input-group__append {
        width: 80px;
        background-color: #2E9FFF;
        color: #fff;
        padding: 0;
        line-height: 35px;
        height: 35px;
        text-align: center;
        border-radius: 8px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 0;
    }

    .title {
        margin-bottom: 20px;
    }

    .table-body {
        padding: 40px 20px 20px;
        background-color: #fff;
    }

    .outline {
        display: inline-block;
        vertical-align: middle;
        line-height: 32px;
        height: 32px;
        color: #F56E6E;
        cursor: pointer;
        font-size: 24px;
        margin: 0 5px;
    }

    .plugin-list {
        width: 200px;
        margin-bottom: 20px;
    }

    .plugin-list .plugin-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 10px 0;
    }

    .plugin-list .plugin-item:last-child {
        border: none;
    }
    .button-item {
        margin: 20px 0;
        width: 80px;
    }

    .template-list .el-table {
        max-height: 500px;
        overflow: auto;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="loading">
        <div style="margin-bottom: 20px">基础配置</div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="150px" :model="form" ref="form">
                <el-form-item label="APPID">
                    <el-input class="currency-width" v-model="form.appid"></el-input>
                </el-form-item>
                <el-form-item label="APPSECRET">
                    <el-input class="currency-width" v-model="form.appsecret"></el-input>
                </el-form-item>
                <el-form-item label="消息校验Token">
                    <el-input class="currency-width" v-model="form.token"></el-input>
                </el-form-item>
                <el-form-item label="消息解密Key">
                    <el-input class="currency-width" v-model="form.encoding_aes_key"></el-input>
                </el-form-item>
                <el-form-item label="当月可提审次数">
                    <el-button type="primary" @click="showQuota" type="text">点击查询</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div style="margin-bottom: 20px">绑定开放平台小程序参数</div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="150px" :model="form" ref="form">
                <el-form-item label="APPID">
                    <el-input class="currency-width" v-model="form.third_appid"></el-input>
                </el-form-item>
            </el-form>
        </div>
        <div style="margin-bottom: 20px">上传至开放平台 <el-button @click="showLog" type="text">发布记录</el-button></div>
        <div>
            <el-steps :active="step" finish-status="success" align-center
                      style="border-bottom: 1px solid #ebeef5;padding-bottom: 20px">
                <el-step title="扫描二维码登录"></el-step>
                <el-step title="上传成功"></el-step>
            </el-steps>
            <div style="text-align: center; padding: 20px 0" v-if="!upload_step">
                <el-button type="primary" @click="login" :loading="upload_loading" v-if="!login_qrcode">获取登录二维码
                </el-button>
                <div style="text-align: center" v-if="login_qrcode">
                    <img :src="login_qrcode"
                         style="width: 150px;height: 150px; border: 1px solid #e2e2e2;margin-bottom: 12px">
                    <div style="margin-bottom: 12px;">请使用微信扫码登录</div>
                    <div style="color: #909399;">
                        <div>上传小程序前请扫码登录。</div>
                        <div>扫码登录后大约会有10秒左右延时，请您耐心等待。</div>
                        <div>您的微信号必须是该小程序的管理员或者开发者才可扫码登录。</div>
                    </div>
                </div>
                <el-button style="margin-top: 10px" type="primary" @click="checkUpload" :loading="upload_loading" v-if="login_qrcode && !upload_success">上传小程序
                </el-button>
            </div>
            <div style="text-align: center;margin-top: 10px" v-if="upload_step">
                <el-button style="margin-top: 10px" type="primary" @click="checkUpload" :loading="upload_loading" v-if="!upload_success">上传小程序
                </el-button>
                <div v-else style="padding: 20px 0;">
                    <div style="margin-bottom: 12px">
                        <span>上传成功！</span>
                    </div>
                    <div style="margin-bottom: 12px">
                        <div>版本号：{{version}}</div>
                    </div>
                </div>
            </div>
        </div>
    </el-card>
    <el-button class='button-item' :loading="submitLoading" type="primary" @click="submit" size="small">保存</el-button>
    <el-dialog class="template-list" title="发布记录" :visible.sync="dialogTableVisible">
        <el-table :data="list" v-loading="formLoading">
            <el-table-column property="user_version" label="版本号" width="350"></el-table-column>
            <el-table-column property="create_at" label="添加模板库时间"></el-table-column>
            <el-table-column label="操作" width="150">
                <template slot-scope="scope">
                    <el-button class="set-el-button" size="mini" type="text" circle @click="destroy(scope.row,scope.$index)">
                        <el-tooltip class="item" effect="dark" content="删除" placement="top">
                            <img src="statics/img/mall/del.png" alt="">
                        </el-tooltip>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-dialog>
    <el-dialog class="quota-list" title="查询当月可提审次数" :visible.sync="dialogQuotaVisible" width="25%">
        <div class="quota-content" v-loading="quotaLoading">
            <div class="quota-item">当月总共可提审次数: {{limit}}次</div>
            <div class="quota-item">当月剩余可提审次数: {{rest}}次</div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="dialogQuotaVisible = false">我知道了</el-button>
        </span>
    </el-dialog>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                submitLoading: false,
                formLoading: false,
                dialogTableVisible: false,
                dialogQuotaVisible: false,
                quotaLoading: false,
                form: {
                    appid: '',
                    appsecret: '',
                    token: '',
                    encoding_aes_key: '',
                },
                list: [],
                step: 0,
                upload_loading: false,
                upload_step: false,
                login_qrcode: false,
                upload_success: false,
                version: '',
                rest: 0,
                limit: 0,
            };
        },
        created() {
            this.loadData();
        },
        methods: {
            login() {
                this.upload_loading = true;
                this.upload_step = false;
                this.$request({
                    params: {
                        r: 'admin/setting/upload',
                        action: 'login',
                        branch: '',
                    },
                }).then(e => {
                    this.upload_loading = false;
                    if (e.data.code === 0) {
                        this.step = 1;
                        this.login_qrcode = e.data.data.qrcode;
                    } else {
                        this.$alert(e.data.msg, '提示', {
                            callback() {
                                // location.reload();
                            },
                        });
                    }
                }).catch(e => {
                    this.upload_loading = false;
                });
            },
            checkUpload() {
                this.$confirm('上传小程序需扫码登录, 请确定完成已扫码登录操作。', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.upload();
                })
            },
            destroy(column, index) {
                this.$confirm('确认删除该记录吗?', '提示', {
                    type: 'warning'
                }).then(() => {
                    this.formLoading = true;
                    request({
                        params: {
                            r: 'admin/setting/del-template'
                        },
                        data: {template_id: column.template_id},
                        method: 'post'
                    }).then(e => {
                        this.list.splice(index, 1)
                        this.formLoading = false;
                    }).catch(e => {
                        this.formLoading = false;
                    });

                });
            },
            upload() {
                this.upload_loading = true;
                this.upload_step = true;
                this.$request({
                    params: {
                        r: 'admin/setting/upload',
                        action: 'upload',
                        branch: '',
                    },
                }).then(e => {
                    this.upload_loading = false;
                    if (e.data.code === 0) {
                        this.step = 2;
                        this.upload_success = true;
                        this.version = e.data.data.version;
                    } else {
                        this.upload_step = false;
                        this.$alert(e.data.msg, '提示');
                    }
                }).catch(e => {
                    this.upload_loading = false;
                });
            },
            loadData() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/wxapp',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        if (e.data.data.platform) {
                            this.form = e.data.data.platform;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                })
            },
            getLog() {
                this.formLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/template-list',
                    },
                }).then(e => {
                    this.formLoading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list.template_list;
                    }
                }).catch(e => {
                });
            },
            showLog() {
                this.dialogTableVisible = true;
                this.getLog();
            },
            showQuota() {
                let self = this;
                self.$prompt('请填写已授权三方的小程序商城id', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    inputPattern: /\S+/,
                    inputErrorMessage: '请填写已授权三方的小程序商城id',
                }).then(({value}) => {
                    this.dialogQuotaVisible = true;
                    this.getQuota(value);
                }).catch(() => {

                });
            },
            getQuota(mall_id) {
                this.quotaLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/quota',
                        mall_id: mall_id,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.quotaLoading = false;
                        this.rest = e.data.data.rest;
                        this.limit = e.data.data.limit;
                    } else {
                        this.dialogQuotaVisible = false;
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.dialogQuotaVisible = false;
                    this.$message.error(e.data.msg);
                });
            },
            submit() {
                this.submitLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/wxapp',
                    },
                    method: 'post',
                    data: {
                        platform: this.form,
                    },
                }).then(e => {
                    this.submitLoading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            updateSuccess(e) {
                this.$message.success('上传成功')
            }
        }
    });
</script>
