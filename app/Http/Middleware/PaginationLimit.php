<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaginationLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maximumLimit = config('paginate.maximum_limit');

        if ($request->limit && $request->limit > $maximumLimit) {
            $request->merge(['limit' => $maximumLimit]);
        }

        return $next($request);
    }
}
