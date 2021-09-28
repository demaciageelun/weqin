<?php
defined('YII_ENV') or exit('Access Denied');
Yii::$app->loadViewComponent('app-rich-text');
?>
<style>
    .mobile-box {
        width: 400px;
        height: calc(800px - 150px);
        padding: 35px 11px;
        background-color: #fff;
        border-radius: 30px;
        background-size: cover;
        position: relative;
        font-size: .85rem;
        float: left;
        margin-right: 1rem;
    }

    .mobile-box .show-box {
        height: calc(667px - 150px);
        width: 375px;
        overflow: auto;
        font-size: 12px;
    }

    .show-box::-webkit-scrollbar { /*滚动条整体样式*/
        width: 1px; /*高宽分别对应横竖滚动条的尺寸*/
    }

    .account-box > div {
        background-color: #fff;
        border-radius: 4px;
        padding: 8px 0;
        height: 100%;
    }


    .order-bar-box > div {
        background-color: #fff;
        border-radius: 8px;
        height: 100%;
    }


    .mobile-menus-box > div {
        background-color: #fff;
        border-radius: 8px;
        height: 100%;
    }


    .menus-box .menu-item {
        cursor: move;
        background-color: #fff;
        margin: 5px 0;
    }

    .head-bar {
        width: 378px;
        height: 64px;
        position: relative;
        background: url('statics/img/mall/home_block/head.png') center no-repeat;
    }

    .head-bar div {
        position: absolute;
        text-align: center;
        width: 378px;
        font-size: 16px;
        font-weight: 600;
        height: 64px;
        line-height: 88px;
    }

    .head-bar img {
        width: 378px;
        height: 64px;
    }

    .title {
        padding: 18px 20px;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }

    .text-input {
        margin-left: 2%;
    }

    .share-text {
        margin-left: 12px;
    }

    .share-tt .el-form-item__error {
        margin-left: 30px;
    }

    .share-text .default {
        width: 10%;
        min-width: 100px;
    }

    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .recharge {
        background: #FFFFFF;
        padding: 20px 12px 0;
    }

    .recharge .account {
        font-size: 12px;
        border-left: 3px solid #ff4544;
        padding-left: 12px;
        color: #666666;
        margin-bottom: 16px;
    }

    .recharge .bg {
        background-repeat: no-repeat;
        background-size: 351px 80px;
        height: 80px;
        width: 351px;
        color: #666666;
    }

    .recharge .bg img {
        width: 36px;
        height: 36px;
        margin-left: 20px;
        flex-grow: 0;
    }

    .recharge .bg .balance-text {
        font-size: 21px;
        margin-left: 10px;
        flex-grow: 1;
    }

    .recharge .bg .balance-price {
        font-size: 23px;
        margin-right: 28px;
        flex-grow: 0;
    }

    .recharge .amount-text {
        font-size: 12px;
        color: #999999;
        margin-top: 28px;
    }

    .recharge .input {
        margin-top: 20px;
        border-radius: 17px;
    }

    .recharge .text {
        margin-top: -8px;
        word-break: break-all;
        text-align: justify;
        font-size: 24px;
        color: #666666;
    }

    .recharge .btn {
        position: absolute;
        bottom: 20px;
        width: 350px;
    }

    .lottery-pic {
        width: 75px;
        height: 77px;
        position: absolute;
        bottom: 75px;
        right: 20px;
    }

    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
    }

    .reset {
        position: absolute;
        top: 3px;
        left: 90px;
    }

    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
    }

    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
        padding-right: 60%;
    }

    .form-body.tt {
        padding-right: 40%;
    }

    .form-button {
        margin: 0 !important;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }

    .el-input-group__append {
        background-color: #fff;
        color: #353535;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <el-tabs v-model="activeName">
            <el-tab-pane label="基础设置" name="basic"></el-tab-pane>
            <el-tab-pane label="自定义设置" name="customize"></el-tab-pane>
        </el-tabs>
        <el-form :model="editForm" :rules="rules" ref="form" size="small" label-width="150px" v-loading="listLoading">
            <template v-if="activeName === 'basic'">
                <div class="form-body">
                    <el-form-item label="开启余额功能" prop="status">
                        <el-switch v-model="editForm.config.status" active-value="1" inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="是否开放自定义金额" prop="type">
                        <el-switch v-model="editForm.config.type" active-value="1" inactive-value="0"></el-switch>
                    </el-form-item>
                    <template v-if="editForm.customize.is_lottery_open != -1">
                        <el-form-item label="抽奖开关" prop="is_lottery_open">
                            <el-switch v-model="editForm.customize.is_lottery_open" active-value="1"
                                       inactive-value="0"></el-switch>
                        </el-form-item>
                        <template v-if="editForm.customize.is_lottery_open == 1">
                            <el-form-item label="选择活动" prop="lottery_type">
                                <el-radio v-for="plugin of selectList" v-model="editForm.customize.lottery_type"
                                          :label="plugin.value">
                                    {{plugin.label}}
                                </el-radio>
                            </el-form-item>
                        </template>
                    </template>
                    <el-form-item label="背景图片" prop="bj_pic_url" size="small">
                        <app-attachment @selected="bjSelected">
                            <el-tooltip class="item" effect="dark" content="建议尺寸750*324" placement="top">
                                <el-button size="mini">选择文件</el-button>
                            </el-tooltip>
                            <el-button size="mini" @click.stop="resetImg('bj')" class="reset" type="primary">恢复默认
                            </el-button>
                        </app-attachment>
                        <app-gallery style="margin-top: 10px;" v-if="editForm.config.bj_pic_url.url"
                                     :url="editForm.config.bj_pic_url.url"
                                     width="80px" height="80px" :show-delete="true"
                                     @deleted="delPic('bj')"></app-gallery>
                        <app-image style="margin-top: 10px;margin-bottom: 10px" v-else mode="aspectFill"
                                   :src="editForm.config.bj_pic_url.url" width="80" height="80"></app-image>
                    </el-form-item>
                    <el-form-item label="广告图片" prop="ad_pic_url" size="small">
                        <app-attachment @selected="adSelected">
                            <el-tooltip class="item" effect="dark" content="建议尺寸750*180" placement="top">
                                <el-button size="mini">选择文件</el-button>
                            </el-tooltip>
                            <el-button size="mini" @click.stop="resetImg('ad')" class="reset" type="primary">恢复默认
                            </el-button>
                        </app-attachment>
                        <app-gallery style="margin-top: 10px;" v-if="editForm.config.ad_pic_url.url"
                                     :url="editForm.config.ad_pic_url.url"
                                     width="80px" height="80px" :show-delete="true"
                                     @deleted="delPic('ad')"></app-gallery>
                        <app-image style="margin-top: 10px;margin-bottom: 10px" v-else mode="aspectFill"
                                   :src="editForm.config.ad_pic_url.url" width="80" height="80"></app-image>
                    </el-form-item>
                    <el-form-item label="广告图片跳转链接" prop="page_url">
                        <el-input :disabled="true" size="small" v-model="editForm.config.page_url" autocomplete="off">
                            <app-pick-link slot="append" @selected="selectAdvertUrl">
                                <el-button size="mini">选择链接</el-button>
                            </app-pick-link>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="充值按钮文字" prop="re_name">
                        <el-input type="input" size="small" v-model="editForm.config.re_name"></el-input>
                    </el-form-item>
                    <el-form-item label="充值说明图标" prop="re_pic_url" size="small">
                        <app-attachment @selected="iconSelected">
                            <el-tooltip class="item" effect="dark" content="建议尺寸36*36" placement="top">
                                <el-button size="mini">选择文件</el-button>
                            </el-tooltip>
                            <el-button size="mini" @click.stop="resetImg('re')" class="reset" type="primary">恢复默认
                            </el-button>
                        </app-attachment>
                        <app-gallery style="margin-top: 10px;" v-if="editForm.config.re_pic_url.url"
                                     :url="editForm.config.re_pic_url.url"
                                     width="80px" height="80px" :show-delete="true"
                                     @deleted="delPic('re')"></app-gallery>
                        <app-image style="margin-top: 10px;margin-bottom: 10px" v-else mode="aspectFill"
                                   :src="editForm.config.re_pic_url.url" width="80" height="80"></app-image>
                    </el-form-item>
                    <el-form-item label="充值说明" prop="explain">
                        <div style="width: 458px; min-height: 458px;">
                            <app-rich-text v-model="editForm.config.explain"></app-rich-text>
                        </div>
                    </el-form-item>
                </div>
                <el-button :loading="btnLoading" size="small" type="primary" class="button-item" @click="onSubmit">
                    保存
                </el-button>
            </template>
            <div v-if="activeName === 'customize'" style="display: flex;">
                <div class="mobile-box">
                    <div class="head-bar" flex="main:center cross:center">
                        <div>充值中心</div>
                    </div>
                    <div class="show-box" style="position: relative">
                        <div class="recharge" flex="dir:top">
                            <div class="account">我的账户</div>
                            <div :style="{'background-image': `url(${customize_bg})`}" class="bg"
                                 flex="dir:left cross:center">
                                <img class="image" :src="balance_icon"></img>
                                <div class="balance-text">{{editForm.customize.balance_title}}</div>
                                <div class="balance-price">￥565.66</div>
                            </div>

                            <div class="input grey">
                                <el-input disabled :placeholder="`手动输入` + editForm.customize.recharge_amount_title"/>
                            </div>
                            <div flex="dir:left main:justify" style="margin-top: 12px">
                                <div class="account">充值推荐</div>
                                <div flex="dir:left">
                                    <div class="account" style="border-width: 0">
                                        {{editForm.customize.recharge_explanation_title}}
                                    </div>
                                    <app-image style="height: 7px;width: 7px;margin: 6px 3px"
                                               src="<?= \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/statics/img/app/balance/info.png" ?>"
                                    ></app-image>
                                </div>
                            </div>
                            <div class="lottery-pic" v-if="editForm.customize.is_lottery_open == 1">
                                <app-image style="width: 100%;height: 100%"
                                           :src="editForm.customize.lottery_icon_url"></app-image>
                            </div>
                            <div class="btn">
                                <div :style="btnStyle">
                                    {{editForm.customize.recharge_btn_title}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 100%;">
                    <div>
                        <div class="title">文字</div>
                        <div class='form-body tt share-tt'>
                            <el-form-item label="余额" prop="balance_title">
                                <div flex="dir:left cross:center" class="share-text">
                                    <app-image width="12px" height="12px" mode="aspectFill"
                                               :src="customize_pic"></app-image>
                                    <el-input class="text-input" v-model="editForm.customize.balance_title"
                                              maxlength="10"></el-input>
                                </div>
                            </el-form-item>
                            <el-form-item label="充值金额" prop="recharge_amount_title">
                                <div flex="dir:left cross:center" class="share-text">
                                    <app-image width="12px" height="12px" mode="aspectFill"
                                               :src="customize_pic"></app-image>
                                    <el-input class="text-input"
                                              v-model="editForm.customize.recharge_amount_title"></el-input>
                                </div>
                            </el-form-item>
                            <el-form-item label="充值说明" prop="recharge_explanation_title">
                                <div flex="dir:left cross:center" class="share-text">
                                    <app-image width="12px" height="12px" mode="aspectFill"
                                               :src="customize_pic"></app-image>
                                    <el-input class="text-input"
                                              v-model="editForm.customize.recharge_explanation_title"></el-input>
                                </div>
                            </el-form-item>
                        </div>
                    </div>
                    <div>
                        <div class="title">按钮</div>
                        <div class='form-body tt'>
                            <el-form-item label="按钮圆角" prop="recharge_btn_radius">
                                <div flex="dir:left">
                                    <el-slider style="width: 50%;margin-right: 20px"
                                               input-size="mini"
                                               v-model.number="editForm.customize.recharge_btn_radius"
                                               @input="sliderInput"
                                               :max="40"
                                               :min="0"
                                               :show-tooltip="false"></el-slider>
                                    <el-input-number v-model.number="editForm.customize.recharge_btn_radius" :min="0"
                                                     :max="40"></el-input-number>
                                    <div style="margin-left: 10px">px</div>
                                </div>
                            </el-form-item>
                            <el-form-item label="按钮文本" prop="recharge_btn_title">
                                <el-input class="text-input" style="margin-left: 0"
                                          v-model="editForm.customize.recharge_btn_title"></el-input>
                            </el-form-item>
                            <div flex="dir:left">
                                <el-form-item label="填充颜色" prop="recharge_btn_background">
                                    <div flex="dir:left cross:center">
                                        <el-color-picker v-model="editForm.customize.recharge_btn_background"
                                                         size="small"></el-color-picker>
                                        <el-input size="small" class="text-input" style="width: 50%"
                                                  v-model="editForm.customize.recharge_btn_background"></el-input>
                                    </div>
                                </el-form-item>
                                <el-form-item label="文本颜色" prop="recharge_btn_color">
                                    <div flex="dir:left cross:center">
                                        <el-color-picker v-model="editForm.customize.recharge_btn_color"
                                                         size="small"></el-color-picker>
                                        <el-input size="small" class="text-input" style="width: 50%"
                                                  v-model="editForm.customize.recharge_btn_color"></el-input>
                                    </div>
                                </el-form-item>
                            </div>
                        </div>
                    </div>
                    <div v-if="editForm.customize.is_lottery_open != -1">
                        <div class="title">抽奖</div>
                        <div class='form-body tt'>
                            <template v-if="editForm.customize.is_lottery_open == 1">
                                <el-form-item label="自定义图标" prop="lottery_icon_url">
                                    <app-attachment style="margin-bottom:10px" :multiple="false" :max="1"
                                                    @selected="selectIconUrl">
                                        <el-tooltip effect="dark"
                                                    content="建议尺寸:150 * 156"
                                                    placement="top">
                                            <el-button size="mini">选择图标</el-button>
                                        </el-tooltip>
                                    </app-attachment>
                                    <div style="margin-right: 20px;display:inline-block;position: relative;cursor: move;">
                                        <app-attachment :multiple="false" :max="1"
                                                        @selected="selectIconUrl">
                                            <app-image mode="aspectFill"
                                                       width="80px"
                                                       height='80px'
                                                       :src="editForm.customize.lottery_icon_url">
                                            </app-image>
                                        </app-attachment>
                                        <el-button v-if="editForm.customize.lottery_icon_url" class="del-btn"
                                                   size="mini" type="danger" icon="el-icon-close"
                                                   circle
                                                   @click="delPic('lottery')"></el-button>
                                    </div>
                                    <el-button size="mini" @click="resetImg('lottery')" class="reset"
                                               type="primary">
                                        恢复默认
                                    </el-button>
                                </el-form-item>
                            </template>
                        </div>
                    </div>
                    <el-button :loading="btnLoading" size="small" type="primary" class="button-item" @click="onSubmit">
                        保存
                    </el-button>
                </div>
            </div>
        </el-form>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                rules: {
                    balance_title: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.editForm.customize.balance_title) {
                                    callback();
                                }
                                callback('余额不能为空');
                            }
                        }
                    ],
                    recharge_amount_title: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.editForm.customize.recharge_amount_title) {
                                    callback();
                                }
                                callback('充值金额不能为空');
                            }
                        }
                    ],
                    recharge_explanation_title: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.editForm.customize.recharge_explanation_title) {
                                    callback();
                                }
                                callback('充值说明不能为空');
                            }
                        }
                    ],
                    recharge_btn_title: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.editForm.customize.recharge_btn_title ){
                                    callback();
                                }
                                callback('按钮文本不能为空');
                            }
                        }
                    ],
                },
                editForm: {
                    customize: {
                        balance_title: "",
                        is_lottery_open: 0,
                        lottery_icon_url: "",
                        lottery_type: "",
                        recharge_amount_title: "",
                        recharge_btn_background: "",
                        recharge_btn_color: "",
                        recharge_btn_radius: 0,
                        recharge_btn_title: "",
                        recharge_explanation_title: "",
                    },
                    config: {
                        ad_pic_defalut: "",
                        ad_pic_url: null,
                        bj_pic_defalut: "",
                        bj_pic_url: null,
                        explain: "",
                        is_pay_password: "",
                        open_type: "",
                        page_url: "",
                        params: "",
                        re_name: "",
                        re_pic_defalut: "",
                        re_pic_url: null,
                        status: "",
                        type: "",
                    },
                },
                selectList: [],
                listLoading: null,
                btnLoading: false,
                activeName: 'basic',
                /************************/
                balance_icon: _baseUrl + '/statics/img/common/icon-balance.png',
                customize_bg: _baseUrl + '/statics/img/app/mall/icon-balance-recharge-bg.png',
                customize_pic: _baseUrl + '/statics/img/mall/customize_jp.png',
            };
        },
        computed: {
            btnStyle() {
                return {
                    height: '44px',
                    color: this.editForm.customize.recharge_btn_color,
                    background: this.editForm.customize.recharge_btn_background,
                    borderRadius: this.editForm.customize.recharge_btn_radius + 'px',
                    textAlign: 'center',
                    lineHeight: '44px',
                    fontSize: '16px',
                }
            }
        },
        methods: {
            sliderInput(e) {
                this.editForm.customize.recharge_btn_radius = e;
            },
            selectAdvertUrl(e) {
                let self = this;
                e.forEach(function (item, index) {
                    self.editForm.config.page_url = item.new_link_url;
                    self.editForm.config.open_type = item.open_type;
                    self.editForm.config.params = item.params;
                })
            },

            bjSelected(list) {
                this.editForm.config.bj_pic_url = list.length ? list[0] : null;
            },

            adSelected(list) {
                this.editForm.config.ad_pic_url = list.length ? list[0] : null;
            },

            iconSelected(list) {
                this.editForm.config.re_pic_url = list.length ? list[0] : null;
            },

            selectIconUrl(e) {
                if (e.length) Object.assign(this.editForm.customize, {lottery_icon_url: e[0].url})
            },

            delPic(obj) {
                if (obj === 'bj') {
                    this.editForm.config.bj_pic_url.url = '';
                }
                if (obj === 'ad') {
                    this.editForm.config.ad_pic_url.url = '';
                }
                if (obj === 're') {
                    this.editForm.config.re_pic_url.url = '';
                }
                if (obj === 'lottery') {
                    Object.assign(this.editForm.customize, {lottery_icon_url: ''})
                }
            },
            resetImg(type) {
                switch (type) {
                    case 'bj':
                        this.editForm.config.bj_pic_url.url = this.editForm.config.bj_pic_defalut;
                        break;
                    case 'ad':
                        this.editForm.config.ad_pic_url.url = this.editForm.config.ad_pic_defalut;
                        break;
                    case 're':
                        this.editForm.config.re_pic_url.url = this.editForm.config.re_pic_defalut;
                        break;
                    case 'lottery':
                        Object.assign(this.editForm.customize, {lottery_icon_url: _baseUrl + '/statics/img/app/balance/bg.gif'})
                        break;
                    default:
                        break;
                }
            },
            onSubmit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/recharge/config'
                            },
                            data: this.editForm,
                            method: 'post',
                        }).then(e => {
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                            this.btnLoading = false;
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/recharge/config'
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        let {setting, selectList} = e.data.data;
                        setting.customize.recharge_btn_radius = Number(setting.customize.recharge_btn_radius);
                        this.editForm = setting;
                        this.selectList = selectList;
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },
        },

        mounted() {
            this.getList();
        }
    })
</script>