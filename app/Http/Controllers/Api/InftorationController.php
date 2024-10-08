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
    public function store(StoreIfromationRequest $request)
    {
        $validated = $request-> validated();

        $information = Information::create($validated);

        return $this->sendResponse(new InformationResource($information), 'All Informations');

    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
