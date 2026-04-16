<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Branch;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $datauser = Users::all();
        return view('users.index', compact('datauser'));
    }

    public function create()
    {
        $dataBranch = Branch::all();
        return view('users.create', compact('dataBranch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_name'  => 'required',
            'user_email' => 'required|unique:user',
            'password' => 'required|min:4',
            'role' => 'required',
            'branch_id' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $photoName = null;

        if ($request->hasFile('photo')) {
            $photoName = $request->file('photo')->store('user', 'public');
        }

        Users::create([
            'user_name'  => $request->user_name,
            'user_email' => $request->user_email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'branch_id'  => $request->branch_id,
            'photo'      => $photoName,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dataEdituser = Users::findOrFail($id);
        $branch = Branch::all();
        return view('users.edit', compact('dataEdituser', 'branch'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_name'  => 'required',
            'user_email' => 'required|unique:user,user_email,' . $id . ',user_id',
            'password' => 'nullable|min:4',
            'role' => 'required',
            'branch_id' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $dataUpdateuser = Users::findOrFail($id);

        // Update foto
        if ($request->hasFile('photo')) {

            if ($dataUpdateuser->photo) {
                Storage::disk('public')->delete($dataUpdateuser->photo);
            }

            $dataUpdateuser->photo = $request->file('photo')->store('user', 'public');
        }

        $dataUpdateuser->user_name = $request->user_name;
        $dataUpdateuser->user_email = $request->user_email;
        $dataUpdateuser->role = $request->role;
        $dataUpdateuser->branch_id = $request->branch_id;

        if ($request->filled('password')) {
            $dataUpdateuser->password = bcrypt($request->password);
        }

        $dataUpdateuser->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diubah.');
    }

    public function destroy($id)
    {
        $user = Users::findOrFail($id);

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
