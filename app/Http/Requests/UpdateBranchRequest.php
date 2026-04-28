<?php

namespace App\Http\Requests;

use App\Enums\BranchType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateBranchRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'unique:branches,name'],
            'address_id' => ['sometimes', 'exists:addresses,id'],
            'type' => ['sometimes', new Enum(BranchType::class)],
            'manager' => ['sometimes', 'exists:users,id'],
            'cashier' => ['sometimes', 'exists:users,id'],
            'parent_branch' => ['sometimes', 'nullable', 'exists:branch,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
