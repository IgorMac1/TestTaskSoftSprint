<?php

namespace core;

class ApiResponse
{
    public static function response($statusCode, $responseData, $errorMessage = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);

        $error = $errorMessage ? ['code' => $statusCode, 'message' => $errorMessage] : null;

        $response = array_merge([
            'status' => $statusCode === 200,
            'error' => $error,
        ], $responseData);

        echo json_encode($response);
    }

}