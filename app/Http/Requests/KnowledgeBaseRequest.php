<?php

namespace App\Http\Requests;

use App\Http\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class KnowledgeBaseRequest extends FormRequest
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
            'category_id' => 'required',
            'explanation' => 'required',
            'keywords' => 'required',
            'solution' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}