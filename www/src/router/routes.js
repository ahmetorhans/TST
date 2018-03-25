export default [
  {
    path: "/",
    component: () => import("layouts/default"),
    children: [{ path: "", component: () => import("pages/index") }]
  },
  {
    path: "/servisid/:id",
    component: () => import("layouts/guest"),
    meta: { requiresAuth: false },
    children: [{ path: "", component: () => import("pages/servisid") }]
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
    path: "/randevular/:randevu*",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [
      { path: "", component: () => import("pages/randevu") }
    
    ]
  },
  {
    path: "/cihazlar/:cihaz*",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [{ path: "", component: () => import("pages/cihaz") }]
  },
  {
    path: "/urunler/:urun*",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [{ path: "", component: () => import("pages/urun") }]
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
    component: () => import("layouts/default"),
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
      { path: "cihaz", component: () => import("pages/raporlar/cihaz") },
      { path: "randevu", component: () => import("pages/raporlar/randevu") }
    ]
  },

  {
    path: "/login",
    component: () => import("pages/login")
  },

  {
    path: "/m/",
    component: () => import("layouts/default"),
    meta: { requiresAuth: true },
    children: [ 
      { path: "profil", component: () => import("pages/profil.musteri") },
      { path: "servisler", component: () => import("pages/servis.musteri") },
      { path: "servisler/servis/:id*", component: () => import("pages/servisDetay.musteri") },
      { path: "cihazlar/:id*", component: () => import("pages/cihaz.musteri") }
    ]
  },
  {
    path: "*",
    component: () => import("pages/404")
  }
];
