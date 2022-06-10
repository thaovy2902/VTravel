<template>
  <div>
    <vue-recaptcha ref="recaptcha" :sitekey="sitekey" @verify="onVerify" />
  </div>
</template>

<script>
  import VueRecaptcha from "vue-recaptcha";
  export default {
    components: { VueRecaptcha },
    data() {
      return {
        sitekey: "6Lc3K-AUAAAAAK10xMhETRaTzqjwXaXY8etuwxnX"
      };
    },
    created() {
      eventBus.$on("resetReCaptcha", this.resetReCaptcha);
    },
    beforeDestroy() {
      eventBus.$off("resetReCaptcha", this.resetReCaptcha);
    },
    methods: {
      onVerify(response) {
        this.$emit("responseReCaptcha", response);
      },
      resetReCaptcha() {
        this.$refs.recaptcha.reset();
      }
    }
  };
</script>

<style></style>
