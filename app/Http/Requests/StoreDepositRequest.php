<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class StoreDepositRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !!$this->user();
    }

    #[Override]
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'sender_id' => $this->user()->id ?? null,
            'sender_type' => 'user',
            'receiver_id' => $this->user()->id ?? null,
            'receiver_type' => 'user'
        ]);
        return parent::prepareForValidation();
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
            'amount' => 'required|integer',
            'fees' => 'required|numeric|decimal:1,2',
            'sender_id' => 'sometimes',
            'sender_type' => 'sometimes|string',
            'receiver_id' => 'sometimes',
            'receiver_type' => 'sometimes|string|nullable',
            'notes' => 'required|string',
            'reference' => 'required|string',
            'meta' => 'nullable|json',
        ];
    }

}
