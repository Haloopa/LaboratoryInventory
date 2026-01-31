<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ProfileMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterMahasiswaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nim' => 'required',
            'kontak' => 'required',
        ]);

        // 1. Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // 2. Beri role
        $user->assignRole('Mahasiswa');

        // 3. Buat profile mahasiswa
        ProfileMahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'kontak' => $request->kontak
        ]);

        return redirect('/home');
    }

}
