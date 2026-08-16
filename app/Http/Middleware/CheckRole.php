<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        if (empty($roles)) {
            return $next($request);
        }

        $userRoleName = strtolower($user->role?->name ?? '');

        foreach ($roles as $role) {
            if (strtolower($role) === $userRoleName) {
                return $next($request);
            }
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
    }
}
