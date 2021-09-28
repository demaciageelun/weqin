<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/10/16
 * Time: 15:18
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
$mchId = Yii::$app->user->identity->mch_id;
?>
<style>
    #pane-second .el-card__body {
        background-color: #F3F3F3;
        padding: 0;
    }

    .title {
        padding: 18px 20px;
        background-color: #fff;
    }

    .el-alert .el-alert__description {
        margin-top: 0
    }

    .el-form-item {
        position: relative;
    }

    .send-btn {
        position: absolute !important;
        top: 0;
        right: -95px;
    }

    .app-sms-setting .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 10px;
        margin-top: 12px;
    }

    .setting {
        border-bottom: 1px solid #F3F3F3;
        padding: 20px;
        padding-right: 50%;
    }
</style>
<template id="app-sms-setting">
    <el-card class="app-sms-setting" shadow="never" style="border:0" v-loading="cardLoading" v-cloak>
        <el-form :model="ruleForm" ref="ruleForm" :rules="rules" size="small" label-width="150px">
            <el-row>
                <el-col :span="24">
                    <div class="title" style="margin-top: 12px">
                        <span>基本设置</span>
                    </div>
                    <div style="background-color: #fff;padding: 20px 50% 20px 20px">
                        <el-form-item label="短信提醒" prop="status">
                            <el-switch
                                    v-model="ruleForm.status"
                                    active-value="1"
                                    inactive-value="0"
                                    active-color="#409EFF">
                            </el-switch>
                        </el-form-item>
                    </div>
                    <template v-if="ruleForm.status == 1">
                        <div style="background-color: #fff;padding: 20px;padding-right: 50%;margin-top: -38px">
                            <el-form-item label="平台" prop="platform">
                                <el-radio v-model="ruleForm.platform" label="aliyun">阿里云</el-radio>
                            </el-form-item>
                            <el-form-item label="access_key_id">
                                <el-input @focus="updateHideStatus(1)"
                                          v-if="hideStatus_1"
                                          readonly
                                          placeholder="access_key_id 被隐藏,点击查看">
                                </el-input>
                                <el-input v-else v-model="ruleForm.access_key_id"
                                          placeholder="请填写 access_key_id"></el-input>
                            </el-form-item>
                            <el-form-item label="access_key_secret">
                                <el-input @focus="updateHideStatus(2)"
                                          v-if="hideStatus_2"
                                          readonly
                                          placeholder="access_key_secret 被隐藏,点击查看">
                                </el-input>
                                <el-input v-else v-model="ruleForm.access_key_secret"
                                          placeholder="请填写 access_key_secret"></el-input>
                            </el-form-item>
                            <el-form-item label="模板签名">
                                <el-input v-model="ruleForm.template_name" placeholder="模板签名"></el-input>
                            </el-form-item>
                        </div>
                        <el-tabs v-model="activeName" style="background-color: #ffffff;">
                            <el-tab-pane label="管理员使用" name="admin">
                                <div class="title">
                                    <span>管理员手机号设置</span>
                                </div>
                                <div class="setting">
                                    <el-form-item>
                                        <template slot="label">
                                            <span>接收短信手机号</span>
                                            <el-tooltip effect="dark" content="请输入手机号后,按回车键"
                                                        placement="top">
                                                <i class="el-icon-info"></i>
                                            </el-tooltip>
                                        </template>
                                        <el-tag v-if="ruleForm.mobile_list.length"
                                                style="margin-right: 5px;"
                                                v-for="(item, index) in ruleForm.mobile_list"
                                                @close="deleteMobile(index)"
                                                :key="item.id"
                                                closable>
                                            {{item}}
                                        </el-tag>
                                        <el-input style="width: 200px"
                                                  @keyup.enter.native="addMobile"
                                                  type="number"
                                                  v-model="mobile"
                                                  placeholder="请输入手机号后,按回车键">
                                        </el-input>
                                    </el-form-item>
                                </div>
                                <template v-for="(item, index) in setting"
                                          v-if="(item.support_mch || mch_id == 0) && item.key === 'admin'">
                                    <div class="title">
                                        <span>{{item.title}}</span>
                                        <span style="color: #999;margin-left: 10px;">{{item.tip}}</span>
                                    </div>
                                    <div class="setting">
                                        <el-form-item>
                                            <el-alert
                                                    title=""
                                                    type="info"
                                                    :closable=false
                                                    :description="item.content">
                                            </el-alert>
                                        </el-form-item>
                                        <el-form-item label="模板ID">
                                            <div style="width: 115%;display: flex;justify-content: space-between">
                                                <el-input style="width: 87%" v-model="ruleForm[index].template_id"
                                                          placeholder="请输入模板ID"></el-input>
                                                <el-button size="small" class="send-btn"
                                                           @click="testSms(index)" :loading="item.loading">测试发送
                                                </el-button>
                                            </div>
                                        </el-form-item>
                                        <el-form-item v-for="(value, key) in item.variable">
                                            <template slot='label'>
                                                <span>{{value.value}}</span>
                                                <el-tooltip effect="dark" :content="value.desc"
                                                            placement="top">
                                                    <i class="el-icon-info"></i>
                                                </el-tooltip>
                                            </template>
                                            <el-input v-model="ruleForm[index][value.key]"
                                                      :placeholder="'请输入' + value.value"></el-input>
                                        </el-form-item>
                                    </div>
                                </template>
                            </el-tab-pane>
                            <el-tab-pane label="用户使用" name="user">
                                <div class="title">
                                    <span>权限设置</span>
                                </div>
                                <div class="setting">
                                    <el-form-item>
                                        <template slot="label">
                                            <span>权限组</span>
                                        </template>
                                        <el-checkbox-group v-model="ruleForm.allow_platform" size="mini">
                                            <el-checkbox :key="item.key" :label="item.key"
                                                         size="mini" v-for="(item, index) in platformList">
                                                {{item.name}}
                                            </el-checkbox>
                                        </el-checkbox-group>
                                        <span>注意：需要下单用户授权了手机号才能收到</span>
                                    </el-form-item>
                                </div>
                                <template v-for="(item, index) in setting"
                                          v-if="(item.support_mch || mch_id == 0) && item.key === 'user'">
                                    <div class="title">
                                        <span>{{item.title}}</span>
                                        <span style="color: #999;margin-left: 10px;">{{item.tip}}</span>
                                    </div>
                                    <div class="setting">
                                        <el-form-item>
                                            <el-alert
                                                    title=""
                                                    type="info"
                                                    :closable=false
                                                    :description="item.content">
                                            </el-alert>
                                        </el-form-item>
                                        <el-form-item label="模板ID">
                                            <div style="width: 115%;display: flex;justify-content: space-between">
                                                <el-input style="width: 87%" v-model="ruleForm[index].template_id"
                                                          placeholder="请输入模板ID"></el-input>
                                                <el-button size="small" class="send-btn"
                                                           @click="testSms(index)" :loading="item.loading">测试发送
                                                </el-button>
                                            </div>
                                        </el-form-item>
                                        <el-form-item v-for="(value, key) in item.variable">
                                            <template slot='label'>
                                                <span>{{value.value}}</span>
                                                <el-tooltip effect="dark" :content="value.desc"
                                                            placement="top">
                                                    <i class="el-icon-info"></i>
                                                </el-tooltip>
                                            </template>
                                            <el-input v-model="ruleForm[index][value.key]"
                                                      :placeholder="'请输入' + value.value"></el-input>
                                        </el-form-item>
                                    </div>
                                </template>
                            </el-tab-pane>
                        </el-tabs>
                    </template>
                </el-col>
            </el-row>
            <el-button :loading="btnLoading" style="margin-top: 20px;padding: 9px 25px" type="primary" @click="store"
                       size="small">保存
            </el-button>
        </el-form>
    </el-card>
