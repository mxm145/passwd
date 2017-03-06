<template>
<div>
<div class="row" v-if="valid">
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item active">
                <p>网络查看</p>
            </a>
            <div class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="g5" v-model="g5">
                    <span class="input-group-addon" @click="add(5)">添加</span>
                </div>
            </div>
            <a href="#" class="list-group-item" v-for="(p, index) in list.g5" @click.prevent="del(p.id, 5, index)">{{p.ip}} <span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item active">
                <p>网络管理</p>
            </a>
            <div class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="g6" v-model="g6">
                    <span class="input-group-addon" @click="add(6)">添加</span>
                </div>
            </div>
            <a href="#" class="list-group-item" v-for="(p, index) in list.g6" @click.prevent="del(p.id, 6, index)">{{p.ip}} <span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item active">
                <p>运维查看</p>
            </a>
            <div class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="g3" v-model="g3">
                    <span class="input-group-addon" @click="add(3)">添加</span>
                </div>
            </div>
            <a href="#" class="list-group-item" v-for="(p, index) in list.g3" @click.prevent="del(p.id, 3, index)">{{p.ip}} <span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item active">
                <p>运维管理</p>
            </a>
            <div class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="g4" v-model="g4">
                    <span class="input-group-addon" @click="add(4)">添加</span>
                </div>
            </div>
            <a href="#" class="list-group-item" v-for="(p, index) in list.g4" @click.prevent="del(p.id, 4, index)">{{p.ip}} <span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
</div>
<div class="row" v-if="valid">
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item active">
                <p>开发查看</p>
            </a>
            <div class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="g1" v-model="g1">
                    <span class="input-group-addon" @click="add(1)">添加</span>
                </div>
            </div>
            <a href="#" class="list-group-item" v-for="(p, index) in list.g1" @click.prevent="del(p.id, 1, index)">{{p.ip}} <span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item active">
                <p>开发管理</p>
            </a>
            <div class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" id="g2" v-model="g2">
                    <span class="input-group-addon" @click="add(2)">添加</span>
                </div>
            </div>
            <a href="#" class="list-group-item" v-for="(p, index) in list.g2" @click.prevent="del(p.id, 2, index)">{{p.ip}} <span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
</div>
<el-dialog title="请输入动态密码" v-model="livecode" size="small" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false">
    <form class="form-horizontal">
        <div class="form-group">
            <label for="livecodeView" class="col-sm-2 control-label">动态密码</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="livecodeView" placeholder="请输入动态密码" v-model="livecodeView">
            </div>
        </div>
    </form>
    <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="valideViewCode">确 定</el-button>
    </span>
</el-dialog>
</div>
</template>
<script>
import vueStrap from 'vue-strap'
import axios from 'axios'
import qs from 'qs'
import Promise from 'promise-polyfill'
if (!window.Promise) {
    window.Promise = Promise;
}
export default {
    name: 'manage',
    data: function(){
        return {
            list: [],
            livecode: true,
            g1: '',
            g2: '',
            g3: '',
            g4: '',
            g5: '',
            g6: '',
            showModal: false,
            livecodeView: '',
            valid: false,
            code: ''
        }
    },
    mounted: function(transition){
    },
    methods: {
        valideViewCode: function(){
            var vm = this;
            if (this.livecodeView != '') {
                axios.post('../dist/manage.php', qs.stringify({par: 'validate', code: this.livecodeView}))
                .then(res => {
                    let r = res.data;
                    if (r.status == 'success') {
                        vm.list = r.data;
                        vm.code = r.code;
                        this.livecode = false;
                        vm.valid = true;
                    }
                }).catch(err => {
                    console.log(err);
                })
            }
        },
        del: function(id, gid, index){
            var vm = this;
            axios.post('/api/manage/del/'+id, qs.stringify({code: this.code, livecode: this.livecodeView}))
            .then(res => {
                let r = res.data;
                if (r.status == 'success') {
                    if (gid == 1) {
                        vm.list.g1.splice(index, 1);
                    }else if (gid == 2) {
                        vm.list.g2.splice(index, 1);
                    }else if (gid == 3) {
                        vm.list.g3.splice(index, 1);
                    }else if (gid == 4) {
                        vm.list.g4.splice(index, 1);
                    }else if (gid == 5) {
                        vm.list.g5.splice(index, 1);
                    }else if (gid == 6) {
                        vm.list.g6.splice(index, 1);
                    }
                }
            }).catch(err => {
                console.log(err);
            })
        },
        add: function(n){
            var value = '';
            if (n == 1) {
                value = this.g1;
            }else if(n == 2){
                value = this.g2;
            }else if(n == 3){
                value = this.g3;
            }else if(n == 4){
                value = this.g4;
            }else if(n == 5){
                value = this.g5;
            }else if(n == 6){
                value = this.g6;
            }
            if (value == '') {
                return;
            }
            var vm = this;
            axios.post('/api/manage/add', qs.stringify({value: value, gid: n, code: this.code, livecode: this.livecodeView}))
            .then(res => {
                let r = res.data;
                if (r.status == 'success') {
                    if (n == 1) {
                        vm.list.g1.unshift(r.data);
                    }else if (n == 2) {
                        vm.list.g2.unshift(r.data);
                    }else if (n == 3) {
                        vm.list.g3.unshift(r.data);
                    }else if (n == 4) {
                        vm.list.g4.unshift(r.data);
                    }else if (n == 5) {
                        vm.list.g5.unshift(r.data);
                    }else if (n == 6) {
                        vm.list.g6.unshift(r.data);
                    }
                }
            }).catch(err => {
                console.log(err);
            })
        }
    }
}
</script>
<style>
.input-group-addon{cursor: pointer;}
.modal.in{background-color: rgba(255,255,255,1);}
</style>