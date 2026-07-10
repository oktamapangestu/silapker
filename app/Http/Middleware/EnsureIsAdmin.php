<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $prioritas = $request->session()->get('pengguna.akses.role.prioritas');
        $threshold = config('services.central_api.admin_priority_threshold');

        $isAdmin = is_numeric($prioritas) && (int) $prioritas <= $threshold;

        abort_unless($isAdmin, 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }
}
