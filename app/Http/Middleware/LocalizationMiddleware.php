<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ប្រសិនបើមានរក្សាទុកភាសាក្នុង Session ឱ្យប្រព័ន្ធកំណត់ដូរភាសាតាមនោះភ្លាម
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        } else {
            App::setLocale('kh'); // បើមិនទាន់មានរើសទេ ឱ្យដើរភាសាខ្មែរជាលំនាំដើម
        }

        return $next($request);
    }
}
