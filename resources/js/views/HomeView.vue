<template>
   <h1>I'm so getting touched in my no no places</h1>
   <button><router-link to="/login">GO here</router-link></button>
   <br>
   <button @click="logout">Log Out</button>
   <h2 v-if="auth.isLoggedIn">Authenticated</h2>
   <h2 v-else>GTFO</h2>
</template>

<script>

import {useAuthStore} from '../stores/auth'
export default{
   setup(){
      const auth = useAuthStore();
      return {auth};
   },

   methods:{
      async logout() 
      {
         await this.auth.logout();
         this.$router.push('/login')
      }
   }
}

</script>