<template>
    <div class="Main-body">
        <form @submit.prevent="submitReservation">
            <input type="text" v-model="form.title" placeholder="title">
            <input type="datetime-local" v-model="form.start_datetime" id="start_datetime" @change="setDateInput" >
            <input type="datetime-local" v-model="form.end_datetime" id="end_datetime" :disabled="!form.start_datetime"
                :min="endDateMin">
            <textarea v-model="form.purpose" placeholder="reason for renting"></textarea>
            <select v-model="form.room_id">
                <option value="" disabled selected>Select room</option>
                <option v-for="room in rooms" :key="room.id" :value="room.id">{{ room.name }}</option>
            </select>

            <input type="file">
            <button id="submit" type="submit" :disabled="!isAvailable">{{ isEditMode ? 'Update Reservation' : 'Submit Reservation' }}</button>
            <div class="" style="background-color: red;">
                <button @click="$router.push('/')">Cancel</button>
            </div>
            <h1>{{ roomInfo }}</h1>
        </form>
    </div>

    <div>
        <button @click="logout">Logout</button>
    </div>

    <div>
        <button @click="$router.push('/create-room')" disabled="true">Create a Room</button>
    </div>
</template>

<script>
import { useAuthStore } from '../stores/auth'

export default {
    data() {
        return {
            form: {
                title: '',
                start_datetime: '',
                end_datetime: '',
                purpose: '',
                room_id: ''

            },
            isEditMode: false,
            rooms: [],
            roomInfo: '',
            startdatemin: '',
            endDateMin: '',
            error: '',
            isAvailable: false,
            reservation_id: ''
        }
    },
    mounted() {
        // Fetch rooms for the select dropdown
        fetch('/get-rooms')
            .then(response => response.json())
            .then(data => {
                this.rooms = data;
            });

        const id = this.$route.params.id;
        this.reservation_id = id;
        let date = new Date();
        
        this.startdatemin = date.getFullYear() + '-' +
                String(date.getMonth() + 1).padStart(2, '0') + '-' +
                String(date.getDate()).padStart(2, '0') + 'T' +
                String(date.getHours()).padStart(2, '0') + ':' +
                String(date.getMinutes()).padStart(2, '0');

        if (id) {
            console.log(id);
            this.isEditMode = true;
            fetch(`/reservation/${id}`, { credentials: 'include' })
                .then(response => response.json())
                .then(data => {
                    this.form = {
                        title: data.title,
                        start_datetime: data.start_datetime.slice(0, 16),
                        end_datetime: data.end_datetime.slice(0, 16),
                        purpose: data.purpose,
                        room_id: data.room_id

                    };

                });

        }
        else {
            console.log(id);
        }
    },
    setup() {
        const auth = useAuthStore();
        console.log(auth.user);
        return { auth };
    },
    watch: {
        'form.end_datetime'(newVal) {
            if(newVal && newVal < this.endDateMin) 
            {
                //this.form.end_datetime = this.endDateMin;
            }
            this.checkAvailability();
        },

        'form.start_datetime'(newVal)
        {
            if(newVal && newVal < this.startdatemin)
            {
                //this.form.start_datetime = this.startdatemin;
            }
            this.checkAvailability();
        },
        'form.room_id'(room_id)
        {
            this.checkAvailability();
        }

    },
    methods: {

        
        setDateInput() {
            let startTime = new Date(this.form.start_datetime);
            startTime.setHours(startTime.getHours() + 1); 
            this.endDateMin =  this.toLocalDatetime(startTime);
            console.log(this.endDateMin);
            this.form.end_datetime = '';
            this.checkAvailability();
            
        },

        async checkAvailability() {

            if (!this.form.start_datetime || !this.form.end_datetime || !this.form.room_id)
            {
                this.isAvailable=false;
                return;
            }
            
            console.log("check:" + this.reservation_id);
            const url = this.reservation_id
                 ?`/availability/${this.reservation_id}`
                 : '/availability';

            const response = await fetch(url, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    room_id: this.form.room_id,
                    starttime: this.form.start_datetime,
                    endtime: this.form.end_datetime
                })
            })

            const data = await response.json();
            this.isAvailable = data.available;
            console.log(this.isAvailable);
            this.roomInfo = data.available? 'Room is Available' : 'Room is Already Booked';
        },


        async submitReservation() {


                if(this.form.end_datetime <= this.form.start_datetime) {
                    this.error = 'End time must be after start time';
                    return;
                }

            const id = this.$route.params.id;

            const url = this.isEditMode ? `/reservations/${id}/edit` : '/create-reservation';
            const method = this.isEditMode ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.form)
            })

            const data = await response.json();
            if (data.success) {
                this.$router.push('/');
            }
            else {
                this.error = data.message;
            }
        },

        async logout() {
            console.log("is this a joke?");
            await this.auth.logout();
            this.$router.push('/login');
        },

        toLocalDatetime(date) {
            return date.getFullYear() + '-' +
                String(date.getMonth() + 1).padStart(2, '0') + '-' +
                String(date.getDate()).padStart(2, '0') + 'T' +
                String(date.getHours()).padStart(2, '0') + ':' +
                String(date.getMinutes()).padStart(2, '0');
        }
    }
}
</script>