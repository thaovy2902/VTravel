import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";
import router from "@/router";

const OWNER = 1;
const ADMIN = 2;

const localStorageKeys = {
  token: "auth__token",
  orderInfo: "order__info",
  userInfo: "user__info",
};

const state = {
  user: null,
  token: localStorage.getItem(localStorageKeys.token),
  loading: false,
};
const getters = {
  user(state) {
    return state.user;
  },
  isOwner(state) {
    return state.user.role_id === OWNER;
  },
  isAdmin(state) {
    return state.user.role_id === ADMIN;
  },
  loggedIn(state) {
    return !!state.user;
  },
  loading(sate) {
    return sate.loading;
  },
};
const actions = {
  async login({ commit }, credentials) {
    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(`auth/login`, "POST", credentials)
        .then(({ data, status }) => {
          if (data && status === 200) {
            commit("setAuth", data);
            vp.$message.success("Đăng nhập thành công");
            router.push({ name: "home" });
            reslove(data);
          }
        })
        .catch((error) => {
          if (error.response) {
            let message;
            if (error.response.status === 422) {
              message = Object.values(error.response.data.message)[0];
            }
            if (error.response.status === 401) {
              message = Object.values(error.response.data.message);
            }
            vp.$notify.error("Lỗi đăng nhập", message);
            reject(error);
          }
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  async register({ commit }, credentials) {
    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(`auth/register`, "POST", credentials)
        .then(({ data, status }) => {
          if (data && status === 200) {
            commit("setAuth", data);
            vp.$message.success("Đăng ký thành công");
            reslove(data);
          }
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            const message = Object.values(error.response.data.message)[0];
            vp.$notify.error("Lỗi đăng ký", message);
            reject(error);
          }
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  async logout({ commit }) {
    await callerApi(`auth/logout`).then(() => {
      commit("purgeAuth");
      commit("clearLocalStorage");
      window.location.replace("/");
    });
  },
  async me({ commit }) {
    try {
      const { data, status } = await callerApi("auth/me");
      if (data && status === 200) {
        commit("setAuth", data);
      }
    } catch ({ response }) {
      if (response && response.status === 401) {
        commit("purgeAuth");
        vp.$notify.warning("Oops...", "Phiên làm việc đã hết hạn, vui lòng đăng nhập lại!");
      }
    }
  },
  async refresh({ commit }) {
    const { data, status } = await callerApi(`auth/refresh`, "POST");
    if (data && status === 200) {
      commit("setAuth", data);
    }
  },
  async forgotPassword({ commit }, payload) {
    try {
      commit("setLoading", true);
      const { data, status } = await callerApi(`auth/forgot-password`, "POST", payload);
      if (data && status === 200) {
        vp.$notify.success("Forgot password", data.message);
      }
    } catch ({ response }) {
      if (response) {
        vp.$notify.error("Error", response.data.message);
        throw new Error(response);
      }
    } finally {
      commit("setLoading", false);
    }
  },
  async resetPassword({ commit }, payload) {
    try {
      commit("setLoading", true);
      const { data, status } = await callerApi(`auth/reset-password`, "PUT", payload);
      if (data && status === 200) {
        vp.$notify.success("Reset password", data.message);
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message);
        vp.$notify.error("Error", message);
        throw new Error(response);
      }
    } finally {
      commit("setLoading", false);
    }
  },
  async setUser({ commit }, user) {
    commit("setUser", user);
  },
};
const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  setUser(state, data) {
    state.user = data;
  },
  setAuth(state, data) {
    state.loading = false;
    state.user = data.user;
    state.token = data.auth__token;
    localStorage.setItem(localStorageKeys.token, data.auth__token);
  },
  purgeAuth(state) {
    state.user = null;
    state.token = null;
    localStorage.removeItem(localStorageKeys.token);
  },
  clearLocalStorage(state) {
    localStorage.removeItem(localStorageKeys.orderInfo);
    localStorage.removeItem(localStorageKeys.userInfo);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
