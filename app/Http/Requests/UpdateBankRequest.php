<?php

namespace App\Http\Requests;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBankRequest extends FormRequest
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
        $bankId = $this->route('bank')->id ?? null;

        return [
            'owner_id' => [
                'sometimes',
                'exists:users,id',
                'integer',
            ],
            'manager_id' => [
                'sometimes',
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
                'sometimes',
                'string',
                'max:255',
            ],
            'bank_branch' => [
                'nullable',
                'string',
                'max:255',
            ],
            'bank_type' => [
                'sometimes',
                'string',
                Rule::in(Bank::getTypes()),
            ],
            'account_number' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('banks', 'account_number')->ignore($bankId),
            ],
            'balance' => [
                'sometimes',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'visible_to' => [
                'sometimes',
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
            'owner_id.exists' => 'The selected owner does not exist.',
            'bank_type.in' => 'The bank type must be MFS, BANK, or AGENT_BANK.',
            'account_number.unique' => 'This account number is already registered.',
            'balance.min' => 'The balance cannot be negative.',
            'balance.regex' => 'The balance must have at most 2 decimal places.',
            'visible_to.in' => 'The visibility setting is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Remove null values
        $data = array_filter($this->all(), function ($value) {
            return $value !== null;
        });

        $this->replace($data);
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
            if ($this->has('manager_id') && ! empty($this->manager_id)) {
                $ownerId = $this->owner_id ?? $this->route('bank')->owner_id;
                if (in_array($ownerId, $this->manager_id)) {
                    $validator->errors()->add(
                        'manager_id',
                        'The owner cannot be added as a manager.'
                    );
                }
            }

            // Prevent editing if bank has transactions
            if ($this->has('balance') || $this->has('account_number')) {
                $bank = $this->route('bank');
                if ($bank && $bank->transactions()->exists()) {
                    $validator->errors()->add(
                        'balance',
                        'Cannot modify balance or account number when transactions exist.'
                    );
                }
            }
        });
    }

    /**
     * Get validated data with defaults
     */
    public function validatedWithDefaults(): array
    {
        $validated = $this->validated();

        // Ensure defaults for nullable fields
        if ($this->has('is_active')) {
            $validated['is_active'] = $validated['is_active'] ?? true;
        }

        if ($this->has('meta')) {
            $validated['meta'] = $validated['meta'] ?? [];
        }

        if ($this->has('manager_id')) {
            $validated['manager_id'] = $validated['manager_id'] ?? [];
        }

        return $validated;
    }

    /**
     * Get only the fields that should be updated
     */
    public function getUpdateData(): array
    {
        $data = $this->validatedWithDefaults();

        // Only return fields that are present in the request
        return array_filter($data, function ($value, $key) {
            return $this->has($key);
        }, ARRAY_FILTER_USE_BOTH);
    }
}
