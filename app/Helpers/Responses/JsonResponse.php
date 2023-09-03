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
    public function success($data = null, string $message)
    {
        $payload = ['message' => $message];

        if (isNotEmptyStringOrNull($data)) {
            if (array_key_exists('data', $data)) {
                $payload = array_merge($payload, $data);
            } else {
                $payload['data'] = $data;
            }
        }

        return $this->response($payload, Response::HTTP_OK);
    }

    /**
     * @param string $message
     * @param integer|null $statusCode
     * @param mixed $error
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

    /**
     * @param string|null $message
     * @param mixed $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized(?string $message = null, $error = null)
    {
        return $this->error($message ?? 'Unauthorized.', Response::HTTP_UNAUTHORIZED, $error);
    }
}
