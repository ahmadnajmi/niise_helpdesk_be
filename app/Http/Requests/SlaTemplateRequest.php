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
            'service_level' => 'required',
            'timeframe_channeling' => 'nullable',
            'timeframe_channeling_type' => 'nullable',
            'timeframe_incident' => 'nullable',
            'timeframe_incident_type' => 'nullable',
            'response_time_reply' => 'nullable',
            'response_time_reply_type' => 'nullable',
            'response_time_reply_penalty' => 'nullable',
            'timeframe_solution' => 'nullable',
            'timeframe_solution_type' => 'nullable',
            'timeframe_solution_penalty' => 'nullable',
            'response_time_location' => 'nullable',
            'response_time_location_type' => 'nullable',
            'response_time_location_penalty' => 'nullable',
            'notes' => 'nullable'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}
