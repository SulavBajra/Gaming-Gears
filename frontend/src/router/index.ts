import { createRouter, createWebHistory } from 'vue-router'
import AppLayout from '../components/layout/AppLayout.vue'
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'
import UserProfile from '@/components/pages/profile/UserProfile.vue'
import OrderProfile from '@/components/pages/profile/UserOrder.vue'
import Cart from '@/views/CartView.vue'
import ProfileLayout from '@/components/layout/ProfileLayout.vue'
import ProfileForm from '@/components/pages/profile/ProfileForm.vue'
import ProfileEditForm from '@/components/pages/profile/ProfileEditForm.vue'
import Wishlist from '@/views/WishlistView.vue'

const router = createRouter({
  linkActiveClass: '',
  linkExactActiveClass: 'router-link-active',
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
    },
    {
      path: '/register',
      name: 'register',
      component: Register,
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: () => import('@/views/payment/CheckoutView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/order-success',
      name: 'order-success',
      component: () => import('@/views/payment/OrderSuccessView.vue'),
      meta: { requiresAuth: true },
    },
    {
      path: '/profile',
      component: ProfileLayout,
      children: [
        {
          path: '',
          name: 'profile',
          component: UserProfile,
        },
        {
          path: 'create',
          name: 'profile.create',
          component: ProfileForm,
        },
        {
          path: 'edit',
          name: 'profile.edit',
          component: ProfileEditForm,
        },
        {
          path: 'orders',
          name: 'orders',
          component: OrderProfile,
        },
      ],
    },

    {
      path: '/',
      component: AppLayout,
      children: [
        {
          path: '',
          name: 'home',
          component: () => import('@/views/HomeView.vue'),
        },
        {
          path: 'shop',
          name: 'shop',
          component: () => import('@/views/ShopView.vue'),
        },
        {
          path: '/shop/:slug',
          name: 'product.show',
          component: () => import('@/views/ProductView.vue'),
        },
        {
          path: 'about',
          name: 'about',
          component: () => import('@/views/AboutView.vue'),
        },
        {
          path: '/cart',
          name: 'cart',
          component: Cart,
        },
        {
          path: '/wishlist',
          name: 'wishlist',
          component: Wishlist,
        },
      ],
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('@/views/NotFoundView.vue'),
    },
  ],
})

export default router
