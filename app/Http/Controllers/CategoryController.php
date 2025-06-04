<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\CategoryCollection;
use App\Http\Resources\CategoryResources;
use App\Http\Requests\CategoryRequest;
use App\Http\Services\CategoryServices;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        
        $data =  Category::paginate($limit);

        return new CategoryCollection($data);
    }

    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->all();

            $data = CategoryServices::create($data);
           
            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Category $category)
    {
        $data = new CategoryResources($category);

        return $this->success('Success', $data);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $data = $request->all();

            $data = CategoryServices::update($category,$data);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        CategoryServices::delete($category);

        return $this->success('Success', null);
    }

}
