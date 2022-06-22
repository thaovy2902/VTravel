import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  orders: [],
  loading: false,
};

const getters = {
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
};

const actions = {
  async fetchOrders({ commit }, payload) {
    let url = "admin/orders";
    if (payload.page) {
      url += `?page=${payload.page}`;
    } else {
      url += "?page=1";
    }
    if (payload.perPage) url += `&perPage=${payload.perPage}`;
    if (payload.status) url += `&status=${payload.status}`;
    if (payload.from_date) url += `&from_date=${payload.from_date}`;
    if (payload.to_date) url += `&to_date=${payload.to_date}`;
    if (payload.q) url += `&q=${payload.q}`;

    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(url)
        .then((resp) => {
          if (resp && resp.status === 200) {
            commit("fetchOrders", resp.data.data);
            reslove(resp);
          }
        })
        .catch((err) => {
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  async updateOrder({ commit }, payload) {
    try {
      await callerApi(`admin/orders/${payload.id}`, "PUT", payload.values);
    } catch ({ response }) {
      if (response) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  fetchOrders(state, data) {
    state.orders = data;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
