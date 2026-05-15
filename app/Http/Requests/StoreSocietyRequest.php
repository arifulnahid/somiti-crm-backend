<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreSocietyRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:societies,name',
            'logo' => 'sometimes|nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'cover_image' => 'sometimes|nullable|string|max:255',
            'description' => 'required|string',
            'address' => 'required|exists:addresses,id',
            'committee' => 'nullable|array',
            'established_at' => 'date',
            'meta' => 'nullable|array',
        ];
    }

    #[Override]
    protected function prepareForValidation()
    {
        if($this->has('established_at') && $this->input('established_at')){
           try {
            $formattedDate = Carbon::parse($this->input('established_at'))->toDateString();

            $this->merge([
                'established_at' => $formattedDate
            ]);
           } catch (\Throwable $th) {
            //throw $th;
           }
        }

        return parent::prepareForValidation();
    }
}
