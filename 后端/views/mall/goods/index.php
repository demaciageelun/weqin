<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */

Yii::$app->loadViewComponent('app-goods-list');
$mchId = Yii::$app->user->identity->mch_id;

Yii::$app->loadViewComponent('goods/app-add-cat');
?>
<style>

</style>
<div id="app" v-cloak>
    <app-goods-list
            :is-show-export-goods="isShowExportGoods"
            @get-all-checked="getAllChecked"
            :is_edit_goods_name='true'
            :is-goods-type="is_goods_type"
            ref="goodsList"
            :is-show-svip="isShowSvip"
            :is-show-integral="isShowIntegral"
            :is-show-update="isShowUpdate"
            :batch-list="is_mch ? mchBatchList : batchList">
        <template slot="column-col">
            <el-table-column v-if="!is_mch" label="是否加入快速购买">
                <template slot-scope="scope">
                    <el-tooltip class="item" effect="dark"
                                content="虚拟商品不能加入快速购买"
                                v-if="scope.row.goodsWarehouse.type === 'ecard'"
                                placement="top">
                        <el-switch
                                :active-value="1"
                                :inactive-value="0"
                                :disabled="scope.row.goodsWarehouse.type === 'ecard'"
                                @change="switchQuickShop(scope.row)"
                                v-model="scope.row.mallGoods.is_quick_shop">
                        </el-switch>
                    </el-tooltip>
                    <el-switch
                            v-else
                            :active-value="1"
                            :inactive-value="0"
                            @change="switchQuickShop(scope.row)"
                            v-model="scope.row.mallGoods.is_quick_shop">
                    </el-switch>
                </template>
            </el-table-column>
        </template>
        <template slot="batch" slot-scope="item">
            <div v-if="item.item === 'quick'">
                <el-form-item label="是否加入快速购买">
                    <el-switch
                            @change="batch('quick')"
                            v-model="batchList[0].params.status"
                            :active-value="1"
                            :inactive-value="0">
                    </el-switch>
                </el-form-item>
            </div>
            <div v-if="item.item === 'negotiable'">
                <el-form-item label="是否加入商品面议">
                    <el-tooltip effect="dark" content="如果开启面议，则商品无法在线支付" placement="top">
                        <i class="el-icon-info"></i>
                    </el-tooltip>
                    <el-switch @change="batch('negotiable')"
                               v-model="batchList[1].params.status"
                               :active-value="1"
                               :inactive-value="0">
                    </el-switch>
                </el-form-item>
            </div>
            <div v-if="item.item === catLabel">
                <el-form-item label="修改分类">
                    <el-tag style="margin-right: 5px;margin-bottom:5px" v-for="(item,index) in catsForm"
                            :key="index" type="warning" closable disable-transitions
                            @close="destroyCat(index)"
                    >{{item.label}}
                    </el-tag>
                    <app-add-cat ref="cats" :append-to-body="true" :new-cats="batchList[2].params.cat_ids"
                                 @select="selectCat"
                                 style="display: inline-block">
                        <template slot="open">
                            <el-button type="primary" size="small" style="margin: 0 5px">选择分类</el-button>
                        </template>
                    </app-add-cat>
                    <el-button type="text" @click="$navigate({r:'mall/cat/edit'}, true)">添加分类</el-button>
                </el-form-item>
            </div>
            <div v-if="item.item === mchCatLabel">
                <el-form-item label="修改分类">
                    <el-tag style="margin-right: 5px;margin-bottom:5px" v-for="(item,index) in mchCatsForm"
                            :key="index" type="warning" closable disable-transitions
                            @close="destroyMchCat(index)"
                    >{{item.label}}
                    </el-tag>
                    <app-add-cat ref="cats" :append-to-body="true" :new-cats="mchBatchList[0].params.cat_ids"
                                 @select="selectMchCat"
                                 :mch_id="<?= intval($mchId) ?>"
                                 style="display: inline-block">
                        <template slot="open">
                            <el-button type="primary" size="small" style="margin: 0 5px">选择分类</el-button>
                        </template>
                    </app-add-cat>
                    <el-button type="text" @click="$navigate({r:'mall/cat/edit'}, true)">添加分类</el-button>
                </el-form-item>
            </div>
        </template>
    </app-goods-list>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            let catLabel = 'cats';
            let mchCatLabel = 'mch_cats';
            return {
                catLabel,
                mchCatLabel,
                is_mch: <?= $mchId?>,
                mchBatchList: [
                    {
                        name: '多商户分类',
                        key: mchCatLabel,
                        url: 'mall/goods/batch-update-cats',
                        content: '批量设置商品分类,是否继续',
                        params: {
                            cat_ids: []
                        }
                    }
                ],
                batchList: [
                    {
                        name: '快速购买',
                        key: 'quick',
                        url: 'mall/goods/batch-update-quick',
                        content: '批量移除快速购买,是否继续',
                        params: {
                            status: 0
                        }
                    },
                    {
                        name: '面议',
                        key: 'negotiable',
                        url: 'mall/goods/batch-update-negotiable',
                        content: '批量移除商品面议,是否继续',
                        params: {
                            status: 0
                        }
                    },
                    {
                        name: '分类',
                        key: catLabel,
                        url: 'mall/goods/batch-update-cats',
                        content: '批量设置商品分类,是否继续',
                        params: {
                            cat_ids: []
                        }
                    },
                ],
                isShowIntegral: true,
                isShowSvip: true,
                isAllChecked: false,
                isShowExportGoods: true,
                isShowUpdate: true,
                is_goods_type: true,
                catsForm: [],
                mchCatsForm: [],
            };
        },
        watch: {
            'catsForm'(newData) {
                let cat_ids = newData.map(item => {
                    return item.value;
                })
                Object.assign(this.batchList[2].params, {cat_ids})
            },
            'mchCatsForm'(newData) {
                let cat_ids = newData.map(item => {
                    return item.value;
                })
                Object.assign(this.mchBatchList[0].params, {cat_ids})
            },
        },
        methods: {
            selectMchCat(e) {
                this.mchCatsForm = e;
            },
            destroyMchCat(index) {
                this.mchCatsForm.splice(index, 1);
            },
            destroyCat(index) {
                this.catsForm.splice(index, 1);
            },
            selectCat(e) {
                this.catsForm = e;
            },
            // 加入快速购买
            switchQuickShop(row) {
                let self = this;
                request({
                    params: {
                        r: 'mall/goods/switch-quick-shop',
                    },
                    method: 'post',
                    data: {
                        id: row.id
                    }
                }).then(e => {
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            batch(key) {
                let isAllChecked = this.isAllChecked;
                if (!this.is_mch) {
                    if (key === 'quick') {
                        this.batchList[0].content = isAllChecked ? '警告: 批量设置所有商品' + (this.batchList[0].params.status ? '加入' : '移除') + '快速购买,是否继续' : '批量' + (this.batchList[0].params.status ? '加入' : '移除') + '快速购买,是否继续';
                    } else if (key === 'negotiable') {
                        this.batchList[1].content = isAllChecked ? '警告: 批量设置所有商品' + (this.batchList[1].params.status ? '加入' : '移除') + '商品面议,是否继续' : '批量' + (this.batchList[1].params.status ? '加入' : '移除') + '商品面议,是否继续';
                    }
                }
            },
            getAllChecked(isAllChecked) {
                if (!this.is_mch) {
                    this.batchList[0].content = isAllChecked ? '警告: 批量设置所有商品' + (this.batchList[0].params.status ? '加入' : '移除') + '快速购买,是否继续' : '批量' + (this.batchList[0].params.status ? '加入' : '移除') + '快速购买,是否继续';
                    this.batchList[1].content = isAllChecked ? '警告: 批量设置所有商品' + (this.batchList[1].params.status ? '加入' : '移除') + '商品面议,是否继续' : '批量' + (this.batchList[1].params.status ? '加入' : '移除') + '商品面议,是否继续';
                }
                this.isAllChecked = isAllChecked;
            }
        },
        mounted() {
            if (this.is_mch) {
                this.isShowIntegral = false;
                this.isShowSvip = false;
                this.isShowUpdate = false;
            }
        }
    });
</script>
