import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  slides: [],
  loading: false
};

const getters = {
  slides(state) {
    return state.slides;
  },
  getSlideById(state) {
    return slideId => {
      return state.slides.find(slide => {
        return slide.id === slideId;
      });
    };
  },
  loading(state) {
    return state.loading;
  }
};

const actions = {
  async fetchSlides({ commit }, payload) {
    let url = "admin/slides";
    if (payload.page) url += `?page=${payload.page}`;
    if (payload.perPage) url += `&perPage=${payload.perPage}`;
    if (payload.sortBy && payload.orderBy) url += `&sortBy=${payload.sortBy}&orderBy=${payload.orderBy}`;

    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(url)
        .then(resp => {
          if (resp.data && resp.status === 200) {
            commit("fetchSlides", resp.data.data);
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

  async createSlide({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/slides`, "POST", payload);
      if (data && status === 201) {
        commit("createSlide", data);
        vp.$notify.success("Success", "Thêm thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateSlide({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/slides/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateSlide", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateActiveSlide({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/update-active-slide/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateSlide", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async deleteSlide({ commit }, id) {
    try {
      const { data, status } = await callerApi(`admin/slides/${id}`, "DELETE");
      if (data && status === 200) {
        commit("deleteSlide", id);
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
  fetchSlides(state, data) {
    state.slides = data;
  },
  createSlide(state, data) {
    state.slides.unshitf(data);
    state.slides.pop();
  },
  updateSlide(state, data) {
    const index = state.slides.findIndex(item => item.id === data.id);
    state.slides.splice(index, 1, data);
  },
  deleteSlide(state, id) {
    const index = state.slides.findIndex(item => item.id === id);
    state.slides.splice(index, 1);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
