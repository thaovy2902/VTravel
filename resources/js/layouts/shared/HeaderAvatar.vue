<template>
  <a-dropdown style="display: inline-block; height: 100%; vertical-align: initial" placement="bottomRight" :trigger="['click']">
    <div :style="{ cursor: 'pointer' }">
      <span style="marginRight:6px">
        <b>{{ $auth.user.name }}</b>
      </span>
      <a-avatar v-if="$auth.user.avatar" :src="$auth.user.avatar" :size="32" shape="circle" />
      <a-avatar v-else icon="user" :size="32" />
    </div>

    <a-menu style="width: 150px" slot="overlay">
      <a-menu-item>
        <router-link :to="{ name: 'profile.info' }"><a-icon type="user" :style="{ marginRight: '5px' }" />Trang cá nhân</router-link>
      </a-menu-item>
      <a-menu-divider />
      <a-menu-item>
        <a @click="logout"> <a-icon type="logout" :style="{ marginRight: '5px' }" />Đăng xuất </a>
      </a-menu-item>
    </a-menu>
  </a-dropdown>
</template>

<script>
  import { mapActions } from "vuex";
  export default {
    methods: {
      ...mapActions("auth", ["logout"])
    }
  };
</script>

<style></style>
