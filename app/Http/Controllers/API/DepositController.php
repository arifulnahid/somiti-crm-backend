<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Models\Transaction;
use App\Services\DepositService;

class DepositController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreDepositRequest $request, DepositService $service)
    {
        $validate = $request->validated();

        return response()->json($validate);
    }
}
