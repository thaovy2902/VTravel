<template>
  <div class="description">
    <div class="title"><a-icon v-if="icon" :type="icon" /> {{ title }}</div>
    {{ content }}
  </div>
</template>

<script>
  export default {
    props: ["icon", "title", "content"]
  };
</script>

<style lang="less" scoped>
  .description {
    font-size: 14px;
    line-height: 22px;
    color: rgba(0, 0, 0, 0.65);
    margin-bottom: 4px;
    .title {
      font-weight: 500;
      margin-right: 8px;
      display: inline-block;
      color: rgba(0, 0, 0, 0.85);
    }
  }
</style>
