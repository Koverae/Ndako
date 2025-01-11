<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class PropertyFeature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsProperty(Builder $query, $property_id)
    {
        return $query->where('property_id', $property_id);
    }

    public function scopeIsFeature(Builder $query, $property_id)
    {
        return $query->where('feature_id', $property_id);
    }

    public function feature() {
        return $this->belongsTo(Feature::class);
    }

    public function scopeIsUnitType(Builder $query, $type_id)
    {
        return $query->where('property_unit_type_id', $type_id);
    }

}
