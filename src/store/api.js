import axios from 'axios'
import qs from 'qs'
import Promise from 'promise-polyfill'
if (!window.Promise) {
  window.Promise = Promise
}

function fetch(child, data) {
  if (data) {
    return axios.post(child, qs.stringify(data))
  }else{
    return axios.post(child)
  }
}

export function fetchAll(tag){
  return fetch('/api/all/' + tag)
}

export function fetchOne(type, tag, id, livecodeView){
  return fetch('/api/one/' +type + '/' + tag + '/' + id, {livecodeView: livecodeView})
}

export function update(tag, id, one){
  return fetch('/api/update/' + tag + '/' + id, one)
}

export function add(tag, one){
  return fetch('/api/add/' + tag, one)
}

export function validAdd(tag){
  return fetch('/api/validadd/' + tag)
}

export function checkIP(tag, ip){
  return fetch('/api/checkip/' + tag, {ip: ip})
}