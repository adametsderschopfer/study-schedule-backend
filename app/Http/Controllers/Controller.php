<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($response = ['success' => true], $message = '', $code = 200)
    {
        if ($message !== '') {
            $response['message'] = $message;
        }
        return response()->json($response, $code);
    }

    public function sendError($error = '', $errorMessages = [], $code = 404)
    {
        $response['message'] = $error;
        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
