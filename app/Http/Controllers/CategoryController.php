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
        
        $data =  Category::filter()->search($request->search)->sortByField($request)->paginate($limit);
        
        return new CategoryCollection($data);
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        $data = CategoryServices::create($data);
        
        return $data;
    }

    public function show(Category $category)
    {
        $data = new CategoryResources($category);

        return $this->success('Success', $data);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->all();

        $data = CategoryServices::update($category,$data);

        return $data;
    }

    public function destroy(Category $category)
    {
        $data = CategoryServices::delete($category);

        return $data;
    }

}
