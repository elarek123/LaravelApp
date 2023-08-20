<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function showhomepage(){
        if(auth() ->check()){
            return view('homepage-feed');
        }
        else{
            return view('homepage');
        }
    }

    public function register(Request $request){
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:30', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required' , 'min:8', 'confirmed'],
        ]);

        
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'You are now registered');
    }
    public function login(Request $request){
        $incomingFields = $request->validate([
            'loginusername' => ['required',],
            'loginpassword' => ['required'],
        ]);
        if (auth()->attempt(['name' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])){
            $request->session()->regenerate();
            return redirect('/') ->with('success', 'You are now logged in');
        }
        else{
            return redirect('/') ->with('error', 'Login failed');
        }
        
    }
    
    public function logout(){
        auth()->logout();
        return redirect('/') ->with('success', 'You are now logged out');
    }
}
