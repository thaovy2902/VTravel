import Vue from "vue";
import VueRouter from "vue-router";
import VueMeta from "vue-meta";
import { sync } from "vuex-router-sync";
import routes from "./routes";
import store from "@/store";
import globalMiddleware from "./middleware/global-middleware";

Vue.use(VueRouter);
Vue.use(VueMeta, {
  keyName: "metaInfo",
  attribute: "data-vue-meta",
  tagIDKeyName: "vmid",
  refreshOnceOnNavigation: true,
});

const router = new VueRouter({
  mode: "history",
  routes,
  scrollBehavior: (to, from, savedPosition) => {
    if (savedPosition) {
      return savedPosition;
    } else {
      return document.getElementById("app").scrollIntoView();
    }
  },
});

sync(store, router);
globalMiddleware(router);

export default router;
