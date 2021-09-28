<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
$baseUrl = Yii::$app->request->baseUrl;
?>
<style>
    .form_box {
        background-color: #fff;
        padding: 30px 20px;
        padding-right: 40%;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }
    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 0;
    }

    .choose {
        margin-left: 10px;
        width: 364px;
        height: 93px;
        border-radius: 6px;
        background-color: #e1f0ff;
        color: #3a3a3a;
        position: relative;
    }

    .choose .left {
        width: 57px;
        height: 57px;
        background-color: #fff;
        border-radius: 8px;
        margin: 0 18px;
    }

    .choose .left img {
        height: 38px;
        width: 38px;
    }

    .choose .button-item {
        margin-top: 0;
        position: absolute;
        right: 23px;
    }

    .choose .button-item.issue {
        padding: 9px 18px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span>基础设置</span>
        </div>
        <div class="form_box">
            <el-row>
                <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="150px">
                    <el-col :span="12">
                        <el-form-item v-if="has_third_permission" label="是否使用第三方授权" prop="is_third">
                            <el-radio-group :disabled="third && third.id > 0" v-model="is_third">
                                <el-radio :label="0">否</el-radio>
                                <el-radio :label="1">是</el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item v-if="is_third == 0" label="公众号AppId" prop="appid">
                            <el-input v-model.trim="ruleForm.appid"></el-input>
                        </el-form-item>
                        <el-form-item v-if="is_third == 0" label="公众号appSecret" prop="appsecret">
                            <el-input @focus="hidden.appsecret = false"
                                      v-if="hidden.appsecret"
                                      readonly
                                      placeholder="已隐藏内容，点击查看或编辑">
                            </el-input>
                            <el-input v-else v-model.trim="ruleForm.appsecret"></el-input>
                        </el-form-item>
                        <div v-if="is_third == 1 && !third" class="choose" flex="dir:left cross:center">
                            <div class="left" flex="main:center cross:center">
                                <img src="statics/img/plugins/wechat/wait.png" alt="">
                            </div>
                            <div>选择已有公众号</div>
                            <el-button class='button-item' type="primary" @click="auth" size="small">去绑定</el-button>
                        </div>
                        <div v-if="is_third == 1 && third">
                            <div v-if="!qrcode" class="choose" flex="dir:left cross:center">
                                <div class="left" flex="main:center cross:center">
                                    <img src="statics/img/plugins/wechat/pass.png" alt="">
                                </div>
                                <div>公众号绑定成功</div>
                            </div>
                        </div>
                    </el-col>
                </el-form>
            </el-row>
        </div>
        <el-button v-if="activeName != 'first' || is_third == 0" class='button-item' :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small">保存</el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                third: null, // 版本信息
                is_third: 0, //是否开启第三方授权
                has_third_permission: false,
                activeName: 'first',
                hidden: {
                    appid: true,
                    appsecret: true,
                },
                ruleForm: {
                    appid: '',
                    appsecret: '',
                },
                rules: {
                    appid: [
                        {required: true, message: '请输入appid', trigger: 'change'},
                    ],
                    appsecret: [
                        {required: true, message: '请输入appsecret', trigger: 'change'},
                    ]
                },
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            // 授权弹窗
            auth() {
                window.open('<?=$baseUrl?>/index.php?r=mall/wechat/authorizer');
                this.$confirm('请在新窗口中完成微信公众号授权', '提示', {
                    confirmButtonText: '已成功授权',
                    cancelButtonText: '授权失败，重试',
                    type: 'warning'
                }).then(() => {
                    this.$message({
                        type: 'success',
                        message: '已完成授权!'
                    });
                    this.getDetail();
                }).catch(() => {
                });
            },
            store(formName) {
                let self = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/wechat/setting'
                            },
                            method: 'post',
                            data: {
                                appid: self.ruleForm.appid,
                                appsecret: self.ruleForm.appsecret,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: '/mall/wechat/setting'
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    self.has_third_permission = e.data.data.has_third_permission;
                    self.third = e.data.data.third;
                    if(!self.has_third_permission) {
                        self.is_third = 0;
                    }
                    if(self.third && self.has_third_permission) {
                        self.is_third = 1;
                    }
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data.detail;
                    } else {
                        if(e.data.msg != '信息暂未配置') {
                            self.$message.error(e.data.msg);
                        }
                        self.rules.appid[0].required = true;
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
