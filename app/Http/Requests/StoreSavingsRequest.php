<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSavingsRequest extends FormRequest
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
            'installment' => 'required|integer|min:1',
            'duration' => ['required', Rule::in(['1Y', '2Y', '3Y', '4Y', '5Y', '10Y'])],
            'deadline' => 'required|date|after:today',
            'meta' => 'nullable|array',
            'nominee_ids' => 'nullable|array',
            'nominee_ids.*' => 'exists:nominees,id'
        ];
    }

    public function messages(): array
    {
        return [
            'deadline.after' => 'Deadline must be a future date',
            'duration.in' => 'Invalid duration. Allowed: 1Y, 2Y, 3Y, 4Y, 5Y, 10Y'
        ];
    }
}