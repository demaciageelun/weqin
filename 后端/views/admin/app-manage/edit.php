<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
Yii::$app->loadViewComponent('app-dialog-template');
Yii::$app->loadViewComponent('admin/app-permissions-setting');
Yii::$app->loadViewComponent('app-rich-text');
?>

<style>
    .common-width {
        width: 350px;
    }

    .el-card__header {
        height: 60px;
        line-height: 60px;
        padding: 0 20px;
    }

    .el-form-item__label {
        position: relative;
        padding-left: 20px;
        color: #999999;
        font-size: 13px;
    }

    .common-width .el-input__inner {
        height: 35px;
        line-height: 35px;
        border-radius: 8px;
    }

    .form .el-form-item {
        margin-bottom: 25px;
        position: relative;
    }

    .form {
        display: flex;
        justify-content: center;
        margin-left: -60px;
        margin-top: 15px;
    }

    .show-password {
        position: absolute;
        right: -30px;
        top: 6.5px;
        height: 22px;
        width: 22px;
        display: block;
        cursor: pointer;
    }

    .permissions-list {
        width: 300px;
    }

    .permissions-item {
        height: 24px;
        line-height: 24px;
        border-radius: 12px;
        padding: 0 12px;
        margin-right: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        color: #999999;
        background-color: #F7F7F7;
        display: inline-block;
        font-size: 12px;
    }

    .permissions-item.active {
        background-color: #F5FAFF;
        color: #57ADFF;
    }

    .submit-btn {
        height: 32px;
        width: 65px;
        line-height: 32px;
        text-align: center;
        border-radius: 16px;
        padding: 0;
    }
</style>

<div id="app" v-cloak>
    <el-card v-loading="cardLoading" class="box-card">
        <div slot="header" class="clearfix">
            <span>编辑应用</span>
        </div>
        <div class="form">
            <el-form @submit.native.prevent ref="form" label-position="left" :model="form" :rules="rules" label-width="130px" size="small">
                <el-form-item label="应用名称" prop="display_name">
                    <el-input  class="common-width" v-model="form.display_name" maxlength="8" show-word-limit></el-input>
                </el-form-item>
                <el-form-item label="应用图标" prop="pic_url_type">
                    <el-radio v-model="form.pic_url_type" :label="1">系统默认</el-radio>
                    <el-radio v-model="form.pic_url_type" :label="2">自定义</el-radio>
                    <template v-if="form.pic_url_type == 2">
                        <app-attachment style="margin: 10px 0;" v-model="form.pic_url" :simple="true">
                            <el-tooltip effect="dark" content="建议尺寸80*80" placement="top">
                                <el-button style="margin-bottom: 10px;" size="mini">上传图片</el-button>
                            </el-tooltip>
                        </app-attachment>
                        <app-gallery
                            :url="form.pic_url" 
                            :show-delete="true" 
                            @deleted="form.pic_url = ''"
                            width="80px" 
                            height="80px">
                        </app-gallery>
                    </template>
                </el-form-item>
                <el-form-item label="应用简介" prop="content">
                    <el-input  class="common-width" v-model="form.content" show-word-limit></el-input>
                </el-form-item>
                <el-form-item label="未购买用户可见" prop="is_show">
                    <el-switch v-model="form.is_show" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="购买方式" prop="pay_type">
                    <el-radio v-model="form.pay_type" label="online">在线支付</el-radio>
                    <el-radio v-model="form.pay_type" label="service">联系客服</el-radio>
                </el-form-item>
                <el-form-item label="售价" prop="price">
                    <el-input 
                            type="number" 
                            min="0"
                             oninput="this.value = this.value.replace(/[^0-9\.]/, '');"
                            v-model="form.price">
                        <template slot="append">元</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="应用详情" style="width: 600px;">
                    <app-rich-text :simple-attachment="true" v-model="form.detail"></app-rich-text>
                </el-form-item>
                <el-form-item>
                    <el-button :loading="btnLoading" class="submit-btn" type="primary" @click="store('form')">保存
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                btnLoading: false,
                cardLoading: false,
                form: {
                    display_name: '',
                    pic_url_type: 2,
                    pic_url: '',
                    content: '',
                    is_show: '',
                    pay_type: '',
                    price: '',
                    detail: '',
                },
                rules: {
                    display_name: [
                        {required: true, message: '请填写应用名称', trigger: 'change'},
                        {min: 1, max: 15, message: '长度在 1 到 8 个字符', trigger: 'change'}
                    ],
                    pic_url: [
                        {required: true, message: '请添加应用图标', trigger: 'change'},
                    ],
                    pay_type: [
                        {required: true, message: '请选择购买方式', trigger: 'change'},
                    ],
                    price: [
                        {required: true, message: '请填写售价', trigger: 'change'},
                    ],
                }
            };
        },
        methods: {
            store(formName) {
                let self = this;
                self.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        let params = JSON.parse(JSON.stringify(self.form));
                        request({
                            params: {
                                r: 'admin/app-manage/edit',
                            },
                            method: 'post',
                            data: params,
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'admin/app-manage/index',
                                });
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
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
                        r: 'admin/app-manage/edit',
                        name: getQuery('name'),
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code === 0) {
                        self.form = e.data.data.detail;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {

                });
            }
        },
        mounted() {
            this.getDetail();
        }
    });
</script>
