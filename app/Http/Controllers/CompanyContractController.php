<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\CompanyContract;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\CompanyContractCollection;
use App\Http\Resources\CompanyContractResources;
use App\Http\Requests\CompanyContractRequest;

class CompanyContractController extends Controller
{
    //testing
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  CompanyContract::paginate($limit);

        return new CompanyContractCollection($data);
    }

    public function store(CompanyContractRequest $request)
    {
        try {
            $data = $request->all();

            $create = CompanyContract::create($data);
           
            $data = new CompanyContractResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(CompanyContract $company_contract)
    {
        $data = new CompanyContractResources($company_contract);

        return $this->success('Success', $data);
    }

    public function update(CompanyContractRequest $request, CompanyContract $company_contract)
    {
        try {
            $data = $request->all();

            $update = $company_contract->update($data);

            $data = new CompanyContractResources($company_contract);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(CompanyContract $company_contract)
    {
        $company_contract->delete();

        return $this->success('Success', null);
    }
}
