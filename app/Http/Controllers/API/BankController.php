<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $query = Bank::with(['owner']);
        
        // Filter by bank type
        if ($request->has('bank_type')) {
            $query->ofType($request->bank_type);
        }
        
        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }
        
        // Filter by visibility based on user role
        $user = auth()->user();
        if ($user && !$user->hasRole('admin')) {
            $query->visibleTo($user->role);
        }
        
        return response()->json([
            'data' => $query->paginate(15)
        ]);
    }

    public function store(StoreBankRequest $request)
    {
        $data = $request->validatedWithDefaults();
        $bank = Bank::create($data);
        
        return response()->json([
            'message' => 'Bank created successfully',
            'data' => $bank->load(['owner'])
        ], 201);
    }

    public function show(Bank $bank)
    {
        // Check visibility
        $user = auth()->user();
        if (!$user->hasRole('admin') && !$bank->isVisibleTo($user->role)) {
            return response()->json([
                'message' => 'You do not have permission to view this bank'
            ], 403);
        }
        
        return response()->json([
            'data' => $bank->load(['owner'])
        ]);
    }

    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $data = $request->getUpdateData();
        $bank->update($data);
        
        return response()->json([
            'message' => 'Bank updated successfully',
            'data' => $bank->fresh()->load(['owner'])
        ]);
    }

    public function destroy(Bank $bank)
    {
        // Check if bank has transactions
        if ($bank->transactions()->exists()) {
            return response()->json([
                'message' => 'Cannot delete bank with existing transactions'
            ], 400);
        }
        
        $bank->delete();
        
        return response()->json([
            'message' => 'Bank deleted successfully'
        ]);
    }

    public function addManager(Request $request, Bank $bank)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        if ($bank->addManager($request->user_id)) {
            return response()->json([
                'message' => 'Manager added successfully',
                'data' => $bank->fresh()
            ]);
        }
        
        return response()->json([
            'message' => 'User is already a manager'
        ], 400);
    }

    public function removeManager(Request $request, Bank $bank)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        if ($bank->removeManager($request->user_id)) {
            return response()->json([
                'message' => 'Manager removed successfully',
                'data' => $bank->fresh()
            ]);
        }
        
        return response()->json([
            'message' => 'User is not a manager'
        ], 400);
    }
}
