<?php
/**
 * Created by PhpStorm
 * User: 风哀伤
 * Date: 2020/12/18
 * Time: 3:19 下午
 * @copyright: ©2020 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
Yii::$app->loadViewComponent('app-member-auth');
?>
<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .button-item {
        /*margin-top: 12px;*/
        padding: 9px 25px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="loading">
        <div slot="header">
            <span>商品设置</span>
        </div>
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="172px" size="small">
            <div class="form-body">
                <el-form-item label="会员等级浏览权限" prop="show_goods_auth">
                    <app-member-auth v-model="ruleForm.show_goods_auth" :members="members"></app-member-auth>
                </el-form-item>
                <el-form-item label="会员等级购买权限" prop="buy_goods_auth">
                    <app-member-auth v-model="ruleForm.buy_goods_auth" :members="members"></app-member-auth>
                </el-form-item>
                <el-form-item label="开售提醒" prop="is_remind_sell_time">
                    <div>
                        <el-switch v-model="ruleForm.is_remind_sell_time" :active-value="1"
                                   :inactive-value="0"></el-switch>
                        <div style="color: #cccccc">开启后，定时开售的商品在开售前5分钟，买家可订阅开售提醒消息
                            <el-button type="text" @click="dialogVisible = true">查看示例</el-button>
                        </div>
                    </div>
                </el-form-item>
                <el-form-item>
                    <template slot='label'>
                        <span>商品详情底部客服导航</span>
                    </template>
                    <div>
                        <el-radio-group v-model="ruleForm.show_contact_type">
                            <el-radio :label="0">关闭</el-radio>
                            <el-radio :label="1">官方客服</el-radio>
                            <el-radio :label="2">外链客服</el-radio>
                            <el-radio :label="3">联系电话</el-radio>
                        </el-radio-group>
                    </div>
                </el-form-item>
                <el-form-item prop="good_negotiable">
                    <template slot='label'>
                        <span>商品面议联系方式</span>
                        <el-tooltip effect="dark" placement="top">
                            <div slot="content">若客服和外链客服两者都不勾选，默认勾选在线客服；客服和外链客服前端统一显示为客服
                            </div>
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-checkbox-group v-model="ruleForm.good_negotiable" size="mini">
                        <el-checkbox label="contact" size="mini">在线客服</el-checkbox>
                        <el-checkbox label="contact_tel" size="mini">联系电话</el-checkbox>
                        <el-checkbox label="contact_web" size="mini">外链客服</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
            </div>
            <el-button :loading="submitLoading" class="button-item" size="small" type="primary"
                       @click="submit('ruleForm')">保存
            </el-button>
        </el-form>
        <el-dialog
                title="查看开售提醒示例图"
                :visible.sync="dialogVisible"
                width="30%"
                :before-close="handleClose">
            <div flex="dir:left main:center">
                <img :src="sell_time_tip" width="466" height="457">
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button size="mini" type="primary" @click="dialogVisible = false">我知道了</el-button>
            </span>
        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    good_negotiable: [],
                    show_contact_type: 0,
                    show_goods_auth: '-1',
                    buy_goods_auth: '-1',
                    is_remind_sell_time: 0
                },
                rules: {},
                loading: false,
                submitLoading: false,
                members: [],
                dialogVisible: false,
                sell_time_tip: "<?= \Yii::$app->request->baseUrl?>/statics/img/mall/sell_time_tip.png",
            };
        },
        created() {
            this.loadData();
            this.getMembers();
        },
        methods: {
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'mall/index/goods',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.ruleForm = e.data.data.detail;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            submit(formName) {
                this.$refs[formName].validate((valid, mes) => {
                    if (valid) {
                        this.submitLoading = true;
                        request({
                            params: {
                                r: 'mall/index/goods',
                            },
                            method: 'post',
                            data: {
                                ruleForm: JSON.stringify(this.ruleForm)
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
                    } else {
                        this.$message.error(Object.values(mes).shift().shift().message);
                    }
                });
            },
            // 获取会员列表
            getMembers() {
                let self = this;
                request({
                    params: {
                        r: 'mall/mall-member/all-member'
                    },
                    method: 'get',
                    data: {}
                }).then(e => {
                    if (e.data.code === 0) {
                        self.members = e.data.data.list;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.loading = false;
                });
            },
        },
        watch: {
            'ruleForm.good_negotiable': function () {
                if (this.ruleForm.good_negotiable.length === 0) {
                    this.ruleForm.good_negotiable.push('contact');
                }
            }
        }
    });
</script>

