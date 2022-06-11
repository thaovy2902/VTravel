import { callerApi } from "@/helpers/caller-api";

const state = {
  cities: []
};

const getters = {
  cities(state) {
    return state.cities;
  }
};

const actions = {
  async fetchCities({ commit }) {
    try {
      const { data, Status } = await callerApi(`cities`);
      if (data && Status === 200) {
        commit("fetchCities", data);
      }
    } catch ({ response }) {
      console.log(response.data);
    }
  }
};

const mutations = {
  fetchCities(state, data) {
    state.cities = data;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
