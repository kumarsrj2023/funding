<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MikeMcLin\WpPassword\Facades\WpPassword;
use App\Models\User;
use Helper;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (auth()->check())
        {
            return redirect()->route('home');
        }

        if (request()->method() == 'GET')
        {
            return view('auth.login');
        }

        $validator = \Validator::make($request->all(), [
            'email' => 'required|max:255',
            'password' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $user = User::where('user_email', $request->email)->whereNotNull('user_pass')->first();

        if (empty($user))
        {
            $user = User::where('user_login', $request->email)->whereNotNull('user_pass')->first();

            if (empty($user))
            {
                return redirect()->back()->withInput()->with('error_message', 'Login Failed. Email/Password is incorrect.');
            }
        }

        if (!WpPassword::check($request->password, $user->user_pass))
        {
            return redirect()->back()->withInput()->with('error_message', 'Login Failed. Email/Password is incorrect.');
        }

        $remember = false;

        if (!empty($request->remember))
        {
            $remember = true;
        }

        auth()->login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
