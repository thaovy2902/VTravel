import store from "@/store";
import { vp } from "@/helpers/tools";

export default function RedirectIfAuthenticated(router) {
  router.beforeEach((to, from, next) => {
    let meta = to.matched.some(record => record.meta.guest);
    let user = store.state.auth.user;

    if (meta && user) {
      if (from.fullPath) {
        next(false);
      } else {
        next({ name: "home" });
      }
      vp.$message.error("Trang này chỉ giành cho khách");
      return;
    } else {
      next();
    }
  });
}
