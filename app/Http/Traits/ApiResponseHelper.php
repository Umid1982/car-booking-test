<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseHelper
{

    /**
     * @param mixed $data
     * @param string $name
     * @param int $code
     * @param string|null $message
     * @return JsonResponse
     */
    public function successResponse(
        mixed   $data,
        string  $name = 'data',
        int     $code = 200,
        ?string $message = null
    ): JsonResponse
    {
        $response = [
            'success' => true,
            $name => $data,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse(
        string $message,
        int    $code = 400
    ): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
