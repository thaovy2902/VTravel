import { callerApi } from "@/helpers/caller-api";
import store from "@/store";

const state = {
  tours: [],
  loading: false
};

const getters = {
  tours(state) {
    return state.tours;
  },
  loading(state) {
    return state.loading;
  }
};

const actions = {
  async fetchTours({ commit }) {
    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      const query = store.state.route.query;
      let url = `tours`;
      if (query.page) url += `?page=${query.page}`;
      if (query.q) url += `&q=${query.q}`;
      if (query.sortBy) url += `&sortBy=${query.sortBy}`;
      if (query.orderBy) url += `&orderBy=${query.orderBy}`;

      if (query.category) url += `&category=${query.category}`;
      if (query.depart) url += `&depart=${query.depart}`;
      if (query.toPlace) url += `&toPlace=${query.toPlace}`;
      if (query.featured) url += `&featured=${query.featured}`;
      if (query.minPrice) url += `&minPrice=${query.minPrice}`;
      if (query.maxPrice) url += `&maxPrice=${query.maxPrice}`;

      callerApi(url)
        .then(resp => {
          if (resp.data && resp.status === 200) {
            commit("fetchTours", resp.data.data);
            reslove(resp.data.meta);
          }
        })
        .catch(error => {
          reject(error);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  }
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  fetchTours(state, data) {
    state.tours = data;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
