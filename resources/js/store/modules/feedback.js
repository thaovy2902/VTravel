import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  feedbacks: [],
  loading: false
};

const getters = {
  feedbacks(state) {
    return state.feedbacks;
  },
  loading(state) {
    return state.loading;
  }
};

const actions = {
  async fetchFeedbacks({ commit }, payload) {
    return new Promise((reslove, reject) => {
      let url = "owner/feedbacks";
      if (payload.page) url += `?page=${payload.page}`;
      if (payload.perPage) url += `&perPage=${payload.perPage}`;
      if (payload.sortBy && payload.orderBy) url += `&sortBy=${payload.sortBy}&orderBy=${payload.orderBy}`;
      commit("setLoading", true);
      callerApi(url)
        .then(resp => {
          if (resp && resp.status === 200) {
            commit("fetchFeedbacks", resp.data.data);
            reslove(resp);
          }
        })
        .catch(err => {
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },

  async updateFeedback({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`owner/feedbacks/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateFeedback", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response) {
        const message = response.data.message;
        vp.$notify.error("Error", message);
      }
    }
  },

  async deleteFeedback({ commit }, id) {
    try {
      const { data, status } = await callerApi(`owner/feedbacks/${id}`, "DELETE");
      if (data && status === 200) {
        commit("deleteFeedback", id);
        vp.$notify.success("Success", data.message);
      }
    } catch ({ response }) {
      if (response) {
        const message = response.data.message;
        vp.$notify.error("Error", message);
      }
    }
  }
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  fetchFeedbacks(state, data) {
    state.feedbacks = data;
  },
  updateFeedback(state, data) {
    const index = state.feedbacks.findIndex(item => item.id === data.id);
    state.feedbacks.splice(index, 1, data);
  },
  deleteFeedback(state, id) {
    const index = state.feedbacks.findIndex(item => item.id === id);
    state.feedbacks.splice(index, 1);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
