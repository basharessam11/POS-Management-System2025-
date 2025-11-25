<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class Language
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // استخدم اللغة من الجلسة إذا موجودة، وإلا استخدم اللغة الافتراضية من config
        $locale = $request->session()->get('locale', Config::get('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}
