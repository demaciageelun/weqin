<?php
defined('YII_ENV') or exit('Access Denied');
Yii::$app->loadViewComponent('app-select-member');
Yii::$app->loadViewComponent('goods/app-select-card');
Yii::$app->loadViewComponent('goods/app-select-coupon');

$permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
$is_plugin_show = array_search('pond', $permission) !== false || array_search('scratch', $permission) !== false;
?>
<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-input {
        width: 50%;
    }

    .form-button {
        margin: 0 !important;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0 0 20px
    }

    .button-item {
        padding: 9px 25px;
    }
</style>
<section id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" class="clearfix">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer"
                                          @click="$navigate({r:'mall/recharge/index'})">充值管理</span></el-breadcrumb-item>
                <el-breadcrumb-item>充值编辑</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="form" v-loading="loading" label-width="10rem" :rules="FormRules" ref="form">
                <el-form-item prop="name">
                    <template slot='label'>
                        <span>充值名称</span>
                        <el-tooltip effect="dark" content="在充值管理显示"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-input class="form-input" size="small" v-model="form.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item prop="pay_price">
                    <template slot='label'>
                        <span>支付金额</span>
                        <el-tooltip effect="dark" content="用户支付多少就充值多少"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-input size="small" type="number"
                              class="form-input"
                              oninput="this.value = this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');"
                              v-model="form.pay_price" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item prop="send_type" label="充值奖励">
                    <el-checkbox-group v-model="form.send_type">
                        <el-checkbox :label="1">余额</el-checkbox>
                        <el-checkbox :label="2">积分</el-checkbox>
                        <el-checkbox :label="4">会员</el-checkbox>
                        <el-checkbox :label="8">优惠券</el-checkbox>
                        <el-checkbox :label="16">卡券</el-checkbox>
                        <el-checkbox v-if="is_plugin_show" :label="32">抽奖次数</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
                <el-form-item prop="send_price" v-if="form.send_type.indexOf(1) !== -1">
                    <template slot='label'>
                        <span>赠送金额</span>
                        <el-tooltip effect="dark" content="用户充值时，赠送的金额，默认为0"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-input class="form-input"
                              size="small" type="number"
                              oninput="this.value = this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');"
                              v-model="form.send_price" autocomplete="off"></el-input>
                </el-form-item>

                <el-form-item prop="send_integral" v-if="form.send_type.indexOf(2) !== -1">
                    <template slot='label'>
                        <span>赠送积分</span>
                        <el-tooltip effect="dark" content="用户充值时，赠送的积分，默认为0"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-input size="small" type="number"
                              class="form-input"
                              oninput="this.value = this.value.match(/\d*/)"
                              v-model="form.send_integral"
                              autocomplete="off"
                    ></el-input>
                </el-form-item>
                <el-form-item prop="send_member_id" label="赠送会员" v-if="form.send_type.indexOf(4) !== -1">
                    <div flex="dir:left cross:center">
                        <el-tag @close="closeMember" v-if="tempMemberName" closable style="margin-right: 12px">
                            {{tempMemberName}}
                        </el-tag>
                        <app-select-member v-model="form.send_member_id" @change="changeSendMemberId">
                            <el-button type="small">选择会员等级</el-button>
                        </app-select-member>
                    </div>
                </el-form-item>

                <el-form-item prop="send_coupon" label="赠送优惠券" v-if="form.send_type.indexOf(8) !== -1">

                    <el-tag v-for="(coupon, index) in form.send_coupon" @close="couponDelete(index)"
                            :key="index" :disable-transitions="true"
                            style="margin: 0 10px 10px 0;" closable>
                        {{coupon.send_num}}张 | {{coupon.name}}
                    </el-tag>
                    <div style="display: inline-block">
                        <app-select-coupon v-model="form.send_coupon" @select="couponSubmit">
                            <el-button type="button" size="mini">选择优惠券
                            </el-button>
                        </app-select-coupon>
                    </div>
                </el-form-item>
                <el-form-item prop="send_card" label="赠送卡券" v-if="form.send_type.indexOf(16) !== -1">
                    <el-tag v-for="(card, index) in form.send_card" @close="cardDelete(index)"
                            :key="index" :disable-transitions="true"
                            style="margin: 0 10px 10px 0;" closable>
                        {{card.num}}张 | {{card.name}}
                    </el-tag>
                    <el-button type="button" size="mini" @click="cardDialogVisible = true">新增卡券
                    </el-button>
                </el-form-item>

                <el-form-item prop="lottery_limit" label="赠送抽奖次数"
                              v-if="is_plugin_show && form.send_type.indexOf(32) !== -1">
                    <el-input class="form-input"
                              oninput="this.value = this.value.match(/[1-9]\d*|/)"
                              size="small" v-model="form.lottery_limit" autocomplete="off">
                        <template slot="append">次</template>
                    </el-input>
                </el-form-item>
            </el-form>
        </div>
        <el-button class="button-item" type="primary" size='mini' :loading=btnLoading @click="onSubmit">保存</el-button>
    </el-card>
    <app-select-card :is-show="cardDialogVisible" :rule-form="ruleForm" @select="cardSubmit"></app-select-card>
