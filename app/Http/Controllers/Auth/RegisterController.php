<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileMahasiswa;
use App\Models\ProfileDosen;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role'      => ['required', 'in:dosen,mahasiswa'],

            // VALIDASI NIP/NIM DINAMIS
            'nip'       => ['required_if:role,dosen', 'nullable'],
            'nim'       => ['required_if:role,mahasiswa', 'nullable'],

            'contact'   => ['required', 'string', 'max:255'],
        ]);
    }

    protected function create(array $data)
    {
        // 1. buat user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        // 2. assign role
        $user->assignRole($data['role']);

        // 3. insert profil sesuai role
        if ($data['role'] === 'mahasiswa') {
            ProfileMahasiswa::create([
                'user_id' => $user->id,
                'nim'     => $data['nim'],
                'kontak'  => $data['contact'],  // jika ada
            ]);
        }

        if ($data['role'] === 'dosen') {
            ProfileDosen::create([
                'user_id' => $user->id,
                'nip'     => $data['nip'],
                'kontak'  => $data['contact'],  // jika ada
            ]);
        }

        return $user;
    }
}
