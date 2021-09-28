<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.9ysw.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/9 14:52
 */
?>
<style>
    .form-body {
        display: flex;
        justify-content: center;
        position: relative;
    }

    .form-body .el-form {
        width: 650px;
        margin-top: 10px;
    }

    .submit-btn {
        height: 32px;
        width: 65px;
        line-height: 32px;
        text-align: center;
        border-radius: 16px;
        padding: 0;
    }

    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
    }

    .wechat-image {
        height: 232px;
        width: 200px;
        cursor: pointer;
        position: relative;
    }

    .wechat-end-box {
        height: 32px;
        line-height: 32px;
        width: 200px;
        padding: 0 12px;
        color: #606266;
        border-left: 1px solid #e2e2e2;
        border-right: 1px solid #e2e2e2;
        border-bottom: 1px solid #e2e2e2;
        word-break: break-all;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="loading">
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="在线支付" name="first"></el-tab-pane>
            <el-tab-pane label="线下客服" name="second"></el-tab-pane>
        </el-tabs>
        <div class="form-body">
            <el-form :model="ruleForm" ref="ruleForm" :rules="rules" label-width="150px" position-label="right">
                <template v-if="activeName == 'first'">
                    <el-form-item label="支付方式">
                        <el-checkbox-group v-model="ruleForm.pay_list">
                            <el-checkbox label="微信"></el-checkbox>
                            <el-checkbox label="支付宝"></el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>

                    <template v-if="isShow('微信')">
                        <div style="margin-bottom: 20px;height: 20px;">
                            <span style="position: absolute;left: 10px">微信支付配置</span>
                        </div>
                        <el-form-item label="微信APPID" prop="wechat_appid">
                            <el-input class="out-max" size="small" v-model.trim="ruleForm.wechat_appid"></el-input>
                        </el-form-item>
                        <el-form-item label="微信支付商户号" prop="wechat_mchid">
                            <el-input class="out-max" size="small" v-model.trim="ruleForm.wechat_mchid"></el-input>
                        </el-form-item>
                        <el-form-item label="微信支付Api密钥" prop="wechat_key">
                            <el-input @focus="hidden.wechat_key = false"
                                      class="out-max" size="small"
                                      v-if="hidden.wechat_key"
                                      readonly
                                      placeholder="已隐藏内容，点击查看或编辑">
                            </el-input>
                            <el-input v-else class="out-max" size="small" v-model.trim="ruleForm.wechat_key"></el-input>
                        </el-form-item>
                        <!-- <el-form-item label="微信支付apiclient_cert.pem" prop="wechat_cert_pem">
                            <el-input @focus="hidden.wechat_cert_pem = false"
                                      class="out-max" size="small"
                                      v-if="hidden.wechat_cert_pem"
                                      readonly
                                      type="textarea"
                                      :rows="5"
                                      placeholder="已隐藏内容，点击查看或编辑">
                            </el-input>
                            <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                      v-model="ruleForm.wechat_cert_pem"></el-input>
                        </el-form-item>
                        <el-form-item label="微信支付apiclient_key.pem" prop="wechat_key_pem">
                            <el-input @focus="hidden.wechat_key_pem = false"
                                      class="out-max" size="small"
                                      v-if="hidden.wechat_key_pem"
                                      readonly
                                      type="textarea"
                                      :rows="5"
                                      placeholder="已隐藏内容，点击查看或编辑">
                            </el-input>
                            <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                      v-model="ruleForm.wechat_key_pem"></el-input>
                        </el-form-item> -->
                    </template>

                    <template v-if="isShow('支付宝')">
                        <div style="margin-bottom: 20px;height: 20px;">
                            <span style="position: absolute;left: 10px">支付宝支付配置</span>
                        </div>
                        <el-form-item label="应用AppID" prop="alipay_app_id">
                            <el-input class="out-max" size="small" v-model.trim="ruleForm.alipay_app_id"></el-input>
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
                            <el-input v-else class="out-max" size="small" type="textarea" :rows="5" v-model.trim="ruleForm.alipay_public_key"></el-input>
                        </el-form-item>
                        <el-form-item label="应用私钥" prop="alipay_private_key">
                            <el-input @focus="hidden.alipay_private_key = false"
                                      class="out-max" size="small"
                                      v-if="hidden.alipay_private_key"
                                      readonly
                                      type="textarea"
                                      :rows="5"
                                      placeholder="已隐藏内容，点击查看或编辑">
                            </el-input>
                            <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                      v-model="ruleForm.alipay_private_key"></el-input>
                        </el-form-item>
                        <el-form-item label="应用公钥证书" prop="alipay_appcert">
                            <el-input @focus="hidden.alipay_appcert = false"
                                      class="out-max" size="small"
                                      v-if="hidden.alipay_appcert"
                                      readonly
                                      type="textarea"
                                      :rows="5"
                                      placeholder="已隐藏内容，点击查看或编辑">
                            </el-input>
                            <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                      v-model="ruleForm.alipay_appcert"></el-input>
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
                            <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                      v-model="ruleForm.alipay_rootcert"></el-input>
                        </el-form-item>
                    </template>
                </template>

                <template v-if="activeName == 'second'">
                    <el-form-item label="客服微信" prop="list">
                        <el-button v-if="ruleForm.customer_service_list.length < 10" size="mini" @click="addWechat">选择</el-button>
                        <div flex="dir:left" style="flex-wrap:wrap">
                            <div v-for="(value,index) in ruleForm.customer_service_list" style="margin-right: 24px;margin-top: 12px">
                                <div class="wechat-image" flex="dir:top"
                                     @click="editWechat(value,index)">
                                    <el-image :src="value.qrcode_url" style="height: 200px;width:100%"></el-image>
                                    <el-tooltip class="v" effect="dark" :content="'微信号'+ value.name" placement="top">
                                        <div class="wechat-end-box">微信号：{{value.name}}</div>
                                    </el-tooltip>
                                    <el-button class="del-btn" size="mini" type="danger"
                                               icon="el-icon-close" circle @click.stop="picClose(index)"></el-button>
                                </div>
                            </div>
                        </div>
                        <div style="color:#909399">注意：最多允许上传10张，前端随机展示一张</div>
                    </el-form-item>
                </template>

                <el-form-item>
                    <el-button class="submit-btn" type="primary" @click="submit('ruleForm')" :loading="submitLoading">保存</el-button>
                </el-form-item>           
            </el-form>
        </div>

        <!--客服微信-->
        <el-dialog title="客服微信" :visible.sync="wechatVisible" width="50%" :close-on-click-modal="false">
            <el-form :model="wechatForm" label-width="150px" :rules="wechatRules" ref="wechatForm"
                     @submit.native.prevent>
                <el-form-item label="联系人" prop="name">
                    <el-input size="small" v-model.trim="wechatForm.name"></el-input>
                </el-form-item>
                <el-form-item label="联系电话" prop="mobile">
                    <el-input size="small" v-model.trim="wechatForm.mobile"></el-input>
                </el-form-item>
                <el-form-item label="工作时间">
                    <el-time-select
                        :disabled="wechatForm.is_all_day"
                        size="small"
                        placeholder="起始时间"
                        v-model="wechatForm.start_time"
                        :picker-options="{
                          start: '00:00',
                          step: '00:30',
                          end: '24:00'
                        }">
                      </el-time-select>
                      <el-time-select
                        :disabled="wechatForm.is_all_day"
                        size="small"
                        placeholder="结束时间"
                        v-model="wechatForm.end_time"
                        :picker-options="{
                          start: '00:00',
                          step: '00:30',
                          end: '24:00',
                          minTime: wechatForm.start_time
                        }">
                      </el-time-select>
                    <div><el-checkbox v-model="wechatForm.is_all_day">全天在线</el-checkbox></div>
                </el-form-item>
                <el-form-item label="客服微信二维码" prop="qrcode_url">
                    <div style="margin-bottom:10px;">
                        <app-attachment v-model="wechatForm.qrcode_url" :simple="true">
                            <el-tooltip effect="dark" content="建议尺寸360*360" placement="top">
                                <el-button style="margin-bottom: 10px;" size="mini">选择文件</el-button>
                            </el-tooltip>
                        </app-attachment>
                        <app-gallery
                            :url="wechatForm.qrcode_url" 
                            :show-delete="true" 
                            @deleted="wechatForm.qrcode_url = ''"
                            width="80px" 
                            height="80px">
                        </app-gallery>
                    </div>
                </el-form-item>
                <el-form-item label="客服微信号" prop="wechat_name">
                    <el-input size="small" v-model="wechatForm.wechat_name" maxlength="20" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="wechatVisible = false">取消</el-button>
                <el-button size="small" type="primary" @click.native="wechatSubmit">提交</el-button>
            </div>
        </el-dialog>
    </el-card>
