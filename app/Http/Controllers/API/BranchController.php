<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $branch = Branch::all();

        return response()->json([
            'status' => true,
            'message' => 'Branch',
            'data' => $branch,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBranchRequest $request): JsonResponse
    {
         $data = $request->validated();

        $branch = Branch::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Branch Create Successfully',
            'data' => $branch,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        return response()->json([
            'status' => true,
            'message' => 'Branch',
            'data' => $branch,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $validated = $request->validated();

        $branch->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Branch Updated',
            'data' => $branch,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
