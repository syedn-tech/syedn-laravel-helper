<?php

namespace Syedn\Helper\Responses;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse as BaseJsonResponse;
use Illuminate\Support\Facades\Log;

class JsonResponse
{
    const HTTP_OK = 200;

    const HTTP_CREATED = 201;

    const HTTP_BAD_REQUEST = 400;

    const HTTP_UNAUTHORIZED = 401;

    const HTTP_FORBIDDEN = 403;

    const HTTP_NOT_FOUND = 404;

    const HTTP_UNPROCESSABLE_ENTITY = 422;

    const HTTP_INTERNAL_SERVER_ERROR = 500;

    const HTTP_BAD_GATEWAY = 502;

    const HTTP_OKS = [
        self::HTTP_OK,
        self::HTTP_CREATED,
    ];

    const TIMEZONE = 'UTC';

    protected static $message = [
        self::HTTP_OK => 'OK',
        self::HTTP_BAD_REQUEST => 'Bad request',
        self::HTTP_UNAUTHORIZED => 'Unauthorized',
        self::HTTP_FORBIDDEN => 'Forbidden',
        self::HTTP_NOT_FOUND => 'Not found',
        self::HTTP_UNPROCESSABLE_ENTITY => 'Unprocessable entity',
        self::HTTP_INTERNAL_SERVER_ERROR => 'Internal server error',
        self::HTTP_BAD_GATEWAY => 'Bad Gateway',
    ];

    public static function success($data = null, $statusCode = self::HTTP_OK, $title = null, $message = null): BaseJsonResponse
    {
        return self::buildResponse(
            $data,
            $statusCode,
            $title,
            $message
        );
    }

    public static function error($statusCode, $title = null, $message = null, $errors = null): BaseJsonResponse
    {
        Log::error(response()->json($errors, $statusCode, [], JSON_PRESERVE_ZERO_FRACTION));

        return self::buildResponse(
            null,
            $statusCode,
            $title,
            $message,
            $errors
        );
    }

    private static function buildResponse($data, $statusCode, $message = null, $errors = null): BaseJsonResponse
    {
        $response['success'] = in_array($statusCode, self::HTTP_OKS);
        $response['data'] = $data;
        $response['message'] = $message;
        $response['error'] = $errors;

        $response['meta'] = [
            'time_stamp' => Carbon::now()->format('Y-m-d H:i:s T'),
            'time_zone' => self::TIMEZONE,
        ];

        return response()->json(
            $response,
            $statusCode,
            [],
            JSON_PRESERVE_ZERO_FRACTION
        );
    }
}
