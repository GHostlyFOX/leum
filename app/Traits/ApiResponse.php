<?php

namespace App\Traits;

trait ApiResponse
{
    protected function successResponse($data, string $message = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        $response = ['success' => true];
        if ($message) $response['message'] = $message;
        if ($data !== null) $response['data'] = $data;
        return response()->json($response, $code);
    }

    protected function errorResponse(string $message, int $code = 400, $errors = null): \Illuminate\Http\JsonResponse
    {
        $response = ['success' => false, 'message' => $message];
        if ($errors !== null) $response['errors'] = $errors;
        return response()->json($response, $code);
    }

    protected function paginatedResponse($items, $resourceClass = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $resourceClass ? $resourceClass::collection($items) : $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }
}
