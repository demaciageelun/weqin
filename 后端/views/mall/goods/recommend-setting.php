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
        padding-right: 30%;
        background-color: #fff;
        margin-bottom: 20px;
        min-width: 1000px;
    }

    .goods-list {
        flex-wrap: wrap;
        margin-top: 10px;
        cursor: move;
    }

    .goods-item,
    .goods-add {
        width: 50px;
        height: 50px;
        position: relative;
        border: 1px solid #e2e2e2;
        margin-right: 15px;
        margin-bottom: 15px;
    }

    .goods-add .el-button {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 0;
        padding: 0;
    }

    .goods-delete {
        position: absolute;
        left: calc(100% - 13px);
        top: -13px;
        width: 25px;
        height: 25px;
        line-height: 25px;
        padding: 0 0;
        visibility: hidden;
        z-index: 1;
    }

    .goods-delete .el-icon-close {
        position: absolute;
        top: 6px;
        left: 6px;
    }

    .goods-item:hover .goods-delete {
        visibility: visible;
    }

    .goods-pic {
        background-size: cover;
        background-position: center;
        width: 100%;
        height: 100%;
        background-color: #f6f6f6;
        background-repeat: no-repeat;
    }

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        margin: 0;
    }

    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 10px;
    }

    .mobile-box {
        width: 400px;
        padding: 35px 11px;
        background-color: #fff;
        border-radius: 30px;
        background-size: cover;
        position: relative;
        font-size: .85rem;
        float: left;
        margin-right: 1rem;
    }

    .mobile-box .show-box {
        height: calc(667px - 20px);;
        width: 375px;
        overflow: auto;
        font-size: 12px;
        position: relative;
        background: #f7f7f7;
        overflow-x: hidden;
    }

    .mobile-box .show-box .bg {
        height: 350px;
        width: 325px;
        background-size: 100% 100%;
        background-repeat: no-repeat;
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

    .mobile-box .show-box .btn {
        height: 33.5px;
        width: 212px;
        background-color: #fddB8E;
        border-radius: 16px;
        bottom: 23px;
        font-size: 15px;
        color: #cb0908;
        text-align: center;
        margin-left: 29px;
        position: absolute;
        left: 25px;
    }

    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
    }

    .reset {
        position: absolute;
        top: 3px;
        left: 90px;
    }
</style>

