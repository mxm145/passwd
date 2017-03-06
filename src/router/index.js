import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

const list = resolve => {
  require.ensure(['../components/list.vue'], () => {
    resolve(require('../components/list.vue'))
  })
}
const add = resolve => {
    require.ensure(['../components/add.vue'], () => {
        resolve(require('../components/add.vue'))
    })
}
const err = resolve => {
    require.ensure(['../components/error.vue'], () => {
        resolve(require('../components/error.vue'))
    })
}
export default new Router({
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/list/:tag',
      name: 'list',
      component: list
    },
    {
      path: '/add/:tag',
      name: 'add',
      component: add
    },
    {
      path: '/err',
      name: 'err',
      component: err
    },
    {
      path: '*',
      redirect: {name: 'err'}
    }
  ]
});