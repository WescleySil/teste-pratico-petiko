import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import TasksView from '../views/TasksView.vue'
import UsersView from '../views/UsersView.vue'
import { isAuthenticated, requiresAuth } from '../utils/auth'

const requireAuth = (to: any, _from: any, next: any) => {
  if (isAuthenticated()) {
    next()
  } else {
    localStorage.removeItem('auth_token')

    next({
      path: '/login',
      query: { redirect: to.fullPath }
    })
  }
}

const checkAuthOnEveryRoute = (to: any, _from: any, next: any) => {
  if (!requiresAuth(to.path)) {
    next()
    return
  }

  if (!isAuthenticated()) {
    localStorage.removeItem('auth_token')
    next({
      path: '/login',
      query: { redirect: to.fullPath }
    })
    return
  }

  next()
}

const routes = [
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    redirect: '/tasks',
    beforeEnter: requireAuth
  },
  {
    path: '/tasks',
    name: 'Tasks',
    component: TasksView,
    beforeEnter: requireAuth
  },
  {
    path: '/users',
    name: 'Users',
    component: UsersView,
    beforeEnter: requireAuth
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(checkAuthOnEveryRoute)

export default router