<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Loan::class, 'loan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Loan::with('user');
        
        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by amount range
        if ($request->has('min_amount')) {
            $query->where('amount', '>=', $request->min_amount);
        }
        
        if ($request->has('max_amount')) {
            $query->where('amount', '<=', $request->max_amount);
        }
        
        // Filter by duration
        if ($request->has('duration')) {
            $query->where('duration', $request->duration);
        }
        
        // High value loans
        if ($request->has('high_value')) {
            $query->highValue($request->high_value_threshold ?? 50000);
        }
        
        $loans = $query->latest()->paginate($request->per_page ?? 15);
        
        return response()->json([
            'success' => true,
            'data' => $loans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request): JsonResponse
    {
        $loan = Loan::create($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Loan created successfully',
            'data' => $loan->load('user')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $loan->load('user')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanRequest $request, Loan $loan): JsonResponse
    {
        $loan->update($request->validated());
        
        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully',
            'data' => $loan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan): JsonResponse
    {
        $loan->delete($loan);
        
        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully'
        ]);
    }


     public function calculateSummary(Loan $loan): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'loan_id' => $loan->id,
                'total_payable' => $loan->total_payable,
                'duration_months' => $loan->duration_in_months,
                'monthly_installment' => $loan->installment,
                'total_interest' => $loan->total_payable - $loan->amount
            ]
        ]);
    }
}
