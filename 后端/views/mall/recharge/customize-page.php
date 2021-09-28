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
        width: 35%;
    }

    .share-text {
        margin-left: -67px;
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
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span>自定义设置</span>
        </div>
        <el-form :model="form" size="small" label-width="100px" v-loading="listLoading">
            <div style="display: flex;">
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
                                <div class="balance-text">{{form.balance_title}}</div>
                                <div class="balance-price">￥565.66</div>
                            </div>

                            <div class="input grey">
                                <el-input disabled :placeholder="`手动输入` + form.recharge_amount_title"/>
                            </div>
                            <div flex="dir:left main:justify" style="margin-top: 12px">
                                <div class="account">充值推荐</div>
                                <div flex="dir:left">
                                    <div class="account" style="border-width: 0">{{form.recharge_explanation_title}}
                                    </div>
                                    <app-image style="height: 7px;width: 7px;margin: 6px 3px"
                                               src="<?= \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/statics/img/app/balance/info.png" ?>"
                                    ></app-image>
                                </div>
                            </div>
                            <div class="lottery-pic" v-if="form.is_lottery_open == 1">
                                <app-image style="width: 100%;height: 100%" :src="form.lottery_icon_url"></app-image>
                            </div>
                            <div class="btn">
                                <div :style="btnStyle">
                                    {{form.recharge_btn_title}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width: 100%;">
                    <div>
                        <div class="title">文字</div>
                        <div class='form-body'>
                            <el-form-item prop="balance_title">
                                <div flex="dir:left cross:center" class="share-text">
                                    <div class="default">余额</div>
                                    <app-image width="12px" height="12px" mode="aspectFill"
                                               :src="customize_pic"></app-image>
                                    <el-input class="text-input" v-model="form.balance_title" maxlength="10"></el-input>
                                </div>
                            </el-form-item>
                            <el-form-item prop="recharge_amount_title">
                                <div flex="dir:left cross:center" class="share-text">
                                    <div class="default">充值金额</div>
                                    <app-image width="12px" height="12px" mode="aspectFill"
                                               :src="customize_pic"></app-image>
                                    <el-input class="text-input" v-model="form.recharge_amount_title"></el-input>
                                </div>
                            </el-form-item>
                            <el-form-item prop="recharge_explanation_title">
                                <div flex="dir:left cross:center" class="share-text">
                                    <div class="default">充值说明</div>
                                    <app-image width="12px" height="12px" mode="aspectFill"
                                               :src="customize_pic"></app-image>
                                    <el-input class="text-input" v-model="form.recharge_explanation_title"></el-input>
                                </div>
                            </el-form-item>
                        </div>
                    </div>
                    <div>
                        <div class="title">按钮</div>
                        <div class='form-body'>
                            <el-form-item label="按钮圆角" prop="recharge_btn_radius">
                                <div flex="dir:left">
                                    <el-slider style="width: 50%;margin-right: 20px"
                                               input-size="mini"
                                               v-model="form.recharge_btn_radius"
                                               @input="sliderInput"
                                               :max="40"
                                               :min="0"
                                               :show-tooltip="false"></el-slider>
                                    <el-input-number v-model.number="form.recharge_btn_radius" :min="0"
                                                     :max="40"></el-input-number>
                                    <div style="margin-left: 10px">px</div>
                                </div>
                            </el-form-item>
                            <el-form-item label="按钮文本" prop="recharge_btn_title">
                                <el-input class="text-input" style="margin-left: 0"
                                          v-model="form.recharge_btn_title"></el-input>
                            </el-form-item>
                            <div flex="dir:left">
                                <el-form-item label="填充颜色" prop="recharge_btn_background">
                                    <div flex="dir:left cross:center">
                                        <el-color-picker v-model="form.recharge_btn_background"
                                                         size="small"></el-color-picker>
                                        <el-input size="small" class="text-input" style="width: 50%"
                                                  v-model="form.recharge_btn_background"></el-input>
                                    </div>
                                </el-form-item>
                                <el-form-item label="文本颜色" prop="recharge_btn_color">
                                    <div flex="dir:left cross:center">
                                        <el-color-picker v-model="form.recharge_btn_color"
                                                         size="small"></el-color-picker>
                                        <el-input size="small" class="text-input" style="width: 50%"
                                                  v-model="form.recharge_btn_color"></el-input>
                                    </div>
                                </el-form-item>
                            </div>
                        </div>
                    </div>
                    <div v-if="form.is_lottery_open != -1">
                        <div class="title">抽奖</div>
                        <div class='form-body'>
                            <el-form-item label="开关" prop="is_lottery_open">
                                <el-switch v-model="form.is_lottery_open" active-value="1"
                                           inactive-value="0"></el-switch>
                            </el-form-item>
                            <template v-if="form.is_lottery_open == 1">
                                <el-form-item label="选择活动" prop="lottery_type">
                                    <el-radio v-for="plugin of select_list" v-model="form.lottery_type" :label="plugin.value">
                                        {{plugin.label}}</el-radio>
                                </el-form-item>
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
                                                       :src="form.lottery_icon_url">
                                            </app-image>
                                        </app-attachment>
                                        <el-button v-if="form.lottery_icon_url" class="del-btn"
                                                   size="mini" type="danger" icon="el-icon-close"
                                                   circle
                                                   @click="removeIconUrl"></el-button>
                                    </div>
                                    <el-button size="mini" @click="resetImg('lottery_icon_url')" class="reset"
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
                select_list: [],
                form: {
                    is_lottery_open: 0,
                    lottery_type: '',
                    lottery_icon_url: '',
                    'balance_title': '',
                    'recharge_amount_title': '',
                    'recharge_explanation_title': '',
                    'recharge_btn_radius': 0,
                    'recharge_btn_title': '',
                    'recharge_btn_background': '',
                    'recharge_btn_color': '',
                },
                balance_icon: _baseUrl + '/statics/img/common/icon-balance.png',
                customize_bg: _baseUrl + '/statics/img/app/mall/icon-balance-recharge-bg.png',
                customize_pic: _baseUrl + '/statics/img/mall/customize_jp.png',
                listLoading: false,
                btnLoading: false,
            };
        },
        computed: {
            btnStyle() {
                return {
                    height: '44px',
                    color: this.form.recharge_btn_color,
                    background: this.form.recharge_btn_background,
                    'border-radius': this.form.recharge_btn_radius + 'px',
                    'text-align': 'center',
                    'line-height': '44px',
                    'font-size': '16px',
                }
            }
        },
        methods: {
            selectIconUrl(e) {
                if (e.length) Object.assign(this.form, {lottery_icon_url: e[0].url})
            },
            removeIconUrl() {
                Object.assign(this.form, {lottery_icon_url: ''})
            },
            resetImg(type) {
                const host = "<?php echo \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/" ?>";
                Object.assign(this.form, {lottery_icon_url: host + 'statics/img/app/balance/bg.gif'})
            },
            sliderInput(e) {
                this.form.recharge_btn_radius = e;
            },
            onSubmit() {
                this.btnLoading = true;
                request({
                    params: {
                        r: 'mall/recharge/customize-page'
                    },
                    data: this.form,
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
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/recharge/customize-page'
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        e.data.data.recharge_btn_radius = parseInt(e.data.data.setting.recharge_btn_radius);
                        this.select_list = e.data.data.select_list;
                        this.form = e.data.data.setting;
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