<?php

namespace App\Http\Controllers\API;

use App\Filters\V1\SocietyFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocietyRequest;
use App\Http\Requests\UpdateSocietyRequest;
use App\Http\Resources\SocietyResource;
use App\Models\Society;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SocietyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Process query filters
        $filter = new SocietyFilter;
        $filterItems = $filter->transform($request);

        // 2. Fetch cleanly validated pagination inputs
        $perPage = (int) $request->query('pageSize', 10);
        $currentPage = (int) $request->query('currentPage', 1);

        $societies = Society::with(['address', 'members'])->paginate($perPage, ['*'], 'page', $currentPage);

        return $this->successResponse(SocietyResource::collection($societies));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocietyRequest $request)
    {
        $society = Society::create($request->validated());

        return $this->successResource($society, 'Society created successfully', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Society $society): JsonResponse
    {
        $society->load(['address', 'members']);

        return $this->successResource($society, 'Society get successfully', Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocietyRequest $request, Society $society): JsonResponse
    {
        $society->update($request->validated());

         return $this->successResource($society, 'Society get successfully', Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Society $society): JsonResponse
    {
        $society->delete();

        return response()->json(null, 204);
    }
}
