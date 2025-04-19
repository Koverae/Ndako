<?php

namespace Modules\ChannelManager\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ChannelManager\Transformers\Property\PropertyUnitResource;
use Modules\ChannelManager\Transformers\Property\PropertyUnitTypeResource;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $client = $request->get('api_client');
    
        $units = PropertyUnit::with(['unitType'])->isCompany($client->company_id)->get();
    
        return response()->json([
            'success' => true,
            'data' => PropertyUnitResource::collection($units),
            'message' => 'Units retrieved successfully',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        return response()->json([]);
    }

    /**
     * Show the specified resource.
     */

     public function show($id)
     {
        //  $client = app('ndako.api_client');

         $unit = PropertyUnit::where('id', $id)->firstOrFail();

         return response()->json($unit);
     }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //

        return response()->json([]);
    }

        /**
     * Display a listing of the resource.
     */
    public function typeIndex(Request $request)
    {
        $client = $request->get('api_client');
    
        $units = PropertyUnitType::with(['property'])->isCompany($client->company_id)->get();
    
        return response()->json([
            'success' => true,
            'data' => PropertyUnitTypeResource::collection($units),
            'message' => 'Units retrieved successfully',
        ]);
    }

    /**
     * Show the specified resource.
     */

     public function typeShow($id)
     {
        //  $client = app('ndako.api_client');

         $unit = PropertyUnitType::where('id', $id)->firstOrFail();

         return response()->json([
            'success' => true,
            'data' => [
                'id' => $unit->id,
                'name' => $unit->name,
                // 'slug' => $unit->slug,
                'price' => $unit->prices()->isDefault(true)->first()->price,
                'description' => $unit->description,
                'images' => $unit->images,
                'features' => $unit->features->map(function ($propertyFeature) {
                    return [
                        'name' => $propertyFeature->feature->name,
                        'icon' => $propertyFeature->feature->icon ?? null,
                    ];
                }), // assuming a facilities relationship
                'created_at' => $unit->created_at,
            ],
            'message' => 'Unit details fetched successfully.'
        ]);
     }
}
