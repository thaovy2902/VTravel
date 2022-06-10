<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    protected function respondData($data = NULL, $statusCode = Response::HTTP_OK)
    {
        return response()->json($data, $statusCode);
    }

    protected function respondSuccess($message, $statusCode = Response::HTTP_OK)
    {
        $response = [
            'message' => $message
        ];

        return response()->json($response, $statusCode);
    }

    protected function respondError($error, $statusCode = Response::HTTP_NOT_FOUND)
    {
        $response = [
            'message' => $error
        ];

        return response()->json($response, $statusCode);
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
