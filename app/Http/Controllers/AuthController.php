<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Users; // Pastikan model user namanya User, bukan Users

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('authen.login');
    }

public function proseslogin(Request $request){
        $request->validate([
            'user_email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = Users::where('user_email', $request->input('user_email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user);
            if ($user->role == 'Admin') {
                return redirect()->intended('/admindashboard');
            } elseif ($user->role == 'Cabang') {
                return redirect()->intended('/branchdashboard');
            } else {
                return redirect()->intended('/sales/create');
            }
        } else {
            return redirect()->route('login')->with('succes', 'invalid login credentials');
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login');
    }
}
