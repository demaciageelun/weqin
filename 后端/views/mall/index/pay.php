<style>
    .button-item {
        padding: 9px 25px;
    }

    .card-padding {
        margin-bottom: 12px;
    }

    .has-gutter {
        /* element使用 */
        display: none;
    }
</style>
<div id="app" v-cloak>
    <el-card style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="listLoading">
        <div slot="header" class="header-box">
            <span>支付设置</span>
        </div>
        <template v-for="item of permission">
            <el-card shadow="never" class="card-padding">
                <div slot="header">
                    <span v-if="item === 'wxapp'">小程序支付方式设置</span>
                    <span v-if="item === 'wechat'">公众号支付方式设置</span>
                    <span v-if="item === 'mobile'">H5支付方式设置</span>
                </div>
                <el-row>
                    <el-table style="width: 100%"
                              :data="formatData(option,`${item}`)"
                              :span-method="WSpanMethod"
                              border
                    >
                        <el-table-column prop="platform" label="平台" width="280">
                            <template slot-scope="scope">
                                <div style="height: 122px" flex="dir:left cross:center main:center">
                                    <template v-if="item === 'wxapp'">
                                        <app-image height="54px" width="54px"
                                                   src="statics/img/mall/pay-type/3.png"></app-image>
                                        <span style="font-size: 20px;color: #353535;margin-left: 10px">小程序</span>
                                    </template>
                                    <template v-if="item === 'wechat'">
                                        <app-image height="54px" width="54px"
                                                   src="statics/img/mall/pay-type/4.png"></app-image>
                                        <span style="font-size: 20px;color: #353535;margin-left: 10px">公众号</span>
                                    </template>
                                    <template v-if="item === 'mobile'">
                                        <app-image height="54px" width="54px"
                                                   src="statics/img/mall/pay-type/5.png"></app-image>
                                        <span style="font-size: 20px;color: #353535;margin-left: 10px">H5</span>
                                    </template>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column prop="label" label="支付方式">
                            <template slot-scope="scope">
                                <span class="cell" v-if="scope.$index == 0">支付方式</span>
                                <div v-else style="height: 72px" flex="dir:left cross:center">
                                    <template v-if="scope.row.label === 'wx'">
                                        <app-image height="30px" width="30px"
                                                   src="statics/img/mall/pay-type/1.png"></app-image>
                                        <span style="color: #353535;margin-left: 10px">微信支付</span>
                                    </template>
                                    <template v-if="scope.row.label === 'ali'">
                                        <app-image height="30px" width="30px"
                                                   src="statics/img/mall/pay-type/2.png"></app-image>
                                        <span style="color: #353535;margin-left: 10px">支付宝支付</span>
                                    </template>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column prop="value" label="模板名称">
                            <template slot-scope="scope">
                                <span class="cell" v-if="scope.$index == 0">模板名称</span>
                                <div v-else>
                                    <template v-if="scope.row.label === 'wx'">
                                        <el-select v-model="option[item][scope.row.label]" size="small"
                                                   placeholder="请选择支付名称">
                                            <el-option
                                                    v-for="item in wxOption"
                                                    :key="item.label"
                                                    :label="item.label"
                                                    :value="item.value.toString()">
                                            </el-option>
                                        </el-select>
                                    </template>
                                    <template v-if="scope.row.label === 'ali'">
                                        <el-select v-model="option[item][scope.row.label]" size="small"
                                                   placeholder="请选择支付名称">
                                            <el-option
                                                    v-for="item in aliOption"
                                                    :key="item.label"
                                                    :label="item.label"
                                                    :value="item.value.toString()">
                                            </el-option>
                                        </el-select>
                                    </template>

                                    <el-button @click="$navigate({r:'mall/pay-type/edit'},true)"
                                               style="margin-left: 12px"
                                               type="primary"
                                               size="small">添加支付方式
                                    </el-button>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-row>
            </el-card>
        </template>
        <el-button class="button-item" type="primary" size='mini' :loading="btnLoading" @click="onSubmit">保存</el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                listLoading: false,
                btnLoading: false,
                form: {
                    payment_type: [],
                    wechat_payment: [],
                    mobile_payment: []
                },
                option: {},
                permission: [],

                wxOption: [],
                aliOption: [],
            };
        },
        mounted() {
            this.getOption();
            this.getList();
        },
        methods: {
            onSubmit() {
                this.btnLoading = true;
                let para = Object.assign({}, this.option);
                request({
                    params: {
                        r: 'mall/pay-type-setting/edit',
                    },
                    data: para,
                    method: 'POST'
                }).then(e => {
                    this.btnLoading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.btnLoading = false;
                });
            },
            formatData(info, type) {
                let arr = [{
                    platform: '',
                    label: '支付方式',
                    value: '模板名称',
                }];
                for (let index in info[type]) {
                    arr.push({
                        platform: '',
                        label: index,
                        value: ''
                    })
                }
                return arr;
            },
            getOption() {
                request({
                    params: {
                        r: 'mall/pay-type/index',
                        limit: 99999,
                    }
                }).then(e => {
                    if (e.data.code === 0) {
                        let wxOption = [{label: '无', value: ''}];
                        let aliOption = [{label: '无', value: ''}];
                        e.data.data.list.forEach(item => {
                            if (item.type == 2) {
                                aliOption.push({label: item.name, value: item.id});
                            }
                            if (item.type == 1) {
                                wxOption.push({label: item.name, value: item.id});
                            }
                        })
                        this.wxOption = wxOption;
                        this.aliOption = aliOption;
                    }
                })
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/pay-type-setting/edit',
                    }
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        let {option, permission} = e.data.data;
                        this.option = option;
                        this.permission = permission;
                    }
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            WSpanMethod({row, column, rowIndex, columnIndex}) {
                if (columnIndex === 0) {
                    if (rowIndex % 5 === 0) {
                        return {
                            rowspan: 5,
                            colspan: 1
                        };
                    } else {
                        return {
                            rowspan: 0,
                            colspan: 0
                        };
                    }
                }
            }
        },
    });
</script>


