<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|integer|min:1000|max:10000000',
            'installment' => 'required|integer|min:100',
            'duration' => ['required', Rule::in(['1M', '6M', '1Y', '2Y', '3Y', '5Y', '10Y'])],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Minimum loan amount is 1000',
            'amount.max' => 'Maximum loan amount is 10,000,000',
            'duration.in' => 'Invalid duration. Allowed: 1M, 6M, 1Y, 2Y, 3Y, 5Y, 10Y',
        ];
    }
}
