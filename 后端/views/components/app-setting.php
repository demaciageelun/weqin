<?php
/**
 * Created by PhpStorm.
 * User: fjt
 * Date: 2019/12/6
 * Time: 16:48
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

?>
<style>
    .red {
        display:inline-block;
        padding:0 25px;
        color: #ff4544;
    }

    .app-setting .member-list {
        width: 500px;
    }

    .app-setting .member-item {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .app-setting-dialog .input-item {
        display: inline-block;
        width: 250px;
        margin-bottom: 40px;
    }

    .app-setting-dialog .input-item .el-input__inner {
        border-right: 0;
    }

    .app-setting-dialog .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .app-setting-dialog .input-item .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .app-setting-dialog .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .app-setting-dialog .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .app-setting-dialog .input-item .el-input-group__append .el-button {
        margin: 0;
    }

    .app-setting-dialog .member-item {
        height: 40px;
    }

    .app-setting-dialog .no-list-tip {
        width: 100%;
        text-align: center;
        font-size: 16px;
        padding: 10px 0;
        color: #999999;
    }
</style>
<template id="app-setting">
    <div>
        <div class="app-setting">
            <el-form size="mini" :data="form" :label-width="label_width + 'px'">
                <el-card style="margin-bottom: 10px">
                    <div slot="header">购买设置</div>

                    <!--是否开启分销-->
                    <el-form-item label="是否开启分销" prop="is_share" v-if="is_share && form.is_share != -1">
                        <el-switch
                                v-model="form.is_share"
                                :active-value="1"
                                :inactive-value="0">
                        </el-switch>
                        <span class="red">注：必须在“
                            <el-button type="text" @click="$navigate({r:'mall/share/basic'}, true)">分销中心=>基础设置</el-button>
                            ”中开启，才能使用
                        </span>
                    </el-form-item>

                    <!--是否开启区域允许购买-->
                    <el-form-item class="switch" label="是否开启区域允许购买" v-if="is_territorial_limitation">
                        <el-switch v-model="form.is_territorial_limitation" :active-value="1"
                                   :inactive-value="0"></el-switch>
                        <span class="ml-24 red">注：必须在“
                            <el-button type="text" @click="$navigate({r:'mall/territorial-limitation/index'}, true)">
                                系统管理=>区域允许购买
                            </el-button>
                            ”中开启，才能使用
                        </span>
                    </el-form-item>

                <!--是否开启起送规则-->
                <el-form-item class="switch" label="是否开启起送规则" v-if="is_offer_price">
                    <el-switch v-model="form.is_offer_price" :active-value="1"
                               :inactive-value="0"></el-switch>
                    <span class="ml-24 red">注：必须在“
                        <el-button type="text" @click="$navigate({r:'mall/index/rule', tab: 'fourth'}, true)">
                            系统管理=>规则设置=>起送规则
                        </el-button>
                        ”中开启，才能使用
                    </span>
                </el-form-item>

                <!--支付方式-->
                <el-form-item label="支付方式" prop="payment_type" v-if="is_payment">
                    <label slot="label">支付方式
                        <el-tooltip class="item" effect="dark"
                                    :content="is_surpport_huodao?'默认支持线上支付；若三个都不勾选，则视为勾选线上支付':'默认支持线上支付；若两个都不勾选，则视为勾选线上支付'"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </label>
                    <el-checkbox-group v-model="form.payment_type" size="mini" :min="1" :max="3">
                        <el-checkbox label="online_pay" size="mini">线上支付</el-checkbox>
                        <el-checkbox label="huodao" size="mini" v-if="is_surpport_huodao">货到付款</el-checkbox>
                        <el-checkbox label="balance" size="mini">余额支付</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>

                <!--发货方式-->
                <el-form-item label="发货方式" prop="send_type" v-if="is_send_type">
                    <label slot="label">发货方式
                        <el-tooltip
                            class="item"
                            effect="dark"
                            content="自提需要设置门店，如果您还未设置门店请保存本页后设置门店"
                            placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </label>
                    <div>
                        <el-checkbox-group v-model="form.send_type" :min="1" :max="send_type_desc.length">
                            <el-checkbox v-for="item in send_type_desc"   :label="item.key">
                                {{item.modify ? item.modify : item.origin}}{{item.origin !== item.modify && item.modify ? `(${item.origin})` : ''}}
                            </el-checkbox>
                        </el-checkbox-group>
                    </div>
                </el-form-item>
                <el-form-item label="会员等级购买权限" prop="is_vip_show" v-if="is_vip_show">
                    <el-switch
                            v-model="form.is_vip_show"
                            :active-value="1"
                            :inactive-value="0">
                    </el-switch>
                </el-form-item>
                <el-form-item label="会员等级" prop="vip_show_limit" v-if="is_vip_show && form.is_vip_show">
                    <div class="member-list">
                        <el-tag @close="handleClose(index)" class="member-item" v-for="(item,index) in form.vip_show_limit" :key="item.id" closable>
                        {{item.name}}
                        </el-tag>
                        <el-button @click="openMemberDialog">选择会员等级</el-button>
                    </div>
                </el-form-item>
                </el-card>
            <el-card style="margin-bottom: 10px" v-if="is_discount">
                <div slot="header">优惠叠加设置</div>
                <el-form-item label="优惠券" v-if="is_coupon">
                    <el-switch v-model="form.is_coupon" :active-value="1"
                               :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="超级会员卡" v-if="form.svip_status != -1 && form.svip_status != null && form.svip_status != undefined">
                    <el-switch v-model="form.svip_status" :active-value="1"
                               :inactive-value="0"></el-switch>
                    <span class="ml-24 red">注：必须在“
                                <el-button type="text" @click="$navigate({r:'plugin/vip_card/mall/setting/index'}, true)">
                                    插件中心=>超级会员卡
                                </el-button>
                                ”中开启，才能使用
                </el-form-item>
                <el-form-item label="会员价" v-if="is_member_price">
                    <el-switch v-model="form.is_member_price" :active-value="1"
                               :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="积分抵扣" v-if="is_integral">
                    <el-switch v-model="form.is_integral" :active-value="1"
                               :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="满减优惠" v-if="is_full_reduce">
                    <el-switch v-model="form.is_full_reduce" :active-value="1"
                               :inactive-value="0"></el-switch>
                </el-form-item>
            </el-card>
            <slot></slot>
        </el-form>
        <el-dialog class="app-setting-dialog" title="选择会员等级" :visible.sync="dialogVisible" width="30%">
            <el-form @submit.native.prevent size="mini">
                <div class="input-item">
                    <el-input @keyup.enter.native="toSearch" size="small" placeholder="请输入会员等级名称"
                              v-model="keyword" clearable
                              @clear="toSearch">
                        <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                    </el-input>
                </div>
            </el-form>
            <div v-loading="listLoading">
                <div class="member-item" v-for="item in list" :key="item.id">
                    <el-checkbox :checked="item.checked" @change="checkedChange(item)" :label="item.id">{{item.name}}</el-checkbox>
                </div>
            </div>
            <div class="no-list-tip" v-if="list.length == 0 && !listLoading">暂无会员等级</div>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination
                        @current-change="pagination"
                        background
                        layout="prev, pager, next"
                        :page-count="pageCount">
                </el-pagination>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button size="small" @click="dialogVisible = false">取 消</el-button>
                <el-button size="small" type="primary" @click="submitMember">确 定</el-button>
            </span>
        </el-dialog> 
    </div>
</template>

<script>
    Vue.component('app-setting', {
        template: '#app-setting',
        props: {
            value: Object,
            is_share: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_sms: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_mail: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_print: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_territorial_limitation: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_vip_show: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            is_regional: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            is_payment: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_send_type: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_surpport_huodao: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_surpport_city: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_coupon: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            label_width: {
                type: Number,
                default() {
                    return 180;
                }
            },
            label_show: {
                type: Boolean,
                default() {
                    return true
                }
            },
            is_member_price: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_integral: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_discount: {
                type: Boolean,
                default() {
                    return true;
                }
            },
            is_full_reduce: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            is_offer_price: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            sign: {
                type: String
            }

        },
        data() {
            return {
                setting: {
                    is_share: 0,
                    is_sms: 0,
                    is_mail: 0,
                    is_print: 0,
                    is_territorial_limitation: 0,
                    send_type: ['express', 'offline'],
                    payment_type: ['online_pay'],
                    is_coupon: 0,
                    svip_status: -1,
                    is_member_price: 0,
                    is_integral: 0,
                    is_offer_price: 1,
                    vip_show_limit: [],
                    is_vip_show: 0,
                    is_full_reduce: 0,
                    is_offer_price: 1
                },
                dialogVisible: false,
                keyword: '',
                page: 1,
                listLoading: false,
                list: [],
                pageCount: 0,
                send_type_desc: []
            }
        },
        computed: {
            form() {
                for (let key in this.setting) {
                    if (typeof this.value[key] === 'undefined') {
                        this.value[key] = this.setting[key];
                    }
                }
                return this.value;
            },
            send_type_list() {
                let list = [];
                for (let i in this.form.send_type) {
                    if (this.form.send_type[i] == 'express') {
                        list.push('快递配送');
                    }
                    if (this.form.send_type[i] == 'offline') {
                        list.push('到店自提');
                    }
                    if (this.sign !== 'mch' && this.form.send_type[i] == 'city') {
                        list.push('同城配送');
                    }
                }
                return list;
            }
        },
        methods: {
            checkedChange(item) {
                item.checked = !item.checked
            },
            handleClose(index) {
                this.form.vip_show_limit.splice(index,1)
            },
            submitMember() {
                this.form.vip_show_limit = [];
                for(let item of this.list) {
                    if(item.checked) {
                        this.form.vip_show_limit.push(item)
                    }
                }
                this.dialogVisible = false;
            },
            openMemberDialog() {
                this.dialogVisible = !this.dialogVisible;
                this.getList();
            },
            toSearch() {
                this.page = 1;
                this.getList();
            },

            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                self.list = [];
                request({
                    params: {
                        r: 'mall/mall-member/index',
                        page: self.page,
                        keyword: this.keyword
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    for(let row of self.list) {
                        row.checked = false;
                        for(let item of self.form.vip_show_limit) {
                            if(item.level == row.level) {
                                row.checked = true;
                            } 
                        }
                    }
                    self.pageCount = e.data.data.pagination.page_count;
                }).catch(e => {
                    console.log(e);
                });
            },
        },
        created() {
          if (this.is_send_type) {
            request({
                  params: {
                       r: 'mall/index/setting-one',
                       column: 'send_type_desc'
                   },
                  method: 'get'
                }).then(e => {
                    this.send_type_desc = e.data.data;
                    if (this.sign === 'mch') {
                        for (let i = 0; i < this.send_type_desc.length; i++) {
                            if (this.send_type_desc[i].key === 'city') {
                                this.$delete(this.send_type_desc, i);
                            }
                        }
                    }

              })
         }
        }
    });
</script>
