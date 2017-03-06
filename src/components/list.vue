<template>
<div>
  <el-row>
    <el-col :span="24">
      <div class="grid-content">
        <el-input v-model="search" placeholder="请输入需要搜索的IP"></el-input>
      </div>
    </el-col>
  </el-row>
  <el-table :data="filteredlist" border stripe>
    <el-table-column prop="ip" label="ip" width="160"></el-table-column>
    <el-table-column prop="service" label="服务"></el-table-column>
    <el-table-column prop="location" label="地点"></el-table-column>
    <el-table-column width="180" :render-header="renderHeader" header-align="center">
      <template scope="scope">
        <el-button size="middle" type="info" @click="detail(scope.row.id)">详细</el-button>
        <el-button size="middle" type="info" @click="update(scope.row.id)">修改</el-button>
      </template>
    </el-table-column>
  </el-table>
  <el-dialog title="请输入动态密码" v-model="livecode" size="tiny">
    <el-form>
      <el-form-item label="动态密码">
        <el-input v-model="livecodeView" id="livecodeView" placeholder="请输入动态密码" auto-complete="off"></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="valideViewCode">确 定</el-button>
    </span>
  </el-dialog>
  <el-dialog title="修改信息" v-model="updates" size="small" :close-on-click-modal="false" :close-on-press-escape="false" :lock-scroll="false">
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">ip</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input v-model="one.ip"></el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">登录用户</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input v-model="one.loginUser"></el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">登录密码</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input v-model="one.loginPassword"></el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">root密码</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input v-model="one.rootPassword"></el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">ssh端口</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input v-model="one.sshPort"></el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">跳板机</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-select v-model="one.jump">
            <el-option
              v-for="jp in jumpArr"
              :label="jp.label"
              :value="jp.value">
            </el-option>
          </el-select>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">服务</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input
            type="textarea"
            :rows="3"
            v-model="one.service">
          </el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">位置</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input
            type="textarea"
            :rows="3"
            v-model="one.location">
          </el-input>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">其他</div></el-col>
      <el-col :span="16">
        <div class="grid-content">
          <el-input
            type="textarea"
            :rows="3"
            v-model="one.other">
          </el-input>
        </div>
      </el-col>
    </el-row>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="updateForm">确 定</el-button>
    </span>
  </el-dialog>
  <el-dialog title="详细信息" v-model="details" size="small" :close-on-click-modal="false" :close-on-press-escape="false" :lock-scroll="false">
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">ip</div></el-col>
      <el-col :span="16"><div class="grid-content bg-purple-light">{{one.ip}}</div></el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">登录用户</div></el-col>
      <el-col :span="16">
        <div class="grid-content bg-purple-light">
          <span id="lU">{{one.loginUser}}</span>
          <span class="input-group-button">
            <button class="btn cbtn" type="button" data-clipboard-target="#lU">
              <img :src="clippy" width="13" height="15" alt="Copy">
            </button>
          </span>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">登录密码</div></el-col>
      <el-col :span="16">
        <div class="grid-content bg-purple-light">
          <span class="input-group-button">
            <button class="btn cbtn" type="button" :data-clipboard-text="one.loginPassword">
              <img :src="clippy" width="13" height="15" alt="Copy"> 复制密码
            </button>
          </span>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">root密码</div></el-col>
      <el-col :span="16">
        <div class="grid-content bg-purple-light">
          <span class="input-group-button">
            <button class="btn cbtn" type="button" :data-clipboard-text="one.rootPassword">
              <img :src="clippy" width="13" height="15" alt="Copy"> 复制密码
            </button>
          </span>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">ssh端口</div></el-col>
      <el-col :span="16"><div class="grid-content bg-purple-light">{{one.sshPort}}</div></el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">跳板机</div></el-col>
      <el-col :span="16"><div class="grid-content bg-purple-light">{{one.jump}}</div></el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">服务</div></el-col>
      <el-col :span="16"><div class="grid-content bg-purple-light" v-html="one.service"></div></el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">位置</div></el-col>
      <el-col :span="16"><div class="grid-content bg-purple-light" v-html="one.location"></div></el-col>
    </el-row>
    <el-row :gutter="10">
      <el-col :span="8"><div class="grid-content bg-purple-dark">其他</div></el-col>
      <el-col :span="16"><div class="grid-content bg-purple-light" v-html="one.other"></div></el-col>
    </el-row>
  </el-dialog>
