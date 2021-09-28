<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
?>
<style>
    .form_box {
        background-color: #fff;
        padding: 30px 20px;
        padding-right: 40%;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 15px;
    }

    .copy-btn {
        margin-left: 20px;
    }
    .copy-btn+.el-button {
        margin-left: 5px;
    }
    .tip {
        padding-left: 14px;
        margin-bottom: 30px;
    }
    .tip span {
        margin-left: 10px;
    }
    .text-button {
        color: #3399ff;
        cursor: pointer;
    }
    .dialog-text {
        margin-bottom: 20px;
        font-size: 18px;
        display: block;
        text-decoration: none;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span>服务器配置</span>
        </div>
        <div class="form_box">
            <div class="tip" flex="dir:left cross:center">
                <div>请将以下信息填入微信公众号，并启用服务器配置。完成操作后，再保存此页面。</div>
                <span class="text-button" @click="dialogVisible = true">查看引导</span>
            </div>
            <el-form :model="ruleForm"
                     ref="ruleForm"
                     label-width="172px"
                     size="small">
                <el-form-item label="服务器地址(URL)" prop="server">
                    <div flex="dir:left cross:center" style="height: 32px;">
                        <div id="server">{{ruleForm.server}}</div>
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" data-clipboard-target="#server">
                            <el-tooltip effect="dark" content="复制" placement="top">
                                <img src="statics/img/plugins/copy.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                </el-form-item>
                <el-form-item label="令牌(Token)" prop="token">
                    <div flex="dir:left cross:center" style="height: 32px;">
                        <div id="token">{{ruleForm.token}}</div>
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" data-clipboard-target="#token">
                            <el-tooltip effect="dark" content="复制" placement="top">
                                <img src="statics/img/plugins/copy.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button circle size="mini" type="text" @click="ruleForm.token = randomWord(32)">
                            <el-tooltip effect="dark" content="重置" placement="top">
                                <img src="statics/img/mall/order/refresh.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                </el-form-item>
                <el-form-item label="消息加解密密钥(EncodingAESKey)" prop="encodingAESKey">
                    <div flex="dir:left cross:center">
                        <div id="key">{{ruleForm.encodingAESKey}}</div>
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" data-clipboard-target="#key">
                            <el-tooltip effect="dark" content="复制" placement="top">
                                <img src="statics/img/plugins/copy.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button circle size="mini" type="text" @click="ruleForm.encodingAESKey = randomWord(43)">
                            <el-tooltip effect="dark" content="重置" placement="top">
                                <img src="statics/img/mall/order/refresh.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                </el-form-item>
            </el-form>
        </div>
        <el-button class='button-item' :loading="btnLoading" @click="store" type="primary" size="small">保存</el-button>
        <el-dialog title="查看引导" :visible.sync="dialogVisible" width="867px">
            <a href="https://mp.weixin.qq.com/" target="_blank" class="text-button dialog-text">进入微信公众平台-开发-基本配置-服务器配置</a>
            <img src="statics/img/mall/server.png" alt="">
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="dialogVisible = false">我知道了</el-button>
            </span>
        </el-dialog>
    </el-card>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/js/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.copy-btn');

    var self = this;
    clipboard.on('success', function (e) {
        self.ELEMENT.Message.success('复制成功');
        e.clearSelection();
    });
    clipboard.on('error', function (e) {
        self.ELEMENT.Message.success('复制失败，请手动复制');
    });
    const app = new Vue({
        el: '#app',
        data() {
            return {
                dialogVisible: false,
                ruleForm: {},
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            randomWord(length){
                var str = "",
                    arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
             
                for(var i=0; i<length; i++){
                    pos = Math.round(Math.random() * (arr.length-1));
                    str += arr[pos];
                }
                return str;
            },
            store() {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: 'mall/wechat/server'
                    },
                    method: 'post',
                    data: {
                        token: self.ruleForm.token,
                        encodingAESKey: self.ruleForm.encodingAESKey,
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
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: '/mall/wechat/server'
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data;
                        self.ruleForm.token = e.data.data.token ? e.data.data.token : this.randomWord(32);
                        self.ruleForm.encodingAESKey = e.data.data.encodingAESKey ? e.data.data.encodingAESKey : this.randomWord(43);
                    } else {
                        if(e.data.msg == '微信公众平台信息尚未配置。') {
                            this.$alert(e.data.msg, '提示', {
                                confirmButtonText: '确定',
                                callback: action => {
                                    navigateTo({
                                        r:'mall/wechat/setting'
                                    })
                                }
                            });
                        }else {
                            self.$message.error(e.data.msg);
                        }
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
