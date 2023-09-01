<?php

namespace App\Http\Middleware;

use App\Helpers\Facades\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiSecure
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!is_null(config('app.api_key'))) {
            $requestApiKey = $request->header('X-api-key');

            if ($requestApiKey !== config('app.api_key')) {
                return JsonResponse::error('Unauthorized.', HttpResponse::HTTP_UNAUTHORIZED, '');
            }
        }

        return $next($request);
    }
}
