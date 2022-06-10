import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  ratings: [],
  loading: false
};

const getters = {
  ratings: state => {
    return state.ratings;
  },
  getRatingById(state) {
    return ratingId => {
      return state.ratings.find(rating => {
        return rating.id === ratingId;
      });
    };
  },
  loading: state => {
    return state.loading;
  }
};

const actions = {
  async fetchRatings({ commit }, payload) {
    let url = "admin/ratings";
    if (payload.page) url += `?page=${payload.page}`;
    if (payload.perPage) url += `&perPage=${payload.perPage}`;
    if (payload.sortBy && payload.orderBy) url += `&sortBy=${payload.sortBy}&orderBy=${payload.orderBy}`;

    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(url)
        .then(resp => {
          if (resp && resp.status === 200) {
            commit("fetchratings", resp.data.data);
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

  async createRating({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/ratings`, "POST", payload);
      if (data && status === 201) {
        commit("createRating", data);
        vp.$notify.success("Success", "Thêm thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateRating({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/ratings/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateRating", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async deleteRating({ commit }, id) {
    try {
      const { data, status } = await callerApi(`admin/ratings/${id}`, "DELETE");
      if (data && status === 200) {
        commit("deleteRating", id);
        vp.$notify.success("Success", data.message);
      }
    } catch ({ response }) {
      if (response) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  }
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  fetchratings(state, data) {
    state.ratings = data;
  },
  createRating(state, data) {
    state.ratings.unshift(data);
    state.roles.pop();
  },
  updateRating(state, data) {
    const index = state.ratings.findIndex(item => item.id === data.id);
    state.ratings.splice(index, 1, data);
  },
  deleteRating(state, id) {
    const index = state.ratings.findIndex(item => item.id === id);
    state.ratings.splice(index, 1);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
