<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ProfileMahasiswa;
use App\Models\ProfileDosen;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile', [
            'user' => $user,
            'role' => $user->getRoleNames()->first(),
            'email' => $user->email,
            'id_number' => $user->mahasiswaProfile->nim ?? $user->dosenProfile->nip ?? null,
            'phone' => $user->mahasiswaProfile->kontak ?? $user->dosenProfile->kontak ?? null,
            'totalRiwayat' => Peminjaman::where('user_id', $user->id)->count()
        ]);
    }

    /**
     * Update data profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        // ================= VALIDASI =================
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'kontak' => 'nullable|string|max:20',
            'id_number' => 'nullable|string|max:30',
        ]);

        // ================= UPDATE USER =================
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // ================= UPDATE PROFILE SESUAI ROLE =================
        if ($role === 'mahasiswa') {
            ProfileMahasiswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => $request->id_number,
                    'kontak' => $request->phone,
                ]
            );
        }

        if ($role === 'dosen') {
            ProfileDosen::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $request->id_number,
                    'kontak' => $request->phone,
                ]
            );
        }

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' =>'Password lama yang anda masukan salah']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }
}
