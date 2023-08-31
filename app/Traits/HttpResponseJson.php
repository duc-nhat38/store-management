<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait HttpResponseJson
{
    /**
     * @param array $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseJson($payload, int $statusCode)
    {
        return response()->json(array_merge(
            [
                'status_code' => $statusCode
            ],
            $payload
        ), $statusCode);
    }

    /**
     * @param mixed $data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccess($data, string $message)
    {
        return $this->responseJson(['message' => $message, 'data' => $data], Response::HTTP_OK);
    }

    /**
     * @param string $message
     * @param mixed $error
     * @param integer|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseError(string $message, $error, ?int $statusCode = null)
    {
        return $this->responseJson([
            'message' => $message,
            'errors' => (array) $error
        ], $statusCode ?? Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
