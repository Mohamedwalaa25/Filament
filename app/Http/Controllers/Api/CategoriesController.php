<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        if (!$categories <= 0){
            return $this->sendError('No data Yet');
        }

        return $this->sendResponse(CategoryResource::collection($categories), 'All Categories');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategory $request)
    {
        $validated = $request-> validated();


        $category = Category::create($validated);


        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::find($id);


        if (!$category){
            return $this->sendError('faild Category');
        }
        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategory $request, $id)
    {
        $validated = $request-> validated();
        $category = Category::find($id);

        if (!$category){
            return $this->sendError('faild Category');
        }

        $category->update($validated);

        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category){
            return $this->sendError('faild Category');
        }


        $category->delete();
        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }
}