</div>
</template>
<script>
import Clipboard from 'clipboard'
import clippy from '../images/clippy.png'
export default {
  name: 'list',
  data: function(){
    return {
      livecode: false,
      details: false,
      updates: false,
      search: '',
      list: [],
      codetype: '',
      livecodeView: '',
      one: {
        ip: '',
        loginUser: '',
        loginPassword: '',
        rootPassword: '',
        sshPort: '',
        jump: '',
        service: '',
        location: '',
        other: ''
      },
      jumpArr: [{
        label: '201.4',
        value: '201.4'
      },{
        label: '201.15',
        value: '201.15'
      },{
        label: '201.88',
        value: '201.88'
      },{
        label: '直连',
        value: '直连'
      },{
        label: '未知',
        value: '未知'
      }],
      tag: '',
      add: '',
      clippy: clippy
    }
  },
  mounted: function(){
    let tag = Number(this.$route.params.tag);
    if (!parseInt(tag) || parseInt(tag) < 1 || parseInt(tag) > 3) {
      this.$router.replace({name: 'err'});
    }else{
      this.tag = tag;
      this.loadLists();
      if (tag == 1) {
        document.title = '开发';
      }else if (tag == 2) {
        document.title = '运维';
      }else if (tag == 3) {
        document.title = '网络';
      }
    }
    var clipboard = new Clipboard('.cbtn');
  },
  computed: {
    filteredlist: function(){
      var self = this;
      return self.list.filter(function(list){
        return list.ip.indexOf(self.search) !== -1;
      })
    }
  },
  methods: {
    renderHeader: function(createElement, { column }){
      return createElement(
        'router-link', {props: {to: '/add/' + this.tag, 'tag': 'el-button'}}, ['添加']
      );
    },
    loadLists: function(){
      this.$store.dispatch('FETCH_ALL', {
        tag: this.tag
      }).then(() => {
        this.list = this.$store.state.list
        this.add = this.$store.state.add
      })
    },
    updateForm: function(){
      this.$store.dispatch('UPDATE')
      .then(() => {
        this.updates = false;
      })
      .catch((err) => {
        console.log(err);
      })
    },
    update: function(id){
      this.mutateId(id);
      this.livecodeView = '';
      this.codetype = 'update';
      this.livecode = true;
    },
    detail: function(id){
      this.mutateId(id);
      this.livecodeView = '';
      this.codetype = 'detail';
      this.livecode = true;
    },
    mutateId: function(id){
      this.$store.commit('SET_ID', id);
    },
    valideViewCode: function(){
      this.preventDefault;
      this.$store.dispatch('FETCH_ONE', {
        type: this.codetype,
        livecodeView: this.livecodeView
      }).then(() => {
        this.one = this.$store.state.one;
        this.livecode = false;
        if (this.codetype == 'detail') {
          this.details = true;
        }else{
          this.updates = true;
        }
      }).catch((err) => {
        console.log(err);
      })
    }
  }
}
</script>
<style>
.cbtn{
  position: relative;
  display: inline-block;
  padding: 6px 12px;
  font-size: 13px;
  font-weight: bold;
  line-height: 20px;
  color: #333;
  white-space: nowrap;
  vertical-align: middle;
  cursor: pointer;
  background-color: #eee;
  background-image: linear-gradient(#fcfcfc,#eee);
  border: 1px solid #d5d5d5;
  border-radius: 3px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  -webkit-appearance: none;
  margin-left: 10px;
}
.grid-content{
  line-height: 36px
}
.el-row {
  margin-bottom: 20px;
  text-align: center;
  &:last-child {
    margin-bottom: 0;
  }
}
.bg-purple-dark {
  background: #99a9bf;
  color: #fff
}
.bg-purple {
  background: #d3dce6;
}
.bg-purple-light {
  text-align: left;
  padding-left: 20px
}
.grid-content {
  border-radius: 2px;
  min-height: 36px;
}
.row-bg {
  padding: 10px 0;
  background-color: #f9fafc;
}
</style>