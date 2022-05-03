<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SignInController extends BaseController
{
    public function signIn(Request $request)
    {
        //dd('Our own auth!'); //laravel conslog log maar op het scherm na de code
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if(Auth::attempt([
            'email'=>$request->input('email'),
            'password'=>$request->input('password')
        ],$request->has('remember'))){//cookie voor de user 
            return redirect()->route('admin.index');
        }
       return redirect()->back()->with('fail','Authentication failed'); //error message in login.blade
    }
}
