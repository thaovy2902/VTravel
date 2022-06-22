import { callerApi } from "@/helpers/caller-api";
import { vp } from "@/helpers/tools";

let url = "admin/discount-codes";

const state = {
  discountCodes: [],
  loading: false,
};

const getters = {};

const actions = {
  async fetchDiscountCodes({ commit }, payload) {
    let url = "admin/discount-codes";
    if (payload.page) url += `?page=${payload.page}`;
    if (payload.perPage) url += `&perPage=${payload.perPage}`;
    if (payload.sortBy && payload.orderBy) url += `&sortBy=${payload.sortBy}&orderBy=${payload.orderBy}`;

    return new Promise((reslove, reject) => {
      commit("setLoading", true);
      callerApi(url)
        .then(({ data, status }) => {
          if (data && status === 200) {
            commit("fetchDiscountCodes", data.data);
            reslove(data.meta);
          }
        })
        .catch((err) => {
          reject(err);
        })
        .finally(() => {
          commit("setLoading", false);
        });
    });
  },
  async createDiscountCode({ commit, dispatch }, payload) {
    try {
      const { data, status } = await callerApi(url, "POST", payload);
      if (data && status === 201) {
        dispatch("fetchDiscountCodes", {});
        vp.$notify.success("Success", "Thêm thành công");
      }
    } catch (error) {
      console.log(error);
    }
  },
  async sendMailDiscountCode({ commit }, payload) {
    try {
      vp.$message.loading("Đang gửi mail", 0);
      const { data, status } = await callerApi("admin/send-mail-discount-code", "POST", payload);
      if (data && status === 200) {
        vp.$notify.success("Thành công", data.message);
      }
    } catch ({ response }) {
      if (response) {
        vp.$message.error("Lỗi, không gửi được mail");
      }
    } finally {
      vp.$message.destroy();
    }
  },
};

const mutations = {
  setLoading(state, data) {
    state.loading = data;
  },
  fetchDiscountCodes(state, data) {
    state.discountCodes = data;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
