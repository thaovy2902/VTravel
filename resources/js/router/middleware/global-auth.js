import store from "@/store";

export default function RedirectIfAuthenticated(router) {
  router.beforeEach(async (to, from, next) => {
    let token = store.state.auth.token;
    let user = store.state.auth.user;

    try {
      !user && token && (await store.dispatch("auth/me"));
    } catch (e) {
      console.error(e);
    }

    next();
  });
}