</div>
<script>
new Vue({
    el: '#app',
    data() {
        return {
            loading: false,
            activeName: 'first',
            ruleForm: {
                pay_list: [],
                wechat_appid: '',
                wechat_mchid: '',
                wechat_key: '',
                wechat_cert_pem: '',
                wechat_key_pem: '',

                alipay_app_id: '',
                alipay_public_key: '',
                alipay_private_key: '',
                alipay_appcert: '',
                alipay_rootcert: '',

                customer_service_list: []
            },
            rules: {
                wechat_appid: [
                    {required: true, message: '请填写微信AppId',},
                ],
                wechat_mchid: [
                    {required: true, message: '请填写微信支付商号',},
                ],
                wechat_key: [
                    {required: true, message: '请填写微信支付Api密钥',},
                ],
                alipay_app_id: [
                    {required: true, message: '请填写应用AppID',},
                ],
                alipay_public_key: [
                    {required: true, message: '请填写支付宝公钥',},
                ],
                alipay_private_key: [
                    {required: true, message: '请填写支付宝私钥',},
                ],
                alipay_appcert: [
                    {required: true, message: '请填写应用公钥证书',},
                ],
                alipay_rootcert: [
                    {required: true, message: '请填写支付宝根证书',},
                ],
            },
            hidden: {
                wechat_key: true,
                wechat_cert_pem: true,
                wechat_key_pem: true,
                alipay_public_key: true,
                alipay_private_key: true,
                alipay_appcert: true,
                alipay_rootcert: true,
            },
            submitLoading: false,

            wechatVisible: false,
            wechatForm: {
                name: '',
                mobile: '',
                start_time: '',
                end_time: '',
                is_all_day: false,
                wechat_name: '',
                qrcode_url: '',
            },
            wechatRules: {
                name: [
                    {required: true, message: '请填写联系人', trigger: 'blur'},
                ],
                mobile: [
                    {required: true, message: '请填写联系方式', trigger: 'blur'},
                ],
                qrcode_url: [
                    {required: true, message: '请上传二维码', trigger: 'blur'},
                ],
            },
        };
    },
    computed: {
    },
    created() {
        this.getSetting();
    },
    methods: {
        handleClick(tab, event) {
            console.log(tab, event);
        },
        submit(formName) {
            this.$refs[formName].validate((valid) => {
                let self = this;
                if (valid) {
                    self.submitLoading = true;
                    request({
                        params: {
                            r: 'admin/setting/pay-setting'
                        },
                        method: 'post',
                        data: {
                            form: self.ruleForm,
                        }
                    }).then(e => {
                        self.submitLoading = false;
                        if (e.data.code == 0) {
                            self.$message.success(e.data.msg);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        self.$message.error(e.data.msg);
                        self.submitLoading = false;
                    });
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        getSetting() {
            this.loading = true;
            this.$request({
                params: {
                    r: 'admin/setting/pay-setting',
                },
            }).then(e => {
                this.loading = false;
                if (e.data.code === 0) {
                    this.ruleForm = e.data.data.setting;
                } else {
                    this.$message.error(e.data.msg);
                }
            }).catch(e => {
            });
        },
        isShow(name) {
            let sign = false;
            this.ruleForm.pay_list.forEach(function(item) {
                if (item == name) {
                    sign = true;
                }
            })

            return sign;
        },
        editWechat(item, index) {
            this.index = index;
            this.wechatForm = Object.assign({}, item);
            this.wechatVisible = true;
        },
        picClose(index) {
            this.ruleForm.customer_service_list.splice(index, 1);
        },
        wechatSelect(e) {
            if (e.length) {
                this.wechatForm.qrcode_url = e[0].url;
            }
        },

        wechatClose() {
            this.wechatForm.qrcode_url = '';
        },
        wechatSubmit() {
            if (!this.ruleForm.customer_service_list) {
                this.ruleForm.customer_service_list = [];
            }
            this.$refs.wechatForm.validate((valid) => {
                if (valid) {
                    if (this.index === -1) {
                        this.ruleForm.customer_service_list.push(Object.assign({}, this.wechatForm));
                    } else {
                        this.ruleForm.customer_service_list.splice(this.index, 1, this.wechatForm);
                    }
                    this.wechatVisible = false;
                }
            });
        },
        addWechat() {
            this.index = -1;
            this.wechatForm = {
                name: '',
                mobile: '',
                start_time: '',
                end_time: '',
                is_all_day: false,
                wechat_name: '',
                qrcode_url: '',
            };
            this.wechatVisible = true
        },
    },
});
</script>