import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";
import router from "@/router";

const state = {
  read: {},
  unRead: {}
};

const getters = {
  read(state) {
    return state.read;
  },
  unRead(state) {
    return state.unRead;
  },
  unReadCount(state) {
    return state.unRead.length;
  }
};

const actions = {
  async fetchNotification({ commit }) {
    try {
      const { data, status } = await callerApi(`notifications`);
      if (data && status === 200) {
        commit("fetchNotification", data);
      }
    } catch (error) {
      console.log(error);
    }
  },
  async readNotification({ commit }, payload) {
    try {
      const { status } = await callerApi("markAsRead", "POST", payload);
      if (status === 200) {
        commit("readNotification", payload.id);
        router.push({ name: "admin.orders" });
      }
    } catch (error) {
      console.log(error);
    }
  },
  async markAsReadAll({ commit, dispatch }) {
    try {
      const { status } = await callerApi("markAsReadAll", "POST");
      if (status === 200) {
        dispatch("fetchNotification");
      }
    } catch (error) {
      console.log(error);
    }
  },
  async deleteNotification({ commit, dispatch }) {
    try {
      const { status } = await callerApi("deleteNotification", "POST");
      if (status === 200) {
        dispatch("fetchNotification");
      }
    } catch (error) {
      console.log(error);
    }
  },
  broadcastNotification({ commit }, data) {
    commit("broadcastNotification", data);
    const message = `${data.orderer} vừa đặt tour ${data.tour}`;
    vp.$notify.info("Đơn đặt tour mới", message);
  }
};

const mutations = {
  fetchNotification(state, data) {
    state.read = data.read;
    state.unRead = data.unRead;
  },
  readNotification(state, notificationId) {
    const notification = state.unRead.find(item => item.id === notificationId);
    const index = state.unRead.findIndex(item => item.id === notificationId);
    state.unRead.splice(index, 1);
    state.read.unshift(notification);
  },
  broadcastNotification(state, data) {
    state.unRead.unshift(data);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
