import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null as any,
        isLoggedIn: false
    }),
    actions: {
        async fetchUser() {
            const response = await fetch('/api/user');
            if(response.ok) {
                this.user = await response.json();
                this.isLoggedIn = true;
            } else {
                this.user = null;
                this.isLoggedIn = false;
            }
        },
        async login(form: { name: string, password: string }) {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')!.getAttribute('content')!
                },
                body: JSON.stringify(form)
            });
            const data = await response.json();
            if(data.success) {
                this.user = data.user;
                this.isLoggedIn = true;
            }
            return data;
        },
        async logout() {
            await fetch('/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')!.getAttribute('content')!
                }
            });
            this.user = null;
            this.isLoggedIn = false;
        }
    }
});