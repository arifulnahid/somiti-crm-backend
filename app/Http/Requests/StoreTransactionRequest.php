<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'sender_id' => 'nullable|exists:users,id',
            'receiver_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'meta' => 'nullable|array',
        ];
    }
}
