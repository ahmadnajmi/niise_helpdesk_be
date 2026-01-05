<?php

namespace App\Http\Requests;

use App\Http\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SlaTemplateRequest extends FormRequest
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
            'severity_id' => 'required',
            'service_level' => 'nullable',
            'response_time' => 'nullable',
            'response_time_type' => 'nullable',
            'response_time_penalty' => 'nullable',
            'resolution_time' => 'nullable',
            'resolution_time_type' => 'nullable',
            'resolution_time_penalty' => 'nullable',
            'response_time_location' => 'nullable',
            'response_time_location_type' => 'nullable',
            'response_time_location_penalty' => 'nullable',
            'temporary_resolution_time'=> 'nullable',
            'temporary_resolution_time_type'=> 'nullable',
            'temporary_resolution_time_penalty'=> 'nullable',
            'verify_resolution_time'=> 'nullable',
            'verify_resolution_time_type'=> 'nullable',
            'verify_resolution_time_penalty'=> 'nullable',
            'verify_resolution_time_penalty_type'=> 'nullable',
            'notes' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}
