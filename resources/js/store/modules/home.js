import { callerApi } from "@/helpers/caller-api";

const state = {
  slides: [],
  topRatings: [],
  toursNew: [],
  toursFeatured: [],
  loadingSlide: false,
  loadingRating: false,
  loadingToursNew: false,
  loadingToursFeatured: false
};

const getters = {
  topRatings(state) {
    return state.topRatings;
  },
  toursNew(state) {
    return state.toursNew;
  },
  toursFeatured(state) {
    return state.toursFeatured;
  },
  slides(state) {
    return state.slides;
  },
  loadingSlide(state) {
    return state.loadingSlide;
  },
  loadingRating(state) {
    return state.loadingRating;
  },
  loadingToursNew(state) {
    return state.loadingToursNew;
  },
  loadingToursFeatured(state) {
    return state.loadingToursFeatured;
  }
};

const actions = {
  async fetchSlides({ commit }) {
    try {
      commit("setLoadingSlide", true);
      const { data, Status } = await callerApi("slides");
      if (data && Status === 200) {
        commit("fetchSlides", data.data);
      }
    } catch ({ response }) {
      console.log(response.data.message);
    } finally {
      commit("setLoadingSlide", false);
    }
  },
  async fetchTopRating({ commit }) {
    try {
      commit("setLoadingRating", true);
      const { data, Status } = await callerApi("top-rating");
      if (data && Status === 200) {
        commit("fetchTopRating", data.data);
      }
    } catch ({ response }) {
      console.log(response.data.message);
    } finally {
      commit("setLoadingRating", false);
    }
  },
  async fetchToursNew({ commit }) {
    try {
      commit("setLoadingToursNew", true);
      const { data, Status } = await callerApi("tours-new");
      if (data && Status === 200) {
        commit("fetchToursNew", data.data);
      }
    } catch ({ response }) {
      console.log(response.data.message);
    } finally {
      commit("setLoadingToursNew", false);
    }
  },
  async fetchToursFeatured({ commit }) {
    try {
      commit("setLoadingToursFeatured", true);
      const { data, Status } = await callerApi("tours-featured");
      if (data && Status === 200) {
        commit("fetchToursFeatured", data.data);
      }
    } catch ({ response }) {
      console.log(response.data.message);
    } finally {
      commit("setLoadingToursFeatured", false);
    }
  }
};

const mutations = {
  setLoadingSlide(state, data) {
    state.loadingSlide = data;
  },
  setLoadingRating(state, data) {
    state.loadingRating = data;
  },
  setLoadingToursNew(state, data) {
    state.loadingToursNew = data;
  },
  setLoadingToursFeatured(state, data) {
    state.loadingToursFeatured = data;
  },
  fetchSlides(state, data) {
    state.slides = data;
  },
  fetchTopRating(state, data) {
    state.topRatings = data;
  },
  fetchToursNew(state, data) {
    state.toursNew = data;
  },
  fetchToursFeatured(state, data) {
    state.toursFeatured = data;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
