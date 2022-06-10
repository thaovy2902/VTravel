import admin from "./groups/admin";
import user from "./groups/user";
import owner from "./groups/owner";

export default [
  {
    path: "/admin/",
    component: () => import("@/layouts/AdminLayout"),
    meta: { admin: true },
    children: [...admin],
  },
  {
    path: "/owner/",
    component: () => import("@/layouts/OwnerLayout"),
    meta: { owner: true },
    children: [...owner],
  },
  {
    path: "/",
    component: () => import("@/layouts/GlobalLayout"),
    meta: { inactive: true },
    children: [...user],
  },
  {
    path: "/login",
    name: "login",
    meta: { guest: true },
    component: () => import("@/pages/auth/Login"),
  },
  {
    path: "/register",
    name: "register",
    meta: { guest: true },
    component: () => import("@/pages/auth/Register"),
  },
  {
    path: "/forgot-password",
    name: "forgotpassword",
    meta: { guest: true },
    component: () => import("@/pages/auth/ForgotPassword"),
  },
  {
    path: "/response-password-reset",
    name: "resetpassword",
    meta: { guest: true },
    component: () => import("@/pages/auth/ResetPassword"),
  },
  {
    path: "/forbidden",
    name: "forbidden",
    component: () => import("@/pages/exception/403"),
  },
  {
    path: "*",
    name: "notfound",
    component: () => import("@/pages/exception/404"),
  },
];
