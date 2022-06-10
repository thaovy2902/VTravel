export default [
  {
    path: "dashboard",
    name: "owner.dashboard",
    component: () => import("@/pages/owner/Dashboard")
  },
  {
    path: "feedbacks",
    name: "owner.feedbacks",
    component: () => import("@/pages/owner/Feedback")
  },
  {
    path: "permissions",
    name: "owner.permissions",
    component: () => import("@/pages/owner/Permission")
  }
];
