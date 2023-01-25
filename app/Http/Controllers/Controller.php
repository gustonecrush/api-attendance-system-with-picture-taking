<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendResponse($result, $message, $code)
    {
        $response = [
            'success' => true,
            'status_code' => $code,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }

    protected function sendError($error, $errorMessages = [], $code)
    {
        $response = [
            'success' => false,
            'status_code' => $code,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['error'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
