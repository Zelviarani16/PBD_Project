<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCustom as User;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function login()
    {

        if (session('login')) {
        return redirect('/dashboard');
    }
        return view('auth.login');
    }

    // Proses login
    public function loginPost(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        // Cek user di database
        $user = User::where('username', $username)
                    ->where('password', $password)
                    ->first();

        // Jika user ditemukan dan role = Super Admin
        if ($user && $user->idrole === 'SADM1') {

            // Simpan sesi login
            session([
                'login'    => true,
                'iduser'   => $user->iduser,
                'username' => $user->username,
                'idrole'   => $user->idrole,
            ]);

            return redirect('/dashboard');
        }

        // Jika login gagal
        return back()->with('error', 'Username atau password salah / bukan Super Admin.');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
