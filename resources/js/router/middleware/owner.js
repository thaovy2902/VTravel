import store from "@/store";
import { vp } from "@/helpers/tools";

export default function RedirectIfAuthenticated(router) {
  router.beforeEach((to, from, next) => {
    let meta = to.matched.some(record => record.meta.owner);
    let user = store.state.auth.user;

    if (meta && !user) {
      next({ name: "login" });
    } else {
      if (meta && user && user.role_id !== 1) {
        next({ name: "home" });
        vp.$message.error("Trang này chỉ giành cho chủ sở hữu");
      } else {
        next();
      }
    }
  });
}
