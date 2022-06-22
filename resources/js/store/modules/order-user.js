import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";
import store from "@/store";
import router from "@/router";

const localStorageKeys = {
  orderInfo: "order__info",
  userInfo: "user__info",
};

const rand = () => {
  return Math.random()
    .toString(36)
    .substr(2);
};

const state = {
  tour: {},
  discountCode: {},
  orders: [],
  payments: [],
  loading: false,
  verified: false,
  orderInfo: JSON.parse(localStorage.getItem(localStorageKeys.orderInfo)),
  userInfo: JSON.parse(localStorage.getItem(localStorageKeys.userInfo)),
};

const getters = {
  tour(state) {
    return state.tour;
  },
  orders(state) {
    return state.orders;
  },
  getOrderById(state) {
    return (orderId) => {
      return state.orders.find((order) => {
        return order.id === orderId;
      });
    };
  },
  loading(state) {
    return state.loading;
  },
  verified(state) {
    return state.verified;
  },
};

const actions = {
  async fetchOrders({ commit }) {
    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      const query = store.state.route.query;
      let url = `orders`;
      if (query.page) url += `?page=${query.page}`;
      if (query.perPage) url += `&perPage=${query.perPage}`;
      if (query.status) url += `&status=${query.status}`;
      if (query.q) url += `&q=${query.q}`;

      callerApi(url)
        .then(({ data, status }) => {
          if (data && status === 200) {
            commit("fetchOrders", data.data);
            reslove(data.meta);
          }
        })
        .catch((error) => {
          reject(error);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  async getTourData({ commit, state }) {
    try {
      const tourId = state.orderInfo.tour_id;
      const { data, status } = await callerApi(`get-tour-data/${tourId}`);
      if (data && status === 200) {
        commit("getTourData", data);
      }
    } catch (error) {
      console.log(error);
    }
  },
  async createOrder({ commit }, payload) {
    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi("orders", "POST", payload)
        .then(({ data, status }) => {
          if (data && status === 201) {
            reslove(data);
          }
        })
        .catch((error) => {
          reject(error);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  async cancelOrder({ commit, dispatch }, payload) {
    try {
      const { data, status } = await callerApi(`orders/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        vp.$message.success("Hủy thành công");
        dispatch("fetchOrders");
      }
    } catch (error) {
      console.log(error);
    }
  },
  async sendVerifyToMail({ commit }, payload) {
    try {
      commit("setLoading", true);
      const { data, status } = await callerApi("send-mail-verify-transaction", "POST", payload);
      if (data && status === 200) {
        vp.$message.success(data.message);
      }
    } finally {
      commit("setLoading", false);
    }
  },
  async verifyTransaction({ commit }, payload) {
    try {
      const { data, status } = await callerApi("verify-transaction", "POST", payload);
      if (data && status === 200) {
        commit("setVerify", data.message.isSuccess);
      }
    } catch ({ response }) {
      if (response && response.status === 404) {
        commit("setVerify", response.data.message.isSuccess);
        vp.$message.error(response.data.message.message);
        throw new Error(response);
      }
    }
  },
  saveOrderInfo({ commit }, payload) {
    const slug = store.state.route.params.slug;
    const token = (rand() + rand() + rand() + rand()).substr(0, 60);
    const data = {
      token,
      slug,
      ...payload,
    };
    commit("saveOrderInfo", data);
    router.push({ name: "checkout", query: { step: 0, token: token } });
  },
  destroyOrderInfo({ commit }) {
    localStorage.removeItem(localStorageKeys.orderInfo);
  },
  async applyDiscount({ commit }, payload) {
    try {
      const { data, status } = await callerApi("apply-discount", "POST", payload);
      if (data && status === 200) {
        commit("setDiscount", data);
        vp.$message.info("Áp dụng mã giảm giá");
      }
    } catch ({ response }) {
      if (response) {
        vp.$message.error(response.data.message);
      }
    }
  },
  clearDiscount({ commit }) {
    commit("clearDiscount");
  },
  checkToken({ commit, state }) {
    const tokenUrl = store.state.route.query.token;
    const tokenLocalStorage = state.orderInfo.token;
    if (tokenUrl !== tokenLocalStorage) {
      vp.$message.error("Xin lỗi, không tìm thấy token");
      router.push({ name: "home" });
    }
  },
  async fetchPayments({ commit }) {
    try {
      const { data, status } = await callerApi("payments");
      if (data && status === 200) {
        commit("fetchPayments", data.data);
      }
    } catch (error) {
      console.log(error);
    }
  },
  saveUserInfo({ commit }, payload) {
    commit("saveUserInfo", payload);
  },
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  getTourData(state, data) {
    state.tour = data;
  },
  fetchOrders(state, data) {
    state.orders = data;
  },
  setVerify(state, data) {
    state.verified = data;
  },
  saveOrderInfo(state, data) {
    state.orderInfo = data;

    localStorage.setItem(localStorageKeys.orderInfo, JSON.stringify(data));
  },
  destroyOrderInfo(state) {
    state.orderInfo = null;

    localStorage.removeItem(localStorageKeys.orderInfo);
  },
  setDiscount(state, data) {
    state.discountCode = data;
  },
  clearDiscount(state) {
    state.discountCode = {};
  },
  fetchPayments(state, data) {
    state.payments = data;
  },
  saveUserInfo(state, data) {
    state.userInfo = data;

    localStorage.setItem(localStorageKeys.userInfo, JSON.stringify(data));
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
