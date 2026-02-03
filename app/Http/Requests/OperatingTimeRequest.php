<?php

namespace App\Http\Requests;

use App\Http\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OperatingTimeRequest extends FormRequest
{
    use ResponseTrait;
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
            'day_start' => 'required',
            'day_end' => 'required',
            'branch_id' => 'required',
            'duration' => 'required',
            'operation_start' => 'required',
            'operation_end' => 'required',
            'is_active' => 'nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors()->all(),[],422);
      
        throw new HttpResponseException($response);
    }
}
