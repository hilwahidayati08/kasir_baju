<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

public function update(Request $request)
{
    $user = Auth::user();

    if ($request->hasFile('photo')) {

        $photoName = time() . '.' . $request->photo->extension();

        $request->photo->storeAs('public/user', $photoName);

        $user->photo = $photoName;
    }

    $user->user_name = $request->user_name;
    $user->user_email = $request->user_email;

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return back()->with('success', 'Profile updated!');
}

}
