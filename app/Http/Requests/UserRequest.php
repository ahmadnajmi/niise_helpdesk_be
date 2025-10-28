<?php

namespace App\Http\Requests;

use App\Http\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'nickname' => 'nullable',
            'ic_no' => 'string',
            'email' => 'required',
            'phone_no' => 'required',
            'category_office' => 'nullable',
            'position' => 'nullable',
            'branch_id' =>'nullable',
            'is_active' => 'nullable',
            'address' => 'nullable',
            'postcode' => 'nullable',
            'city' => 'nullable',
            'state_id' => 'nullable',
            'fax_no' => 'nullable',
            'company_id' => 'nullable',
            'group_user' => 'array|nullable',
            'group_user_access' => 'array|nullable',
            'role' => 'required',
        ];
        if ($this->routeIs('user.store')) {
            $rules['ic_no'] .= '|required'; 
        }
        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}
