<template>
  <a-layout ref="container" :style="{ overflowY: 'auto' }">
    <global-header />
    <a-layout-content id="global-layout" class="tb-margin">
      <router-view />
    </a-layout-content>
    <global-footer />
  </a-layout>
</template>

<script>
  import GlobalHeader from "./GlobalHeader";
  import GlobalFooter from "./GlobalFooter";

  export default {
    components: { GlobalHeader, GlobalFooter },
    created() {
      const fb = document.getElementById("fb-root");
      if (fb) {
        fb.removeAttribute("style");
      }
    },
  };
</script>

<style scoped>
  #global-layout {
    margin-top: 64px;
    margin-bottom: 32px;
    min-height: calc(100vh - 165px);
  }
</style>
