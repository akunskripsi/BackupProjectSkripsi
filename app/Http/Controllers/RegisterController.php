<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ambil ID role 'user' dari tabel roles
        $userRoleId = DB::table('roles')->where('name', 'user')->value('id');

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $userRoleId,
            'status'   => 'submitted', // default dari migration
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }
}
