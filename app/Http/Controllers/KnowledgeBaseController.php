<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\KnowledgeBase;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\KnowledgeBaseCollection;
use App\Http\Resources\KnowledgeBaseResources;
use App\Http\Requests\KnowledgeBaseRequest;

class KnowledgeBaseController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  KnowledgeBase::search($request->search)->paginate($limit);

        return new KnowledgeBaseCollection($data);
    }

    public function store(KnowledgeBaseRequest $request)
    {
        try {
            $data = $request->all();

            $create = KnowledgeBase::create($data);
           
            $data = new KnowledgeBaseResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(KnowledgeBase $knowledge_base)
    {
        $data = new KnowledgeBaseResources($knowledge_base);

        return $this->success('Success', $data);
    }

    public function update(KnowledgeBaseRequest $request, KnowledgeBase $knowledge_base)
    {
        try {
            $data = $request->all();

            $update = $knowledge_base->update($data);

            $data = new KnowledgeBaseResources($knowledge_base);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(KnowledgeBase $knowledge_base)
    {
        $knowledge_base->delete();

        return $this->success('Success', null);
    }
}
