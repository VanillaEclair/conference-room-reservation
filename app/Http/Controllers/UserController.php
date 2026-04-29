<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['success'=> true]);
        // return redirect('/');
    }
    public function login(Request $request)
    {
        $user = $request->validate([
            'name'  =>['required'],
            'password'  =>['required']  
        ]);

        $rooms = Room::all();

        if(auth()->attempt(['name'=> $user['name'], 'password' => $user['password']]))
        {
                    $request->session()->regenerate();
        return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        // return back()->withErrors(['username' => 'Invalid credentials']);
    }



    public function createUser(Request $request)
    {
        $user = $request->validate(
            [
                'username'=> ['required'],
                'email'=> ['required'],
                'password'=> ['required']
            ]);

        $createdUser = User::create([
            'name'=> $user['username'],
            'email'=> $user['email'],
            'password'=> bcrypt($user['password']),
        ]);

            auth()->login($createdUser);   
            return response()->json(['success'=> true]);
    }

    public function getUser(Request $request, $id)
    {
        $user = User::find($id);
        return response()->json($user);
    }
}
