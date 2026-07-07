<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Cara pakai di routes/web.php:
     *   Route::middleware(['auth', 'role:admin'])->group(function () { ... });
     *   Route::middleware(['auth', 'role:admin,member'])->group(function () { ... });
     *
     * Bisa terima lebih dari 1 role sekaligus (dipisah koma).
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $allowedRoles = explode(',', $roles);

        if (! $request->user()) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (! in_array($request->user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // User dengan status blocked tidak boleh akses fitur apa pun
        if ($request->user()->role === 'member' && $request->user()->status === 'blocked') {
            abort(403, 'Akun Anda telah diblokir. Silakan hubungi admin.');
        }

        return $next($request);
    }
}