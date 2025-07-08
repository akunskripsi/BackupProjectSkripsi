<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah user dengan email tersebut ada
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Akun dengan email tersebut belum terdaftar.',
            ]);
        }

        // Coba autentikasi jika user ditemukan
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Tambahan validasi status akun
            if ($user->status == 'submitted') {
                Auth::logout(); // Logout langsung jika status belum aktif
                return back()->withErrors([
                    'email' => 'Akun anda masih menunggu persetujuan admin',
                ]);
            } elseif ($user->status == 'rejected') {
                Auth::logout(); // Logout juga
                return back()->withErrors([
                    'email' => 'Akun anda telah ditolak admin',
                ]);
            }

            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
