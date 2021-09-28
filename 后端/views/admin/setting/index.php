<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2019/3/22
 * Time: 16:23
 */
Yii::$app->loadViewComponent('app-rich-text');
?>
<style>
    .my-img {
        height: 50px;
        border: 1px solid #d7dae2;
        border-radius: 2px;
        margin-top: 10px;
        background-color: #e2e2e2;
        overflow: hidden;
    }

    .form-body {
        display: flex;
        justify-content: center;
    }

    .form-body .el-form {
        width: 450px;
        margin-top: 10px;
    }

    .currency-width {
        width: 300px;
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

    .preview {
        height: 75px;
        line-height: 75px;
        text-align: center;
        width: 200px;
        background-color: #F7F7F7;
        color: #BBBBBB;
        margin-top: 10px;
        font-size: 12px;
    }

    .qr-title:first-of-type {
        margin-top: 0;
    }

    .qr-title {
        color: #BBBBBB;
        font-size: 13px;
        margin-top: 10px;
    }

    .line {
        border: none;
        border-bottom: 1px solid #e2e2e2;
        margin: 40px 0;
    }

    .title {
        margin-bottom: 20px;
    }

    .submit-btn {
        height: 32px;
        width: 65px;
        line-height: 32px;
        text-align: center;
        border-radius: 16px;
        padding: 0;
    }



    .check-title {
        background-color: #F3F5F6;
        width: 100%;
        padding: 0 20px;
    }

    .check-list {
        display: flex;
        flex-wrap: wrap;
        padding: 0 20px;
    }

    .check-list .el-checkbox {
        width: 145px;
    }

    .el-checkbox {
        height: 50px;
        line-height: 50px;
    }

    .window {
        border: 1px solid #EBEEF5;
    }

    .check-title .el-checkbox__label {
        font-size: 16px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="loading">
        <div style="margin-bottom: 20px">站点配置</div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="150px">
                <el-form-item label="站点logo">
                    <el-input disabled class="currency-width isAppend">
                        <template slot="append">
                            <app-upload @complete="updateSuccess" accept="image/vnd.microsoft.icon" :params="params" :simple="true">
                                <el-button size="small">上传logo</el-button>
                            </app-upload>
                        </template>
                    </el-input>
                    <div style="height: 40px;line-height: 40px" class="preview">仅支持上传 .ico 格式文件</div>
                </el-form-item>
            </el-form>
        </div>
        <div style="margin-bottom: 20px">基础配置</div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="150px" :model="form" ref="form">
                <!-- 商城设置 -->
                <el-form-item label="网站名称">
                    <el-input class="currency-width" v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="网站简称">
                    <el-input class="currency-width" v-model="form.description"></el-input>
                </el-form-item>
                <el-form-item label="网站关键字">
                    <el-input type="textarea" class="currency-width" v-model="form.keywords"></el-input>
                    <div style="color: #909399;font-size: 12px;line-height: 1;margin-top: 10px">
                        多个关键字用英文,号隔开 例如: 衣服,包包,鞋子
                    </div>
                </el-form-item>
                <el-form-item label="LOGO图片URL">
                    <el-input class="currency-width isAppend" v-model="form.logo">
                        <template slot="append">
                            <app-attachment v-model="form.logo" :simple="true">
                                <el-button>上传图片</el-button>
                            </app-attachment>
                        </template>
                    </el-input>
                    <img class="my-img" style="background-color: #100a46;border-color: #100a46; height: 36px;"
                         v-if="form.logo" :src="form.logo">
                    <div v-else class="preview">建议尺寸98*50</div>
                </el-form-item>
                <el-form-item label="底部版权信息">
                    <el-input class="currency-width" v-model="form.copyright"></el-input>
                </el-form-item>
                <el-form-item label="底部版权url">
                    <el-input class="currency-width" v-model="form.copyright_url"
                              placeholder="例如:https://www.baidu.com">
                    </el-input>
                </el-form-item>
                <el-form-item label="登录页背景图">
                    <el-input class="currency-width isAppend" v-model="form.passport_bg">
                        <template slot="append">
                            <app-attachment v-model="form.passport_bg" :simple="true">
                                <el-button>上传图片</el-button>
                            </app-attachment>
                        </template>
                    </el-input>
                    <img class="my-img" style="background-color: #100a46;border-color: #100a46; height: 108px;"
                         v-if="form.passport_bg" :src="form.passport_bg">
                    <div v-else class="preview">建议尺寸1920*1080</div>
                </el-form-item>
                <el-form-item label="管理页背景图">
                    <el-input class="currency-width isAppend" v-model="form.manage_bg">
                        <template slot="append">
                            <app-attachment v-model="form.manage_bg" :simple="true">
                                <el-button>上传图片</el-button>
                            </app-attachment>
                        </template>
                    </el-input>
                    <img class="my-img" style="background-color: #100a46;border-color: #100a46; height: 100px;"
                         v-if="form.manage_bg" :src="form.manage_bg">
                    <div v-else style="height: 40px;line-height: 40px" class="preview">建议尺寸1920*200</div>
                </el-form-item>
                <el-form-item label="开启注册功能" :style="{'margin-bottom': form.open_register == 0 ? '20px' : 0}">
                    <el-radio v-model="form.open_register" label="1">是</el-radio>
                    <el-radio v-model="form.open_register" label="0">否</el-radio>
                </el-form-item>
                <template v-if="form.open_register == 1">
                    <el-form-item label="是否需要审核" style="margin-bottom: 0">
                        <div>
                            <el-radio v-model="form.open_verify" label="1">是</el-radio>
                            <el-radio v-model="form.open_verify" label="0">否</el-radio> 
                        </div>
                    </el-form-item>
                    <template v-if="form.open_verify == 0">
                        <el-form-item label="设置默认用户组" style="margin-bottom: 0;margin-bottom: 10px;">
                            <el-autocomplete size="small" v-model="form.user_group_name" value-key="name" :fetch-suggestions="querySearchAsync" placeholder="请输入用户组名" @select="selectUserGroup">
                            </el-autocomplete>
                        </el-form-item>
                        <el-form-item label="设置默认体验套餐">
                            <div flex="dir:left cross:center" style="margin-bottom: 10px;">
                                <div style="width: 85px;color: #606266;line-height: 0">可体验商城</div>
                                <div style="line-height: 0;">
                                    <el-input type="number" size="small" placeholder="请输入天数" v-model="form.use_days">
                                        <template slot="append">天</template>
                                    </el-input>
                                </div>
                            </div>
                            <div flex="dir:left cross:center" style="margin-bottom: 10px;">
                                <div style="width: 85px;color: #606266;line-height: 0">可创建商城</div>
                                <div style="line-height: 0;">
                                    <el-input type="number" size="small" placeholder="请输入个数" v-model="form.create_num">
                                        <template slot="append">个</template>
                                    </el-input>
                                </div>
                            </div>
                        </el-form-item>
                    </template>
                    <el-form-item label="是否开启短信通知">
                        <div>
                            <el-radio v-model="form.open_sms" label="1">是</el-radio>
                            <el-radio v-model="form.open_sms" label="0">否</el-radio>
                        </div>
                        <div style="color: #BBBBBB;font-size: 12px;line-height: 20px;">请在此页面底部填写短信配置</div>
                    </el-form-item>
                </template>
                <el-form-item label="证件信息是否必填">
                    <el-radio v-model="form.is_required" label="1">是</el-radio>
                    <el-radio v-model="form.is_required" label="0">否</el-radio>
                </el-form-item>
                <el-form-item label="注册页背景图">
                    <el-input class="currency-width isAppend" v-model="form.registered_bg">
                        <template slot="append">
                            <app-attachment v-model="form.registered_bg" :simple="true">
                                <el-button>上传图片</el-button>
                            </app-attachment>
                        </template>
                    </el-input>
                    <img class="my-img" style="background-color: #100a46;border-color: #100a46; height: 100px;"
                         v-if="form.registered_bg" :src="form.registered_bg">
                    <div v-else style="height: 40px;line-height: 40px" class="preview">建议尺寸1920*200</div>
                </el-form-item>
                <el-form-item label="注册页二维码">
                    <div class="qr-title">图片1</div>
                    <el-input style="margin-bottom: 10px;" class="currency-width" placeholder="添加图片描述"
                              v-model="form.qr1_about"></el-input>
                    <el-input class="currency-width isAppend" v-model="form.qr1">
                        <template slot="append">
                            <app-attachment v-model="form.qr1" :simple="true">
                                <el-button>上传图片</el-button>
                            </app-attachment>
                        </template>
                    </el-input>
                    <img class="my-img" style="background-color: #100a46;border-color: #100a46; height: 70px;"
                         v-if="form.qr1" :src="form.qr1">
                    <div v-else style="height: 140px;line-height: 140px" class="preview">建议尺寸140*140</div>
                    <div class="qr-title">图片2</div>
                    <el-input style="margin-bottom: 10px;" class="currency-width" placeholder="添加图片描述"
                              v-model="form.qr2_about"></el-input>
                    <el-input class="currency-width isAppend" v-model="form.qr2">
                        <template slot="append">
                            <app-attachment v-model="form.qr2" :simple="true">
                                <el-button>上传图片</el-button>
                            </app-attachment>
                        </template>
                    </el-input>
                    <img class="my-img" style="background-color: #100a46;border-color: #100a46; height: 70px;"
                         v-if="form.qr2" :src="form.qr2">
                    <div v-else style="height: 140px;line-height: 140px" class="preview">建议尺寸140*140</div>
                </el-form-item>
                <el-form-item label="注册协议" style="width: 600px;">
                    <app-rich-text :simple-attachment="true" v-model="form.register_protocol"></app-rich-text>
                </el-form-item>
                <!-- 分割线 -->
                <hr :style="line" class="line">
                <!-- 短信设置 -->
                <!-- <el-form-item> -->
                <div :style="line" class="title">
                    <span style="font-size: 15px;">短信配置（阿里云）</span>
                    <span style="color: #909399;font-size: 12px;">用于发送（注册、重置密码）短信验证码、注册结果短信通知。</span>
                </div>
                <!-- </el-form-item> -->

                <el-form-item label="AccessKeyId">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.access_key_id"></el-input>
                </el-form-item>
                <el-form-item label="AccessKeySecret">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.access_key_secret"></el-input>
                </el-form-item>
                <el-form-item label="短信签名">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.sign"></el-input>
                </el-form-item>
                <el-form-item label="验证码模板ID">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.tpl_id"></el-input>
                    <div style="color: #909399;font-size: 12px;line-height: 1;margin-top: 10px">模板示例: 您的验证码是${code}
                    </div>
                </el-form-item>
                <el-form-item label="注册审核成功模板ID">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.register_success_tpl_id"></el-input>
                    <div style="color: #909399;font-size: 12px;line-height: 1;margin-top: 10px">
                        用于用户注册审核成功的通知，模板示例：您注册的账户${name}审核已通过。
                    </div>
                </el-form-item>
                <el-form-item label="注册审核失败模板ID">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.register_fail_tpl_id"></el-input>
                    <div style="color: #909399;font-size: 12px;line-height: 1;margin-top: 10px">
                        用于用户注册审核失败的通知，模板示例：您注册的账户${name}审核未通过。
                    </div>
                </el-form-item>
                <el-form-item v-if="form.open_sms == 1" label="平台注册申请通知">
                    <el-input class="currency-width" v-model="form.ind_sms.aliyun.register_apply_tpl_id"></el-input>
                    <div style="color: #909399;font-size: 12px;line-height: 1;margin-top: 10px">
                        用于管理员接收注册申请的通知，模板示例：您有新的平台注册申请，请及时处理。
                    </div>
                </el-form-item>

                <el-form-item>
                    <el-button class="submit-btn" type="primary" @click="submit" :loading="submitLoading">保存</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                submitLoading: false,
                line: {
                    width: '450px',
                    marginLeft: '-150px'
                },
                form: {
                    name: '',
                    logo: '',
                    copyright: '',
                    passport_bg: '',
                    open_register: '0',
                    open_verify: '1',
                    open_sms: '0',
                    is_required: '1',
                    register_protocol: '',
                    ind_sms: {
                        aliyun: {
                            access_key_id: '',
                            access_key_secret: '',
                            sign: '',
                            tpl_id: '',
                            register_success_tpl_id: '',
                            register_fail_tpl_id: '',
                            register_apply_tpl_id: ''
                        }
                    },
                    use_days: 0,
                    create_num: 0,
                    user_group_name: '',
                    user_group_id: null
                },
                params: {
                    r: 'admin/setting/upload-logo'
                },
            };
        },
        created() {
            this.loadData();
            this.$nextTick(function () {
                this.line.width = this.$refs.body.clientWidth + 'px';
                this.line.marginLeft = -(this.$refs.body.clientWidth - 450) / 2 + 'px';
            })
        },
        computed: {
            
        },
        methods: {
            loadData() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/index',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        if (e.data.data.setting) {
                            this.form = e.data.data.setting;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            submit() {
                this.submitLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/index',
                    },
                    method: 'post',
                    data: {
                        setting: JSON.stringify(this.form),
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
            },
            querySearchAsync(query, cb) {
                let self = this;
                if (query == '') {
                    this.form.user_group_id = null;
                }
                request({
                    params: {
                        r: 'admin/user/user-group',
                        keyword_name: 'name',
                        keyword_value: query,
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        let list = e.data.data.list;
                        let newList = [];
                        list.forEach(function(item) {
                            newList.push({
                                id: item.id.toString(),
                                name: item.name
                            })
                        })
                        cb(newList);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            selectUserGroup(row) {
                this.form.user_group_id = row.id
                this.form.user_group_name = row.name
            },
        }
    });
</script>