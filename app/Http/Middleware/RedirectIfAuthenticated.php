<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                return match ($user->role) {
                    User::ROLE_ADMIN => redirect()->route('admin.dashboard'),
                    User::ROLE_DOCTOR => redirect()->route('doctor.dashboard'),
                    User::ROLE_NURSE => redirect()->route('nurse.dashboard'),
                    User::ROLE_PATIENT => redirect()->route('patient.dashboard'),
                    default => redirect('/'),
                };
            }
        }

        return $next($request);
    }
}
