<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
?>

<style>
    .nav-box {
        width: 220px;
        height: 45px;
        line-height: 45px;
        border: 1px solid #000000;
        text-align: center;
    }

    .bottom-icon {
        width: 80px;
        height: 80px;
        border: 1px solid #eeeeee;
    }

    .nav-action {
        cursor: pointer;
    }

    .nav-icon {
        width: 30px;
        height: 30px;
    }

    .nav-add {
        border: 1px dashed #eeeeee;
        cursor: pointer;
    }

    .nav-add-icon {
        font-size: 50px;
        color: #eeeeee;
    }

    .form-body {
        padding: 24px;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-body .el-form-item__content {
        width: 485px;
    }

    .button-item {
        padding: 9px 25px;
    }

    .mobile {
        width: 404px;
        height: 736px;
        border-radius: 30px;
        background-color: #fff;
        padding: 33px 12px 33px;
        margin-right: 24px;
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

    .screen {
        border: 2px solid #F3F5F6;
        height: 670px;
        width: 380px;
        margin: 0 auto;
        position: relative;
        background-color: #F7F7F7;
    }

    .screen .foot {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 376px;
        background-color: #f6f6f6;
        border: 1px solid #e2e2e2;
        /*padding: 5px 25px;*/
        height: 50px;
        /*display: flex;*/
        /*text-align: center;*/
        /*justify-content: space-between;*/
        /*font-size: 11px;*/
    }

    .screen .foot .keyword img {
        width: 22px;
        height: 22px;
    }
    .screen .foot .keyword {
        height: 36px;
        width: 42px;
        border-right: 2px solid #efefef;
        flex-shrink: 0;
    }

    .screen .foot .keyword+.menus-list {
        flex-grow: 1;
        height: 50px;
        flex-shrink: 0;
    }

    .screen .foot .keyword+.menus-list .menu-item {
        flex-grow: 1;
        flex-shrink: 0;
        font-size: 15px;
        flex:1;
        position: relative;
    }

    .screen .foot .keyword+.menus-list .menu-item .close-btn {
        position: absolute;
        right: 0;
        top: 0;
        padding: 5px;
        width: 19px;
        height: 19px;
        display: none;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu:hover {
        color: #409EFF;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu:hover>.close-btn {
        display: block;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu .sub-menu-item:hover {
        color: #409EFF;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu .sub-menu-item:hover>.close-btn {
        display: block;
    }

    .screen .foot .keyword+.menus-list .menu-item .line {
        position: absolute;
        right: 0;
        top: 6px;
        width: 2px;
        height: 38px;
        background-color: #efefef;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu {
        cursor: pointer;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border: 1px solid transparent;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu.active {
        border: 1px solid #409EFF;
        color: #409EFF;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu {
        position: absolute;
        bottom: 70px;
        width: 100%;
        left: 0;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu:before{
        box-sizing: content-box;
        width: 0px;
        height: 0px;
        position: absolute;
        bottom: -15px;
        left:50%;
        margin-left: -7px;
        padding:0;
        border-bottom:8px solid transparent;
        border-top:8px solid #f6f6f6;
        border-left:8px solid transparent;
        border-right:8px solid transparent;
        display: block;
        content:'';
        z-index: 12;
    }
    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu:after{
        box-sizing: content-box;
        width: 0px;
        height: 0px;
        position: absolute;
        bottom: -18px;
        left:50%;
        margin-left: -8px;
        padding:0;
        border-bottom:9px solid transparent;
        border-top:9px solid #e2e2e2;
        border-left:9px solid transparent;
        border-right:9px solid transparent;
        display: block;
        content:'';
        z-index:10
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu .sub-menu-item {
        width: 100%;
        height: 50px;
        background-color: #f6f6f6;
        border: 1px solid #e2e2e2;
        position: relative;
        margin-top: -1px;
        color: #353535;
    }

    .screen .foot .keyword+.menus-list .menu-item.toggle-menu .sub-menu .sub-menu-item.active {
        border: 1px solid #409EFF;
        color: #409EFF;
        z-index: 2;
    }

    .screen .foot .keyword+.menus-list .menu-item.add-menu {
        cursor: pointer;
        height: 50px;
        line-height: 50px;
        text-align: center;
    }
    .screen .foot .keyword+.menus-list .menu-item.add-menu img {
        width: 16px;
        height: 16px;
    }

    .screen .foot .nav-icon + div {
        margin-top: -10px;
    }

    .title {
        padding: 18px 20px;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }
    .tips {
        margin-bottom: 20px;
        padding: 22px 0;
        background-color: #f3f5f7;
    }
    .tips img {
        width: 36px;
        height: 36px;
        margin: 0 20px;
    }
    .content-input .el-textarea .el-textarea__inner{
        resize: none;
    }
    .customize-share-title {
         margin-top: 10px;
         width: 80px;
         height: 80px;
         position: relative;
         cursor: move;
     }
    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="cardLoading">
        <div slot="header">
            <div>
                <span>菜单设置</span>
            </div>
        </div>
        <div style="display: flex;">
            <div class="mobile">
                <div class="screen">
                    <div class="head-bar" flex="main:center cross:center">
                        <div>公众号</div>
                    </div>
                    <div class="foot" flex="dir:left cross:center">
                        <div class="keyword" flex="main:center cross:center">
                            <img src="statics/img/mall/wechat/keyword.png" alt="">
                        </div>
                        <div class="menus-list" flex="dir:left">
                            <el-button v-if="list.length == 0" @click="addMenu" class="menu-item" type="text" icon="el-icon-plus">添加菜单</el-button>
                            <div @click="showSubMenu(item,index)" class="menu-item toggle-menu" :class="index == activeIndex ? 'active' : ''" v-for="(item,index) in list" :key="index">
                                {{item.name ? item.name : '菜单名称'}}
                                <div v-if="item.sub_button && index == activeIndex" class="sub-triangle"></div>
                                <img class="close-btn" @click.stop="delMenuItem(index)" src="statics/img/mall/wechat/icon-close.png" alt="">
                                <div class="line"></div>
                                <div v-if="item.sub_button && index == activeIndex" class="sub-menu">
                                    <div @click.stop="showSubMenuItem(sub,idx)" v-for="(sub,idx) in item.sub_button" class="sub-menu-item" :key="idx"  :class="idx == activeSubIndex ? 'active' : ''">
                                        {{sub.name ? sub.name : '子菜单名称'}}
                                    <img class="close-btn" @click.stop="delSubMenuItem(idx,index)" src="statics/img/mall/wechat/icon-close.png" alt="">
                                    </div>
                                    <div @click.stop="addSubMenu(index)" v-if="item.sub_button.length < 5" class="sub-menu-item add-menu" flex="main:center cross:center">
                                        <img src="statics/img/mall/wechat/add.png" alt="">
                                    </div>
                                </div>
                            </div>
                            <div @click="addMenu" v-if="list.length == 1 || list.length == 2" class="menu-item add-menu" flex="main:center cross:center">
                                <img src="statics/img/mall/wechat/add.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width: 100%;">
                <el-form @submit.native.prevent class="form-body" :model="form" :rules="rules" size="small" ref="form" label-width="120px">
                    <div class="tips" flex="dir:left cross:center">
                        <img src="statics/img/mall/wechat/tips.png" alt="">
                        <div>
                            <div>由于微信接口延迟，菜单修改后需点击“提交发布”，最长可能需要30分钟才会更新至公众号。点击“提交发布”前的操作仅在当前页面生效。</div>
                            <div>如需公众号菜单即时生效，可先取消关注，再重新关注。</div>
                        </div>
                    </div>
                    <div v-if="form">
                        <div v-if="form.sub_button && form.sub_button.length > 0" style="padding: 12px 0 24px 40px">
                            已为“{{form.name ? form.name : '菜单名称'}}”添加了子菜单，无法设置其他内容。
                        </div>
                        <el-form-item label="菜单名称" prop="name">
                            <el-input @input="checkName" v-model.trim="form.name" placeholder="仅支持中英文和数字，字数不超过4个汉字或8个字母"></el-input>
                        </el-form-item>
                        <div v-if="!form.sub_button || form.sub_button.length == 0">
                            <el-form-item required label="菜单消息" prop="type">
                                <el-radio-group v-model="form.type">
                                    <el-radio label="click">发送消息</el-radio>
                                    <el-radio label="view">跳转网页</el-radio>
                                    <el-radio label="miniprogram">跳转小程序</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item required label="消息内容" v-if="form.type == 'click'" prop="reply_type">
                                <el-radio-group v-model="form.reply_type">
                                    <el-radio :label="0">文字</el-radio>
                                    <el-radio :label="1">图片</el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item class="content-input" v-if="form.type == 'click'" prop="content">
                                <el-input v-if="form.reply_type == 0" :rows="8" type="textarea" v-model="form.content"></el-input>
                                <div v-else>
                                    <app-attachment v-model="form.picurl" :multiple="false" :max="1">
                                        <el-button size="mini">选择图片</el-button>
                                    </app-attachment>
                                    <div class="customize-share-title">
                                        <app-image mode="aspectFill" width='80px' height='80px'
                                                   :src="form.picurl ? form.picurl : ''"></app-image>
                                        <el-button v-if="form.picurl" class="del-btn" size="mini"
                                                   type="danger" icon="el-icon-close" circle
                                                   @click="form.picurl = ''"></el-button>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="跳转网页" v-if="form.type == 'view'" prop="url">
                                <el-input placeholder="请填写以http://或https://开头的有效链接" size="small" v-model="form.url">
                                </el-input>
                            </el-form-item>
                            <el-form-item label="APPID" v-if="form.type == 'miniprogram'" prop="appid">
                                <el-input placeholder="请确保公众号与小程序已绑定" size="small" v-model="form.appid">
                                </el-input>
                            </el-form-item>
                            <el-form-item label="页面地址" v-if="form.type == 'miniprogram'" prop="page">
                                <el-input placeholder="请填写跳转页面的小程序访问路径" size="small" v-model="form.page">
                                </el-input>
                                <app-pick-link @selected="selectPageUrl">
                                    <el-button type="text" size="mini">选择链接</el-button>
                                </app-pick-link>
                            </el-form-item>
                            <el-form-item label="备用网址" v-if="form.type == 'miniprogram'" prop="url">
                                <el-input placeholder="无法打开小程序时跳转的页面" size="small" v-model="form.url">
                                </el-input>
                            </el-form-item>
                        </div>
                    </div>
                </el-form>
                <el-button v-if="form" class="button-item" :loading="btnLoading" type="primary" @click="store('form')"
                           size="small">提交发布
                </el-button>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            var checkUrl = (rule, value, callback) => {
                if (this.form.type === 'view') {
                    if(!this.form.url) {
                        callback(new Error('请填写跳转网页'));
                    }else if(this.form.url.indexOf('http://') == -1 && this.form.url.indexOf('https://') == -1) {
                        callback(new Error('请填写以http://或https://开头的有效链接'));
                    } else {
                        callback();
                    }
                } else if (this.form.type === 'miniprogram' && !this.form.url) {
                    callback(new Error('请填写无法打开小程序时跳转的页面'));
                } else {
                    callback();
                }
            };
            return {
                ruleForm: {
                    name: '',
                    type: 'click',
                    reply_type: 0,
                    key: '',
                    content: '',
                    url: '',
                    appid: '',
                    page: ''
                },
                form: null,
                formItem: {},
                activeIndex: -1,
                activeSubIndex: -1,
                list: [],
                rules: {
                    name: [
                        {required: true, message: '请填写菜单名称', trigger: 'blur'},
                    ],
                    url: [
                        {required: true, validator: checkUrl, trigger: 'blur'},
                    ],
                    appid: [
                        {required: true, message: '请填写APPID', trigger: 'blur'},
                    ],
                    page: [
                        {required: true, message: '请填写跳转页面的小程序访问路径', trigger: 'blur'},
                    ]
                },
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            checkName(value) {
                this.form.name = value.replace(/[^\w\u4E00-\u9FA5]/g, '');
                let num = 0;
                for(let index in value) {
                    if(/[\u4e00-\u9fa5]/.test(value[index])) {
                        num += 2;
                    }else {
                        num++;
                    }
                    if(num > 8) {
                        value = value.substr(0, index);
                        this.form.name = value.replace(/[^\w\u4E00-\u9FA5]/g, '');
                        break;
                    }
                }
            },
            selectPageUrl(e) {
                this.form.page = e[0].new_link_url;
                this.$forceUpdate();
            },
            showSubMenu(item,index) {
                this.activeIndex = index;
                this.form = item;
                if(!this.list[index].sub_button) {
                    this.list[index].sub_button = [];
                }
            },
            showSubMenuItem(item,index) {
                console.log(item)
                this.form = null;
                this.activeSubIndex = index;
                this.$nextTick(()=>{
                    this.form = item;
                })
            },
            delMenuItem(index) {
                this.list.splice(index,1)
                this.$forceUpdate();
            },
            delSubMenuItem(idx,index) {
                this.list[index].sub_button.splice(idx,1)
                this.$forceUpdate();
            },
            addMenu() {
                let item = JSON.parse(JSON.stringify(this.ruleForm))
                this.list.push(item)
            },
            addSubMenu(index) {
                let item = JSON.parse(JSON.stringify(this.ruleForm))
                this.list[index].sub_button.push(item);
                this.$forceUpdate();
            },
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/wechat/menus'
                            },
                            method: 'post',
                            data: {
                                list: self.list,
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
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/wechat/menus',
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.list = e.data.data;
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
            },
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
