<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'division' => ['required', 'string'],
            'district' => ['required', 'string'],
            'upazila' => ['required', 'string'],
            'thana' => ['required', 'string'],
            'union' => ['required', 'string'],
            'village' => ['required', 'string'],
            'ward' => ['required', 'string'],
            'ward_no' => ['required', 'numeric'],
            'post_office' => ['required', 'string'],
            'postal_code' => ['required', 'numeric'],
        ];
    }
}
