<?php

use Symfony\Component\HttpFoundation\Response;

function apiSuccess($data, string $message = null, int $code = 200)
{
    return response()->json([
        'status' => 'success',
        'message' => $message,
        'data' => $data
    ], $code);
}

/**
 * Return an error JSON response.
 *
 * @param  string  $message
 * @param  int  $code
 * @param  array|string|null  $data
 * @return \Illuminate\Http\JsonResponse
 */
function apiError(string $message = null, int $code = Response::HTTP_BAD_REQUEST, $errors = null)
{
    return response()->json([
        'status' => 'error',
        'message' => $message,
        'errors' => $errors
    ], $code);
}

function spaUrlBuilder(String $path) {
    return config('app.spa_url').$path;
}
