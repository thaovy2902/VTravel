export default [
  {
    path: "dashboard",
    name: "admin.dashboard",
    component: () => import("@/pages/admin/Dashboard"),
  },
  {
    path: "users",
    name: "admin.users",
    component: () => import("@/pages/admin/User"),
  },
  {
    path: "tours",
    name: "admin.tours",
    component: () => import("@/pages/admin/Tour"),
  },
  {
    path: "orders",
    name: "admin.orders",
    component: () => import("@/pages/admin/Order"),
  },
  {
    path: "ratings",
    name: "admin.ratings",
    component: () => import("@/pages/admin/Rating"),
  },
  {
    path: "slides",
    name: "admin.slides",
    component: () => import("@/pages/admin/Slide"),
  },
  {
    path: "discount-codes",
    name: "admin.discountcodes",
    component: () => import("@/pages/admin/DiscountCode"),
  },
];
