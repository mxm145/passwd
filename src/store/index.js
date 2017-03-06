import Vue from 'vue'
import Vuex from 'vuex'
import {fetchAll, fetchOne, update, add} from './api'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    tag: null,
    list: [],
    one: {},
    add: null,
    id: null
  },

  actions: {
    FETCH_ALL: ({commit}, {tag}) => {
      commit('SET_ACTIVE_TAG', tag)
      return fetchAll(tag).then((val) => {
        commit('SET_LIST', val.data.data)
        commit('SET_ADDABLE', val.data.add)
      })
    },
    FETCH_ONE: ({commit, state}, {type, livecodeView}) => {
      let tag = state.tag;
      let id  = state.id;
      return fetchOne(type, tag, id, livecodeView).then((val) => {
        if (val.data.status == 'success') {
          commit('SET_ONE', val.data.data)
        }else{
          throw 'the livecode is uncorrect'
        }
      })
    },
    UPDATE: ({commit, state}) => {
      let tag = state.tag;
      let one = state.one;
      let id = state.id;
      return update(tag, id, one).then((val) => {
        if (val.data.status !== 'success') {
          throw 'update fail'
        }
      })
    },
    ADD: ({commit, state}) => {
      let tag = state.tag;
      let one = state.one;
      return add(tag, one).then((val) => {
        if (val.data.status !== 'success') {
          throw 'add fail'
        }
      })
    }
  },

  mutations: {
    SET_LIST: (state, items) => {
      state.list = items
    },
    SET_ACTIVE_TAG: (state, tag) => {
      state.tag = tag
    },
    SET_ADDABLE: (state, addable) => {
      state.add = addable
    },
    SET_ONE: (state, items) => {
      state.one = items
    },
    SET_ID: (state, id) => {
      state.id = id
    }
  }
})
export default store