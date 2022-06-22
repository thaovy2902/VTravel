import { callerApi } from "@/helpers/caller-api";

const state = {
  statistic: {},
  loadingStatistic: false,
  newOrders: [],
  loadingNewOrder: false,
  ratioOrder: {},
  loadingRatioOrder: false,
  popularTour: [],
  loadingPopularTour: false,
  revenue: {},
  loadingRevenue: false
};

const getters = {
  countUserTotal(state) {
    if (state.statistic.countUser) {
      return state.statistic.countUser.total;
    }
  },
  countUserDifference(state) {
    if (state.statistic.countUser) {
      const prevMonth = state.statistic.countUser.prevMonth;
      const currentMonth = state.statistic.countUser.currentMonth;

      return currentMonth - prevMonth;
    }
  },
  countTourTotal(state) {
    if (state.statistic.countTour) {
      return state.statistic.countTour.total;
    }
  },
  countTourDifference(state) {
    if (state.statistic.countTour) {
      const prevMonth = state.statistic.countTour.prevMonth;
      const currentMonth = state.statistic.countTour.currentMonth;

      return currentMonth - prevMonth;
    }
  },
  countOrderTotal(state) {
    if (state.statistic.countOrder) {
      return state.statistic.countOrder.total;
    }
  },
  countOrderDifference(state) {
    if (state.statistic.countOrder) {
      const prevMonth = state.statistic.countOrder.prevMonth;
      const currentMonth = state.statistic.countOrder.currentMonth;

      return currentMonth - prevMonth;
    }
  },
  totalRevenue(state) {
    if (state.statistic.totalRevenue) {
      return state.statistic.totalRevenue.total;
    }
  },
  totalRevenueDifference(state) {
    if (state.statistic.countTour) {
      const prevMonth = state.statistic.totalRevenue.prevMonth;
      const currentMonth = state.statistic.totalRevenue.currentMonth;

      return currentMonth - prevMonth;
    }
  },
  loadingStatistic(state) {
    return state.loadingStatistic;
  },
  newOrders(state) {
    return state.newOrders;
  },
  loadingNewOrder(state) {
    return state.loadingNewOrder;
  },
  getOrderById(state) {
    return orderId => {
      return state.newOrders.find(order => {
        return order.id === orderId;
      });
    };
  },
  totalOrderDomestic(state) {
    if (state.ratioOrder) {
      return state.ratioOrder.totalOrderDomestic;
    }
  },
  totalOrderForeign(state) {
    if (state.ratioOrder) {
      return state.ratioOrder.totalOrderForeign;
    }
  },
  loadingRatioOrder(state) {
    return state.loadingRatioOrder;
  },
  popularTour(state) {
    return state.popularTour;
  },
  loadingPopularTour(state) {
    return state.loadingPopularTour;
  },
  revenue(state) {
    return state.revenue;
  },
  loadingRevenue(state) {
    return state.loadingRevenue;
  }
};

const actions = {
  async fetchStatistic({ commit }) {
    try {
      commit("setLoadingStatistic", true);
      const { data, status } = await callerApi(`dashboard/statistic`);
      if (data && status === 200) {
        commit("fetchStatistic", data);
      }
    } catch ({ response }) {
      console.log(response.data);
    } finally {
      commit("setLoadingStatistic", false);
    }
  },
  async fetchNewOrder({ commit }, payload) {
    return new Promise((reslove, reject) => {
      let url = "dashboard/new-order";
      if (payload.page) url += `?page=${payload.page}`;
      if (payload.q) url += `&q=${payload.q}`;
      commit("setLoadingNewOrder", true);
      callerApi(url)
        .then(resp => {
          if (resp && resp.status === 200) {
            commit("fetchNewOrder", resp.data.data);
            reslove(resp.data.meta);
          }
        })
        .catch(err => {
          reject(err);
        })
        .finally(() => {
          commit("setLoadingNewOrder", false);
        });
    });
  },
  async updateStatusOrder({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`dashboard/update-status-order/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateStatusOrder", data);
      }
    } catch ({ response }) {
      console.log(response.data);
    }
  },
  async fetchRatioOrder({ commit }) {
    try {
      commit("setLoadingRatioOrder", true);
      const { data, status } = await callerApi("dashboard/ratio-order");
      if (data && status === 200) {
        commit("fetchRatioOrder", data);
      }
    } catch ({ response }) {
      console.log(response.data);
    } finally {
      commit("setLoadingRatioOrder", false);
    }
  },
  async fetchPopularTour({ commit }) {
    try {
      commit("setLoadingPopularTour", true);
      const { data, status } = await callerApi("dashboard/popular-tour");
      if (data && status === 200) {
        commit("fetchPopularTour", data);
      }
    } catch ({ response }) {
      console.log(response.data);
    } finally {
      commit("setLoadingPopularTour", false);
    }
  },
  async fetchRevenue({ commit }, payload) {
    try {
      let url = "dashboard/revenue";
      if (payload.type) url += `?type=${payload.type}`;
      if (payload.month) url += `&month=${payload.month}`;
      if (payload.quarter) url += `&quarter=${payload.quarter}`;
      if (payload.year) url += `&year=${payload.year}`;

      commit("setLoadingRevenue", true);
      const { data, status } = await callerApi(url);
      if (data && status === 200) {
        commit("fetchRevenue", data);
      }
    } catch ({ response }) {
      console.log(response);
    } finally {
      commit("setLoadingRevenue", false);
    }
  }
};

const mutations = {
  setLoadingStatistic(state, data) {
    state.loadingStatistic = data;
  },
  fetchStatistic(state, data) {
    state.statistic = data;
  },
  setLoadingNewOrder(state, data) {
    state.loadingNewOrder = data;
  },
  fetchNewOrder(state, data) {
    state.newOrders = data;
  },
  updateStatusOrder(state, data) {
    const index = state.newOrders.findIndex(item => item.id === data.id);
    state.newOrders.splice(index, 1, data);
  },
  setLoadingRatioOrder(state, data) {
    state.loadingRatioOrder = data;
  },
  fetchRatioOrder(state, data) {
    state.ratioOrder = data;
  },
  setLoadingPopularTour(state, data) {
    state.loadingPopularTour = data;
  },
  fetchPopularTour(state, data) {
    state.popularTour = data;
  },
  setLoadingRevenue(state, data) {
    state.loadingRevenue = data;
  },
  fetchRevenue(state, data) {
    state.revenue = data;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
