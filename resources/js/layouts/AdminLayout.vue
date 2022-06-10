<template>
  <a-layout :style="{ minHeight: '100vh' }">
    <manage-side-bar :manageRole="manageRole" />
    <a-layout>
      <manage-header />
      <a-layout-content class="layout-content">
        <router-view :key="$route.fullPath" />
      </a-layout-content>
      <manage-footer />
    </a-layout>
  </a-layout>
</template>

<script>
  import ManageHeader from "./shared/ManageHeader";
  import ManageSideBar from "./shared/ManageSideBar";
  import ManageFooter from "./shared/ManageFooter";

  export default {
    components: { ManageHeader, ManageSideBar, ManageFooter },
    created() {
      const fb = document.getElementById("fb-root");
      if (fb) {
        fb.style.display = "none";
      }
    },
    data() {
      return {
        manageRole: "admin",
      };
    },
  };
</script>

<style lang="less" scoped>
  .layout-content {
    margin: 24px;
    min-height: 280px;
  }
  .ant-menu-root.ant-menu-inline,
  .ant-menu-root.ant-menu-vertical,
  .ant-menu-root.ant-menu-vertical-left,
  .ant-menu-root.ant-menu-vertical-right {
    border: none;
  }
  .ant-drawer-content-wrapper {
    transition: all 0.5s;
  }
</style>
