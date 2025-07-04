<?php

namespace App\Http\Requests;

use App\Http\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IncidentRequest extends FormRequest
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
            'incident_date' => 'required',
            'branch_id' => 'required',
            'category_id' => 'required',
            'complaint_id' => 'nullable',
            'information' => 'required',
            'knowledge_base_id'   => 'nullable',
            'received_via'   => 'required',
            'report_no'   => 'nullable',
            'incident_asset_type'   => 'nullable',
            'date_asset_loss'   => 'nullable',
            'date_report_police'   => 'nullable',
            'report_police_no'   => 'nullable',
            'asset_siri_no'   => 'nullable',
            'group_id'   => 'required',
            'operation_user_id'   => 'required',
            'appendix_file'   => 'nullable',
            'name' => 'required_without:complaint_id',
            'email' => 'required_without:complaint_id',
            'phone_no' => 'required_without:complaint_id',
            'office_phone_no'=> 'nullable',
            'extension_no'=> 'nullable',
        ];
    }   

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}
