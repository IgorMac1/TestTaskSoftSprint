<?php

namespace core;

class ApiResponse
{
    public static function response($httpStatusCode, $status, $responseData,$errorCode = null,$errorMessage = null, $userNotFoundId = [])
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($httpStatusCode);

        $error = $errorMessage ? ['code' => $errorCode, 'message' => $errorMessage] : null;

        if (empty($userNotFoundId)) {
            $response = array_merge([
                'status' => $status,
                'error' => $error
            ], $responseData);
        } else {
            $response = array_merge([
                'status' => $status,
                'error' => $error,
                'notFoundId' => $userNotFoundId
            ], $responseData);
        }
        echo json_encode($response);
        die();
    }
}