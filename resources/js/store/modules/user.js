import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

const state = {
  users: [],
  loading: false
};

const getters = {
  users: state => {
    return state.users;
  },
  getUserById(state) {
    return userId => {
      return state.users.find(user => {
        return user.id === userId;
      });
    };
  },
  loading(state) {
    return state.loading;
  }
};

const actions = {
  async fetchUsers({ commit }, payload) {
    let url = "admin/users";
    if (payload.page) url += `?page=${payload.page}`;
    if (payload.perPage) url += `&perPage=${payload.perPage}`;
    if (payload.sortBy && payload.orderBy) url += `&sortBy=${payload.sortBy}&orderBy=${payload.orderBy}`;
    if (payload.q) url += `&q=${payload.q}`;

    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(url)
        .then(resp => {
          if (resp && resp.status === 200) {
            commit("fetchUsers", resp.data.data);
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

  async createUser({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/users`, "POST", payload);
      if (data && status === 201) {
        commit("createUser", data);
        vp.$notify.success("Success", "Thêm thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateUser({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/users/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateUser", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response && response.status === 422) {
        const message = Object.values(response.data.message)[0];
        vp.$notify.error("Error", message);
      }
    }
  },

  async updateActiveUser({ commit }, payload) {
    try {
      const { data, status } = await callerApi(`admin/update-active-user/${payload.id}`, "PUT", payload.values);
      if (data && status === 202) {
        commit("updateUser", data);
        vp.$notify.success("Success", "Cập nhật thành công");
      }
    } catch ({ response }) {
      if (response) {
        const message = response.data.message;
        vp.$notify.error("Error", message);
      }
    }
  },

  async deleteUser({ commit }, id) {
    try {
      const { data, status } = await callerApi(`admin/users/${id}`, "DELETE");
      if (data && status === 200) {
        commit("deleteUser", id);
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
  fetchUsers(state, data) {
    state.users = data;
  },
  createUser(state, data) {
    state.users.unshift(data);
    state.users.pop();
  },
  updateUser(state, data) {
    const index = state.users.findIndex(item => item.id === data.id);
    state.users.splice(index, 1, data);
  },
  deleteUser(state, id) {
    const index = state.users.findIndex(item => item.id === id);
    state.users.splice(index, 1);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
