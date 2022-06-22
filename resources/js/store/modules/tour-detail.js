import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";
import store from "@/store";

const state = {
  tour: {},
  relatedTours: {},
  ratings: [],
  avgRating: {},
  loading: false,
};

const getters = {
  tour(state) {
    return state.tour;
  },
  relatedTours(state) {
    return state.relatedTours;
  },
  ratings(state) {
    return state.ratings;
  },
  loading(state) {
    return state.loading;
  },
  getRating(state) {
    return state.ratings.find((item) => item.is_author === true);
  },
};

const actions = {
  async getTour({ commit }) {
    try {
      commit("setLoading", true);
      const slug = store.state.route.params.slug;
      const { data, status } = await callerApi(`tours/${slug}`);
      if (data && status === 200) {
        commit("getTour", data);
        // commit("fetchRatings", data.ratings);
      }
    } catch ({ response }) {
      if (response && response.status === 404) {
        vp.$notify.error("Lỗi", "Không tìm thấy");
      }
    } finally {
      commit("setLoading", false);
    }
  },
  async fetchRelatedTours({ commit }, payload) {
    const currentTourId = payload.currentTourId;
    const toPlace = payload.toPlace;
    try {
      const { data, status } = await callerApi(`related-tour?currentTourId=${currentTourId}&toPlace=${toPlace}`);
      if (data && status === 200) {
        commit("fetchRelatedTours", data.data);
      }
    } catch ({ response }) {
      console.log(response.data);
    }
  },
  async fetchRatings({ commit }, payload) {
    const slug = store.state.route.params.slug;
    const page = payload.page || 1;
    return new Promise((reslove, reject) => {
      callerApi(`tours/${slug}/ratings?page=${page}`)
        .then(({ data, status }) => {
          if (data && status === 200) {
            commit("fetchRatings", data.data);
            reslove(data);
          }
        })
        .catch(({ response }) => {
          if (response && response.status === 404) {
            vp.$notify.error("Lỗi", "Not found");
            reject(response);
          }
        });
    });
  },
  async sendRating({ commit, dispatch }, payload) {
    try {
      const slug = store.state.route.params.slug;
      const { data, status } = await callerApi(`tours/${slug}/ratings`, "POST", payload);
      if (data && status === 201) {
        vp.$message.success("Đánh giá thành công");
        commit("sendRating", data);
        dispatch("getAvgRating");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },
  async updateRating({ commit, dispatch }, payload) {
    try {
      const { data, status } = await callerApi(`ratings/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        vp.$message.success("Sửa thành công");
        commit("updateRating", data);
        dispatch("getAvgRating");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },
  async deleteRating({ commit, dispatch }, id) {
    try {
      const { data, status } = await callerApi(`ratings/${id}`, "DELETE");
      if (data && status === 200) {
        vp.$message.success("Xóa đánh giá thành công");
        dispatch("fetchRatings", { page: 1 });
        dispatch("getAvgRating");
      }
    } catch ({ response }) {
      if (response && response.status === 401) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },
  async getAvgRating({ commit }) {
    try {
      const slug = store.state.route.params.slug;
      const { data, status } = await callerApi(`avg-rating/${slug}`);
      if (data && status === 200) {
        commit("getAvgRating", data);
      }
    } catch (error) {
      console.log(error);
    }
  },
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  getTour(state, data) {
    state.tour = data;
  },
  fetchRelatedTours(state, data) {
    state.relatedTours = data;
  },
  fetchRatings(state, data) {
    state.ratings = data;
  },
  sendRating(state, data) {
    state.ratings.unshift(data);
  },
  updateRating(state, data) {
    const index = state.ratings.findIndex((item) => item.id === data.id);
    state.ratings.splice(index, 1, data);
  },
  getAvgRating(state, data) {
    state.avgRating = data;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
