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

            $create = CategoryServices::create($data);
           
            $data = new CategoryResources($create);

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

            // $update = $category->update($data);

            $update = CategoryServices::update($category,$data);


            $data = new CategoryResources($category);

            return $this->success('Success', $data);
          
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->success('Success', null);
    }

}
