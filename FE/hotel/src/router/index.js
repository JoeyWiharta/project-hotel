import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import AdminDashboard from '@/views/Admin/AdminDashboard.vue' // Import AdminDashboard
import RoomList from '@/views/Admin/RoomList.vue' // Import RoomList
import RoomForm from '@/views/Admin/RoomForm.vue' // Import RoomForm
import { useUserStore } from '../stores/counter'
import RoomListViewOnly from '@/views/Admin/RoomListViewOnly.vue'
import LandingPage from '@/views/User/LandingPage.vue'
import HomePage from '@/views/User/HomePage.vue'
import AdminDatabase from '@/views/Admin/AdminDatabase.vue'
import AdminHistory from '@/views/Admin/AdminHistory.vue'
import UserBooking from '@/views/User/UserBooking.vue'
import UserBookingForm from '@/views/User/UserBookingForm.vue'
import UserOrder from '@/views/User/UserOrder.vue'
import AboutPage from '@/views/User/AboutPage.vue'
import ContactPage from '@/views/User/ContactPage.vue'

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
      component: LandingPage,
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
    },
    {
      path: '/admin-database',
      name: 'admindatabase',
      component: AdminDatabase,
      beforeEnter: [requireAuth, requireRole('admin')]
    },
    {
      path: '/homepage',
      name: 'homepage',
      component: HomePage,
      beforeEnter: [requireAuth, requireRole('user')]
    },
    {
      path: '/admin-history',
      name: 'admin-history',
      component: AdminHistory,
      beforeEnter: [requireAuth, requireRole('admin')]
    },
    {
      path: '/user-booking',
      name: 'user-booking',
      component: UserBooking,
      beforeEnter: [requireAuth, requireRole('user')]
    },
    {
      path: '/booking-form',
      name: 'booking-form',
      component:UserBookingForm,
      beforeEnter: [requireAuth, requireRole('user')]
    },
    {
      path: '/user-order',
      name: 'user-order',
      component:UserOrder,
      beforeEnter: [requireAuth, requireRole('user')]
    },
    {
      path: '/about',
      name: 'about',
      component:AboutPage,
      beforeEnter: [requireAuth, requireRole('user')]
    },
    {
      path: '/contact',
      name: 'contact',
      component:ContactPage,
      beforeEnter: [requireAuth, requireRole('user')]
    },
  ]
})

export default router
