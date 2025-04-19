<?php

namespace Modules\ChannelManager\Transformers\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyUnitTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->prices()->isDefault(true)->first()->price,
            'images' => $this->images,
            'features' => $this->features->map(function ($propertyFeature) {
                return [
                    'name' => $propertyFeature->feature->name,
                    'icon' => $propertyFeature->feature->icon ?? null,
                ];
            }),
            'status' => $this->status,
        ];
    }
}
