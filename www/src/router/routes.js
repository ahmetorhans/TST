
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
    path: '/login',
    component: () => import('pages/login')
  },
  { 
    path: '*',
    component: () => import('pages/404')
  }
]
