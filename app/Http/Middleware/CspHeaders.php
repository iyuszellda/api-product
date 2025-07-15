<?php

namespace App\Http\Middleware;

use Closure;

class CspHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' https://yustinusmargiyuna.cc https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https:; " .
            "object-src 'none'; " .
            "frame-ancestors 'none';"
        );
        return $response;
    }
}