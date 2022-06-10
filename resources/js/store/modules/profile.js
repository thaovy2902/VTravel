import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  user: {},
  loading: false
};

const getters = {
  user(state) {
    return state.user;
  },
  loading(state) {
    return state.loading;
  }
};

const actions = {
  async getUser({ commit }) {
    try {
      commit("setLoading", true);
      const { data, status } = await callerApi("profile");
      if (data && status === 200) {
        commit("getUser", data);
      }
    } catch ({ response }) {
      if (response) {
        console.log(response.data);
        vp.$message.error("Something wrong!");
      }
    } finally {
      commit("setLoading", false);
    }
  },
  async updatePassword({ commit }, payload) {
    try {
      const { data, status } = await callerApi("auth/update-password", "PUT", payload);
      if (data && status === 200) {
        vp.$message.success(data.message);
      }
    } catch (error) {
      if (error.response && error.response.status === 422) {
        vp.$notify.error("Lỗi", error.response.data.message);
        throw new Error(error);
      }
    }
  },
  async updateDetails({ commit, dispatch }, payload) {
    try {
      const { data, status } = await callerApi("auth/update-details", "PUT", payload);
      if (data && status === 200) {
        dispatch("auth/setUser", data.user, { root: true });
        vp.$message.success(data.message);
      }
    } catch (error) {
      if (error.response && error.response.status === 422) {
        vp.$notify.error("Lỗi", error.response.data.message);
        throw new Error(error);
      }
    }
  },
  async updateAvatar({ commit, dispatch }, payload) {
    try {
      const { data, status } = await callerApi("auth/update-avatar", "PUT", payload);
      if (data && status === 200) {
        dispatch("auth/setUser", data.user, { root: true });
        vp.$message.success(data.message);
      }
    } catch ({ response }) {
      console.log(response.data);
    }
  }
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  getUser(state, data) {
    state.user = data;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
