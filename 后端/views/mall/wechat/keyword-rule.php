<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
Yii::$app->loadViewComponent('app-wechat-reply');
?>
<style>
    .form_box {
        background-color: #fff;
        padding: 30px 20px;
        padding-right: 30%;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 15px;
    }
    .form_box .form-item .el-form-item__content .el-input {
        width: 345px;
    }
    .form_box .form-item .select-input .el-input .el-input--suffix {
        width: 124px;
    }
    .add-btn .el-button {
        background-color: #fff;
        padding: 9px 20px;
        border-color: #409EFF;
        color: #409EFF;
    }
    .select-input {
        position: relative;
        margin-bottom: 20px;
    }
    .select-input .del-keyword-btn {
        position: absolute;
        left: 365px;
        bottom: 0;
        padding: 0;
        border: 0;
    }
    .line-text {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" class="el-card-app" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="cardLoading">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <span
                        style="color: #409EFF;cursor: pointer"
                        @click="$navigate({r:'mall/wechat/reply',tab: 'two'})"
                    >关键词回复</span>
                </el-breadcrumb-item>
                <el-breadcrumb-item>{{edit ? '编辑规则':'新建规则'}}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form_box">
            <el-form :model="form" ref="form" :rules="rules" size="small" label-width="150px">
                <el-form-item class="form-item" label="规则名称" prop="name" >
                    <el-input size='small' v-model="form.name" placeholder="请输入规则名称" show-word-limit maxlength="15"></el-input>
                </el-form-item>
                <el-form-item class="form-item" label="关键词" prop="keyword" >
                    <div v-for="(item,index) in form.keyword_list" :key="index" class="select-input">
                        <el-input placeholder="请输入关键词" v-model="item.name" show-word-limit maxlength="15">
                            <el-select v-model="item.status" slot="prepend" placeholder="请选择">
                                <el-option label="全匹配" :value="0"></el-option>
                                <el-option label="模糊匹配" :value="1"></el-option>
                            </el-select>
                        </el-input>
                        <el-button v-if="index > 0" class="del-keyword-btn" size="small" type="text" @click="destroy(index)" circle>
                            <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                    <div class="add-btn">
                        <el-button plain @click="addKeyword" size="small">+添加关键词</el-button>
                    </div>
                </el-form-item>
                <el-form-item label="回复方式" prop="type">
                    <el-radio-group v-model="form.status">
                        <el-radio :label="0">回复全部</el-radio>
                        <el-radio :label="1">随机回复</el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="回复内容" prop="show_reply_list">
                    <el-table border :data="form.show_reply_list" ref="reply_table" max-height="760" style="width: 100%;margin-bottom: 20px">
                        <el-table-column prop="index" label="序号"></el-table-column>
                        <el-table-column prop="type" label="类型" width="260">
                            <template slot-scope="scope">
                                {{scope.row.type == 4 ? '图文' : scope.row.type == 3 ? '视频' : scope.row.type == 2 ? '语言' : scope.row.type == 1 ? '图片' : '文字'}}
                            </template>
                        </el-table-column>
                        <el-table-column prop="content" width="320" label="内容">
                            <template slot-scope="scope">
                                <div v-if="scope.row.type == 0">{{scope.row.content}}</div>
                                <div v-else-if="scope.row.type == 1">
                                    <app-image mode="aspectFill" width='120px' height='120px' :src="scope.row.url"></app-image>
                                </div>
                                <div v-if="scope.row.type == 2">
                                    <audio width="300" :src="scope.row.url" controls="controls"></audio>
                                </div>
                                <div v-if="scope.row.type == 3">
                                    <video width="300" :src="scope.row.url" controls="controls"></video>
                                </div>
                                <div v-if="scope.row.type == 4">
                                    <div class="line-text">{{scope.row.title}}</div>
                                    <div class="line-text">{{scope.row.content}}</div>
                                    <app-image mode="aspectFill" width='120px' height='120px' :src="scope.row.picurl"></app-image>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column label="操作" width="180px">
                            <template slot-scope="scope">
                                <el-button size="small" type="text" @click="editRules(scope.row)" circle>
                                    <el-tooltip class="item" effect="dark" content="编辑" placement="top">
                                        <img src="statics/img/mall/edit.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button size="small" type="text" @click="destroyReply(scope.$index)" circle>
                                    <el-tooltip class="item" effect="dark" content="删除" placement="top">
                                        <img src="statics/img/mall/del.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                        <el-button plain @click="addContent" size="small">+添加回复</el-button>
                    <div class="add-btn">
                    </div>
                </el-form-item>
            </el-form>
        </div>
        <el-button class='button-item' :loading="btnLoading" @click="store" type="primary" size="small">保存</el-button>
        <el-dialog title="添加回复" :visible.sync="dialogVisible" width="720px">
            <app-wechat-reply v-if="replyForm" :form="replyForm" @update="update"></app-wechat-reply>
            <span slot="footer" class="dialog-footer">
                <el-button size="small" @click="dialogVisible = false">取 消</el-button>
                <el-button size="small" :loading="dialogBtnLoading" type="primary" @click="addRlue">确 定</el-button>
            </span>
        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                valid: true,
                dialogVisible: false,
                edit: false,
                cardLoading: false,
                btnLoading: false,
                dialogBtnLoading: false,
                replyForm: null,
                text: '',
                video_url: '',
                voice_url: '',
                pic_url: '',
                form: {
                    keyword_list: [
                        {status: 0, content: ''}
                    ],
                    show_reply_list: [],
                    status: 0,
                    name: ''
                },
                keyword_item: {
                    status: 0, content: ''
                },
                rules: {
                    name: [
                        {message: '请填写规则名称', trigger: 'blur', required: true},
                        { min: 1, max: 15, message: '长度在 1 到 15 个字', trigger: 'blur' }
                    ],
                },
            };
        },
        methods: {
            destroyReply(index) {
                this.form.show_reply_list.splice(index,1);
            },
            update(e) {
                this.text = e.text
                this.video_url = e.video_url
                this.voice_url = e.voice_url
                this.pic_url = e.pic_url
                this.replyForm = e.form
                this.valid = e.valid;
            },
            editRules(item) {
                this.dialogVisible = true;
                this.text = '';
                this.video_url = '';
                this.voice_url = '';
                this.pic_url = '';
                this.valid = true;
                this.replyForm = null;
                setTimeout(()=>{
                    this.replyForm = JSON.parse(JSON.stringify(item));
                })
            },
            addContent() {
                this.replyForm = null;
                setTimeout(()=>{
                    this.dialogVisible = true;
                    this.text = '';
                    this.video_url = '';
                    this.voice_url = '';
                    this.pic_url = '';
                    this.valid = true;
                    this.replyForm = {
                        type: 0,
                        content: '',
                        video_url: ''
                    }
                })
            },
            addRlue() {
                if(!this.valid) {
                    return false;
                }else {
                    this.dialogBtnLoading = true;
                    if(this.replyForm.type == 0) {
                        this.replyForm.content = this.text;
                    }
                    if(this.replyForm.type == 1) {
                        this.replyForm.url = this.pic_url;
                    }
                    if(this.replyForm.type == 2) {
                        this.replyForm.url = this.voice_url;
                    }
                    if(this.replyForm.type == 3) {
                        this.replyForm.url = this.video_url;
                    }
                    request({
                        params: {
                            r: 'mall/wechat/keyword-reply'
                        },
                        method: 'post',
                        data: this.replyForm
                    }).then(e => {
                        this.dialogBtnLoading = false;
                        if (e.data.code == 0) {
                            this.dialogVisible = false;
                            if(this.replyForm.id > 0) {
                                this.form.show_reply_list.splice(+this.replyForm.index -1, 1,this.replyForm)
                            }else {
                                this.replyForm.id = e.data.data;
                                this.replyForm.index = this.form.show_reply_list.length +1;
                                this.form.show_reply_list.push(this.replyForm);
                                this.$nextTick(()=>{
                                    this.$refs.reply_table.bodyWrapper.scrollTop = 999999;
                                })
                            }
                            this.$message.success(e.data.msg);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.$message.error(e.data.msg);
                        this.btnLoading = false;
                    });
                }
            },
            addKeyword() {
                let item = JSON.parse(JSON.stringify(this.keyword_item))
                this.form.keyword_list.push(item)
            },
            destroy(index) {
                this.form.keyword_list.splice(index,1)
            },
            store() {
                let self = this;
                self.btnLoading = true;
                this.form.reply_list = [];
                for(let item of this.form.show_reply_list) {
                    this.form.reply_list.push(item.id)
                }
                request({
                    params: {
                        r: 'mall/wechat/keyword-rule'
                    },
                    method: 'post',
                    data: this.form
                }).then(e => {
                    self.btnLoading = false;
                    if (e.data.code == 0) {
                        self.$message.success(e.data.msg);
                        setTimeout(function () {
                            navigateTo({
                                r:'mall/wechat/reply',
                                tab: 'two'
                            })
                        }, 1000);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.$message.error(e.data.msg);
                    self.btnLoading = false;
                });
            },
            getDetail(id) {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/wechat/keyword-rule',
                        id: id
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.form = e.data.data;
                        for(let index in this.form.show_reply_list) {
                            this.form.show_reply_list[index].index = +index + 1;
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            if (getQuery('id')) {
                this.getDetail(getQuery('id'));
                this.edit = true;
            }
        }
    });
</script>
