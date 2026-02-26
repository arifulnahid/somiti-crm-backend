<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:20',
            'current_balance' => 'required|numeric|min:0',
            'type' => 'required|in:deposit,withdraw,send,receive',
            'status' => 'required|in:pending,success,failed',
            'success' => 'required|boolean',
            'sender_type' => 'required|string|in:user,branch',
            'sender_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $table = $this->sender_type === 'user' ? 'users' : 'branches';
                    if (! DB::table($table)->where('id', $value)->exists()) {
                        $fail('The selected sender is invalid.');
                    }
                },
            ],
            'receiver_type' => 'required|string|in:user,branch',
            'receiver_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $table = $this->sender_type === 'user' ? 'users' : 'branches';
                    if (! DB::table($table)->where('id', $value)->exists()) {
                        $fail('The selected receiver is invalid.');
                    }
                },
            ],
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ];
    }
}
