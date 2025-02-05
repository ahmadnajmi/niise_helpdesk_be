<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Traits\ResponseTrait;

class ModuleRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'nullable',
            'is_active' =>'nullable',
            'module_id' => 'nullable',
            'sub_module.*.name' => 'required|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}
