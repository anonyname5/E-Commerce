<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            Log::error('AdminMiddleware: User not authenticated');
            return redirect()->route('login')->with('error', 'You must be logged in to access this area.');
        }

        $user = Auth::user();
        Log::info('AdminMiddleware: User authenticated', [
            'id' => $user->id,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'isAdmin()' => $user->isAdmin(),
        ]);

        if (!$user->isAdmin()) {
            Log::error('AdminMiddleware: User is not an admin');
            return redirect()->route('home')->with('error', 'You do not have permission to access this area.');
        }

        Log::info('AdminMiddleware: Admin access granted');
        return $next($request);
    }
} 