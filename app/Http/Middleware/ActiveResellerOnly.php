<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveResellerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->isReseller() && (! auth()->user()->reseller || ! auth()->user()->reseller->isActive())) {
            abort(404);
        }

        return $next($request);
    }
}
