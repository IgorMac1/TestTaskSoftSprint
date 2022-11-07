<?php

namespace core;

class ApiResponse
{
    public static function response($statusCode, $status, $responseData, $errorMessage = null, $userNotFoundId = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);

        if (empty($userNotFoundId)) {
            $response = array_merge([
                'code' => $statusCode,
                'status' => $status,
                'error' => $errorMessage
            ], $responseData);
        } else {
            $response = array_merge([
                'code' => $statusCode,
                'status' => $status,
                'error' => $errorMessage,
                'notFoundId' => $userNotFoundId
            ], $responseData);
        }
        echo json_encode($response);
        die();
    }
}