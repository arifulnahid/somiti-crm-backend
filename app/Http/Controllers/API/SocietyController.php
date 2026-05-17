<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSocietyRequest;
use App\Http\Requests\UpdateSocietyRequest;
use App\Http\Resources\SocietyResource;
use App\Models\Society;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SocietyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Log::info($request->query());
        $societies = Society::with(['address', 'members'])->simplePaginate(10, ['*'], 'page', 2);

        return $this->successResponse(SocietyResource::collection($societies));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocietyRequest $request)
    {
        $society = Society::create($request->validated());

        return response()->json($society, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Society $society): JsonResponse
    {
        $society->load(['address', 'members']);

        return response()->json($society);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocietyRequest $request, Society $society): JsonResponse
    {
        $society->update($request->validated());

        return response()->json($society);
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
