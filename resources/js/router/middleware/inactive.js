import store from "@/store";
import { vp } from "@/helpers/tools";

export default function RedirectIfAuthenticated(router) {
  router.beforeEach((to, from, next) => {
    let meta = to.matched.some((record) => record.meta.inactive);
    let user = store.state.auth.user;

    if (from.name === "register") {
      next();
      return;
    }

    if (meta && user && !user.is_active) {
      next({ name: "forbidden" });
      vp.$message.error("Tài khoản bạn đã bị khóa");
    } else {
      next();
    }
  });
}
