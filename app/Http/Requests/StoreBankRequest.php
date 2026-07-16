<?php

namespace App\Http\Requests;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBankRequest extends FormRequest
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
            'owner_id' => [
                'required',
                'exists:users,id',
                'integer',
            ],
            'manager_id' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (! empty($value) && ! is_array($value)) {
                        $fail('The manager list must be an array.');
                    }
                    if (! empty($value)) {
                        foreach ($value as $managerId) {
                            if (! User::find($managerId)) {
                                $fail('Manager ID '.$managerId.' does not exist.');
                            }
                        }
                    }
                },
            ],
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'bank_branch' => [
                'nullable',
                'string',
                'max:255',
            ],
            'bank_type' => [
                'required',
                'string',
                Rule::in(Bank::getTypes()),
            ],
            'account_number' => [
                'required',
                'string',
                'max:100',
                'unique:banks,account_number',
            ],
            'balance' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'visible_to' => [
                'required',
                'string',
                Rule::in(Bank::getVisibilityOptions()),
            ],
            'meta' => [
                'nullable',
                'array',
            ],
            'meta.*' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'owner_id.required' => 'The bank owner is required.',
            'owner_id.exists' => 'The selected owner does not exist.',
            'bank_name.required' => 'The bank name is required.',
            'bank_type.required' => 'The bank type is required.',
            'bank_type.in' => 'The bank type must be MFS, BANK, or AGENT_BANK.',
            'account_number.required' => 'The account number is required.',
            'account_number.unique' => 'This account number is already registered.',
            'visible_to.required' => 'The visibility setting is required.',
            'visible_to.in' => 'The visibility setting is invalid.',
            'balance.min' => 'The balance cannot be negative.',
            'balance.regex' => 'The balance must have at most 2 decimal places.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->is_active ?? true,
            'manager_id' => $this->manager_id ?? [],
            'meta' => $this->meta ?? [],
            'balance' => $this->balance ?? 0,
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'owner_id' => 'bank owner',
            'manager_id' => 'bank managers',
            'bank_name' => 'bank name',
            'bank_branch' => 'bank branch',
            'bank_type' => 'bank type',
            'account_number' => 'account number',
            'balance' => 'balance',
            'is_active' => 'active status',
            'visible_to' => 'visibility',
            'meta' => 'metadata',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check if manager_id contains owner_id
            if (! empty($this->manager_id) && in_array($this->owner_id, $this->manager_id)) {
                $validator->errors()->add(
                    'manager_id',
                    'The owner cannot be added as a manager.'
                );
            }
        });
    }

    /**
     * Get validated data with defaults
     */
    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();

        // Ensure defaults
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['meta'] = $validated['meta'] ?? [];
        $validated['manager_id'] = $validated['manager_id'] ?? [];

        return $validated;
    }
}
