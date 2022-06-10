<template>
  <div class="loading-container">
    <a-spin class="loading" size="large" tip="Loading..." />
  </div>
</template>

<script>
  export default {};
</script>

<style scoped>
  .loading-container {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    height: 100vh;
    width: 100%;
    z-index: 1000;
    background: #000;
    opacity: 0.7;
  }
  .loading-container .loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>
