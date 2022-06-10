<template>
  <a-layout-header class="layout-header">
    <div class="right">
      <header-notification />
      <header-avatar />
    </div>
  </a-layout-header>
</template>

<script>
  import HeaderNotification from "./HeaderNotification";
  import HeaderAvatar from "./HeaderAvatar";
  export default {
    components: { HeaderNotification, HeaderAvatar }
  };
</script>

<style lang="less">
  .layout-header {
    background: #fff;
    padding: 0 24px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-end;
    box-shadow: 0px 0.3px 5px 0px rgba(0, 21, 41, 0.35);
    .right {
      display: flex;
      flex-direction: row;
      align-items: center;
    }
  }
</style>
