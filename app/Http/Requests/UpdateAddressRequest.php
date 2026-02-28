<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'division' => ['sometimes', 'string'],
            'district' => ['sometimes', 'string'],
            'upazila' => ['sometimes', 'string'],
            'thana' => ['sometimes', 'string'],
            'union' => ['sometimes', 'string'],
            'village' => ['sometimes', 'string'],
            'ward' => ['sometimes', 'string'],
            'ward_no' => ['sometimes', 'numeric'],
            'post_office' => ['sometimes', 'string'],
            'postal_code' => ['sometimes', 'numeric'],
        ];
    }
}
