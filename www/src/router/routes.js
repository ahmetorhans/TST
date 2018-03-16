export default [
  {
    path: "/",
    component: () => import("layouts/default"),
    children: [{ path: "", component: () => import("pages/index") }]
  },
  {
    path: "/kullanicilar",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [{ path: "", component: () => import("pages/user") }]
  },
  {
    path: "/cariler/:cari*",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [{ path: "", component: () => import("pages/cari") }]
  },
  {
    path: "/servisler",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/servis") },
      { path: "servis/:id*", component: () => import("pages/servisDetay") }
    ]
  },
  {
    path: "/cihazlar/:cihaz*",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [{ path: "", component: () => import("pages/cihaz") }]
  },
  {
    path: "/ayarlar/",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [ 
      { path: "", component: () => import("pages/profil") },
      { path: "profil", component: () => import("pages/profil") }
    ]
  },
  {
    path: "/raporlar",
    component: () => import("layouts/rapor"),
    meta: { requiresAuth: true },
    name: 'raporlar',
    children: [
      { path: "", component: () => import("pages/raporlar/servis") },
      { path: "servis", component: () => import("pages/raporlar/servis") },
      {
        path: "servisBekleyen",
        meta: { rapor: true },
        component: () => import("pages/raporlar/servisBekleyen")
      },
      {
        path: "servisCari",
        component: () => import("pages/raporlar/servisCari")
      },
      {
        path: "cihazCari",
        component: () => import("pages/raporlar/cihazCari")
      },
      { path: "cari", component: () => import("pages/raporlar/cari") },
      { path: "cihaz", component: () => import("pages/raporlar/cihaz") }
    ]
  },

  {
    path: "/login",
    component: () => import("pages/login")
  },
  {
    path: "*",
    component: () => import("pages/404")
  }
];
