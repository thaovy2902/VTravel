<template>
  <div class="container">
    <a-card class="login m-auto shadow" :bordered="false" :bodyStyle="{ padding: '0' }">
      <a-row>
         <slot></slot>
      </a-row>
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
    .login {
      width: 1000px;
      .logo {
        text-align: center;
        margin-bottom: 20px;
      }
    }
  }
</style>
