<?php

namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\JsonResponse response(array|mixed $payload, int $statusCode)
 * @method static \Illuminate\Http\JsonResponse success(mixed $data, string $message)
 * @method static \Illuminate\Http\JsonResponse error(string $message, ?int $statusCode = null, $error)
 */
class JsonResponse extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'JsonResponse';
    }
}
