<?php

namespace App\Services;

use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Handle a transfer between two entities.
     */
    public function executeTransfer($sender, $receiver, float $amount, string $note = ''): Transaction
    {
        return DB::transaction(function () use ($sender, $receiver, $amount, $note) {
            // 1. Logic: Check if sender has enough balance
            if ($sender->balance < $amount) {
                throw new Exception('Insufficient funds.');
            }

            // 2. Logic: Deduct and Add (The "Accounting" part)
            $sender->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            // 3. Create the Transaction Record (Polymorphic)
            return Transaction::create([
                'id' => \Illuminate\Support\Str::ulid(),
                'sender_id' => $sender->id,
                'sender_type' => get_class($sender),
                'receiver_id' => $receiver->id,
                'receiver_type' => get_class($receiver),
                'amount' => $amount,
                'status' => 'completed',
                'description' => $note,
            ]);
        });
    }

    /**
     * Handle a deposit between two entities.
     */
    public function deposit($sender, $receiver, float $amount, string $note = ''): Transaction
    {
        return DB::transaction(function () use ($sender, $receiver, $amount, $note) {
            // 1. Logic: Check if sender has enough balance
            if ($sender->balance < $amount) {
                throw new Exception('Insufficient funds.');
            }

            // 2. Logic: Deduct and Add (The "Accounting" part)
            $sender->decrement('balance', $amount);
            $receiver->increment('balance', $amount);

            // 3. Create the Transaction Record (Polymorphic)
            return Transaction::create([
                'id' => \Illuminate\Support\Str::ulid(),
                'sender_id' => $sender->id,
                'sender_type' => get_class($sender),
                'receiver_id' => $receiver->id,
                'receiver_type' => get_class($receiver),
                'amount' => $amount,
                'status' => 'completed',
                'description' => $note,
            ]);
        });
    }
}
