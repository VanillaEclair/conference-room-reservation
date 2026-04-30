<template>
    <p>Requests</p>
    <div class="">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Event</th>
                    <th>Requested By</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>status</th>
                    <th>actions</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="reservation in reservations" :key="reservation.id">
                    <td>{{ reservation.title }}</td>
                    <td>{{ reservation.purpose }}</td>
                    <td>{{ reservation.user?.name }}</td>
                    <td>{{ reservation.start_datetime }}</td>
                    <td>{{ reservation.end_datetime }}</td>
                    <td>{{ reservation.status }}</td>
                    <td>
                        <button @click="accept(reservation)" style="background-color: blue;">Accept</button>
                        <button @click="decline(reservation)" style="background-color: red;">Decline</button>
                        <button @click="$router.push(`/reservation/${reservation.id}/edit`)" style="background-color: orange;">Edit</button>
                        <button @click="remove(reservation)" style="background-color: red;">Delete</button>
                    </td>

                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total: 2 users</td>
                </tr>
            </tfoot>
        </table>


        <button @click="logout">Logout</button>
        <br>
        <router-link to="/create-reservation">Create Reservation</router-link>

    </div>
</template>


<script>
import { useAuthStore } from '../stores/auth'

export default {
    data() {
        return {
            reservations: []
        }
    },
    mounted() {
        fetch('/reservations')
            .then(response => response.json())
            .then(data => {
                this.reservations = data;
            });
    },
 
    setup() {
        const auth = useAuthStore();
        console.log(auth.user);
        return { auth };
    },
    methods:
    {

        async logout() {
            await this.auth.logout();
            this.$router.push('/login');
        },

        async accept(reservation) {
            // console.log(reservation.id);
            fetch(`/reservations/${reservation.id}/accept`,
                {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the status locally so UI reflects change immediately
                        reservation.status = 'Accepted';
                    }
                });

        },

        async decline(reservation) {
            fetch(`/reservations/${reservation.id}/decline`,
                {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }

            )
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        reservation.status = 'Declined';
                    }
                });
        },

        async remove(reservation) {
            fetch(`/reservations/${reservation.id}/remove`,
                {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }

            )
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        reservation.status = 'Removed';
                    }
                });
        },

        async edit(reservation) {
            fetch(`/reservations/${reservation.id}/edit`,
                {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }

            )
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        reservation.status = 'Edited';
                    }
                });
        }




    }

}


</script>