
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    @auth
    <div class="Main-body">
        <form action="/create-reservation" method="POST">
            @csrf
            <input type="text" name="title" placeholder="title">
            <input type="datetime-local" name="start_datetime" id="">
            <input type="datetime-local" name="end_datetime" id="">
            <textarea name="purpose" placeholder="reason for renting" id=""></textarea>
            <input type="file">
            <button>Submit</button>
        </form>
    </div>

    <div class="">
        <h4>{{ auth()->user()->name }}</h4>
        <form action="/logout" method="POST">
            @csrf
            <button>Logout</button>
        </form>
     
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