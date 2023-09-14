<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create(){
        return view('users.register');
    }

    public function store(Request $request){

        $formFields = $request->validate([
            'name'=>['required','min:3'],
            'email'=>['required','email',Rule::unique('users','email')],
            'password'=>'required|confirmed|min:6',

        ]);

        //Store the password in hashing code
        
        $formFields['password']= bcrypt($formFields['password']);
        
        //Create user then login
        $user = User::create($formFields);
        auth()->login($user);
        return redirect('/')->with('message','User Created And Logged In Successfully');
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('meesage', 'Logged Out');
    }

    public function login(){
        return view('users.login');
    }

    public function authenticate(Request $request){
            $formFields = $request->validate([
                'email'=>['required','email'],
                'password'=>'required'
            ]);
        
            if(auth()->attempt($formFields)){
                $request->session()->regenerate();
               return redirect('/')->with('message','You are logged in');  
            }
            
           return back()->withErrors(['email'=>'Invalid Email Or Password'])->onlyInput('email');
    }
}




