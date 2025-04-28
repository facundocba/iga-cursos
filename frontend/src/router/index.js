import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import CursosView from '../views/CursosView.vue'
import DetallesCursoView from '../views/DetallesCursoView.vue'
import CheckoutView from '../views/CheckoutView.vue'
import MisComprasView from '../views/MisComprasView.vue'
import AdminLoginView from '../views/AdminLoginView.vue'
import AdminDashboardView from '../views/AdminDashboardView.vue'

// Guardia para rutas de administrador
const requireAuth = (to, from, next) => {
  const token = localStorage.getItem('admin_token')
  if (token) {
    next()
  } else {
    next({ name: 'admin-login' })
  }
}

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/cursos',
    name: 'cursos',
    component: CursosView
  },
  {
    path: '/cursos/:id',
    name: 'detalles-curso',
    component: DetallesCursoView,
    props: true
  },
  {
    path: '/checkout/:cursoId',
    name: 'checkout',
    component: CheckoutView,
    props: true
  },
  {
    path: '/mis-compras',
    name: 'mis-compras',
    component: MisComprasView
  },
  {
    path: '/admin/login',
    name: 'admin-login',
    component: AdminLoginView
  },
  {
    path: '/admin/dashboard',
    name: 'admin-dashboard',
    component: AdminDashboardView,
    beforeEnter: requireAuth
  },
  {
    path: '/about',
    name: 'about',
    // Carga perezosa para la ruta "Acerca de"
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router