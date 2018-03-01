
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
  { // Always leave this as last one
    path: '/login',
    component: () => import('pages/login')
  },
  { // Always leave this as last one
    path: '*',
    component: () => import('pages/404')
  }
]
