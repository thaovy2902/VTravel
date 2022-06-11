<template>
  <div :style="{ marginTop: '24px' }">
    <a-breadcrumb style="margin-bottom:16px">
      <a-breadcrumb-item>
        <router-link :to="{ name: 'home' }">Home</router-link>
      </a-breadcrumb-item>
      <a-breadcrumb-item>Personal Information</a-breadcrumb-item>
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
          { key: "profile.info", name: "Personal Information", icon: "profile" },
          { key: "profile.orders", name: "Payment History", icon: "history" },
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
