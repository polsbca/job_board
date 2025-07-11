<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add any routes that should be excluded from CSRF verification
        // '/stripe/*',
        '/api/*', // Allow all API routes to skip CSRF
    ];

    /**
     * Determine if the request needs to be verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldVerify(Request $request)
    {
        // Allow all API requests to skip CSRF verification
        if ($request->is('api/*')) {
            return false;
        }

        return parent::shouldVerify($request);
    }
}
