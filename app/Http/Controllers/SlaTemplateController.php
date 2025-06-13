<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SlaTemplate;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\SlaTemplateCollection;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Requests\SlaTemplateRequest;

class SlaTemplateController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  SlaTemplate::paginate($limit);

        return new SlaTemplateCollection($data);
    }

    public function store(SlaTemplateRequest $request)
    {
        try {
            $data = $request->all();

            $data['code'] = $this->generateCode();

            $create = SlaTemplate::create($data);
           
            $data = new SlaTemplateResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(SlaTemplate $sla_template)
    {
        $data = new SlaTemplateResources($sla_template);

        return $this->success('Success', $data);
    }

    public function update(SlaTemplateRequest $request, SlaTemplate $sla_template)
    {
        try {
            $data = $request->all();

            $update = $sla_template->update($data);

            $data = new SlaTemplateResources($sla_template);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(SlaTemplate $sla_template)
    {
        $sla_template->delete();

        return $this->success('Success', null);
    }

    public function generateCode(){
        $sla_template = SlaTemplate::orderBy('code','desc')->first();

        if($sla_template){
            $code = $sla_template->code;

            $old_code = substr($code, -2);

            $next_number = str_pad($old_code + 1, 4, '0', STR_PAD_LEFT);
        }
        else{
            $next_number = '0001';
        }

        $new_code = 'ST'.$next_number;
        
        return $new_code;
    }
}
