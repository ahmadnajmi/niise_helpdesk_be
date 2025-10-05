<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SlaTemplate;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\SlaTemplateCollection;
use App\Http\Resources\SlaTemplateResources;
use App\Http\Requests\SlaTemplateRequest;
use App\Http\Services\SlaTemplateServices;

class SlaTemplateController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  SlaTemplate::filter()->search($request->search)->sortByField($request->sort_by)->paginate($limit);

        return new SlaTemplateCollection($data);
    }

    public function store(SlaTemplateRequest $request)
    {
        $data = $request->all();

        $data =  SlaTemplateServices::create($data);

        return $data;
    }

    public function show(SlaTemplate $sla_template)
    {
        $data = new SlaTemplateResources($sla_template);

        return $this->success('Success', $data);
    }

    public function update(SlaTemplateRequest $request, SlaTemplate $sla_template)
    {
        $data = $request->all();

        $data = SlaTemplateServices::update($sla_template,$data);

        return $data;
    }

    public function destroy(SlaTemplate $sla_template)
    {
        SlaTemplateServices::delete($sla_template);

        return $this->success('Success', null);
    }

   
}
