<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\ModuleCollection;
use App\Http\Requests\ModuleRequest;
use App\Models\Module;

class ModuleController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data =  ModuleCollection::collection(Module::paginate(15));

        return $this->success('Success', $data);
    }

    public function store(ModuleRequest $request)
    {
        try {
            $data = $request->all();

            $create = Module::create($data);

            $create_submodule = $this->crateSubModule($data,$create);
           
            $data = new ModuleCollection($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Module $module)
    {
        $data = new ModuleCollection($module);

        return $this->success('Success', $data);
    }

    public function update(ModuleRequest $request, Module $module)
    {
        try {
            $data = $request->all();

            $update = $module->update($data);

            $create_submodule = $this->crateSubModule($data,$module);

            $data = new ModuleCollection($module);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Module $module)
    {
        $module->delete();

        $sub_module = Module::where('module_id',$module->id);

        Module::whereIn('module_id',$sub_module->pluck('id'))->delete();

        $sub_module->delete();

        return $this->success('Success', null);
    }

    public function crateSubModule($data,$create)
    {
        if(isset($data['sub_module'])){

            foreach($data['sub_module'] as $idx => $sub_module){

                $sub_module['module_id'] = $create->id;

                $create_sub_module = Module::create($sub_module);
            }
        }
        return true;
    }
}
