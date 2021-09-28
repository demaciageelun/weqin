<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
?>
<style>
    .form-body {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-button {
        margin: 0!important;
    }

    .form-button .el-form-item__content {
        margin-left: 0!important;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>
<div id="app" v-cloak>
    <el-card class="box-card" v-loading="cardLoading" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span>店铺设置</span>
                <el-button @click="dialogFormVisible = true" type="primary" style="float: right;margin-top: -5px" size="small">
                    修改密码
                </el-button>
            </div>
        </div>
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="140px" size="small">
            <el-row>
                <el-card style="margin-bottom: 10px" shadow="never">
                    <div slot="header">
                        <span>基本信息</span>
                    </div>
                    <el-col :span="12">
                        <el-form-item label="商户账号" prop="username">
                            <el-input disabled v-if="ruleForm.mchUser"
                                      v-model="ruleForm.mchUser.username"></el-input>
                        </el-form-item>
                        <el-form-item label="联系人" prop="realname">
                            <el-input v-model="ruleForm.realname"></el-input>
                        </el-form-item>
                        <el-form-item label="联系电话" prop="mobile">
                            <el-input v-model="ruleForm.mobile"></el-input>
                        </el-form-item>
                        <el-form-item label="微信号" prop="wechat">
                            <el-input v-model="ruleForm.wechat"></el-input>
                        </el-form-item>
                        <el-form-item label="所售类目" prop="mch_common_cat_id">
                            <el-select v-model="ruleForm.mch_common_cat_id" placeholder="请选择">
                                <el-option
                                        v-for="item in commonCats"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="手续费(千分之)" prop="transfer_rate">
                            <label slot="label">手续费(千分之)
                                <el-tooltip class="item" effect="dark"
                                            content="商户每笔订单交易金额扣除的手续费，请填写0~1000范围的整数"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <el-input min="0" max="1000" type="number" disabled
                                      v-model.number="ruleForm.transfer_rate">
                            </el-input>
                        </el-form-item>
                    </el-col>
                </el-card>
                <el-card style="margin-bottom: 10px" shadow="never">
                    <div slot="header">
                        <span>商户信息</span>
                    </div>
                    <el-col :span="12">
                        <el-form-item label="店铺名称" prop="name">
                            <el-input v-model="ruleForm.name"></el-input>
                        </el-form-item>
                        <el-form-item label="店铺Logo" prop="logo">
                            <app-attachment :multiple="false" :max="1" v-model="ruleForm.logo">
                                <el-tooltip class="item"
                                            effect="dark"
                                            content="建议尺寸:100 * 100"
                                            placement="top">
                                    <el-button size="mini">选择文件</el-button>
                                </el-tooltip>
                            </app-attachment>
                            <app-image mode="aspectFill" width='80px' height='80px' :src="ruleForm.logo">
                            </app-image>
                        </el-form-item>
                        <el-form-item label="店铺背景图" prop="bg_pic_url">
                            <app-attachment :multiple="false" :max="1" @selected="picUrl">
                                <el-tooltip class="item"
                                            effect="dark"
                                            content="建议尺寸:750 * 300"
                                            placement="top">
                                    <el-button size="mini">选择文件</el-button>
                                </el-tooltip>
                            </app-attachment>
                            <app-image mode="aspectFill" width='80px' height='80px'
                                       v-if="ruleForm.bg_pic_url"
                                       :src="ruleForm.bg_pic_url.length ? ruleForm.bg_pic_url[0].pic_url : ''">
                            </app-image>
                        </el-form-item>
                        <el-form-item label="营业状态" prop="is_open">
                            <el-radio v-model="ruleForm.is_open" :label="1">营业
                            </el-radio>
                            <el-radio v-model="ruleForm.is_open" :label="2">打烊
                            </el-radio>
                        </el-form-item>
                        <el-form-item v-if="ruleForm.is_open == 1" label="营业时间" prop="open_type">
                            <div>
                                <el-radio v-model="ruleForm.open_type" :label="1">全天24小时</el-radio>
                                <el-radio v-model="ruleForm.open_type" :label="2">自定义时间</el-radio>
                            </div>
                            <div v-if="ruleForm.open_type == 2" style="margin-top: 10px">
                                <el-checkbox-group v-model="ruleForm.week_list" size="mini">
                                    <el-checkbox label="1" size="mini">周一</el-checkbox>
                                    <el-checkbox label="2" size="mini">周二</el-checkbox>
                                    <el-checkbox label="3" size="mini">周三</el-checkbox>
                                    <el-checkbox label="4" size="mini">周四</el-checkbox>
                                    <el-checkbox label="5" size="mini">周五</el-checkbox>
                                    <el-checkbox label="6" size="mini">周六</el-checkbox>
                                    <el-checkbox label="7" size="mini">周日</el-checkbox>
                                </el-checkbox-group>
                            </div>
                            <div v-if="ruleForm.open_type == 2" style="margin-top: 10px">
<!--                                 <div v-for="(item,index) in ruleForm.time_list" :key="index" style="margin-bttom: 6px" flex="dir:left cross:center">
                                    <el-time-picker @focus="getTime(index)" v-if="ruleForm.open_type == 2" is-range v-model="item.value" range-separator="至" start-placeholder="开始时间" value-format="HH:mm:ss" end-placeholder="结束时间" placeholder="选择时间范围">
                                    </el-time-picker>
                                    <el-button type="text" size="mini" circle style="margin: 0 20px;" @click.native="deleteTime(index)">
                                        <el-tooltip effect="dark" content="删除" placement="top">
                                            <img src="statics/img/mall/del.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                </div> -->
                                <div v-for="(item,index) in ruleForm.time_list" :key="index" style="margin-bttom: 6px" flex="dir:left cross:center">
                                    <el-time-picker
                                    v-model="item.value[0]"
                                    :picker-options="{
                                    selectableRange: '00:00:00 - 23:59:59'
                                    }"
                                    placeholder="开始时间"
                                    value-format="HH:mm:ss">
                                    </el-time-picker>
                                    <el-time-picker
                                    style="margin: 0 20px;" 
                                    v-model="item.value[1]"
                                    :picker-options="{
                                    selectableRange: '00:00:00 - 23:59:59'
                                    }"
                                    placeholder="结束时间"
                                    value-format="HH:mm:ss">
                                    </el-time-picker>
                                    <el-button type="text" size="mini" circle @click.native="deleteTime(index)">
                                        <el-tooltip effect="dark" content="删除" placement="top">
                                            <img src="statics/img/mall/del.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                </div>
                                <el-button v-if="ruleForm.time_list.length < 3" @click="addTime" style="border-color: #3a8ee6;color: #3a8ee6" plain>+增加时间段</el-button>
                            </div>
                        </el-form-item>
                        <el-form-item v-if="ruleForm.is_open == 2" label="设置自动开业" prop="is_auto_open">
                            <div>
                                <el-radio v-model="ruleForm.is_auto_open" :label="1">不设置自动开业
                                </el-radio>
                                <el-radio v-model="ruleForm.is_auto_open" :label="2">设置自动开业时间
                                </el-radio>
                                <el-date-picker v-model="ruleForm.auto_open_time" type="datetime" placeholder="选择自动开业时间" value-format="yyyy-MM-dd HH:mm:ss"></el-date-picker>
                            </div>
                        </el-form-item>
                        <el-form-item label="店铺地址" prop="address">
                            <el-input v-model="ruleForm.address"></el-input>
                        </el-form-item>
                        <el-form-item label="客服电话" prop="service_mobile">
                            <el-input v-model="ruleForm.service_mobile"></el-input>
                        </el-form-item>
                        <el-form-item prop="latitude_longitude">
                            <label slot="label">经纬度
                                <el-tooltip class="item" effect="dark"
                                            content="点击地图,可获取经纬度，用于店铺距离排序"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <div flex="dir:left">
                                <el-input v-model="ruleForm.latitude_longitude"
                                          placeholder="请输入经纬度,用英文逗号分离">
                                </el-input>
                                <app-map @map-submit="mapEvent"
                                         :address="ruleForm.address"
                                         :lat="ruleForm.store.latitude"
                                         :long="ruleForm.store.longitude">
                                    <el-button size="small">展开地图</el-button>
                                </app-map>
                            </div>
                        </el-form-item>
                        <el-form-item label="主营内容" prop="scope">
                            <el-input type="textarea" :row="4" v-model="ruleForm.scope"></el-input>
                        </el-form-item>
                        <el-form-item label="店铺简介" prop="description">
                            <el-input type="textarea" :row="4" v-model="ruleForm.description"></el-input>
                        </el-form-item>
                    </el-col>
                </el-card>
            </el-row>
            <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small">保存</el-button>
        </el-form>

        <el-dialog title="修改密码" :visible.sync="dialogFormVisible" width="30%">
            <el-form size="small" @submit.native.prevent="" :model="form" :rules="passwordRules" ref="form">
                <el-form-item label="新密码" prop="password">
                    <el-input type="password" v-model="form.password" autocomplete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false">取 消</el-button>
                <el-button :loading="btnLoading" type="primary" @click="updatePassword('form')">确 定</el-button>
            </div>
        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    store: {}
                },
                rules: {
                    realname: [
                        {required: true, message: '联系人', trigger: 'change'},
                    ],
                    mobile: [
                        {required: true, message: '联系人电话', trigger: 'change'},
                    ],
                    name: [
                        {required: true, message: '店铺名称', trigger: 'change'},
                    ],
                    logo: [
                        {required: true, message: '店铺Logo', trigger: 'change'},
                    ],
                    bg_pic_url: [
                        {required: true, message: '店铺背景图', trigger: 'change'},
                    ],
                    address: [
                        {required: true, message: '店铺详细地址', trigger: 'change'},
                    ],
                    service_mobile: [
                        {required: true, message: '客服电话', trigger: 'change'},
                    ],
                    mch_common_cat_id: [
                        {required: true, message: '所售类目', trigger: 'change'},
                    ],
                    latitude_longitude: [
                        {required: true, message: '店铺经纬度', trigger: 'change'},
                    ],
                    scope: [
                        {required: true, message: '主营内容', trigger: 'change'},
                    ],
                    description: [
                        {required: true, message: '店铺简介', trigger: 'change'},
                    ],
                },
                btnLoading: false,
                cardLoading: false,
                activeName: 'first',

                commonCats: [],
                district: [],
                props: {
                    value: 'name',
                    label: 'name',
                    children: 'list'
                },

                dialogFormVisible: false,
                form: {
                    password: '',
                },
                passwordRules: {
                    password: [
                        {required: true, message: '请输入新密码', trigger: 'change'},
                    ],
                }
            }
        },
        methods: {
            addTime() {
                let para = {
                    value: ['','']
                }
                this.ruleForm.time_list.push(para)
            },

            getTime(i) {
                if(this.ruleForm.time_list[i].value[0] == '' && this.ruleForm.time_list[i].value[1] == '') {
                    this.ruleForm.time_list[i].value = [new Date(2020, 10, 10, 8, 0), new Date(2020, 10, 10, 23, 0)];
                }
            },

            deleteTime(index) {
                this.ruleForm.time_list.splice(index,1)
            },
            getDetail() {
                this.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/mch/mall/mch/edit',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code == 0) {
                        this.ruleForm = e.data.data.detail;
                        this.nickname = this.ruleForm.user.nickname;
                    }
                }).catch(e => {
                });
            },
            store(formName) {
                if(this.ruleForm.open_type == 2 && this.ruleForm.week_list.length == 0) {
                    this.$message.error('请选择营业时间');
                    return false
                }
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/mch/manage'
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
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            // 获取类目列表
            getCommonCatList() {
                request({
                    params: {
                        r: 'plugin/mch/mall/common-cat/all-list',
                    },
                }).then(e => {
                    if (e.data.code == 0) {
                        this.commonCats = e.data.data.list;
                    }
                }).catch(e => {
                });
            },
            //地图确定事件
            mapEvent(e, address) {
                let self = this;
                self.ruleForm.latitude_longitude = e.lat + ',' + e.long;
                self.ruleForm.address = e.address;
            },
            updatePassword(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        self.form.id = self.ruleForm.id;
                        request({
                            params: {
                                r: 'plugin/mch/mall/mch/update-password'
                            },
                            method: 'post',
                            data: {
                                form: self.form,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                self.dialogFormVisible = false;
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
            // 店铺背景图
            picUrl(e) {
                if (e.length) {
                    let self = this;
                    self.ruleForm.bg_pic_url = [];
                    e.forEach(function (item, index) {
                        self.ruleForm.bg_pic_url.push({
                            id: item.id,
                            pic_url: item.url
                        });
                    });
                }
            },
        },
        mounted: function () {
            this.getDetail();
            this.getCommonCatList();
        }
    });
</script>
