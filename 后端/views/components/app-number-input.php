<?php
?>
<style>
    .app-number-input-area .app-number-input {
        margin: 4px;
        background-color: #fff;
        cursor: pointer;
        font-size: 20px;
        border-radius: 8px;
    }
    .app-number-input-area .app-number-input:hover {
        background-color: #f4f8fb;
    }
    .app-number-input-area .app-member-input.el-input {
        height: 42px;
    }
    .app-number-input-area.pad .app-member-input.el-input {
        height: 32px;
    }
    .app-number-input-area .app-member-input.el-input .placeholder {
        color: #C0C4CC;
    }
    .app-number-input-area .app-member-input.el-input input,.app-number-input-area .app-member-input.el-input div {
        border: 0;
        background-color: #f5f9fc;
        border-radius: 8px;
        height: 42px;
        font-size: 18px;
        line-height: 42px;
        font-family: Arial;
    }
    .app-number-input-area.pad .app-member-input.el-input input,.app-number-input-area.pad .app-member-input.el-input div {
        height: 32px;
        line-height: 32px;
        font-size: 14px;
        font-family: Arial;
    }
    .app-number-input-area .app-member-number {
        padding: 4px;
        background-color: #f4f8fb;
        border-radius: 16px;
        margin: 0 auto;
        position: relative;
        flex-wrap: wrap;
        -webkit-user-select:none;
        -moz-user-select:none;
        -ms-user-select:none;
        user-select:none;
    }
    .app-number-input-area .app-member-confirm {
        border-radius: 8px;
        font-size: 20px;
        color: #fff;
        text-align: center;
        cursor: pointer;
        position: absolute;
        bottom: 8px;
        right: 8px;
        z-index: 2;
        background: linear-gradient(to right, #2E9FFF, #3E79FF);
    }
    .app-number-input-area .app-number-label {
        font-size: 15px;
    }
    .app-number-input-area .app-password-input {
        margin: 20px auto;
    }
    .app-number-input-area.pad .app-password-input {
        margin: 12px auto 20px;
    }
    .app-number-input-area .app-password-input .app-password-item {
        width: 38px;
        height: 38px;
        line-height: 38px;
        text-align: center;
        border: 1px solid #e2e2e2;
        margin-left: -1px;
        background-color: #fff;
        font-size: 50px;
    }
    .app-number-input-area .app-password-input .app-password-item>div {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #000;
    }
    .app-number-input-area .app-password-input .app-password-item:first-of-type {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    .app-number-input-area .app-password-input .app-password-item:last-of-type {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .app-number-input-area input::-webkit-outer-spin-button,
    .app-number-input-area input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }
    .app-number-input-area input[type="number"]{
        -moz-appearance: textfield;
    }
</style>
<template id="app-number-input">
    <div class="app-number-input-area" :class="pad ? 'pad': ''" flex="dir:top main:center">
        <div flex="dir:left cross:center" v-if="mode != 'password'">
            <div class="app-number-label" v-if="label">{{label}}</div>
            <div class="app-member-input el-input" v-if="!focus" @click="toggle":style="{'width': inputWidth + 'px','margin' : margin +'px auto'}">
                <div class="el-input__inner" :class="!number ? 'placeholder': ''">{{number ? number : placeholder}}</div>
            </div>
            <el-input v-else @keyup.enter.native="confirmNumber" :ref="name" :type="mode == 'price' ? 'number': mode" class="app-member-input" size="small" :placeholder="placeholder" :autofocus="focus" :value="number" @input="getNumber" :style="{'width': inputWidth + 'px','margin' : margin +'px auto'}" @focus="inputMode('focus')" @blur="inputMode('blur')"></el-input>
        </div>
        <div v-if="mode == 'password'" style="text-align: center;color: #999999">{{ name == 'verify' ? '请再次输入6位支付密码' : '请引导顾客输入6位数支付密码'}}</div>
        <div flex="main:center cross:center" v-if="mode == 'password'">
            <div class="app-password-input" flex="main:center cross:center">
                <div v-for="(item,index) in passwordInput" class="app-password-item" flex="main:center cross:center">
                    <div v-if="number.length > index"></div>
                </div>
            </div>
        </div>
        <slot></slot>
        <div class="app-member-number" flex="dir:left cross:center" :style="{'width': inputWidth + 'px'}">
            <div @click="clickNumber(item)" :style="{'width': item === '0' && mode != 'price' ? zeroButton + 'px' : buttonWidth + 'px','height': buttonHeight + 'px'}" class="app-number-input" flex="main:center cross:center" v-for="(item,index) in list">
                <img v-if="!item" src="statics/img/mall/icon-close.png" alt="">
                <span v-else>{{item}}</span>
            </div>
            <div @click="confirmNumber" class="app-member-confirm" :style="{'width': buttonWidth + 'px','height': confirmHeight + 'px', 'line-height': confirmHeight + 'px'}">确认</div>
        </div>
    </div>
</template>
<script>
    Vue.component('app-number-input', {
        template: '#app-number-input',
        props: {
            mode: { // 输入模式 number:数字输入 price:价格 password:密码 
                type: String,
                default: 'price'
            },
            pad: Boolean,
            price: [Number, String],
            placeholder: { //  数字输入框占位符
                type: String
            },
            label: String, // 数字输入框前文字
            width: { // 键盘宽度
                type: Number
            },
            margin: { // 数字输入框外边距
                type: Number,
                default: 20
            },
            name: String
        },
        data() {
            return {
                passwordInput: ['','','','','',''],
                focus: true,
                number: '',
                overWidth: 480,
                buttonWidth: 110,
                zeroButton: 338,
                buttonHeight: 77,
                confirmHeight: 250,
                inputWidth: 372,
                list: []
            }
        },
        created() {
            let times = this.width > 1281 ? 4 : 3.5
            this.overWidth = this.width / times > 480 ? 480 : this.width / times;
            this.buttonWidth = (this.overWidth-40) / times > 110 ? 110: (this.overWidth-40) / times;
            this.buttonHeight = this.buttonWidth *0.7;
            this.zeroButton = +this.buttonWidth*3 + 12;
            this.confirmHeight = +this.buttonHeight*3 + 15;
            this.inputWidth = this.buttonWidth*4+40;
        },
        mounted () { 
            window.addEventListener('keyup',this.handleKeyup)
        },
        watch: {
            mode: {
                handler(v) {
                    if(v == 'price') {
                        this.list = ['1','2','3','','4','5','6','确认','7','8','9','确认','0','.','00','确认']
                    }else {
                        this.list = ['1','2','3','','4','5','6','确认','7','8','9','确认','0','确认']
                    }
                },
                immediate: true,
            },
            price: {
                handler(v) {
                    if(v > 0) {
                        this.number = v.toString();
                    }
                },
                immediate: true
            },
            name: {
                handler(newValue,oldValue) {
                    if(newValue != oldValue && !this.price) {
                        this.number = '';
                    }
                }
            }
        },
        methods: {
            inputMode(type) {
                let inputStatus = false;
                if(type == 'focus') {
                    inputStatus = true;
                }
                this.$emit('status', inputStatus)
            },
            toggle() {
                this.focus = !this.focus
                this.$nextTick(() => {
                    if(!this.pad) {
                        this.$refs[this.name].focus();
                    }
                });
            },
            handleKeyup(e) {
                if(this.mode == 'password' && e.key > -1) {
                    this.number += e.key;
                }
            },
            getNumber(e) {
                this.$nextTick(() => {
                    if(this.mode == 'price') {
                        if(e.length < 13) {
                            this.number = e.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3')
                        }
                    }else {
                        this.number = e.replace(/[^0-9]/g, '')
                    }
                    this.$emit('input', this.number)
                })
            },
            confirmNumber() {
                this.$emit('change', this.number)
            },
            clickNumber(str) {
                this.focus = false;
                if(str) {
                    if(str != '确认') {
                        if(!(this.mode == 'password' && this.number.length > 5) && !(this.mode == 'price' && this.number.length > 12) && !(this.mode == 'number' && this.number.length > 11)) {
                            this.number += str;
                        }
                        if(this.number[0] == '.') {
                            this.number = '0.'
                        }
                    }else {
                        this.$emit('change', this.number)
                    }
                }else {
                    this.number = '';
                }
                this.$emit('input', this.number)
            },
        }
    });
</script>
