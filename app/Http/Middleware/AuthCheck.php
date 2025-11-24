<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    /**
     * Daftar route yang bisa diakses tanpa login
     */
    protected $except = [
        'login',
        'logout',
        '/',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Jika user belum login
        if (!$request->session()->has('login') || $request->session()->get('login') !== true) {

            // Cek apakah route saat ini ada di daftar except
            foreach ($this->except as $route) {
                if ($request->is($route) || $request->is($route . '/*')) {
                    return $next($request);
                }
            }

            // Jika bukan route except, redirect ke login
            return redirect('/login');
        }

        // Jika sudah login, lanjut
        return $next($request);
    }
}
