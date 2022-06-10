import "./bootstrap";
import Vue from "vue";
import router from "./router";
import store from "./store";

import App from "./App.vue";

//plugins
import "./plugins/ant-design-vue";
import "./plugins/vue-progressbar";
import "./plugins/ckeditor";
import "./plugins/vue-lazyload";
import "./plugins/v-chart";
import "./plugins/vue-timeago";

//helpers
import "./helpers/notifications";
import "./helpers/filters";

//Global components
import "./globalComponent";

Vue.config.productionTip = false;

Vue.prototype.$auth = {
  get user() {
    return store.getters["auth/user"];
  },
  get loggedIn() {
    return store.getters["auth/loggedIn"];
  },
  get isOwner() {
    if (!this.user || !this.loggedIn) {
      return false;
    } else {
      return store.getters["auth/isOwner"];
    }
  },
  get isAdmin() {
    if (!this.user || !this.loggedIn) {
      return false;
    } else {
      return store.getters["auth/isAdmin"];
    }
  }
};

const app = new Vue({
  el: "#app",
  router,
  store,
  render: h => h(App),
  computed: {
    cities() {
      return this.$store.getters["city/cities"];
    }
  },
  created() {
    this.$store.dispatch("city/fetchCities");
  }
});
