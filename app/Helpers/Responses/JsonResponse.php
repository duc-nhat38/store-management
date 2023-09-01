<?php

namespace App\Helpers\Responses;

use Illuminate\Http\Response;

class JsonResponse
{
    /**
     * @param array $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($payload, int $statusCode)
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
    public function success($data, string $message)
    {
        return $this->response(['message' => $message, 'data' => $data], Response::HTTP_OK);
    }

    /**
     * @param string $message
     * @param mixed $error
     * @param integer|null $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message, ?int $statusCode = null, $error = null)
    {
        $payload = ['message' => $message];

        if (isNotEmptyStringOrNull($error)) {
            $payload['errors'] = $error;
        }

        return $this->response($payload, $statusCode ?? Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
