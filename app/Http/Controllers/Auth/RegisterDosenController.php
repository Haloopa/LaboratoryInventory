<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProfileDosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterDosenController extends Controller
{
    public function create()
    {
        return view('auth.register-dosen');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'nip'   => 'required|unique:dosen_profiles',
            'kontak' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // assign role
        $user->assignRole('dosen');

        // buat profile dosen
        ProfileDosen::create([
            'user_id' => $user->id,
            'nip'     => $request->nip,
            'kontak'   => $request->kontak,
        ]);

        return redirect('/home');
    }
}
