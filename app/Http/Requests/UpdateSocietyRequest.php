<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class UpdateSocietyRequest extends FormRequest
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
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('societies', 'name'),
            ],
            'logo' => 'sometimes|nullable|string|max:255',
            'cover_image' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|required|string',
            'address' => 'sometimes|required|exists:addresses,id',
            'committee' => 'sometimes|nullable|array',
            'established_at' => 'sometimes|date',
            'meta' => 'sometimes|nullable|array',
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
