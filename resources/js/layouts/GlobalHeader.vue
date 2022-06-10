<template>
  <div>
    <a-layout-header id="header">
      <a-row>
        <a-col :xs="4" :sm="4" :md="12" :lg="12">
          <a-row :gutter="{ xs: 0, sm: 8, md: 24, lg: 24 }">
            <a-col :xs="24" :sm="24" :md="5" :lg="5">
              <router-link :to="{ name: 'home' }">
                <img class="logo" src="/img/logo.svg" alt="logo" />
              </router-link>
            </a-col>
            <a-col :xs="0" :sm="0" :md="19" :lg="19">
              <a-menu mode="horizontal" :selectedKeys="[$route.name]" :style="{ lineHeight: '62px' }">
                <a-menu-item key="home">
                  <router-link :to="{ name: 'home' }">Trang chủ</router-link>
                </a-menu-item>
                <a-menu-item key="tours">
                  <router-link :to="{ name: 'tours' }">Tour du lịch</router-link>
                </a-menu-item>
              </a-menu>
            </a-col>
          </a-row>
        </a-col>
        <a-col :xs="20" :sm="20" :md="12" :lg="12">
          <div class="menu-right">
            <div v-if="!$auth.loggedIn && !$auth.user">
              <a-button
                :style="{ marginRight: '12px' }"
                class="color-link-icon"
                type="link"
                size="large"
                icon="message"
                @click="openModalFeedback"
              ></a-button>
              <a-button @click="$router.push({ name: 'login' })">Đăng nhập</a-button>
            </div>
            <div v-else :style="{ display: 'flex', flexDirection: 'row', alignItems: 'center' }">
              <a-button style="marginRight:24px" v-if="$auth.isOwner || $auth.isAdmin" @click="$router.push({ name: pageManager })">
                Quản trị
              </a-button>

              <a class="color-link-icon" @click="openModalFeedback">
                <a-icon type="message" :style="{ fontSize: '18px' }" />
              </a>

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
      padding-right: 50px !important;
      padding-left: 50px !important;
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
