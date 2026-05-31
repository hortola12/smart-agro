<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        //  check:  If we are not login or admin, 
        if (!Auth::check() || Auth::user()->role !== 'admin') {

            // Go back to Login and show warming message
            return redirect()->route('login')->withErrors([
                'email' => 'ទំព័រនេះសម្រាប់តែអ្នកគ្រប់គ្រង (Admin) ប៉ុណ្ណោះ។',
            ]);
        }

        // only admin  can enter
        return $next($request);
    }
}
