<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreMemberRequest extends FormRequest
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
            'uuid' => 'required|string|unique:members,uuid',
            'user_id' => 'nullable|exists:users,id',
            'fathers_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'permanent_address' => 'nullable|exists:addresses,id',
            'present_address' => 'nullable|exists:addresses,id',
            'branch_id' => 'nullable|exists:branches,id',
            'nominees' => 'nullable|array',
            'society_id' => 'required|exists:societies,id',
            'occupation' => 'nullable|string|max:255',
            'type' => 'required|in:general,student',
            'meta' => 'nullable|array',
        ];
    }

    protected function prepareForValidation()
    {
        // Generates a UUID and adds it to the request data
        $this->merge([
            'uuid' => (string) Str::uuid(),
        ]);
    }
}
