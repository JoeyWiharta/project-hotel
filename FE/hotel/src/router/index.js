import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import UserDashboard from '@/views/User/UserDashboard.vue'
import AdminDashboard from '@/views/Admin/AdminDashboard.vue' // Import AdminDashboard
import RoomList from '@/views/Admin/RoomList.vue' // Import RoomList
import RoomForm from '@/views/Admin/RoomForm.vue' // Import RoomForm
import { useUserStore } from '../stores/counter'
import RoomListViewOnly from '@/views/Admin/RoomListViewOnly.vue'

const requireAuth = (to, from, next) => {
  const store = useUserStore()
  const token = store.token

  if (!token) {
    next({ name: 'login' })
  } else {
    next()
  }
}

const requireRole = (role) => (to, from, next) => {
  const store = useUserStore()
  const userRole = store.role

  if (userRole !== role) {
    next({ name: 'login' })
  } else {
    next()
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: Login
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    },
    {
      path: '/admin-dashboard',
      name: 'admindashboard',
      component: AdminDashboard,
      beforeEnter: [requireAuth, requireRole('admin')]
    },
    {
      path: '/user-dashboard',
      name: 'userdashboard',
      component: UserDashboard,
      beforeEnter: [requireAuth, requireRole('user')]
    },
    {
      path: '/rooms',
      name: 'rooms',
      component: RoomList,
      beforeEnter: [requireAuth, requireRole('admin')]
    },
    {
      path: '/rooms/add',
      name: 'addroom',
      component: RoomForm,
      beforeEnter: [requireAuth, requireRole('admin')]
    },
    {
      path: '/rooms/edit/:roomnumber',
      name: 'editroom',
      component: RoomForm,
      beforeEnter: [requireAuth, requireRole('admin')]
    },
    {
      path: '/view-rooms',
      name: 'viewrooms',
      component: RoomListViewOnly,
      beforeEnter: [requireAuth, requireRole('admin')]
    }
  ]
})

export default router
