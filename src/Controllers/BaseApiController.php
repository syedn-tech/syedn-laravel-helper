<?php

namespace Syedn\Helper\Controllers;

use Illuminate\Support\MessageBag;
use Illuminate\Routing\Controller;
use Syedn\Helper\Responses\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Calls the given controller method with the given parameters.
     *
     * The method is expected to return either:
     *  - a MessageBag instance, which is returned as a 422 JSON response
     *  - an instance of \Illuminate\Http\JsonResponse, which is returned as is
     *  - any other response, which is returned as a 200 JSON response
     *
     * @param  string  $method  The name of the controller method to call.
     * @param  array  $parameters  The parameters to pass to the method.
     * @return \Illuminate\Http\JsonResponse The JSON response to return.
     */
    public function callAction($method, $parameters)
    {
        $response = $this->{$method}(...array_values($parameters));

        if ($response instanceof MessageBag) {
            return JsonResponse::error(
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                $response
            );
        }

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response;
        }

        return JsonResponse::success($response);
    }
}
