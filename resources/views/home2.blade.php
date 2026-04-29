<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>

<body>

    @auth
        <div class="Main-body">
            <form action="/create-reservation" method="POST">
                @csrf
                <input type="text" name="title" placeholder="title">
                <input type="datetime-local" name="start_datetime" id="start_datetime">
                <input type="datetime-local" name="end_datetime" id="end_datetime" disabled>
                <textarea name="purpose" placeholder="reason for renting" id=""></textarea>
                <select name="room_id" id="room-select">
                    <option value="" disabled selected>Select room</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room['id'] }}">{{ $room['name'] }}</option>
                    @endforeach
                </select>

                <input type="file">
                <button>Submit</button>
                <h1 id="room-info">Test</h1>
            </form>
        </div>

        <div class="">
            <h4>{{ auth()->user()->name }}</h4>
            <form action="/logout" method="POST">
                @csrf
                <button>Logout</button>
            </form>

        </div>

        <div class="">
            <button><a href="{{ route('room.view') }}">Create a Room</a></button>
        </div>

    @else
        <form action="/login" method="POST">
            @csrf
            <input type="text" name="name" placeholder="username">
            <input type="password" name="password" placeholder="password">
            <button>Login</button>
        </form>
        <button><a href="{{ route('user.create') }}">Sign up</a></button>
    @endauth



</body>

</html>

<script>

    let date = new Date();
    let formatted = toLocalDatetime(date);

    window.onload = function () {

        console.log("Page fully loaded!");
        console.log(formatted);
        document.getElementById("start_datetime").min = formatted;
        //2026-04-14T13:43
    }

    document.getElementById('room-select').addEventListener('change', checkAvailability);
    document.getElementById('start_datetime').addEventListener('change', function () {
        checkAvailability();
        setDateInput();
    });
    document.getElementById('end_datetime').addEventListener('change', checkAvailability);

    //=======================CHECK ROOM AVAILABILITY CONFLICT=======================================
    function checkAvailability() {
        let start_datetime = document.getElementById('start_datetime').value;

        let end_datetime = document.getElementById('end_datetime').value;

        let room_id = this.value;

        if (!start_datetime || !end_datetime || !room_id) return;

        console.log(start_datetime);

        fetch('/availability',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ room_id: room_id, starttime: start_datetime, endtime: end_datetime })
            }
        )
            .then(response => response.json())
            .then(data => {
                document.getElementById('room-info').innerText = data.available;
            });
    }


    //CHAGE DATE RANGE OF END TIME INPUT WHEN START TIME IS SET
    function setDateInput() {


        const startTime = new Date(document.getElementById("start_datetime").value);
        startTime.setHours(startTime.getHours()+ 1);
        let formatted = toLocalDatetime(startTime);

        let endInput = document.getElementById('end_datetime');
        endInput.disabled = false;
        endInput.min = formatted;
        endInput.value = '';

        console.log(formatted);

    }

    function toLocalDatetime(date) {
        return date.getFullYear() + '-' +
            String(date.getMonth() + 1).padStart(2, '0') + '-' +
            String(date.getDate()).padStart(2, '0') + 'T' +
            String(date.getHours()).padStart(2, '0') + ':' +
            String(date.getMinutes()).padStart(2, '0');
    }
</script>