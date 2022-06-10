import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  categories: [],
  tours: [],
  loading: false
};

const getters = {
  categories(state) {
    return state.categories;
  },
  tours(state) {
    return state.tours;
  },
  getTourById(state) {
    return tourId => {
      return state.tours.find(tour => {
        return tour.id === tourId;
      });
    };
  },
  loading(state) {
    return state.loading;
  }
};

const actions = {
  async fetchCategories({ commit }) {
    try {
      const { data, status } = await callerApi(`categories`);
      if (data && status === 200) {
        commit("fetchCategories", data.data);
      }
    } catch ({ response }) {
      if (response) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },
  async fetchTours({ commit }, payload) {
    let url = "admin/tours";
    if (payload.page) url += `?page=${payload.page}`;
    if (payload.perPage) url += `&perPage=${payload.perPage}`;
    if (payload.sortBy && payload.orderBy) url += `&sortBy=${payload.sortBy}&orderBy=${payload.orderBy}`;
    if (payload.q) url += `&q=${payload.q}`;

    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(url)
        .then(resp => {
          if (resp.data && resp.status === 200) {
            commit("fetchTours", resp.data.data);
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

  async createTour({ commit, dispatch }, payload) {
    try {
      const { data, status } = await callerApi(`admin/tours`, "POST", payload);
      if (data && status === 201) {
        commit("createTour", data);
        dispatch("fetchTours");
        vp.$notify.success("Success", "Thêm thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateTour({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/tours/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateTour", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateStatusTour({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/update-status-tour/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateTour", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async deleteTour({ commit, dispatch }, id) {
    try {
      const { data, status } = await callerApi(`admin/tours/${id}`, "DELETE");
      if (data && status === 200) {
        commit("deleteTour", id);
        dispatch("fetchTours");
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
  fetchCategories(state, data) {
    state.categories = data;
  },
  fetchTours(state, data) {
    state.tours = data;
  },
  createTour(state, data) {
    state.tours.unshitf(data);
    state.tours.pop();
  },
  updateTour(state, data) {
    const index = state.tours.findIndex(item => item.id === data.id);
    state.tours.splice(index, 1, data);
  },
  deleteTour(state, id) {
    const index = state.tours.findIndex(item => item.id === id);
    state.tours.splice(index, 1);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
