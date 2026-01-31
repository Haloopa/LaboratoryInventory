<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // TAMBAHKAN INI
use Illuminate\Support\Facades\Auth; // TAMBAHKAN INI

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/borrowing/dashboard'; // UBAH INI SAJA: '/home' -> '/borrowing/dashboard'

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * TAMBAHKAN METHOD INI UNTUK HANDLE REDIRECT BERDASARKAN ROLE
     * (TANPA MENGUBAH LOGIC LAIN)
     */
    protected function authenticated(Request $request, $user)
    {
        // Hanya tambahkan logic redirect, tidak mengubah yang lain
        if ($user->hasRole(['admin', 'super admin'])) {
            return redirect('/');
        }

        // Default tetap ke borrowing/dashboard (untuk mahasiswa/dosen)
        return redirect('/borrowing/dashboard');
    }
}
