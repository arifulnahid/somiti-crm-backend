<?php
namespace App\Services;

use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Support\Facades\DB;

class DepositService
{
    protected TransactionService $transactionService ;
    
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    /**
     * Create a deposit request
     */
    public function createDeposit(User $user, array $data): array
    {
        return DB::transaction(function () use ($user, $data) {
            // Create the transaction
            
            return [
                'transaction' => "transaction",
            ];
        });
    }
}