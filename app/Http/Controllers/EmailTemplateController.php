<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\EmailTemplateCollection;
use App\Http\Resources\EmailTemplateResources;
use App\Http\Requests\EmailTemplateRequest;
use App\Http\Services\EmailTemplateServices;

class EmailTemplateController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  EmailTemplate::filter()->search($request->search)->sortByField($request)->paginate($limit);

        return new EmailTemplateCollection($data);
    }

    public function store(EmailTemplateRequest $request)
    {
        $data = $request->all();

        $data = EmailTemplateServices::create($data);
           
        return $data; 
    }

    public function show(EmailTemplate $email_template)
    {
        $data = new EmailTemplateResources($email_template);

        return $this->success('Success', $data);
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $email_template)
    {
        $data = $request->all();

        $data = EmailTemplateServices::update($email_template,$data);

        return $data;
    }

    public function destroy(EmailTemplate $email_template)
    {
        $data = EmailTemplateServices::delete($email_template);

        return $data;
    }
}
