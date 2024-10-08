<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function sendResponse($result, $message)
    {
        $response = [
            'status' => 'success',
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    protected function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'status' => 'error',
            'message' => $error,
            'data'   => null
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
