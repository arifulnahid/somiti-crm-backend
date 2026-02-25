<?php

namespace App\Http\Requests;

use App\Enums\BranchType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreBranchRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'address_id' => ['required', 'exists:addresses,id'],
            'type' => ['required', new Enum(BranchType::class)],
            'manager' => ['required', 'exists:users,id'],
            'cashier' => ['required', 'exists:users,id'],
            'parent_branch' => ['sometimes', 'nullable', 'exists:branch,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
