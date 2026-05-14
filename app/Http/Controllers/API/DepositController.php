<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use App\Models\Deposit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Deposit::with(['user', 'nominees']);

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by duration
        if ($request->has('duration')) {
            $query->where('duration', $request->duration);
        }

        // Filter by deadline status
        if ($request->has('status')) {
            if ($request->status === 'upcoming') {
                $query->upcomingDeadline();
            } elseif ($request->status === 'expired') {
                $query->expired();
            }
        }

        $deposits = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $deposits,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepositRequest $request): JsonResponse
    {
        $deposit = Deposit::create($request->validated());
        
        // Attach nominees if provided
        if ($request->has('nominee_ids')) {
            $deposit->nominees()->sync($request->nominee_ids);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Deposit created successfully',
            'data' => $deposit->load('nominees')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $deposit->load(['user', 'nominees'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepositRequest $request, Deposit $deposit): JsonResponse
    {
        $deposit->update($request->validated());
        
        if ($request->has('nominee_ids')) {
            $deposit->nominees()->sync($request->nominee_ids);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Deposit updated successfully',
            'data' => $deposit->load('nominees')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit): JsonResponse
    {
        $deposit->delete($deposit);
        
        return response()->json([
            'success' => true,
            'message' => 'Deposit deleted successfully'
        ]);
    }

    public function upcomingDeadlines(): JsonResponse
    {
        $deposits = Deposit::upcomingDeadline()
            ->with('user')
            ->limit(10)
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $deposits
        ]);
    }
}
