<template>
  <div id="app">
    <vue-progress-bar />
    <router-view />
  </div>
</template>

<script>
  import { mapState, mapGetters } from "vuex";

  export default {
    metaInfo: {
      title: "Adventure Awaits, Go Find It.",
      titleTemplate: "%s | VTravel",
      meta: [{ vmid: "description", name: "description", content: "VTravel - Adventure Awaits, Go Find It." }],
    },
    computed: {
      ...mapState("auth", ["user"]),
      ...mapGetters("auth", ["loggedIn"]),
    },
    mounted() {
      this.$Progress.finish();
    },
    created() {
      this.$Progress.start();

      this.$router.beforeEach((to, from, next) => {
        if (to.meta.progress !== undefined) {
          let meta = to.meta.progress;
          this.$Progress.parseMeta(meta);
        }

        this.$Progress.start();

        next();
      });

      this.$router.afterEach((to, from) => {
        this.$Progress.finish();
      });
    },
  };
</script>

<style lang="less">
  :global {
    .fade-enter-active,
    .fade-leave-active {
      transition-duration: 0.3s;
      transition-property: opacity;
      transition-timing-function: ease;
    }

    .fade-enter,
    .fade-leave-active {
      opacity: 0;
    }
    .slide-left-enter-active,
    .slide-left-leave-active,
    .slide-right-enter-active,
    .slide-right-leave-active {
      transition-duration: 0.5s;
      transition-property: height, opacity, transform;
      transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
      overflow: hidden;
    }

    .slide-left-enter,
    .slide-right-leave-active {
      opacity: 0;
      transform: translate(2em, 0);
    }

    .slide-left-leave-active,
    .slide-right-enter {
      opacity: 0;
      transform: translate(-2em, 0);
    }

    .page-toggle-enter-active {
      transition: all 0.2s ease-in 0.25s;
    }
    .page-toggle-leave-active {
      transition: all 0.2s ease-out 0s;
    }
    .page-toggle-enter,
    .page-toggle-leave-to {
      opacity: 0;
      padding: 0px;
    }

    html,
    body {
      padding: 0;
      margin: 0;
      line-height: 1.5;
      color: #1c1e21;
      scroll-behavior: smooth;
    }

    ::-webkit-scrollbar {
      width: 7px;
    }
    ::-webkit-scrollbar-track {
      border-radius: 6px;
      background: #f0f2f5;
    }
    ::-webkit-scrollbar-thumb {
      border-radius: 6px;
      background: rgba(0, 0, 0, 0.2);
    }
    ::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.3);
    }
  }

  @media (max-width: 576px) {
    .tb-padding {
      padding-right: 15px;
      padding-left: 15px;
    }
    .tb-margin {
      margin-right: 15px;
      margin-left: 15px;
    }
  }

  @media (min-width: 576px) {
    .tb-padding {
      padding-right: 25px;
      padding-left: 25px;
    }
    .tb-margin {
      margin-right: 25px;
      margin-left: 25px;
    }
  }

  @media (min-width: 768px) {
    .tb-padding {
      padding-right: 25px;
      padding-left: 25px;
    }
    .tb-margin {
      margin-right: 25px;
      margin-left: 25px;
    }
  }

  @media (min-width: 992px) {
    .tb-padding {
      padding-right: 35px;
      padding-left: 35px;
    }
    .tb-margin {
      margin-right: 35px;
      margin-left: 35px;
    }
  }

  @media (min-width: 1200px) {
    .tb-padding {
      padding-right: 120px;
      padding-left: 120px;
    }
    .tb-margin {
      margin-right: 120px;
      margin-left: 120px;
    }
  }
</style>
