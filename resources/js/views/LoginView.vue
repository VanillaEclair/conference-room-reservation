
<template>
    <form @submit.prevent="login">
        <input type="text" v-model="form.name" placeholder="username">
        <input type="password" v-model="form.password" placeholder="password">
        <p v-if="error" style="color:red">{{ error }}</p>
        <button type="submit">Login</button>
    </form>
    <router-link to="/register">Sign up</router-link>
</template>

<script>
import { useAuthStore } from '../stores/auth'

export default {
    data() {
        return {
            form: { name: '', password: '' },
            error: ''
        }
    },
    methods: {
        async login() {
            const auth = useAuthStore();
            const data = await auth.login(this.form);

            if(data.success) {
                this.$router.push('/');
            } else {
                this.error = data.message;
            }
        }
    }
}
</script>


<!-- <template>
    <form @submit.prevent="login">
        <input type="text" v-model="form.name" placeholder="username">
        <input type="password" v-model="form.password" placeholder="password">
        <button>Login</button>
    </form>
    <button><router-link to="/register">Sign up</router-link></button>
</template>

<script>

import { useAuthStore } from '../stores/auth'

export default {
    data() {
        return {
            form: {
                name: '',
                password: ''
            }
        }
    },
    methods: {
        login() {
            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                this.$router.push('/'); // redirect to home on success
            })
            .catch(error => {
                console.error('Login failed:', error);
            });
        }
    }
}
</script> -->