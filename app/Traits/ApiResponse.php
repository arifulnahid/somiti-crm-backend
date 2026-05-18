<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Success response
     *
     * @param  mixed  $data
     */
    protected function successResponse(mixed $data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $headers = [];
        $responseData = $data;

        // Extract paginator if wrapped inside an AnonymousResourceCollection
        $paginator = $data instanceof AnonymousResourceCollection ? $data->resource : $data;

        // Check if we are dealing with a LengthAwarePaginator
        if ($paginator instanceof LengthAwarePaginator) {
            $headers = [
                'X-Total-Count' => $paginator->total(),
                'Access-Control-Expose-Headers' => 'X-Total-Count',
            ];
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $responseData,
            'timestamp' => now()->toIso8601String(),
            'status_code' => $statusCode,
        ], $statusCode, $headers);
    }

    /**
     * Return a standardized success response.
     */
    protected function success($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $responseStructure = [
            'success'     => true,
            'message'     => $message,
            'status_code' => $statusCode,
            'timestamp'   => now()->toIso8601String(),
            'data'        => null,
        ];

        $headers = [];

        // Scenario A: Handled a Paginated Resource Collection
        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof AbstractPaginator) {
            $paginator = $data->resource;

            // Resolve raw array items efficiently without a full HTTP lifecycle simulation
            $responseStructure['data'] = $data->resolve();

            // Extract native metrics directly from the paginator object
            $responseStructure['pagination'] = [
                'total'        => $paginator->total(),
                'count'        => $paginator->count(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'total_pages'  => $paginator->lastPage(),
                'has_more'     => $paginator->hasMorePages(),
            ];


            $headers = [
                'X-Total-Count'   => $paginator->total(),
                'Access-Control-Expose-Headers' => 'X-Total-Count'
            ];
        }
        // Scenario B: Handled a Raw Paginated Eloquent Query (No Resource attached)
        elseif ($data instanceof AbstractPaginator) {
            $responseStructure['data'] = $data->items();
            $responseStructure['pagination'] = [
                'total'        => $data->total(),
                'count'        => $data->count(),
                'per_page'     => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages'  => $data->lastPage(),
                'has_more'     => $data->hasMorePages(),
            ];
        }
        // Scenario C: Handled Standard API Resource Object or Single Item
        elseif ($data instanceof JsonResource) {
            $responseStructure['data'] = $data->resolve();
        }
        // Scenario D: Standard Array or Object
        else {
            $responseStructure['data'] = $data;
        }

        return response()->json($responseStructure, $statusCode, $headers);
    }

    protected function efficientResponse($data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $headers = [];
        $payload = $data;

        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof AbstractPaginator) {
            $paginator = $data->resource;

            // 1. Set base headers that ALL paginators share
            $headers = [
                'X-Per-Page'      => $paginator->perPage(),
                'X-Current-Page'  => $paginator instanceof CursorPaginator ? $paginator->cursor()?->encode() : $paginator->currentPage(),
                'X-Has-More'      => $paginator->hasMorePages() ? 'true' : 'false',
            ];

            // 2. ONLY add total metrics if using standard LengthAwarePaginator
            if ($paginator instanceof LengthAwarePaginator) {
                $headers['X-Total-Count'] = $paginator->total();
                $headers['X-Total-Pages'] = $paginator->lastPage();
            }

            // 3. Expose all active custom headers to React / Axios
            $exposedHeaders = implode(', ', array_keys($headers));
            $headers['Access-Control-Expose-Headers'] = $exposedHeaders;

            // Resolve raw mapped items for the response body
            $payload = $data->resolve();
        }

        return response()->json($payload, $statusCode, $headers);
    }

    /**
     * Error response
     *
     * @param  mixed  $errors
     */
    protected function errorResponse(string $message = 'Error', int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
            'status_code' => $statusCode,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Created response (201)
     *
     * @param  mixed  $data
     */
    protected function createdResponse($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

   /**
     * No content response
     */
    protected function noContentResponse(string $message = 'No content found'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'status_code' => 204,
            'timestamp' => now()->toIso8601String(),
        ], 204);
    }
}
