<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    /*
     * endPoint for get all categories
     * url : {{url}}/api/categories
     * * Method : GET
     * Resource contain id  name parent_id
     */
    public function index()
    {
        $categories = Category::all();

        if (!$categories <= 0) {
            return $this->sendError('No data Yet');
        }

        return $this->sendResponse(CategoryResource::collection($categories), 'All Categories');
    }


    /*
    * endPoint for Store Category
    * url :  {{url}}/api/categories
     * Method : POST
     * StoreCategory Request Validation :
     *    'name' => 'required|string|max:255',
          'parent_id' => 'nullable|exists:categories,id',
         * Resource contain id  name parent_id
    */
    public function store(StoreCategory $request)
    {
        $validated = $request->validated();


        $category = Category::create($validated);


        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }

    /**

     * endPoint for Get 1 item from Category
     * get item form Id
     * url : {{url}}/api/categories/{id}
     * Method : Get
     *      * Resource contain id  name parent_id

     */

    public function show($id)
    {
        $category = Category::find($id);


        if (!$category) {
            return $this->sendError('faild Category');
        }
        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }


    /**

     * endPoint for Update specified 1 item from Category
     * get item form Id
     * url : {{url}}/api/categories/{id}
     * Method : Put
     *      * Resource contain id  name parent_id

     */

    public function update(StoreCategory $request, $id)
    {
        $validated = $request->validated();
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('faild Category');
        }

        $category->update($validated);

        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }


    /**

     * endPoint for Delete specified 1 item from Category
     * get item form Id
     * url : {{url}}/api/categories/{id}
     * Method : Delete
     *      * Resource contain id  name parent_id

     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->sendError('faild Category');
        }


        $category->delete();
        return $this->sendResponse(new CategoryResource($category), 'All Categories');

    }
}
