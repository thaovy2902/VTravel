<template>
  <div class="container">
    <a-card class="login m-auto shadow" :bordered="false" :bodyStyle="{ padding: '16px' }">
      <div class="logo">
        <router-link :to="{ name: 'home' }">
          <img src="/img/logo.svg" alt="logo" />
        </router-link>
      </div>
      <slot></slot>
    </a-card>
  </div>
</template>

<script>
  export default {
    created() {
      const fb = document.getElementById("fb-root");
      if (fb) {
        fb.style.display = "none";
      }
    },
  };
</script>

<style lang="less" scoped>
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
    background: url(https://storage.cloud.google.com/graduation-4e427.appspot.com/images/bg-login.webp?hl=vi) no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
    .login {
      width: 410px;
      .logo {
        text-align: center;
        margin-bottom: 20px;
      }
    }
  }
</style>
