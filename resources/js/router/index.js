import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)
import Layout from "@/layout";
const roleRoot = "develop";
const roleAdmin = "attraction";
export const constantRoutes = [
  {
    path: "/redirect",
    component: Layout,
    hidden: true,
    children: [
      {
        path: "/redirect/:path(.*)",
        component: () => import("@/views/redirect/index"),
      },
    ],
  },
  {
    path: "/login",
    component: () => import("@/views/login/index"),
    hidden: true,
  },
  {
    path: "/continue_reg",
    component: () => import("@/views/continue_reg/index"),
    hidden: true,
  },

  {
    path: "/404",
    component: () => import("@/views/404"),
    hidden: true,
  },
  {
    path: "/",
    component: Layout,
    redirect: "/profile",
    hidden: true,
    children: [
      {
        path: "profile",
        name: "Profile",
        component: () => import("@/views/profile/index"),
        meta: { title: "rightSidebar.profile", icon: "profile" },
      },
    ],
  },
];
export const asyncRoutes = [
  {
    path: "/",
    component: Layout,
    redirect: "noRedirect",
    name: "Clients",
    children: [
      {
        path: "clients",
        component: () => import("@/views/clients/clients"),
        name: "ClientsList",
        meta: {
          title: "rightSidebar.clients",
          icon: "clients",
          roles: [roleRoot],
        },
      },
      {
        path: "new-client",
        component: () => import("@/views/new-client/index"),
        name: "AddNewClient",
        meta: { title: "clients.heading", roles: [roleRoot] },
        hidden: true,
      },
      {
        path: "client-card/:id(\\d+)",
        component: () => import("@/views/client-card"),
        name: "ClientCard",
        meta: {
          title: "rightSidebar.cardClient",
          noCache: true,
          roles: [roleRoot],
          activeMenu: "/client-card",
        },
        hidden: true,
        params: true,
      },
    ],
  },
  {
    path: "/",
    component: Layout,
    redirect: "noRedirect",
    name: "Locations",
    params: true,
    children: [
      {
        path: "locations",
        component: () => import("@/views/locations/locations"),
        name: "LocationsList",
        meta: {
          title: "rightSidebar.locations",
          icon: "locations",
          roles: [roleRoot],
        },
        params: true,
      },
      {
        path: "edit-location/:id(\\d+)",
        component: () => import("@/views/edit-location"),
        name: "EditLocation",
        meta: {
          title: "location.heading",
          noCache: true,
          roles: [roleRoot],
          activeMenu: "/edit-location",
        },
        hidden: true,
        params: true,
      },
    ],
  },
  {
    path: "/",
    component: Layout,
    redirect: "noRedirect",
    name: "attractions",
    params: true,
    children: [
      {
        path: "attractions",
        component: () => import("@/views/attractions/index"),
        name: "AttractionsList",
        meta: {
          title: "rightSidebar.amusements",
          icon: "amusements",
          roles: [roleRoot],
        },
        params: true,
      },
      {
        path: "edit-attraction/:id(\\d+)",
        component: () => import("@/views/edit-attraction"),
        name: "EditAttraction",
        meta: {
          title: "attraction.heading",
          noCache: true,
          roles: [roleRoot],
          activeMenu: "/edit-attraction",
        },
        hidden: true,
        params: true,
      },
    ],
  },
  {
    path: "/",
    component: Layout,
    redirect: "/players",
    children: [
      {
        path: "players",
        name: "Players",
        component: () => import("@/views/players/index"),
        meta: {
          title: "rightSidebar.players",
          roles: [roleRoot],
          icon: "players",
        },
      },
    ],
  },
  // Статистика должна быть дефолтной
  {
    path: "/",
    component: Layout,
    redirect: "/statistics",
    children: [
      {
        path: "statistics",
        name: "Statistics",
        component: () => import("@/views/statistics/index"),
        meta: {
          roles: [roleRoot],
          title: "rightSidebar.statistics",
          icon: "statistics",
        },
      },
    ],
  },
  {
    path: "/",
    component: Layout,
    redirect: "/licenses",
    children: [
      {
        path: "licenses",
        name: "Licenses",
        component: () => import("@/views/licenses/index"),
        meta: {
          roles: [roleRoot],
          title: "rightSidebar.license",
          icon: "licensing",
        },
      },
    ],
  },

  /** when your routing map is too long, you can split it into small modules **/

  //  {
  //    path: "/error",
  //    component: Layout,
  //    redirect: "noRedirect",
  //    name: "ErrorPages",
  //    meta: {
  //      title: "errorPages",
  //      icon: "404",
  //    },
  //    children: [
  //      {
  //        path: "401",
  //        component: () => import("@/views/error-page/401"),
  //        name: "Page401",
  //        meta: { title: "page401", noCache: true },
  //      },
  //      {
  //        path: "404",
  //        component: () => import("@/views/error-page/404"),
  //        name: "Page404",
  //        meta: { title: "page404", noCache: true },
  //      },
  //    ],
  //  },

  //  {
  //    path: "/error-log",
  //    component: Layout,
  //    children: [
  //      {
  //        path: "log",
  //        component: () => import("@/views/error-log/index"),
  //        name: "ErrorLog",
  //        meta: { title: "errorLog", icon: "bug" },
  //      },
  //    ],
  //  },

  // 404 page must be placed at the end !!!
  { path: "*", redirect: "/404", hidden: true },
];

const createRouter = () =>
  new Router({
    mode: "history", // require service support
    base: "/admin",
    scrollBehavior: () => ({ y: 0 }),
    routes:constantRoutes,
  });

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
