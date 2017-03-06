<template>
<div>
  <el-row>
    <el-col></el-col>
  </el-row>
  <el-form ref="form" label-width="80px">
    <el-form-item label="ip地址">
      <el-input v-model="ip" id="ip" @blur="checkip()"></el-input>
      <span v-if="iprequired">ip必填</span>
      <span v-if="ipexsit">ip已存在</span>
    </el-form-item>
    <el-form-item label="登录用户">
      <el-input v-model="loginUser" @blur="checkname()"></el-input>
      <span v-if="namerequired">用户名必填</span>
    </el-form-item>
    <el-form-item label="登录密码">
      <el-input v-model="loginPassword" id="loginPassword"></el-input>
      <el-button @click="generatePassword(1)"><img :src="generateImg" width="16" height="16" alt="生成"> 生成密码</el-button>
      <el-button  class="cbtn" data-clipboard-target="#loginPassword > .el-input__inner"><img :src="clippy" width="13" height="15" alt="Copy"> 复制密码</el-button>
    </el-form-item>
    <el-form-item label="root密码">
      <el-input v-model="rootPassword" id="rootPassword"></el-input>
      <el-button @click="generatePassword(2)"><img :src="generateImg" width="16" height="16" alt="生成"> 生成密码</el-button>
      <el-button  class="cbtn" data-clipboard-target="#rootPassword > .el-input__inner"><img :src="clippy" width="13" height="15" alt="Copy"> 复制密码</el-button>
    </el-form-item>
    <el-form-item label="ssh端口">
      <el-input v-model="sshPort"></el-input>
    </el-form-item>
    <el-form-item label="跳板机">
      <el-select v-model="jump">
        <el-option label="201.4" value="201.4"></el-option>
        <el-option label="201.15" value="201.15"></el-option>
        <el-option label="201.88" value="201.88"></el-option>
        <el-option label="直连" value="直连"></el-option>
        <el-option label="未知" value="未知"></el-option>
      </el-select>
    </el-form-item>
    <el-form-item label="服务">
      <el-input type="textarea" v-model="service"></el-input>
    </el-form-item>
    <el-form-item label="位置">
      <el-input type="textarea" v-model="location"></el-input>
    </el-form-item>
    <el-form-item label="其他">
      <el-input type="textarea" v-model="other"></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="onSubmit">提交</el-button>
      <router-link :to="{path: '/list/'+ tag}" tag="el-button">返回</router-link>
    </el-form-item>
  </el-form>
</div>
</template>
<script>
import Clipboard from 'clipboard'
import generateImg from '../images/generate.png'
import clippy from '../images/clippy.png'
import {validAdd, checkIP} from '../store/api'
export default {
  name: 'add',
  data: function(){
    return {
      ip: '',
      loginUser: '',
      loginPassword: '',
      rootPassword: '',
      sshPort: '',
      service: '',
      location: '',
      jump: '201.4',
      other: '',
      ipexsit: false,
      tag: '',
      iprequired: false,
      namerequired: false,
      generateImg: generateImg,
      clippy: clippy
    }
  },
  mounted: function(){
    let tag = this.$route.params.tag;
    if (!parseInt(tag) || parseInt(tag) < 1 || parseInt(tag) > 3) {
      this.$router.replace({name: 'err'});
    }else{
      this.tag = tag;
      this.$store.commit('SET_ACTIVE_TAG', this.tag);
      validAdd(tag).then((val) => {
        if (val.data.status !== 'success') {
          this.$router.replace({name: 'err'});
        }else{
          if (tag == 1) {
            document.title = '开发';
          }else if (tag == 2) {
            document.title = '运维';
          }else if (tag == 3) {
            document.title = '网络';
          }
        }
      })
    }
    var clipboard = new Clipboard('.cbtn');
  },
  methods: {
    onSubmit: function(){
      if (!this.ipexsit && !this.iprequired && !this.namerequired) {
        let one = {
          ip: this.ip,
          loginUser: this.loginUser,
          loginPassword: this.loginPassword,
          rootPassword: this.rootPassword,
          sshPort: this.sshPort,
          service: this.service,
          location: this.location,
          jump: this.jump,
          other: this.other
        };
        this.$store.commit('SET_ONE', one);
        this.$store.dispatch('ADD').then(() => {
          this.ip = '';
          this.loginUser = '';
          this.loginPassword = '';
          this.rootPassword = '';
          this.sshPort = '';
          this.service = '';
          this.location = '';
          this.other = '';
        }).catch((err) => {
          console.log(err);
        })
      }
    },
    checkip: function(){
      if (this.ip == '') {
        this.iprequired = true;
      }else{
        this.iprequired = false;
        checkIP(this.tag, this.ip).then((res) => {
          if (res.status == 'success') {
            this.ipexsit = true;
          }else{
            this.ipexsit = false;
          }
        }).catch((err) => {
          console.log(err)
        })
      }
    },
    checkname: function(){
      if (this.loginUser == '') {
        this.namerequired = true;
      }else{
        this.namerequired = false;
      }
    },
    generatePassword: function(t){
      let source = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()~',
        len = Math.max(parseInt(Math.random()* 25), parseInt(Math.random()* 30)),
        maxPos = source.length,
        pwd = '';
      for (let i = 0; i < len; i++) {
      　pwd += source.charAt(Math.floor(Math.random() * maxPos));
      }
      if (t == 1) {
        this.loginPassword = pwd;
      }else{
        this.rootPassword = pwd;
      }
    }
  }
}
</script>