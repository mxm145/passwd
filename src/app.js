import Vue from 'vue'
import App from './app.vue'
import ElementUI from 'element-ui'
import store from './store'
import router from './router'

Vue.use(ElementUI)

const app = new Vue({
    router,
    store,
    ...App
})
app.$mount('#app')