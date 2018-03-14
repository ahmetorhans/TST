
export default [
  {
    path: '/',
    component: () => import('layouts/default'),
    children: [
      { path: '', component: () => import('pages/index') }
    ]
  },
  {
    path: '/kullanicilar',
     component: () => import('layouts/default'), meta: { requiresAuth: true }, 
    children: [
      { path: '', component: () => import('pages/user') }
    ]
  },
  {
    path: '/cariler/:cari*',
     component: () => import('layouts/default'), meta: { requiresAuth: true }, 
    children: [
       { path: '', component: () => import('pages/cari') }
    ]
  },
  {
    path: '/servisler',
     component: () => import('layouts/default'), meta: { requiresAuth: true }, 
    children: [
 
      { path: '', component: () => import('pages/servis') },
      { path: 'servis/:id*', component: () => import('pages/servisDetay') }
      
    ]
  },
  {
    path: '/cihazlar',
     component: () => import('layouts/default'), meta: { requiresAuth: true }, 
    children: [
      { path: '', component: () => import('pages/cihaz') }
    ]
  },
  {
    path: '/raporlar',
     component: () => import('layouts/rapor'), meta: { requiresAuth: true }, 
    children: [
      { path: '', component: () => import('pages/rapor') },
      { path: 'servis', component: () => import('pages/raporlar/servis') },
      { path: 'cari', component: () => import('pages/raporlar/cari') }
    ]
  },
  
  { 
    path: '/login',
    component: () => import('pages/login')
  },
  { 
    path: '*',
    component: () => import('pages/404')
  }
]
