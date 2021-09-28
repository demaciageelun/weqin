<?php
Yii::$app->loadViewComponent('app-select-store');
Yii::$app->loadViewComponent('order/app-send');
?>
<style>
    .app-add-order .title-box {
        margin: 15px 0;
    }

    .app-add-order .title-box .text {
        background-color: #FEFAEF;
        color: #E6A23C;
        padding: 6px;
    }

    .aapp-add-order .get-print {
        width: 100%;
        height: 100%;
    }

    .app-add-order .el-table__header-wrapper th {
        background-color: #f5f7fa;
    }

    .app-add-order .el-dialog__body {
        padding: 5px 20px 10px;
    }

    .app-add-order .title-box {
        margin: 15px 0;
    }

    .app-add-order .title-box .text {
        background-color: #FEFAEF;
        color: #E6A23C;
        padding: 6px;
    }
</style>
<template id="app-add-order">
    <div class="app-add-order">
        <el-dialog title="新建订单" :visible.sync="orderDialogVisible" width="35%">
            <!-- -->
            <el-dialog title="提示" :visible.sync="expressPriceVisible" width="10%" append-to-body>
                <el-form label-width="130px"
                         @submit.native.prevent="prev"
                         :model="expressForm"
                         size="small"
                         :rules="expressFormRules"
                         ref="expressForm">
                    <el-form-item prop="express_price_change" label="应收运费">
                        <el-input size="small"
                                  v-model="expressForm.express_price_change"
                                  autocomplete="off"
                        >
                            <template slot="append">元</template>
                        </el-input>
                        <div style="margin-left: -70px">请确定线下已收取运费，是否继续新建订单？</div>
                    </el-form-item>
                </el-form>
                <div slot="footer" style="text-align: right">
                    <el-button size="small" :loading="btnLoading" @click="expressPriceVisible = false">取 消</el-button>
                    <el-button size="small" :loading="btnLoading" type="primary" @click="expressSubmit">确 定</el-button>
                </div>
            </el-dialog>
            <!-------------------------------------------------->
            <el-dialog title="选择地区" :visible.sync="addressDialogVisible" width="50%" append-to-body>
                <div style="margin-bottom: 1rem;">
                    <app-district :edit="area" :radio="true" @selected="selectDistrict" :level="3"></app-district>
                    <div style="text-align: right;margin-top: 1rem;">
                        <el-button type="primary" @click="districtConfirm">
                            确定选择
                        </el-button>
                    </div>
                </div>
            </el-dialog>
            <!-------------------------------------------------->
            <div class="title-box">
                <div class="title-box">
                    <span class="text">商品信息</span>
                </div>
                <el-table
                        v-if="editForm && editForm.mch_list"
                        ref="multipleTable"
                        :data="editForm.mch_list[0].goods_list"
                        tooltip-effect="dark"
                        style="width: 100%"
                        max-height="250"
                        @selection-change="handleSelectionChange">
                    <el-table-column
                            type="selection"
                            width="55">
                    </el-table-column>
                    <el-table-column
                            label="图片"
                            width="60">
                        <template slot-scope="scope">
                            <app-image width="30" height="30" :src="scope.row.cover_pic"></app-image>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="名称"
                            show-overflow-tooltip>
                        <template slot-scope="scope">
                            <span>{{scope.row.name}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="num"
                            label="数量"
                            width="80"
                            show-overflow-tooltip>
                    </el-table-column>
                    <el-table-column
                            label="规格"
                            width="120"
                            show-overflow-tooltip>
                        <template slot-scope="scope">
                          <span v-for="(attrItem,key) in scope.row.attr_list" :key="key">
                                {{attrItem.attr_group_name}}:{{attrItem.attr_name}}
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
                <!-------------------------------------------------->
                <div class="title-box">
                    <el-form label-width="130px"
                             @submit.native.prevent="prev"
                             :model="queryForm"
                             :rules="queryFormRules"
                             ref="queryForm">
                        <div class="title-box">
                            <span class="text">用户信息</span>
                        </div>
                        <el-form-item label="用户昵称">{{userInfo.nickname}}</el-form-item>
                        <el-form-item prop="address_name" label="收货人姓名">
                            <el-input size="small"
                                      v-model="queryForm.address.name"
                                      placeholder="请输入收件人姓名"
                                      autocomplete="off"
                                      maxlength="20"
                                      show-word-limit
                            ></el-input>
                        </el-form-item>
                        <el-form-item prop="address_mobile" label="收货人电话">
                            <el-input size="small"
                                      v-model="queryForm.address.mobile"
                                      placeholder="请输入收件人电话"
                                      autocomplete="off"
                            ></el-input>
                        </el-form-item>
                        <!-------------------------------------------------->
                        <template v-if="editForm && editForm.mch_list[0].delivery.send_type !== 'none'">
                            <div class="title-box">
                                <span class="text">选择发货方式</span>
                            </div>
                            <el-form-item prop="send_type" label="发货方式">
                                <template v-if="editForm" v-for="item of editForm.mch_list[0].delivery.send_type_list">
                                    <el-radio
                                            @change="orderPreview"
                                            v-model="queryForm.list[0].send_type" :label="item.value">
                                        {{item.name}}
                                    </el-radio>
                                </template>
                            </el-form-item>
                            <!-------------------- 快递配送 -------------------->
                            <template v-if="queryForm.list[0].send_type == 'express'">
                                <el-form-item prop="address" label="省市区">
                                    <template v-if="queryForm.address.province">
                                        <el-tag type="info" style="margin:5px;margin-top:0;border:0">
                                            {{queryForm.address.province}}
                                        </el-tag>
                                        <el-tag type="info" style="margin:5px;margin-top:0;border:0">
                                            {{queryForm.address.city}}
                                        </el-tag>
                                        <el-tag type="info" style="margin:5px;margin-top:0;border:0">
                                            {{queryForm.address.district}}
                                        </el-tag>
                                    </template>
                                    <el-button @click="districtChoose" size="small">选择</el-button>
                                </el-form-item>
                                <el-form-item prop="detail" label="详细地址">
                                    <el-input size="small" placeholder="请填写详细地址"
                                              v-model="queryForm.address.detail" autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                                <el-form-item label="运费">
                                    <template slot-scope="scope">
                                        <span style="color:#ff4544">￥{{editForm.mch_list[0].express_price}}</span>
                                    </template>
                                </el-form-item>
                            </template>
                            <!-------------------- 到店自提 -------------------->
                            <template v-if="queryForm.list[0].send_type == 'offline'">
                                <el-form-item prop="store_id" label="门店选择">
                                    <el-tag v-if="queryForm.list[0].store_id" @close="handleStoreClose" closable
                                            disable-transitions>
                                        {{queryForm.list[0].store_name}}
                                    </el-tag>
                                    <app-select-store v-else @change="changeStore">
                                        <el-button size="small">选择门店</el-button>
                                    </app-select-store>
                                </el-form-item>
                            </template>
                            <!-------------------- 同城配送 -------------------->
                            <template v-if="queryForm.list[0].send_type == 'city'">
                                <el-form-item prop="latitude" label="选择地址">
                                    <div>{{queryForm.address.detail}}</div>
                                    <app-map @map-submit="mapEvent"
                                             :address="queryForm.address.detail"
                                             :lat="queryForm.address.latitude"
                                             :long="queryForm.address.longitude">
                                        <el-button size="mini">定位</el-button>
                                    </app-map>
                                </el-form-item>
                                <el-form-item label="运费">
                                    <template slot-scope="scope">
                                        <span style="color:#ff4544">￥{{editForm.mch_list[0].express_price}}</span>
                                    </template>
                                </el-form-item>
                            </template>
                        </template>
                    </el-form>
                </div>
                <div slot="footer" class="dialog-footer" style="text-align: right">
                    <el-button size="small" :loading="btnLoading" @click="orderDialogVisible = false">取 消</el-button>
                    <el-button size="small" :loading="btnLoading" type="primary" @click="orderSubmit">确 定</el-button>
                </div>
        </el-dialog>
    </div>
</template>

<script>
    Vue.component('app-add-order', {
        template: '#app-add-order',
        props: {},
        data() {
            return {
                selectId: [],//选中id
                userInfo: {
                    id: '',
                    nickname: '',
                },
                expressFormRules: {},
                expressForm: {
                    express_price_change: '',
                },
                expressPriceVisible: false,
                queryForm: {
                    user_id: 0,
                    address: {
                        name: '',
                        longitude: '',
                        location: '',
                        mobile: '',
                        province_id: 0,
                        province: '',
                        city_id: 0,
                        city: '',
                        district_id: 0,
                        district: '',
                        detail: '',
                        latitude: '',
                    },
                    list: [{
                        mch_id: 0,
                        goods_list: [{
                            id: 0,
                            attrs: [],
                            num: 1,
                            cat_id: 0,
                            goods_attr_id: 0,
                            cart_id: 0,
                            form_data: []
                        }],
                        distance: 0,
                        remark: "",
                        order_form: [],
                        use_integral: 0,
                        user_coupon_id: 0,
                        store_id: 0,
                        store_name: '',
                        store: [],
                        send_type: "express"
                    }],
                    address_id: 0,
                    send_type: ""
                },
                queryFormRules: {
                    address_name: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.queryForm.address.name) {
                                    callback();
                                } else {
                                    callback('收件人姓名不能为空');
                                }

                            }, trigger: 'change'
                        },
                    ],
                    address_mobile: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.queryForm.address.mobile) {
                                    callback();
                                } else {
                                    callback('收件人电话不能为空');
                                }

                            }, trigger: 'change'
                        },
                    ],
                    address: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.queryForm.address.province) {
                                    callback();
                                } else {
                                    callback('省市区不能为空');
                                }

                            }, trigger: 'change'
                        },
                    ],
                    detail: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.queryForm.address.detail) {
                                    callback();
                                } else {
                                    callback('详细地址不能为空');
                                }

                            }, trigger: 'change'
                        },
                    ],
                    latitude: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.queryForm.address.longitude && this.queryForm.address.latitude) {
                                    callback();
                                } else {
                                    callback('经纬度不能为空');
                                }

                            }, trigger: 'change'
                        },
                    ],
                    store_id: [
                        {
                            required: true, type: 'string', validator: (rule, value, callback) => {
                                if (this.queryForm.list[0].store_id) {
                                    callback();
                                } else {
                                    callback('门店不能为空');
                                }

                            }, trigger: 'change'
                        },
                    ]
                },
                /*******************************地址start*****************************/
                area: [],
                addressTemp: [],
                addressDialogVisible: false,

                sendVisible: false,
                /*******************************地址start*****************************/
                btnLoading: false,
                editForm: null,
                orderDialogVisible: false,
                sendOrder: {},
            }
        },
        mounted() {
        },
        methods: {
            //为空判断
            handleSelectionChange(val) {
                this.selectId = val;
            },
            /**  地址  */
            districtChoose() {
                let para = {
                    id: this.queryForm.address.district_id,
                    name: this.queryForm.address.district
                }
                this.area = [];
                this.addressTemp = [];
                this.addressDialogVisible = true;
            },
            selectDistrict(e) {
                this.addressTemp = e;
            },
            districtConfirm(e) {
                if (this.addressTemp.length === 0) {
                    this.$message({
                        type: 'warning',
                        message: '请选择地区'
                    });
                    return false;
                }
                this.queryForm.address.province_id = this.addressTemp[0].id;
                this.queryForm.address.province = this.addressTemp[0].name;
                this.queryForm.address.city_id = this.addressTemp[1].id;
                this.queryForm.address.city = this.addressTemp[1].name;
                this.queryForm.address.district_id = this.addressTemp[2].id;
                this.queryForm.address.district = this.addressTemp[2].name;
                this.addressDialogVisible = false;
                this.orderPreview();
            },

            /*************** 门店 *********/
            handleStoreClose() {
                Object.assign(this.queryForm.list[0], {
                    store_id: '',
                    store_name: '',
                })
            },
            changeStore(e) {
                Object.assign(this.queryForm.list[0], {
                    store_id: e.id,
                    store_name: e.name,
                })
            },
            /*************** 定位 *********/
            mapEvent(e) {
                let {lat, long, address} = e;
                this.queryForm.address.detail = address;
                this.queryForm.address.longitude = long;
                this.queryForm.address.latitude = lat;
                this.orderPreview();
            },

            /*************************************************/
            expressSubmit() {
                Object.assign(this.queryForm, Object.assign({}, this.expressForm));
                this.orderSubmit('submit');
            },
            orderSubmit(e) {
                this.$refs.queryForm.validate((valid) => {
                    if (valid) {
                        if (this.editForm.mch_list[0].express_price > 0
                            && (this.queryForm.list[0].send_type == 'express' || this.queryForm.list[0].send_type === 'city')
                            && e !== 'submit'
                        ) {
                            this.expressForm.express_price_change = this.editForm.mch_list[0].express_price;
                            this.expressPriceVisible = true;
                            return;
                        }
                        this.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/fission/mall/order/submit'
                            },
                            data: {
                                form_data: JSON.stringify(this.queryForm),
                            },
                            method: 'post'
                        }).then(e => {
                            if (e.data.code === 0) {
                                this.payData(Object.assign({user_id: this.userInfo.id}, e.data.data));
                            } else {
                                this.btnLoading = false;
                                this.$message.error(e.data.msg);
                            }
                        });
                    }
                });
            },

            payData(data) {
                request({
                    params: {
                        r: 'plugin/fission/mall/order/pay-data'
                    },
                    data,
                    method: 'post'
                }).then(e => {
                    if (e.data.code === 0) {
                        if (e.data.data.retry === 1) {
                            this.payData(data);
                        } else {
                            this.btnLoading = false;
                            this.orderDialogVisible = false;
                            this.expressPriceVisible = false;
                            this.$emit('success', e.data.data);
                        }
                    } else {
                        this.btnLoading = false;
                        this.$message.error(e.data.msg);
                    }
                });
            },
            orderPreview() {
                request({
                    params: {
                        r: 'plugin/fission/mall/order/preview'
                    },
                    data: {
                        form_data: JSON.stringify(this.queryForm),
                    },
                    method: 'post'
                }).then(e => {
                    if (e.data.code === 0) {
                        this.orderDialogVisible = true;
                        this.editForm = e.data.data;
                        this.$nextTick(item => {
                            this.$refs['queryForm'].clearValidate();
                        })
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            openDialog(userInfo, preview_data) {
                this.userInfo = userInfo;
                this.queryForm = preview_data;
                Object.assign(this.queryForm, {user_id: this.userInfo.id})
                this.orderPreview();
            },
        }
    })
</script>