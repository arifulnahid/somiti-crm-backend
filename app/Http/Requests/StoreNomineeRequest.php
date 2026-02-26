<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNomineeRequest extends FormRequest
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
            'member_id' => 'required|exists:members,id',
            'name' => 'required|string|max:255',
            'fathers_name' => 'required|string|max:255',
            'mothers_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,none',
            'dob' => 'required|date|before:today',
            'nid' => 'nullable|string|max:50',
            'birth_id' => 'nullable|string|max:50',
            'passport_id' => 'nullable|string|max:50',
            'present_address' => 'nullable|string',
            'relationship' => 'required|in:mother,father,brother,sister,wife',
            'meta' => 'nullable|array',
        ];
    }
}
