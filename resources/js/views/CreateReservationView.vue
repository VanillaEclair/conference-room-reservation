<template>
    <div class="Main-body">
        <form @submit.prevent="submitReservation">
            <input type="text" v-model="form.title" placeholder="title">
            <input type="datetime-local" v-model="form.start_datetime" id="start_datetime" @change="setDateInput">
            <input type="datetime-local" v-model="form.end_datetime" id="end_datetime" :disabled="!form.start_datetime"
                :min="endDateMin">
            <textarea v-model="form.purpose" placeholder="reason for renting"></textarea>
            <select v-model="form.room_id" @change="checkAvailability">
                <option value="" disabled selected>Select room</option>
                <option v-for="room in rooms" :key="room.id" :value="room.id">{{ room.name }}</option>
            </select>

            <input type="file">
            <button type="submit" :disabled="!isAvailable">{{ isEditMode ? 'Update Reservation' : 'Submit Reservation' }}</button>
            <h1>{{ roomInfo }}</h1>
        </form>
    </div>

    <div>
        <button @click="logout">Logout</button>
    </div>

    <div>
        <button @click="$router.push('/create-room')">Create a Room</button>
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
            endDateMin: '',
            error: '',
            isAvailable: true
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

        if (id) {
            console.log("Editing Mode");
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
            console.log("Creating Mode")
        }
    },
    setup() {
        const auth = useAuthStore();
        console.log(auth.user);
        return { auth };
    },
    methods: {
        setDateInput() {
            let startTime = new Date(this.form.start_datetime);
            console.log(startTime);
            startTime.setHours(startTime.getHours() + 1);
            this.endDateMin = this.toLocalDatetime(startTime);
            this.form.end_datetime = '';
            this.checkAvailability();
        },

        checkAvailability() {
            if (!this.form.start_datetime || !this.form.end_datetime || !this.form.room_id) this.available=false; return;

            fetch('/availability', {
                method: 'POST',
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
                .then(response => response.json())
                .then(data => {
                    this.isAvailable = data.available;
                    this.roomInfo = data.available ? 'Room is available' : 'Room is already booked';
                });
        },

        async submitReservation() {


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