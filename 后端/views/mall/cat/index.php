<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

$components = [
    'app-cat-list',
    'app-transfer',
    'app-style'
];
$html = "";
foreach ($components as $component) {
    $html .= $this->renderFile(__DIR__ . "/{$component}.php") . "\n";
}
echo $html;
?>
<style>
    .new-table-body {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .el-dialog__wrapper .el-dialog {
        min-width: 0;
    }
    .el-dialog__wrapper .el-dialog__body {
        padding: 10px 20px;
    }
    .el-dialog__wrapper .el-dialog__footer {
        padding: 10px 20px;
    }
    .el-dialog__wrapper .icon {
        font-size: 20px;
        margin-right: 5px;
        color: #E6A23C;
    }
</style>
<div id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span>分类列表</span>
                <div style="float: right;margin-top: -5px" flex="dir:left">
                    <template v-if="activeName == 'first'">
                        <el-button style="margin-right: 10px;" type="primary" @click="edit" size="small">添加分类
                        </el-button>
                        <app-new-export-dialog-2
                            text='分类导出'
                            :is_next='is_next'
                            :params="searchData"
                            @selected="exportCat"
                            :directly=true
                            action_url="mall/cat/index">
                        </app-new-export-dialog-2>
                    </template>
                </div>
            </div>
        </div>
        <div class="new-table-body">
            <template>
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane label="商品分类" name="first">
                        <app-cat-list @select="catSelect"></app-cat-list>
                    </el-tab-pane>
                    <el-tab-pane label="商品分类转移" name="second">
                        <app-transfer></app-transfer>
                    </el-tab-pane>
                    <el-tab-pane label="分类样式" name="third">
                        <app-style></app-style>
                    </el-tab-pane>
                </el-tabs>
            </template>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                activeName: 'first',
                catIdList: [],
                dialogVisible: false,
                searchData: {
                    choose_list: []
                },
                is_next: false,
            };
        },
        methods: {
            handleClick(tab, event) {
                console.log(tab, event);
            },
            // 编辑
            edit(id) {
                navigateTo({
                    r: 'mall/cat/edit',
                });
            },
            exportCat() {
                if (this.catIdList.length <= 0) {
                    this.$message.warning('请先勾选要导出的分类');
                    return;
                }
                this.searchData.choose_list = this.catIdList;
            },
            catSelect(res) {
                if (res.length > 0) {
                    this.is_next = true;
                } else {
                    this.is_next = false;
                }
                this.catIdList = res;
            }
        },
        mounted: function () {
        },
    });
</script>
