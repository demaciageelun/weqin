<?php
/**
 * @copyright ©2018 Lu Wei
 * @author Lu Wei
 * @link http://www.9ysw.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/14 13:49
 */
?>
<style>
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

    .required-icon .el-form-item__label:before {
        content: '*';
        color: #F56C6C;
        margin-right: 4px;
    }
    .content-input .el-textarea .el-textarea__inner{
        resize: none;
    }
</style>
<template id="app-wechat-reply">
    <el-form @submit.native.prevent :model="form" label-width="140px" :rules="rules" ref="form" size="small">
        <el-form-item label="回复内容" prop="type">
            <el-radio-group v-model="form.type">
                <el-radio :label="0">文字</el-radio>
                <el-radio :label="1">图片</el-radio>
                <el-radio :label="2">语音</el-radio>
                <el-radio :label="3">视频</el-radio>
                <el-radio :label="4">图文</el-radio>
            </el-radio-group>
        </el-form-item>
        <el-form-item class="required-icon" v-if="form.type == 0" prop="text">
            <el-input @blur="changeValue" v-if="form.type == 0" style="width: 480px;" type="textarea" v-model="text"></el-input>
        </el-form-item>
        <el-form-item class="required-icon" v-if="form.type == 2 || form.type == 3" label="语音" prop="voice_url">
            <template slot="label">
                <span>{{form.type == 2 ? '语音':'视频'}}</span>
            </template>
            <div v-if="form.type == 2">
                <app-attachment display="block" :multiple="false" :max="1" @selected="voiceUrl" type="voice">
                    <el-input @blur="changeValue" style="width: 400px;" :disabled="voice_url ? true : false" v-model="voice_url" placeholder="请选择文件">
                        <template slot="append">
                                <el-tooltip class="item"
                                            effect="dark"
                                            content="支持格式mp3;音频大小不能超过2 MB"
                                            placement="top">
                                    <el-button size="mini">选择文件</el-button>
                                </el-tooltip>
                        </template>
                    </el-input>
                </app-attachment>
            </div>
            <el-input @blur="changeValue" v-else style="width: 400px;" v-model="video_url" placeholder="请选择文件或输入链接">
                <template slot="append">
                    <app-attachment :multiple="false" :max="1" @selected="videoUrl"
                                    type="video">
                        <el-tooltip class="item"
                                    effect="dark"
                                    content="支持格式mp4;支持编码H.264;视频大小不能超过10 MB"
                                    placement="top">
                            <el-button size="mini">选择文件</el-button>
                        </el-tooltip>
                    </app-attachment>
                </template>
            </el-input>
        </el-form-item>
        <el-form-item class="required-icon" v-if="form.type == 4" label="标题" prop="title">
            <el-input @blur="changeValue" style="width: 400px;" v-model="form.title"></el-input>
        </el-form-item>
        <el-form-item class="required-icon content-input" v-if="form.type == 4" label="文字" prop="content">
            <el-input @blur="changeValue" style="width: 500px;" :rows="10" type="textarea" v-model="form.content"></el-input>
        </el-form-item>
        <el-form-item class="required-icon" v-if="form.type == 1 || form.type == 4" prop="picurl">
            <template slot="label">
                <span>图片</span>
                <el-tooltip v-if="form.type == 4" effect="dark" placement="top"
                            content="图片支持JPG、PNG格式，较好的效果为大图360*200,小图200*200">
                    <i class="el-icon-info"></i>
                </el-tooltip>
            </template>
            <app-attachment v-if="form.type == 4" v-model="form.picurl" :multiple="false" :max="1" @selected="selectPic">
                <el-button size="mini">选择图片</el-button>
            </app-attachment>
            <app-attachment v-else v-model="pic_url" :multiple="false" :max="1" @selected="selectPic">
                <el-button size="mini">选择图片</el-button>
            </app-attachment>
            <div class="customize-share-title">
                <app-image mode="aspectFill" width='80px' height='80px'
                           :src="form.type == 4 ? form.picurl ? form.picurl : '' : pic_url ? pic_url: ''"></app-image>
                <el-button v-if="form.type == 4 ? form.picurl : pic_url" class="del-btn" size="mini"
                           type="danger" icon="el-icon-close" circle
                           @click="clearPic"></el-button>
            </div>
        </el-form-item>
        <el-form-item class="required-icon" v-if="form.type == 4" label="跳转链接" prop="url">
            <el-input @blur="changeValue" @input="showValue" style="width: 400px;" placeholder="请填写以https开头的有效链接" v-model="form.url"></el-input>
        </el-form-item>
    </el-form>
</template>
<script>
Vue.component('app-wechat-reply', {
    template: '#app-wechat-reply',
    props: {
        form: {
            type: Object,
            default: {
                type: 0,
                content: '',
                video_url: ''
            }
        },
    },
    data() {
        var checkUrl = (rule, value, callback) => {
            if(!this.form.url || this.form.url.indexOf('https://') == -1) {
                callback(new Error('请填写以https://开头的有效链接'));
            } else {
                callback();
            }
        };
        return {
            text: '',
            video_url: '',
            voice_url: '',
            pic_url: '',
            rules: {
                url: [
                    {required: true, validator: checkUrl, trigger: 'blur'},
                ],
            },
        };
    },
    created() {
        if(this.form.type == 0) {
            this.text = this.form.content
        }
        if(this.form.type == 1) {
            this.pic_url = this.form.url
        }
        if(this.form.type == 2) {
            this.voice_url = this.form.url
        }
        if(this.form.type == 3) {
            this.video_url = this.form.url
        }
        setTimeout(()=>{
            if(this.form.type != 4) {
                this.form.content = '';
                this.form.url = '';
            }
        })
    },
    methods: {
        showValue() {
            this.$nextTick(()=>{
                this.$forceUpdate();
            })
        },
        changeValue() {
            this.$refs.form.validate((valid) => {
                this.$emit('update',{
                    text: this.text,
                    video_url: this.video_url,
                    voice_url: this.voice_url,
                    pic_url: this.pic_url,
                    form: this.form,
                    valid: valid
                })
            })
        },
        clearPic() {
            if(this.form.type == 4) {
                this.form.picurl = ''
            }else {
                this.pic_url = '';
            }
            this.changeValue();
        },
        selectPic(e) {
            if (e.length) {
                if(this.form.type == 4) {
                    this.form.picurl = e[0].url
                }else {
                    this.pic_url = e[0].url;
                }
            }
            this.changeValue();
        },
        videoUrl(e) {
            if (e.length) {
                this.video_url = e[0].url;
            }
            this.changeValue();
        },
        voiceUrl(e) {
            if (e.length) {
                this.voice_url = e[0].url;
            }
            this.changeValue();
        },
    },
});
</script>
