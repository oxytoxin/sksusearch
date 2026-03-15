<?php

namespace App\Helpers;

use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    public static function success(
        $data = null,
        string $message = 'Operation successful',
        int $status = 200,
        ?string $code = null
    ) {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($code) {
            $response['code'] = $code;
        }

        return response()->json($response, $status);
    }

    /**
     * Error response
     */
    public static function error(
        string $message = 'Something went wrong',
        int $status = 400,
        ?string $code = null,
        $errors = null
    ) {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($code) {
            $response['code'] = $code;
        }

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }



}
