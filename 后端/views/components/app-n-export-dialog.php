<?php
/**
 * link: http://www.zjhejiang.com/
 * copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 * author: wxf
 */
$url = Yii::$app->urlManager->createUrl(Yii::$app->controller->route);
?>
<style>
    .app-n-export-dialog .el-dialog__body .el-checkbox + .el-checkbox {
        margin-left: 0;
    }

    .app-n-export-dialog .el-date-editor .el-range-separator {
        padding: 0;
    }

    .app-n-export-dialog .modal-body {
        border: 1px solid #F0F2F7;
    }

    .app-n-export-dialog .all-choose {
        height: 50px;
        line-height: 50px;
        background-color: #F3F5F6;
        width: 100%;
        padding-left: 20px;
    }

    .app-n-export-dialog .choose-list {
        padding: 10px 25px 20px;
    }

    .app-n-export-dialog .choose-list .export-checkbox {
        width: 135px;
        height: 30px;
        line-height: 30px;
    }
</style>
<template id="app-n-export-dialog">
    <div class="app-n-export-dialog" style="display: inline-block">
        <div @click="confirm" style="display: inline-block">
            <slot></slot>
        </div>
        <el-dialog :title="title" :visible.sync="progressBarVisible" width="25%">
            <div class="modal-body">
                <el-progress :text-inside="true" :stroke-width="18" :percentage="percentage"></el-progress>
            </div>
            <div v-if="percentage == 100 && downloadStatus" flex="dir:right" style="margin-top: 20px;">
                <el-button type="primary" @click="downLoad">点击下载</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script src="https://cdn.jsdelivr.net/npm/json2csv@4.2.1"></script>
<script src="https://cdn.jsdelivr.net/webtorrent/latest/webtorrent.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/web-streams-polyfill@2.0.2/dist/ponyfill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/streamsaver@2.0.3/StreamSaver.min.js"></script>
<script>
    Vue.component('app-n-export-dialog', {
        template: '#app-n-export-dialog',
        props: {
            title: {
                type: String,
                default: '订单导出'
            },
            fileName: {
                type: String,
                default: 'file'
            },
            directly: {
                type: Boolean,
                default: false
            },
            field_list: Array,
            params: Object,
            action_url: {
                type: String,
                default: '<?=$url?>'
            },
        },
        data() {
            return {
                checkedFields: [],

                progressBarVisible: false,
                percentage: 0,
                number: 0,

                downloadList: [],
                downloadStatus: false,
            }
        },
        computed: {},
        methods: {
            confirm() {
                if (this.directly) {
                    this.checkedFields = this.field_list
                    this.submit();
                }
                this.$emit('selected');
            },
            submit() {
                let self = this;
                self.dialogVisible = false;
                self.downloadList = [];
                self.params.page = 1;
                self.percentage = 0;
                self.submitRequest();
            },

            submitRequest() {
                class down {
                    writer;
                    _isHead = false;
                    _isClose = false;

                    constructor(fileName) {
                        let fileStream = streamSaver.createWriteStream(fileName);
                        this.writer = fileStream.getWriter();
                    }

                    set setValue(value) {
                        [this._isHead, this._isClose] = [...value];

                    }

                    d(myCars, callback) {
                        let [isHead, isClose, csv] = [this._isHead, this._isClose, ''];
                        if (!myCars || myCars.length === 0) {
                            csv = '';
                            isClose = true;//xx
                        } else if (isHead) {
                            let json2csvParser = new json2csv.Parser({
                                header: true,
                            });
                            csv = json2csvParser.parse(myCars);
                        } else {
                            let json2csvParser = new json2csv.Parser({
                                header: false,
                            });
                            csv = json2csvParser.parse(myCars);
                            csv = "\r\n" + csv;
                        }

                        let blob = new Blob(["\uFEFF"+csv], {
                         type: 'text/csv,charset=utf-8'
                        })
                        const readableStream = blob.stream().getReader();
                        readableStream.read().then(res => {
                            if (isClose) {
                                async function close(writer) {
                                    if (res.value) {
                                        await writer.write(res.value);
                                    }
                                    writer.close();
                                }
                                close(this.writer);
                            } else {
                                this.writer.write(res.value).then(callback())
                            }
                        })
                    }
                }

                const self = this;
                let downS = new down(`${self.fileName}.csv`);

                function r() {
                    request({
                        params: {
                            r: self.action_url
                        },
                        data: Object.assign({
                            flag: 'EXPORT',
                            fields: self.checkedFields,
                        }, self.params),
                        method: 'POST',
                    }).then(e => {
                        const dSTATUS = e.headers['d-status'];
                        if (dSTATUS === 'connection') {
                            let downList = Array.from(e.data.data.list);
                            if (Number.parseInt(self.params.page) === 1) {
                                downS.setValue = [true, false];
                            } else {
                                downS.setValue = [false, false];
                            }
                            ++self.params.page;
                            downS.d(downList, r);
                        }
                        if (dSTATUS === 'close') {
                            let downList = Array.from(e.data.data.list);
                            if (Number.parseInt(self.params.page) === 1) {
                                downS.setValue = [true, true];
                            } else {
                                downS.setValue = [false, true];
                            }
                            downS.d(downList, r);
                        }
                    });
                }
                r();
            },
            downLoad() {
                const myCars = this.downloadList;
                const fields = Object.keys(myCars[0]);
                const fileName = this.fileName;
                const json2csvParser = new json2csv.Parser({fields})
                const csv = json2csvParser.parse(myCars)
                let blob = new Blob(['\uFEFF' + csv], {
                    type: 'text/plaincharset=utf-8'
                })
                saveAs(blob, fileName);
            },
        },
    });
</script>
