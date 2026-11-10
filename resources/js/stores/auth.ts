import { defineStore } from 'pinia';
import axios, { getCsrfCookie } from '@/axios';
import type { User } from '@/types';

interface AuthState {
    user: User | null;
    checked: boolean;
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => ({
        user: null,
        checked: false,
    }),

    getters: {
        isAuthenticated: (state): boolean => state.user !== null,
        isAdmin: (state): boolean => state.user?.role === 'admin',
        isOwner: (state): boolean => state.user?.role === 'shop_owner',
    },

    actions: {
        async fetchUser(): Promise<void> {
            if (this.checked) return;
            try {
                const r = await axios.get('/api/user');
                this.user = r.data;
            } catch {
                this.user = null;
            } finally {
                this.checked = true;
            }
        },

        async login(email: string, password: string): Promise<void> {
            await getCsrfCookie();
            const r = await axios.post('/api/login', { email, password });
            this.user = r.data.user;
            this.checked = true;
        },

        async logout(): Promise<void> {
            await axios.post('/api/logout');
            this.user = null;
        },
    },
});
