<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function respondData($data = NULL, $StatusCode = Response::HTTP_OK)
    {
        return response()->json($data, $StatusCode);
    }

    protected function respondSuccess($message, $StatusCode = Response::HTTP_OK)
    {
        $response = [
            'message' => $message
        ];

        return response()->json($response, $StatusCode);
    }

    protected function respondError($error, $StatusCode = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'message' => $error
        ];

        return response()->json($response, $StatusCode);
    }

    protected function respondUnauthorized($error = 'Unauthorized')
    {
        return $this->respondError($error, Response::HTTP_UNAUTHORIZED);
    }

    protected function respondForbidden($error = 'Forbidden')
    {
        return $this->respondError($error, Response::HTTP_FORBIDDEN);
    }
}
