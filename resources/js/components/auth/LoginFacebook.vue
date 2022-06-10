<template>
  <v-facebook-login app-id="1303824389804613" @sdk-init="handleSdkInit"></v-facebook-login>
</template>

<script>
  import VFacebookLogin from "vue-facebook-login-component";

  export default {
    components: { VFacebookLogin },
    data() {
      return {
        facebook: {
          FB: {},
          scope: {}
        }
      };
    },
    methods: {
      handleSdkInit({ FB, scope }) {
        this.facebook.scope = scope;
        this.facebook.FB = FB;
      }
    }
  };
</script>

<style></style>
