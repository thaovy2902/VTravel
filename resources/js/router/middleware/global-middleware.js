import globalAuth from "./global-auth";
import guest from "./guest";
import auth from "./auth";
import admin from "./admin";
import owner from "./owner";
import inactive from "./inactive";

export default router => {
  globalAuth(router);
  guest(router);
  auth(router);
  admin(router);
  owner(router);
  // inactive(router);
};
