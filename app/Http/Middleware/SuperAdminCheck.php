<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                // Ambil role dari session
        $role = $request->session()->get('idrole');

        // Cek apakah role BUKAN superadmin
        if ($role !== 'SADM1') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses.');
        }


        return $next($request);
    }
}
