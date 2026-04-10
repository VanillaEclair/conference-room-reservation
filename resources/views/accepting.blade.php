<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Requests</p>
    <div class="">
        <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Event</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation['title'] }}</td>
                <td>{{ $reservation['purpose'] }}</td>
                <td>{{ $reservation['start_datetime'] }}</td>
                <td>{{ $reservation['end_datetime'] }}</td>
                <td>{{ $reservation['status'] }}</td>
            </tr>
            
            @endforeach
            <tr>
                <td>Sora Shiina</td>
                <td>Elevator fun</td>
                <td>03-12-2000</td>
                <td>2pm</td>
                <td>5pm</td>
                <td><a href="">PDF</a></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
            <td colspan="3">Total: 2 users</td>
            </tr>
        </tfoot>
        </table>
    </div> 
</body>
</html>