<template>
  <a-layout-sider
    breakpoint="md"
    collapsible
    v-model="collapsed"
    :width="220"
    :style="{ boxShadow: '1px 0 6px rgba(0,21,41,.35)', zIndex: '20' }"
  >
    <div class="sidebar-header">
      <router-link :to="{ name: 'home' }">
        <img v-if="!collapsed" style="width: 100px" src="/img/logo.svg" alt="logo" />
        <a-icon v-else theme="filled" type="smile" :style="{ fontSize: '32px', lineHeight: '32px', color: '#188ffd', padding: '10px 0' }" />
      </router-link>
    </div>
    <a-menu v-if="isAdmin" theme="dark" :selectedKeys="[$route.name]" mode="inline">
      <a-menu-item key="admin.dashboard">
        <router-link :to="{ name: 'admin.dashboard' }">
          <a-icon type="dashboard" />
          <span>Dashboard</span>
        </router-link>
      </a-menu-item>
      <a-menu-item key="admin.users">
        <router-link :to="{ name: 'admin.users' }">
          <a-icon type="team" />
          <span>Users</span>
        </router-link>
      </a-menu-item>
      <a-menu-item key="admin.tours">
        <router-link :to="{ name: 'admin.tours' }">
          <a-icon type="table" />
          <span>Tours</span>
        </router-link>
      </a-menu-item>
      <a-menu-item key="admin.orders">
        <router-link :to="{ name: 'admin.orders' }">
          <a-icon type="shopping-cart" />
          <span>Orders</span>
        </router-link>
      </a-menu-item>
      <a-menu-item key="admin.ratings">
        <router-link :to="{ name: 'admin.ratings' }">
          <a-icon type="star" />
          <span>Reviews</span>
        </router-link>
      </a-menu-item>

      <a-menu-item key="admin.slides">
        <router-link :to="{ name: 'admin.slides' }">
          <a-icon type="switcher" />
          <span>Slides</span>
        </router-link>
      </a-menu-item>

      <a-menu-item key="admin.discountcodes">
        <router-link :to="{ name: 'admin.discountcodes' }">
          <a-icon type="gift" />
          <span>Discounts</span>
        </router-link>
      </a-menu-item>
    </a-menu>
  </a-layout-sider>
</template>

<script>
  export default {
    props: {
      manageRole: {
        type: String,
        required: true,
      },
    },
    computed: {
      isAdmin() {
        return this.manageRole === "admin";
      },
    },
    data() {
      return {
        collapsed: false,
      };
    },
  };
</script>

<style>
  .sidebar-header {
    width: 100%;
    height: 64px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
  }
  a {
    color: #868e96;
  }
  a:hover {
    color: #ff8f00 !important;
  }
</style>
