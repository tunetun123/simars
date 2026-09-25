<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is using the default password
            if (Hash::check('password123', $user->password)) {
                // Prevent redirect loops by allowing them on the change-password or logout routes
                // Also allow Livewire internal requests to process the password update
                if (!$request->routeIs('change-password') &&
                    !$request->routeIs('logout') &&
                    !$request->hasHeader('X-Livewire')) {
                    return redirect()->route('change-password')->with('warning', 'Anda diwajibkan mengubah password default sebelum melanjutkan.');
                }
            }
        }

        return $next($request);
    }
}
