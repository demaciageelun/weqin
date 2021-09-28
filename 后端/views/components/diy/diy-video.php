<?php
/**
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2019/4/24
 * Time: 20:07
 * @copyright: ©2019 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
Yii::$app->loadViewComponent('diy/app-padding');
Yii::$app->loadViewComponent('diy/app-video-model');
?>
<style>
    .diy-video {
        width: 100%;
        height: 400px;
        background: #353535;
        position: relative;
    }

    .diy-video .el-input-group__append {
        background-color: #fff
    }

    .diy-component-edit .end {
        padding: 0 24px 10px;
        color: #303133;
        font-size: 13px;
        cursor: pointer;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .diy-component-edit .end span {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        display: block;
    }

    .diy-component-edit .end span:before {
        content: "标题：";
    }
</style>
<template id="diy-video">
    <div>
        <div class="diy-component-preview" :style="styleA">
            <div class="diy-video">
                <img :src="data.pic_url" :style="styleB" style="width: 100%;height:100%;" v-if="data.pic_url">
                <img style="position: absolute;left: 274px;top: 130px" src="statics/img/mall/diy/play.png"
                     height="128px" width="128px" alt="">
            </div>
        </div>
        <div class="diy-component-edit">
            <el-form label-width="100px" @submit.native.prevent>
                <el-form-item label="视频添加">
                    <el-radio-group v-model="data.addType">
                        <el-radio label="auto">视频专区获取</el-radio>
                        <el-radio label="custom">手动添加</el-radio>
                    </el-radio-group>
                    <template v-if="data.addType === 'auto'">
                        <div>
                            <app-video-model @change="videoChange">
                                <el-button size="small">选择视频</el-button>
                            </app-video-model>
                        </div>
                        <el-card v-if="data.video_id" shadow="never" :body-style="{ padding: '0px' }"
                                 style="width: 250px;margin-top: 10px">
                            <el-image :src="data.pic_url" style="height: 134px;width: 100%"></el-image>
                            <div class="end">
                                <span>{{data.video_title}}</span>
                            </div>
                    </template>
                </el-form-item>
                <template v-if="data.addType == 'custom'">
                    <el-form-item label="封面图片">
                        <app-attachment title="选择图片" :multiple="false" :max="1" type="image" v-model="data.pic_url">
                            <el-tooltip class="item" effect="dark"
                                        content="建议尺寸750*400"
                                        placement="top">
                                <el-button size="mini">选择图片</el-button>
                            </el-tooltip>
                        </app-attachment>
                        <app-gallery :url="data.pic_url" :show-delete="true"
                                     @deleted="deletePic"></app-gallery>
                    </el-form-item>
                    <el-form-item label="视频链接">
                        <label slot="label">视频链接
                            <el-tooltip class="item" effect="dark"
                                        content="支持格式mp4;支持编码H.264;视频大小不能超过50 MB"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </label>
                        <el-input size="small" v-model="data.url" placeholder="请输入视频原地址或选择上传视频">
                            <template slot="append">
                                <app-attachment :multiple="false" :max="1" v-model="data.url"
                                                type="video">
                                    <el-button size="mini">选择文件</el-button>
                                </app-attachment>
                            </template>
                        </el-input>
                    </el-form-item>
                </template>
                <el-form-item label="自动播放">
                    <el-switch v-model="data.hasAuto" :inactive-value="0" :active-value="1"></el-switch>
                    <span style="padding-left:10px;color:rgb(204, 204, 204)">默认静音播放</span>
                </el-form-item>
                <el-form-item label="循环播放">
                    <el-switch v-model="data.hasCycle" :inactive-value="0" :active-value="1"></el-switch>
                </el-form-item>
                <app-padding @ss="setStyle" v-model="data">
                    <template slot="c-bg">
                        <span></span>
                    </template>
                </app-padding>
            </el-form>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-video', {
        template: '#diy-video',
        props: {
            value: Object
        },
        data() {
            return {
                data: {
                    pic_url: '',
                    url: '',
                    video_id: '',
                    video_title: '',
                    addType: 'custom',
                    hasAuto: 0,
                    hasCycle: 0,
                    c_padding_top: 0,
                    c_padding_lr: 0,
                    c_padding_bottom: 0,
                    c_border_top: 0,
                    c_border_bottom: 0,
                    bg_padding: '#FFFFFF',
                },
                styleA: {},
                styleB: {},
            }
        },
        created() {
            if (!this.value) {
                this.$emit('input', this.data)
            } else {
                this.data = this.value;
            }
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
        methods: {
            setStyle(styleA, styleB) {
                this.styleA = styleA;
                this.styleB = styleB;
            },
            videoChange(e) {
                this.data.pic_url = e.pic_url;
                this.data.url = e.url;
                this.data.video_title = e.title;
                this.data.video_id = e.id;
            },
            deletePic() {
                this.data.pic_url = '';
            }
        }
    });
</script>
