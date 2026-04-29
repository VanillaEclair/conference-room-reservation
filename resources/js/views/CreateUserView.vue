<template>
    <form @submit.prevent="createAcc">
        <input type="text" v-model="form.username" placeholder="username">
        <input type="text" v-model="form.email" placeholder="email">
        <input type="password" v-model="form.password" placeholder="password">
        <p v-if="error" style="color:red">{{ error }}</p>
        <p v-if="success" style="color:green">{{ success }}</p>
        <button type="submit">Sign Up</button>
    </form>
</template>

<script>
import { useAuthStore } from '../stores/auth';

export default {
    data() {
        return {
            form: {
                username: '',
                email: '',
                password:''
            },
            error: '',
            success: ''
        }
    },
    methods: {
        async createAcc() {
            const response = await fetch('/create-user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.form)
            })

            const data = await response.json();
            if(data.success)
            {
                this.$router.push('/');
            }
            else
            {
                this.error = data.message;
            }
        }
    }
}
</script>