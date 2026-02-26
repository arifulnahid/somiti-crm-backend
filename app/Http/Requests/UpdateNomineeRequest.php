<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNomineeRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'member_id' => 'sometimes|required|exists:members,id',
            'name' => 'sometimes|required|string|max:255',
            'fathers_name' => 'sometimes|required|string|max:255',
            'mothers_name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|in:male,female,none',
            'dob' => 'sometimes|required|date|before:today',
            'nid' => 'nullable|string|max:50',
            'birth_id' => 'nullable|string|max:50',
            'passport_id' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'relationship' => 'sometimes|required|in:mother,father,brother,sister,wife',
            'meta' => 'nullable|array',
        ];
    }
}
