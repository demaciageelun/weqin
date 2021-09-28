<?php
/**
 * Created by PhpStorm
 * User: 风哀伤
 * Date: 2020/11/10
 * Time: 9:21 上午
 * @copyright: ©2020 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */
?>
<template id="app-platform">
    <el-select size="small" style="width: 120px" v-model="platform" @change='toSearch' class="select">
        <el-option key="all" label="全部平台" value=""></el-option>
        <el-option :key="item.key" :label="item.name" :value="item.key" v-for="(item, index) in platformList"></el-option>
    </el-select>
</template>
<script>
    Vue.component('app-platform', {
        template: '#app-platform',
        props: {
            value: String,
        },
        data() {
            return {
                platformList: [],
                platform: '',
            };
        },
        created() {
            this.getPlatform();
        },
        watch: {
            value: {
                handler() {
                    this.platform = JSON.parse(JSON.stringify(this.value))
                },
                immediate: true
            }
        },
        methods: {
            getPlatform() {
                request({
                    params: {
                        r: 'mall/index/platform',
                    },
                    method: 'get',
                }).then(e => {
                    if(e.data.code === 0) {
                        this.platformList = e.data.data
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            toSearch() {
                this.$emit('input', this.platform)
                this.$emit('change');
            }
        }
    });
</script>

