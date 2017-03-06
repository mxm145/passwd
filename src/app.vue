<template>
  <div class="container">
    <transition name="fade" mode="out-in">
      <router-view class="view"></router-view>
    </transition>
  </div>
</template>
<script>
import 'element-ui/lib/theme-default/index.css'
</script>
<style>
.container{
  padding-right:15px;
  padding-left:15px;
  margin-right:auto;
  margin-left:auto
}
@media (min-width:768px){
  .container{width:750px}
}
@media (min-width:992px){
  .container{width:970px}
}
@media (min-width:1200px){
  .container{width:1170px}
}
.fade-enter-active, .fade-leave-active{
  transition: all .2s ease
}
.fade-enter, .fade-leave-active{
  opacity: 0
}
</style>