import Vue from "vue";
import Vuex from "vuex";

import auth from "./modules/auth";
import dashboard from "./modules/dashboard";
import feedback from "./modules/feedback";
import permission from "./modules/permission";
import rating from "./modules/rating";
import slide from "./modules/slide";
import tour from "./modules/tour";
import user from "./modules/user";
import order from "./modules/order";
import discountCode from "./modules/discount-code";

import home from "./modules/home";
import tourDetail from "./modules/tour-detail";
import searchToursAdvance from "./modules/search-tours-advance";
import profile from "./modules/profile";
import orderUser from "./modules/order-user";

import city from "./modules/city";
import notification from "./modules/notification";

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== "production";

export default new Vuex.Store({
  modules: {
    auth,
    dashboard,
    feedback,
    permission,
    rating,
    slide,
    tour,
    user,
    order,
    discountCode,

    home,
    tourDetail,
    searchToursAdvance,
    profile,
    orderUser,

    city,
    notification,
  },
  strict: debug,
});
