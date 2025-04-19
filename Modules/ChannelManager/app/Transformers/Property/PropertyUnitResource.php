<?php

namespace Modules\ChannelManager\Transformers\Property;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'property' => new PropertyResource($this->whenLoaded('property')),
            'type' => new PropertyUnitTypeResource($this->whenLoaded('unitType')),
            // 'tenant' => new TenantResource($this->whenLoaded('tenant')),
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
