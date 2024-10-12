<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIfromationRequest;
use App\Http\Resources\InformationResource;
use App\Models\Category;
use App\Models\Information;
use Illuminate\Http\Request;

class InftorationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /*
    * endPoint for get all Informations
    * url : {{url}}/api/inforations
    * * Method : GET
     * Resource idt title phone address social_media user_id
    */
    public function index()
    {
        $informations = Information::all();

        if (!$informations <= 0){
            return $this->sendError('No data Yet');
        }
        return $this->sendResponse(InformationResource::collection($informations), 'All Informations');
    }

    /**
     * Store a newly created resource in storage.
     */

    /*
    * endPoint for Store Informations
    * url : {{url}}/api/inforations
     * Method : POST
     *      * Resource idt title phone address social_media user_id

     * StoreCategory Request Validation :
     *    'name' => 'required|string|max:255',
          'parent_id' => 'nullable|exists:categories,id',
    */
    public function store(StoreIfromationRequest $request)
    {
        $validated = $request-> validated();

        $information = Information::create($validated);

        return $this->sendResponse(new InformationResource($information), 'All Informations');

    }



    /**
     * endPoint for Get 1 item from Informations
     * get item form Id
     * url : {{url}}/api/inforations/{id}
     * Method : Get
     *      * Resource idt title phone address social_media user_id


     */

    public function show($id)
    {
        $information = Information::find($id);


        if (!$information){
            return $this->sendError('faild Category');
        }
        return $this->sendResponse(new InformationResource($information), 'All Informations');

    }

    /**

     * endPoint for Update specified 1 item from Informations
     * get item form Id
     * url : {{url}}/api/inforations/{id}
     * Method : Put
     *      * Resource idt title phone address social_media user_id


     */

    public function update(Request $request, $id)
    {

        $information = Information::find($id);

        if (!$information){
            return $this->sendError('faild Category');
        }
        $information->update(request()->all());

        return $this->sendResponse(new InformationResource($information), 'All Informations');

    }

    /**

     * endPoint for Delete specified 1 item from Informations
     * get item form Id
     * url : {{url}}/api/inforations/{id}
     * Method : Delete
     *      * Resource idt title phone address social_media user_id


     */
    public function destroy($id)
    {
        $information = Information::find($id);

        if (!$information){
            return $this->sendError('faild Category');
        }

        $information->delete();
        return $this->sendResponse(new InformationResource($information), 'All Informations');

    }
}