</section>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            let checkPrice = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('不能为空'));
                }
                if (value <= 0) {
                    callback(new Error('必须大于0'));
                } else {
                    callback();
                }
            };
            let checkAge = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('不能为空'));
                }
                if (value < 0) {
                    callback(new Error('不能小于0'));
                } else {
                    callback();
                }
            };
            return {
                ruleForm: {
                    cards: [],
                },
                is_plugin_show: "<?= $is_plugin_show?>",
                cardDialogVisible: false,
                form: {
                    name: '',
                    pay_price: '',
                    send_type: [1, 2, 4],
                    send_price: '',
                    send_integral: '',
                    send_member_id: '',
                    send_coupon: [],
                    send_card: [],
                    lottery_limit: 1,
                },
                loading: false,
                btnLoading: false,
                FormRules: {
                    name: [
                        {required: true, message: '充值名称不能为空', trigger: 'blur'},
                    ],
                    pay_price: [
                        {required: true, message: '支付金额不能为空', trigger: 'change'},
                        {validator: checkPrice, trigger: 'change'}
                    ],
                    send_price: [
                        {required: true, message: '赠送金额不能为空', trigger: 'change'},
                        {validator: checkAge, trigger: 'change'}
                    ],
                    send_member_id: [
                        {required: true, message: '赠送会员不能为空', trigger: ['change', 'blur']},
                        {
                            required: true, validator: (rule, value, callback) => {
                                if (this.form.send_member_id > 0) {
                                    callback();
                                }
                                callback('赠送会员不能为空');
                            }
                        }
                    ],
                    send_integral: [
                        {required: true, message: '赠送积分不能为空', trigger: 'change'},
                        {validator: checkAge, trigger: 'change'}
                    ],
                    lottery_limit: [
                        {required: true, message: '赠送抽奖次数不能为空', trigger: 'change'},
                    ],
                    send_coupon: [
                        {required: true, message: '赠送优惠券不能为空', trigger: ['change']},
                    ],
                    send_card: [
                        {required: true, message: '赠送卡券不能为空', trigger: 'change'},
                    ],
                },
                tempMemberName: '',
            };
        },
        methods: {
            couponDelete(index) {
                this.form.send_coupon.splice(index, 1);
            },
            cardDelete(index) {
                this.form.send_card.splice(index, 1);
            },
            couponSubmit(e) {
                this.form.send_coupon = e;
                this.$refs.form.validateField('send_coupon');
            },
            cardSubmit(e) {
                this.form.send_card = e;
                this.$refs.form.validateField('send_card');
            },
            closeMember() {
                this.tempMemberName = '';
                this.form.send_member_id = 0;
            },
            changeSendMemberId(e) {
                if (e) {
                    this.tempMemberName = e.name;
                    this.form.send_member_id = e.id;
                    this.$refs.form.validateField('send_member_id');
                }
            },
            // 提交数据
            onSubmit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        let para = Object.assign(this.form);
                        request({
                            params: {
                                r: 'mall/recharge/edit',
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message({
                                    message: e.data.msg,
                                    type: 'success'
                                });
                                setTimeout(function () {
                                    navigateTo({r: 'mall/recharge/index'});
                                }, 300);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },

            //获取列表
            getList() {
                this.loading = true;
                request({
                    params: {
                        r: 'mall/recharge/edit',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        if (e.data.data.list.id > 0) {
                            this.form = e.data.data.list;
                            this.ruleForm.cards = this.form.send_card;
                            this.tempMemberName = e.data.data.list.member.name;
                        }
                    }
                }).catch(e => {

                });
            },
        },

        created() {
            if (getQuery('id')) {
                this.getList();
            }
        }
    })
</script>