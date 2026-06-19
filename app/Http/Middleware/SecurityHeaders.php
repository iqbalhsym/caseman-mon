<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Strict-Transport-Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // 2. X-Frame-Options (Mencegah Clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 3. X-Content-Type-Options (Mencegah MIME-sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Permissions-Policy (Membatasi akses API browser yang tidak diperlukan)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), interest-cohort=()');

        // 6. Content-Security-Policy (CSP)
        // Karena AdminLTE, jQuery, dan Bootstrap sering menggunakan inline scripts/styles,
        // kita membuat kebijakan awal yang cukup aman namun tidak merusak layout dashboard Anda.
        $csp = "default-src 'self' https:; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:; " .
               "style-src 'self' 'unsafe-inline' https:; " .
               "img-src 'self' data: https:; " .
               "font-src 'self' https: data:; " .
               "connect-src 'self' https:; " .
               "frame-ancestors 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
