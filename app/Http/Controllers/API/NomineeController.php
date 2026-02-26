<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNomineeRequest;
use App\Http\Requests\UpdateNomineeRequest;
use App\Models\Nominee;
use Illuminate\Http\JsonResponse;

class NomineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $nominees = Nominee::with(['user', 'member'])->get();

        return response()->json($nominees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNomineeRequest $request): JsonResponse
    {
        $nominee = Nominee::create($request->validated());

        return response()->json($nominee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Nominee $nominee): JsonResponse
    {
        $nominee->load(['user', 'member']);

        return response()->json($nominee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNomineeRequest $request, Nominee $nominee): JsonResponse
    {
        $nominee->update($request->validated());

        return response()->json($nominee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nominee $nominee): JsonResponse
    {
        $nominee->delete();

        return response()->json(null, 204);
    }
}
