<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

class StoreUserRequest extends FormRequest
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
            'name' => 'string|min:5|max:30|required',
            'email' => 'email:dns|unique:users,email|required',
            'phone' => 'digits:11|unique:users,phone|required',
            'dob' => 'required|date',
            'nid' => 'required_without_all:passport_id,birth_id|nullable|digits_between:10,16|unique:users,nid',
            'birth_id' => 'required_without_all:passport_id,nid|nullable|digits_between:10,16|unique:users,birth_id',
            'passport_id' => 'required_without_all:birth_id,nid|nullable|digits_between:10,16|unique:users,passport_id',
            'active' => 'sometimes|boolean:strict',
            'role' => ['sometimes', new Enum(UserRole::class)],
            'password' => 'sometimes|digits:5'
        ];
    }

    public function prepareForValidation(){
        if(!$this->filled('password')){
            $this->merge([
                'password' => '12345'
            ]);
        }
    }

    public function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->password)
        ]);
    }
}
