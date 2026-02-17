<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpAddress
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = config('app.allowed_ips');

        // Jika tidak ada pembatasan IP (kosong di config), izinkan saja
        if (empty($allowedIps)) {
            return $next($request);
        }

        // Jika user adalah admin, periksa IP-nya
        if ($request->user() && $request->user()->role === 'admin') {
            if (!in_array($request->ip(), explode(',', $allowedIps))) {
                abort(403, 'Akses Ditolak: IP Address Anda (' . $request->ip() . ') tidak terdaftar di sistem.');
            }
        }

        return $next($request);
    }
}
