<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\CompanyCollection;
use App\Http\Resources\CompanyResources;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Company::filter()->search($request->search)->sortByField($request)->paginate($limit);

        return new CompanyCollection($data);
    }

    public function store(CompanyRequest $request)
    {
        try {
            $data = $request->all();

            $create = Company::create($data);
           
            $data = new CompanyResources($create);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Company $company)
    {
        $data = new CompanyResources($company);

        return $this->success('Success', $data);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        try {
            $data = $request->all();

            $update = $company->update($data);

            $data = new CompanyResources($company);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return $this->success('Success', null);
    }
}
