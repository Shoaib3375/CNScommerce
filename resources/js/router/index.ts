import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        // ── Storefront ────────────────────────────────────────────
        {
            path: '/',
            component: () => import('../Pages/Storefront/Home.vue'),
        },
        {
            path: '/shop/:slug',
            component: () => import('../Pages/Storefront/ShopShow.vue'),
        },
        {
            path: '/shop/:shopSlug/products/:productSlug',
            component: () => import('../Pages/Storefront/ProductShow.vue'),
        },

        // ── Auth ──────────────────────────────────────────────────
        {
            path: '/login',
            component: () => import('../Pages/Auth/Login.vue'),
            meta: { guest: true },
        },
        {
            path: '/forgot-password',
            component: () => import('../Pages/Auth/ForgotPassword.vue'),
            meta: { guest: true },
        },
        {
            path: '/reset-password',
            component: () => import('../Pages/Auth/ResetPassword.vue'),
            meta: { guest: true },
        },
        {
            path: '/confirm-password',
            component: () => import('../Pages/Auth/ConfirmPassword.vue'),
            meta: { requiresAuth: true },
        },
        {
            path: '/verify-email',
            component: () => import('../Pages/Auth/VerifyEmail.vue'),
            meta: { requiresAuth: true },
        },

        // ── Admin ──────────────────────────────────────────────────
        {
            path: '/admin/dashboard',
            component: () => import('../Pages/Admin/Dashboard.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/shops',
            component: () => import('../Pages/Admin/Shops/Index.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/shops/create',
            component: () => import('../Pages/Admin/Shops/Create.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/shops/:id/edit',
            component: () => import('../Pages/Admin/Shops/Edit.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/shops/:id',
            component: () => import('../Pages/Admin/Shops/Show.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/owners',
            component: () => import('../Pages/Admin/Owners/Index.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/owners/create',
            component: () => import('../Pages/Admin/Owners/Create.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/owners/:id/edit',
            component: () => import('../Pages/Admin/Owners/Edit.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/products',
            component: () => import('../Pages/Admin/Products/Index.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/products/create',
            component: () => import('../Pages/Admin/Products/Create.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },
        {
            path: '/admin/products/:id/edit',
            component: () => import('../Pages/Admin/Products/Edit.vue'),
            meta: { requiresAuth: true, role: 'admin' },
        },

        // ── Owner ──────────────────────────────────────────────────
        {
            path: '/owner/dashboard',
            component: () => import('../Pages/Owner/Dashboard.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },
        {
            path: '/owner/catalog',
            component: () => import('../Pages/Owner/Catalog/Index.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },
        {
            path: '/owner/shop-products',
            component: () => import('../Pages/Owner/Products/Index.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },
        {
            path: '/owner/shop-products/:id/edit',
            component: () => import('../Pages/Owner/Products/Edit.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },
        {
            path: '/owner/my-products',
            component: () => import('../Pages/Owner/MyProducts/Index.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },
        {
            path: '/owner/my-products/create',
            component: () => import('../Pages/Owner/MyProducts/Create.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },
        {
            path: '/owner/my-products/:id/edit',
            component: () => import('../Pages/Owner/MyProducts/Edit.vue'),
            meta: { requiresAuth: true, role: 'shop_owner' },
        },

        // ── Profile ────────────────────────────────────────────────
        {
            path: '/profile',
            component: () => import('../Pages/Profile/Edit.vue'),
            meta: { requiresAuth: true },
        },
    ],
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();
    await auth.fetchUser();

    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        return '/login';
    }
    if (to.meta.role === 'admin' && !auth.isAdmin) {
        return '/';
    }
    if (to.meta.role === 'shop_owner' && !auth.isOwner) {
        return '/';
    }
    if (to.meta.guest && auth.isAuthenticated) {
        return auth.isAdmin ? '/admin/dashboard' : '/owner/dashboard';
    }
});

export default router;
