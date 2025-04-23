<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\EmailTemplateCollection;
use App\Http\Resources\EmailTemplateResources;
use App\Http\Requests\EmailTemplateRequest;

class EmailTemplateController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  EmailTemplate::paginate($limit);

        return new EmailTemplateCollection($data);
    }

    public function store(EmailTemplateRequest $request)
    {
        try {
            $data = $request->all();

            $create = EmailTemplate::create($data);
           
            $data = new EmailTemplateResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(EmailTemplate $email_template)
    {
        $data = new EmailTemplateResources($email_template);

        return $this->success('Success', $data);
    }

    public function update(EmailTemplateRequest $request, EmailTemplate $email_template)
    {
        try {
            $data = $request->all();

            $update = $email_template->update($data);

            $data = new EmailTemplateResources($email_template);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(EmailTemplate $email_template)
    {
        $email_template->delete();

        return $this->success('Success', null);
    }
}
