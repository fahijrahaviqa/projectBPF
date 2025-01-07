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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if ($role === 'admin' && !auth()->user()->isAdmin()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        // Set layout berdasarkan role
        if ($role === 'admin') {
            view()->share('layout', 'layouts.admin');
        } else {
            view()->share('layout', 'layouts.customer');
        }

        return $next($request);
    }
} 