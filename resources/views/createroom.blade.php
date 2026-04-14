<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <form action="/create-room" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Room Name">
        <input type="text" name="location" placeholder="location">
        <input type="number" name="capacity" min="10" placeholder="capacity">
        <input type="text" name="status" value="Available">
        <button>Create Room</button>
    </form>
</body>
</html>

