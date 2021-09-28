<?php

Yii::$app->loadViewComponent('app-goods');
Yii::$app->loadViewComponent('goods/app-select-goods');
?>
<style>
    .header-box {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 10px;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .out-max {
        width: 500px;
    }

    .out-max > .el-card__header {
        padding: 0 15px;
    }
</style>
<div id="app" v-cloak>
    <div slot="header" class="header-box">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>
                <span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'mall/pay-type/index'})">
                    支付方式
                </span>
            </el-breadcrumb-item>
            <el-breadcrumb-item v-if="id">编辑支付方式</el-breadcrumb-item>
            <el-breadcrumb-item v-else>新建支付方式</el-breadcrumb-item>
        </el-breadcrumb>
    </div>
    <el-card v-loading="listLoading" shadow="never" style="background: #FFFFFF" body-style="background-color: #ffffff;">
        <el-form :model="editForm" ref="editForm" :rules="editFormRules" label-width="150px" position-label="right">
            <el-form-item prop="name" label="支付名称">
                <el-input class="out-max" size="small" v-model="editForm.name"></el-input>
            </el-form-item>
            <el-form-item prop="type" label="支付方式选择">
                <el-radio-group v-model="editForm.type" @change="changeType">
                    <el-radio :label="1">微信</el-radio>
                    <el-radio :label="2">支付宝</el-radio>
                </el-radio-group>
            </el-form-item>
            <template v-if="editForm.type == 1 && editForm.is_service == 0">
                <el-form-item prop="is_service" label="支付类型选择">
                    <el-radio-group v-model="editForm.is_service" @change="changeType">
                        <el-radio :label="0">普通商户</el-radio>
                        <el-radio :label="1">服务商</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="微信APPID" prop="appid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.appid"></el-input>
                </el-form-item>
                <el-form-item label="微信支付商户号" prop="mchid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.mchid"></el-input>
                </el-form-item>
                <el-form-item label="微信支付Api密钥" prop="key">
                    <el-input @focus="hidden.key = false"
                              class="out-max" size="small"
                              v-if="hidden.key"
                              readonly
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" v-model.trim="editForm.key"></el-input>
                </el-form-item>
                <el-form-item label="微信支付apiclient_cert.pem" prop="cert_pem">
                    <el-input @focus="hidden.cert_pem = false"
                              class="out-max" size="small"
                              v-if="hidden.cert_pem"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                              v-model="editForm.cert_pem"></el-input>
                </el-form-item>
                <el-form-item label="微信支付apiclient_key.pem" prop="key_pem">
                    <el-input @focus="hidden.key_pem = false"
                              class="out-max" size="small"
                              v-if="hidden.key_pem"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                              v-model="editForm.key_pem"></el-input>
                </el-form-item>
            </template>
            <template v-if="editForm.type == 1 && editForm.is_service == 1">
                <el-form-item prop="is_service" label="支付类型选择">
                    <el-radio-group v-model="editForm.is_service">
                        <el-radio :label="0">普通商户</el-radio>
                        <el-radio :label="1">服务商</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="微信APPID" prop="appid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.appid"></el-input>
                </el-form-item>
                <el-form-item label="特约商户商户号" prop="mchid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.mchid"></el-input>
                </el-form-item>
                <el-form-item label="服务商AppId" prop="service_appid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.service_appid"></el-input>
                </el-form-item>
                <el-form-item label="服务商商户号" prop="service_mchid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.service_mchid"></el-input>
                </el-form-item>
                <el-form-item label="微信支付服务商Api密钥" prop="service_key">
                    <el-input @focus="hidden.service_key = false"
                              class="out-max" size="small"
                              v-if="hidden.service_key"
                              readonly
                              placeholder="已隐藏内容，点击编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" v-model.trim="editForm.service_key"></el-input>
                </el-form-item>

                <el-form-item label="微信支付服务商apiclient_cert.pem">
                    <app-upload @complete="updateSuccess" accept="" :params="service_cert_pem"
                                :simple="true" style="display: inline-block">
                        <el-button size="small">上传文件</el-button>
                    </app-upload>
                </el-form-item>
                <el-form-item label="微信支付服务商apiclient_key.pem">
                    <app-upload @complete="updateSuccess" accept="" :params="service_key_pem"
                                :simple="true" style="display: inline-block">
                        <el-button size="small">上传文件</el-button>
                    </app-upload>
                </el-form-item>
                <el-form-item label="微信支付特约商户Api密钥" prop="key">
                    <el-input @focus="hidden.key = false"
                              class="out-max" size="small"
                              v-if="hidden.key"
                              readonly
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" v-model.trim="editForm.key"></el-input>
                </el-form-item>
                <el-form-item label="微信支付特约商户apiclient_cert.pem" prop="cert_pem">
                    <el-input @focus="hidden.cert_pem = false"
                              class="out-max" size="small"
                              v-if="hidden.cert_pem"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                              v-model="editForm.cert_pem"></el-input>
                </el-form-item>
                <el-form-item label="微信支付特约商户apiclient_key.pem" prop="key_pem">
                    <el-input @focus="hidden.key_pem = false"
                              class="out-max" size="small"
                              v-if="hidden.key_pem"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                              v-model="editForm.key_pem"></el-input>
                </el-form-item>
            </template>
            <template v-if="editForm.type == 2">
                <el-form-item label="应用AppID" prop="alipay_appid">
                    <el-input class="out-max" size="small" v-model="editForm.alipay_appid"></el-input>
                </el-form-item>
                <el-form-item label="支付宝公钥" prop="alipay_public_key">
                    <el-input @focus="hidden.alipay_public_key = false"
                              class="out-max" size="small"
                              v-if="hidden.alipay_public_key"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else v-model="editForm.alipay_public_key" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
                <el-form-item label="应用私钥" prop="app_private_key">
                    <el-input @focus="hidden.app_private_key = false"
                              class="out-max" size="small"
                              v-if="hidden.app_private_key"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else v-model="editForm.app_private_key" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
                <el-form-item label="应用公钥证书" prop="appcert">
                    <el-input @focus="hidden.appcert = false"
                              class="out-max" size="small"
                              v-if="hidden.appcert"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else v-model="editForm.appcert" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
                <el-form-item label="支付宝根证书" prop="alipay_rootcert">
                    <el-input @focus="hidden.alipay_rootcert = false"
                              class="out-max" size="small"
                              v-if="hidden.alipay_rootcert"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="已隐藏内容，点击查看或编辑">
                    </el-input>
                    <el-input v-else v-model="editForm.alipay_rootcert" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
            </template>
        </el-form>
    </el-card>
    <el-button size="small" style="margin-top: 20px" :loading="btnLoading" type="primary" @click="submit">保存</el-button>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                service_key_pem: {
                    r: 'mall/pay-type/upload-pem',
                    type: 'key',
                    id: getQuery('id'),
                },
                service_cert_pem: {
                    r: 'mall/pay-type/upload-pem',
                    type: 'cert',
                    id: getQuery('id'),
                },
                id: getQuery('id'),
                btnLoading: false,
                listLoading: false,
                hidden: {
                    alipay_public_key: true,
                    app_private_key: true,
                    appcert: true,
                    alipay_rootcert: true,
                    service_key_pem: true,
                    service_cert_pem: true,
                    service_key: true,
                    key: true,
                    cert_pem: true,
                    key_pem: true,
                    alipay_private_key: true,
                },
                editForm: {
                    alipay_appid: '',//支付宝APPID
                    alipay_public_key: '', //支付宝公钥
                    app_private_key: '',//应用私钥
                    appcert: '', //应用公钥证书
                    alipay_rootcert: '', //支付宝根证书
                    service_key_pem: '', //微信支付服务商apiclient_key
                    service_cert_pem: '', //微信支付服务商apiclient_cert
                    service_key: '', //微信支付服务商Api密钥
                    service_mchid: '', //服务商商户号
                    service_appid: '', //服务商AppId
                    appid: '', //微信APPID
                    mchid: '', //微信支付商户号 ////特约商户商户号
                    key: '', //微信支付Api密钥
                    cert_pem: '', //微信支付apiclient_cert
                    key_pem: '', //微信支付apiclient_key
                    is_service: 0, //支付类型选择
                    type: 1, //支付方式选择
                    name: '', //支付名称
                },
                editFormRules: {
                    name: [
                        {required: true, message: '支付名称不能为空', trigger: 'change'},
                    ],
                    type: [
                        {required: true, message: '支付方式选择不能为空', trigger: 'change'},
                    ],
                    is_service: [
                        {required: true, message: '支付类型选择不能为空', trigger: 'change'},
                    ],
                    key: [
                        {required: true, message: '微信支付Api密钥不能为空', trigger: 'change'},
                    ],
                    appid: [
                        {required: true, message: '微信APPID不能为空', trigger: 'change'},
                    ],
                    mchid: [
                        {required: true, message: '商户号不能为空', trigger: 'change'},
                    ],
                    service_appid: [
                        {required: true, message: '服务商AppId不能为空', trigger: 'change'},
                    ],
                    service_mchid: [
                        {required: true, message: '服务商商户号不能为空', trigger: 'change'},
                    ],
                    app_private_key: [
                        {required: true, message: '应用私钥不能为空', trigger: 'change'},
                    ],
                    alipay_public_key: [
                        {required: true, message: '支付宝公钥不能为空', trigger: 'change'},
                    ],
                    alipay_appid: [
                        {required: true, message: '支付宝APPID不能为空', trigger: 'change'},
                    ],
                    appcert: [
                        {required: true, message: '应用公钥证书不能为空', trigger: 'change'},
                    ],
                    alipay_rootcert: [
                        {required: true, message: '支付宝根证书不能为空', trigger: 'change'},
                    ],
                },
            }
        },

        methods: {
            updateSuccess(e) {
                if (e[0].response.data.code == 0) {
                    this.$message.success('上传成功')
                }
            },
            changeType() {
                //clear validate
                this.$refs.editForm.clearValidate();
            },
            submit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        let para = Object.assign({}, this.editForm);
                        request({
                            params: {
                                r: 'mall/pay-type/edit',
                            },
                            data: para,
                            method: 'POST'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                setTimeout(function () {
                                    navigateTo({
                                        r: 'mall/pay-type/index',
                                    })
                                }, 1000);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },
            getForm() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/pay-type/edit',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        this.editForm = e.data.data.detail;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(() => {
                    this.listLoading = false;
                });
            },
        },
        mounted: function () {
            if (getQuery('id')) {
                this.getForm();
            }
        }
    });
</script>
