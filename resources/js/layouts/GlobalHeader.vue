<template>
  <div>
    <a-layout-header id="header">
      <a-row>
        <a-col :xs="4" :sm="4" :md="10" :lg="10">
              <router-link :to="{ name: 'home' }">
                <img class="logo" src="/img/logo.svg" alt="logo" />
              </router-link>
        </a-col>
        <a-col :xs="0" :sm="0" :md="7" :lg="7">
          <a-menu mode="horizontal" :selectedKeys="[$route.name]" :style="{ lineHeight: '62px' }">
            <a-menu-item key="home">
              <router-link :to="{ name: 'home' }">Home</router-link>
            </a-menu-item>
            <a-menu-item key="tours">
              <router-link :to="{ name: 'tours' }">Tours</router-link>
            </a-menu-item>
          </a-menu>
        </a-col>
        <a-col :xs="20" :sm="20" :md="7" :lg="7">
          <div class="menu-right">
            <div v-if="!$auth.loggedIn && !$auth.user">
              <a-button @click="$router.push({ name: 'login' })">Log In</a-button>
            </div>
            <div v-else :style="{ display: 'flex', flexDirection: 'row', alignItems: 'center' }">
              <a-button style="marginRight:24px" v-if="$auth.isOwner || $auth.isAdmin" @click="$router.push({ name: pageManager })">
                Dashboard
              </a-button>

              <header-notification />

              <header-avatar />
            </div>
          </div>
        </a-col>
      </a-row>
    </a-layout-header>
    <modal-feedback :visible="visibleModalFeedback" @close="closeModalFeedback" />
  </div>
</template>

<script>
  import { mapActions } from "vuex";
  import ModalFeedback from "@/components/modal/Feedback";
  import HeaderNotification from "./shared/HeaderNotification";
  import HeaderAvatar from "./shared/HeaderAvatar";
  export default {
    components: { ModalFeedback, HeaderNotification, HeaderAvatar },
    data() {
      return {
        visibleModalFeedback: false,
      };
    },
    computed: {
      pageManager() {
        if (this.$auth.isOwner) return "owner.dashboard";
        if (this.$auth.isAdmin) return "admin.dashboard";
      },
    },
    methods: {
      openModalFeedback() {
        this.visibleModalFeedback = true;
      },
      closeModalFeedback() {
        this.visibleModalFeedback = false;
      },
    },
  };
</script>

<style lang="less" scoped>
  #header {
    position: fixed;
    background: #fff;
    box-shadow: 0px 1px 10px 0 rgba(0, 0, 0, 0.2);
    z-index: 999;
    width: 100%;
    .logo {
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      width: 100px;
      height: 64px;
    }
    @media (max-width: 576px) {
      padding-right: 15px !important;
      padding-left: 15px !important;
    }
    @media (min-width: 576px) {
      padding-right: 25px !important;
      padding-left: 25px !important;
    }
    @media (min-width: 768px) {
      padding-right: 25px !important;
      padding-left: 25px !important;
    }
    @media (min-width: 992px) {
      padding-right: 35px !important;
      padding-left: 35px !important;
    }
    @media (min-width: 1200px) {
      padding-right: 120px !important;
      padding-left: 120px !important;
    }
    .menu-right {
      float: right;
      margin-right: 0;
      margin-left: auto;
    }
  }
  .ant-menu {
    font-size: 15px;
    font-weight: 500;
  }
  .ant-menu-horizontal {
    border-bottom: none;
    .ant-menu-item-selected {
      border-bottom: 2px solid #FF8F00;
    }
  }
</style>