</template>
<script>
    Vue.component('app-sms-setting', {
        template: '#app-sms-setting',
        data() {
            return {
                ruleForm: {},
                mobile: '',
                rules: {},
                btnLoading: false,
                cardLoading: false,
                hideStatus_1: true,
                hideStatus_2: true,
                mch_id: <?= $mchId ?>,
                setting: [],
                testLoading: false,
                activeName: 'admin',
                platformList: [],
            };
        },
        methods: {
            store() {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: 'mall/sms/setting'
                    },
                    method: 'post',
                    data: {
                        form: self.ruleForm,
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

            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/sms/setting',
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data.detail;
                        self.setting = e.data.data.setting;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            addMobile() {
                let self = this;
                let sign = true;
                if (self.mobile) {
                    if (self.ruleForm.mobile_list === undefined) {
                        Vue.set(self.ruleForm, 'mobile_list', [])
                    }
                    self.ruleForm.mobile_list.forEach(function (item, index) {
                        if (self.mobile == item) {
                            sign = false
                            return
                        }
                    });
                    if (sign) {
                        self.ruleForm.mobile_list.push(self.mobile);
                        self.mobile = '';
                    }
                }
            },
            deleteMobile(index) {
                this.ruleForm.mobile_list.splice(index, 1);
            },
            updateHideStatus(type) {
                if (type == 1) {
                    this.hideStatus_1 = false;
                }
                if (type == 2) {
                    this.hideStatus_2 = false;
                }
            },
            testSms(type) {
                this.setting[type].loading = true;
                request({
                    params: {
                        r: 'mall/sms/test-sms',
                        type: type,
                    },
                    data: {
                        form: this.ruleForm
                    },
                    method: 'post',
                }).then(e => {
                    this.setting[type].loading = false;
                    if (e.data.code == 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                    this.setting[type].loading = false;
                });
            },
            getPlatform() {
                request({
                    params: {
                        r: 'mall/index/platform',
                    },
                    method: 'get',
                }).then(e => {
                    if(e.data.code === 0) {
                        this.platformList = e.data.data
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
        },
        mounted: function () {
            this.getDetail();
            this.getPlatform();
        }
    });
</script>

