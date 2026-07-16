<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNomineeRequest;
use App\Http\Requests\UpdateNomineeRequest;
use App\Http\Resources\NomineeResource;
use App\Models\Nominee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NomineeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $memberId = $request->query('member');
        $nominees = Nominee::where('member_id', $memberId)->get();

        return NomineeResource::collection($nominees)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNomineeRequest $request): JsonResponse
    {
        $nominee = Nominee::create($request->validated());

        return NomineeResource::make($nominee)->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Nominee $nominee): JsonResponse
    {
        $nominee->load(['user', 'member']);

        return NomineeResource::make($nominee)->response();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNomineeRequest $request, Nominee $nominee): JsonResponse
    {
        $nominee->update($request->validated());

        return NomineeResource::make($nominee)->response();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nominee $nominee): JsonResponse
    {
        $nominee->delete();

        return NomineeResource::make($nominee)->response()->setStatusCode(204);
    }
}
