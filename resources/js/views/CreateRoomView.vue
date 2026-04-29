<template>
      <form @submit.prevent="createRoom">
        <input type="text" v-model="form.name" placeholder="Room Name">
        <input type="text" v-model="form.location" placeholder="location">
        <input type="number" v-model="form.capacity" min="10" placeholder="capacity">
        <!-- <input type="text" v-model="form.status" value="Available"> -->
        <p v-if="error" style="color:red">{{ error }}</p>
        <p v-if="success" style="color:green">{{ success }}</p>
        <button type="submit">Create Room</button>
    </form>  
</template>

<script>

export default{
    data(){
        return{
            form: {
                name:'',
                location:'',
                capacity: '',
                status: 'Available'
            },
            error: '',
            success: ''
        }
    },

    methods:{
        async createRoom()
        {
            const response = await fetch('create-room', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(this.form)
                    
                });

                const data = await response.json();
                if(data.success)
                {
                    this.success = 'Room Created Succesfully';
                    this.form = {name:'',location:'',capacity: '',status: 'Available'};
                    this.$router.push('/');
                }else
                {
                    this.error = data.message;
                }
           
        }
    }

}


</script>