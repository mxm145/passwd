import Vue from 'vue'
import manage from './components/manage.vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
Vue.use(ElementUI)

new Vue({
  el: '#password',
  components: {
    manage: manage
  }
})