<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    
   public function handle($request, Closure $next)
{
    
    if ($request->user() && $request->user()->role === 'admin') {
        return $next($request);
    }

    abort(403, 'You do not have administrative privileges to access this page.');
}
}
