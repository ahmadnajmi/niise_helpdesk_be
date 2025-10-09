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
            'incident_date' => 'nullable',
            'branch_id' => 'required',
            'category_id' => 'sometimes',
            'complaint_user_id' => 'nullable',
            'information' => 'required',
            'knowledge_base_id'   => 'nullable',
            'received_via'   => 'sometimes',
            'report_no'   => 'nullable',
            'incident_asset_type'   => 'nullable',
            'date_asset_loss'   => 'nullable',
            'date_report_police'   => 'nullable',
            'report_police_no'   => 'nullable',
            'asset_siri_no'   => 'nullable',
            'asset_parent_id'   => 'nullable',
            'asset_component_id'   => 'nullable',
            'group_id'   => 'sometimes',
            'operation_user_id'   => 'sometimes',
            'appendix_file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf,doc,docx|max:2048',
            'asset_file'   => 'nullable|file|mimes:jpg,jpeg,png,mp4,pdf,doc,docx|max:2048',
            'name' => 'required_without:complaint_user_id',
            'email' => 'required_without:complaint_user_id',
            'phone_no' => 'required_without:complaint_user_id',
            'office_phone_no'=> 'nullable',
            'service_recipient_id' => 'nullable',
            'extension_no' => 'nullable',
            'address'=> 'nullable',
            'postcode'=> 'nullable',
            'state_id'=> 'nullable',
            'barcode' => 'nullable',
            'category' => 'nullable',
        ];
    }   

    protected function failedValidation(Validator $validator)
    {
        $response = $this->error($validator->errors(),[],422);
      
        throw new HttpResponseException($response);
    }
}