<div id="app" v-cloak>
    <el-card v-loading="loading" v-if="is_show" style="border:0"
             body-style="background-color: #f3f3f3;padding: 10px 0 0;" shadow="never">
        <el-form @submit.native.prevent ref="form" :model="form" label-width="150px" size="small">
            <el-tabs v-model="activeName">
                <el-tab-pane label="推荐设置" name="first"></el-tab-pane>
                <el-tab-pane label="页面设置" name="second"></el-tab-pane>
            </el-tabs>
            <template v-if="activeName === 'first'">
                <el-card>
                    <div slot="header">
                        商品详情页推荐设置
                    </div>
                    <el-row>
                        <el-col :span="12">
                            <el-form-item label="推荐商品状态">
                                <el-switch :active-value="1" :inactive-value="0"
                                           v-model="form.goods.is_recommend_status"></el-switch>
                            </el-form-item>
                            <el-form-item label="推荐商品显示数量">
                                <el-input v-model="form.goods.goods_num" type="number"
                                          oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                    <template slot="append">个</template>
                                </el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-card>
                <el-card style="margin-top: 10px;" shadow="never">
                    <div slot="header">
                        订单完成后推荐设置
                    </div>
                    <el-row>
                        <el-col :span="12">
                            <el-form-item label="推荐商品状态">
                                <el-switch :active-value="1" :inactive-value="0"
                                           v-model="form.order_pay.is_recommend_status"></el-switch>
                            </el-form-item>
                            <el-form-item v-if="form.order_pay.is_recommend_status" label="自定义推荐商品">
                                <el-switch :active-value="1" :inactive-value="0"
                                           v-model="form.order_pay.is_custom"></el-switch>
                                <span>{{form.order_pay.is_custom ? "最多添加20件商品" : "按商品列表排序显示前10件商品"}}</span>
                                <div v-if="form.order_pay.is_custom">
                                    <draggable v-model="form.order_pay.goods_list" flex class="goods-list">
                                        <div class="goods-item" v-for="(goods,goodsIndex) in form.order_pay.goods_list">
                                            <el-tooltip effect="dark" content="移除商品" placement="top">
                                                <el-button @click="deleteGoods(goodsIndex, 'order_pay')" circle
                                                           class="goods-delete" type="danger"
                                                           icon="el-icon-close"></el-button>
                                            </el-tooltip>
                                            <div class="goods-pic"
                                                 :style="'background-image:url('+goods.cover_pic+')'"></div>
                                        </div>
                                    </draggable>
                                    <div v-if="form.order_pay.goods_list.length < goodsDialog.max" class="goods-add">
                                        <el-tooltip effect="dark" content="添加商品" placement="top">
                                            <el-button @click="showGoodsDialog('order_pay')"
                                                       icon="el-icon-plus"></el-button>
                                        </el-tooltip>
                                    </div>
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-card>
                <el-card style="margin-top: 10px;" shadow="never">
                    <div slot="header">
                        评论后推荐设置
                    </div>
                    <el-row>
                        <el-col :span="12">
                            <el-form-item label="推荐商品状态">
                                <el-switch :active-value="1" :inactive-value="0"
                                           v-model="form.order_comment.is_recommend_status"></el-switch>
                            </el-form-item>
                            <el-form-item v-if="form.order_comment.is_recommend_status" label="自定义推荐商品">
                                <el-switch :active-value="1" :inactive-value="0"
                                           v-model="form.order_comment.is_custom"></el-switch>
                                <span>{{form.order_comment.is_custom ? "最多添加20件商品" : "按商品列表排序显示前10件商品"}}</span>
                                <div v-if="form.order_comment.is_custom">
                                    <draggable v-model="form.order_comment.goods_list" flex class="goods-list">
                                        <div class="goods-item"
                                             v-for="(goods,goodsIndex) in form.order_comment.goods_list">
                                            <el-tooltip effect="dark" content="移除商品" placement="top">
                                                <el-button @click="deleteGoods(goodsIndex, 'order_comment')" circle
                                                           class="goods-delete" type="danger"
                                                           icon="el-icon-close"></el-button>
                                            </el-tooltip>
                                            <div class="goods-pic"
                                                 :style="'background-image:url('+goods.cover_pic+')'"></div>
                                        </div>
                                    </draggable>
                                    <div v-if="form.order_comment.goods_list.length < goodsDialog.max"
                                         class="goods-add">
                                        <el-tooltip effect="dark" content="添加商品" placement="top">
                                            <el-button @click="showGoodsDialog('order_comment')"
                                                       icon="el-icon-plus"></el-button>
                                        </el-tooltip>
                                    </div>
                                </div>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-card>
                <el-card style="margin-top: 10px;" shadow="never">
                    <el-button :loading="btnLoading" type="primary" size="small" @click="store">保存</el-button>
                </el-card>
            </template>
            <div v-if="activeName === 'second'" style="display: flex;">
                <div class="mobile-box">
                    <div class="head-bar" flex="main:center cross:center">
                        <div>商城</div>
                    </div>
                    <div class="show-box">
                        <div flex="dir:left cross:center main:center" style="color: #888888;margin: 20px auto 16px">
                            <div style="height: 1px;width: 25px;background: #888888"></div>
                            <div style="padding-left: 12px">
                                <app-image style="height: 12px;width: 12px"
                                           :src="form.comment_style.pic_url"></app-image>
                            </div>
                            <div style="font-size: 11px;padding-left: 6px;padding-right: 12px"
                                 :style="{color:form.comment_style.text_color }">{{form.comment_style.text}}
                            </div>
                            <div style="height: 1px;width: 25px;background: #888888"></div>
                        </div>
                        <!-- 商品 -->
                        <div flex="main:center">
                            <app-image v-if="form.comment_style.list_style == -1"
                                       src="statics/img/mall/goods/recommend/style-1.png"
                                       style="width: 353px;height: 317px"></app-image>
                            <app-image v-if="form.comment_style.list_style == 0"
                                       src="statics/img/mall/goods/recommend/style0.png"
                                       style="width: 364px;height: 192px"></app-image>
                            <app-image v-if="form.comment_style.list_style == 1"
                                       src="statics/img/mall/goods/recommend/style1.png"
                                       style="width: 375px;height: 559px"></app-image>
                            <app-image v-if="form.comment_style.list_style == 2"
                                       src="statics/img/mall/goods/recommend/style2.png"
                                       style="width: 375px;height: 508px"></app-image>
                            <app-image v-if="form.comment_style.list_style == 3"
                                       src="statics/img/mall/goods/recommend/style3.png"
                                       style="width: 375px;height: 195px"></app-image>
                        </div>
                    </div>
                </div>
                <div style="width: 100%;">
                    <div style="background: #FFFFFF;padding: 40px 0">
                        <el-form-item label="图标" prop="pic_url">
                            <app-attachment style="margin-bottom:10px" :multiple="false" :max="1"
                                            @selected="selectPicUrl">
                                <el-tooltip effect="dark"
                                            content="建议尺寸:24 * 24"
                                            placement="top">
                                    <el-button size="mini">选择图标</el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-right: 20px;display:inline-block;position: relative;cursor: move;">
                                <app-attachment :multiple="false" :max="1"
                                                @selected="selectPicUrl">
                                    <app-image mode="aspectFill"
                                               width="80px"
                                               height='80px'
                                               :src="form.comment_style.pic_url">
                                    </app-image>
                                </app-attachment>
                                <el-button v-if="form.comment_style.pic_url" class="del-btn"
                                           size="mini" type="danger" icon="el-icon-close"
                                           circle
                                           @click="removePicUrl"></el-button>
                            </div>
                            <el-button size="mini" @click="resetImg('pic_url')" class="reset" type="primary">恢复默认
                            </el-button>
                        </el-form-item>
                        <el-form-item label="文字" prop="text">
                            <el-input size="small" style="width: 400px" v-model="form.comment_style.text" maxlength="20"
                                      show-word-limit></el-input>
                        </el-form-item>
                        <el-form-item label="文字颜色" prop="text_color">
                            <div flex="dir:left cross:center">
                                <el-color-picker
                                        @change="(row) => {row == null ? form.comment_style.text_color = '#999999' : ''}"
                                        size="small"
                                        v-model="form.comment_style.text_color"></el-color-picker>
                                <el-input size="small" style="width: 80px;margin-left: 5px;"
                                          v-model="form.comment_style.text_color"></el-input>
                            </div>
                        </el-form-item>
                        <el-form-item label="列表样式" prop="list_style">
                            <el-radio v-model="form.comment_style.list_style" label="-1">列表模式</el-radio>
                            <el-radio v-model="form.comment_style.list_style" label="0">左右滑动</el-radio>
                            <el-radio v-model="form.comment_style.list_style" label="1">一行一个</el-radio>
                            <el-radio v-model="form.comment_style.list_style" label="2">一行两个</el-radio>
                            <el-radio v-model="form.comment_style.list_style" label="3">一行三个</el-radio>
                        </el-form-item>
                    </div>
                    <div slot="footer" class="dialog-footer" style="margin-top: 12px">
                        <el-button :loading="btnLoading" size="small" type="primary" @click="store">保存</el-button>
                    </div>
                </div>
            </div>
        </el-form>
    </el-card>

    <el-dialog @open="getGoods" title="选择商品" :visible.sync="goodsDialog.visible" :close-on-click-modal="false">
        <el-form size="small" :inline="true" :model="search" @submit.native.prevent>
            <el-form-item>
                <div class="input-item">
                    <el-input @clear="toSearch" clearable @keyup.enter.native="toSearch" size="small"
                              placeholder="请输入商品ID/名称搜索"
                              v-model="search.keyword">
                        <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                    </el-input>
                </div>
            </el-form-item>
        </el-form>
        <el-table :data="goodsDialog.list" v-loading="goodsDialog.loading" @selection-change="goodsSelectionChange">
            <el-table-column label="选择" type="selection"></el-table-column>
            <el-table-column label="ID" prop="id" width="100px"></el-table-column>
            <el-table-column label="名称" prop="name"></el-table-column>
        </el-table>
        <div style="text-align: center; margin-top: 15px;">
            <el-pagination
                    v-if="goodsDialog.pagination"
                    style="display: inline-block"
                    background
                    @current-change="goodsDialogPageChange"
                    layout="prev, pager, next, jumper"
                    :page-size.sync="goodsDialog.pagination.pageSize"
                    :total="goodsDialog.pagination.totalCount">
            </el-pagination>
        </div>
        <div slot="footer">
            <el-button @click="goodsDialog.visible = false">取 消</el-button>
            <el-button type="primary" @click="addGoods">确 定</el-button>
        </div>

    </el-dialog>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/unpkg/vuedraggable@2.18.1/dist/vuedraggable.umd.min.js"></script>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {
                    goods: {},
                    comment_style: {},
                    order_pay: {
                        goods_list: []
                    },
                    order_comment: {
                        goods_list: []
                    },
                    fxhb: {
                        goods_list: []
                    },
                },
                goodsDialog: {
                    visible: false,
                    page: 1,
                    loading: null,
                    pagination: null,
                    list: null,
                    index: null,
                    selectedList: null,
                    max: 20,//添加商品最大数量
                },
                loading: false,
                is_show: false,
                btnLoading: false,
                search: {
                    keyword: '',
                },
                activeName: 'second'
            }
        },
        created() {
            this.getSetting();
        },
        methods: {
            selectPicUrl(e) {
                if (e.length) {
                    this.form.comment_style.pic_url = e[0].url;
                }
            },
            removePicUrl() {
                this.form.comment_style.pic_url = '';
            },
            resetImg(type) {
                const host = "<?php echo \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . "/" ?>";
                if (type === 'pic_url') {
                    this.form.comment_style.pic_url = host + 'statics/img/app/goods/icon-favorite.png';
                }
            },
            showGoodsDialog(index) {
                this.goodsDialog.visible = true;
                this.goodsDialog.index = index;
            },
            goodsSelectionChange(e) {
                this.goodsDialog.selectedList = e;
            },
            addGoods() {
                this.goodsDialog.visible = false;
                for (let i in this.goodsDialog.selectedList) {
                    const item = {
                        id: this.goodsDialog.selectedList[i].id,
                        name: this.goodsDialog.selectedList[i].name,
                        cover_pic: this.goodsDialog.selectedList[i].cover_pic,
                        price: this.goodsDialog.selectedList[i].price,
                    };
                    if (this.form[this.goodsDialog.index].goods_list.length < this.goodsDialog.max) {
                        this.form[this.goodsDialog.index].goods_list.push(item);
                    }
                }
            },
            deleteGoods(goodsIndex, index) {
                this.form[index].goods_list.splice(goodsIndex, 1);
            },
            getGoods() {
                let self = this;
                self.goodsDialog.loading = true;
                request({
                    params: {
                        r: 'mall/goods/recommend-goods',
                        page: self.goodsDialog.page,
                        search: JSON.stringify(self.search),
                    },
                    method: 'get',
                }).then(e => {
                    self.goodsDialog.loading = false;
                    self.goodsDialog.list = e.data.data.list;
                    self.goodsDialog.pagination = e.data.data.pagination;
                }).catch(e => {
                    console.log(e);
                });
            },
            getSetting() {
                let self = this;
                self.loading = true;
                request({
                    params: {
                        r: 'mall/goods/recommend-setting',
                    },
                    method: 'get',
                }).then(e => {
                    self.loading = false;
                    self.is_show = true;
                    self.form = e.data.data.setting;
                }).catch(e => {
                    console.log(e);
                });
            },
            store() {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: 'mall/goods/recommend-setting',
                    },
                    method: 'post',
                    data: {
                        form: JSON.stringify(self.form)
                    }
                }).then(e => {
                    self.btnLoading = false;
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg)
                    } else {
                        self.$message.error(e.data.msg)
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            goodsDialogPageChange(page) {
                this.goodsDialog.page = page;
                this.getGoods();
            },
            toSearch() {
                this.getGoods();
            }
        }

    });
</script>
