<template>
  <div :style="{ marginTop: '24px' }">
    <a-breadcrumb style="margin-bottom:16px">
      <a-breadcrumb-item>
        <router-link :to="{ name: 'home' }">Trang chủ</router-link>
      </a-breadcrumb-item>
      <a-breadcrumb-item>Thông tin cá nhân</a-breadcrumb-item>
    </a-breadcrumb>

    <a-tabs :defaultActiveKey="$route.name" @change="onChangeTab">
      <a-tab-pane v-for="tab in tabs" :key="tab.key">
        <span slot="tab">
          <a-icon :type="tab.icon" />
          {{ tab.name }}
        </span>
        <router-view />
      </a-tab-pane>
    </a-tabs>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        tabs: [
          { key: "profile.info", name: "Thông tin cá nhân", icon: "profile" },
          { key: "profile.orders", name: "Lịch sử giao dịch", icon: "history" },
        ],
      };
    },
    methods: {
      onChangeTab(routerName) {
        this.$router.push({ name: routerName });
      },
    },
  };
</script>

<style></style>
