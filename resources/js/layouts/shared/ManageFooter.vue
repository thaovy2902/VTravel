<template>
  <a-layout-footer style="text-align: center">
    Made with
    <a-icon type="heart" theme="twoTone" twoToneColor="#eb2f96" /> by Huu Canh ©{{ getCurrentYear() }}
  </a-layout-footer>
</template>

<script>
  export default {
    methods: {
      getCurrentYear() {
        return new Date().getFullYear();
      }
    }
  };
</script>

<style></style>
