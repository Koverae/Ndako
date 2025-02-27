<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Properties\Database\Factories\Property/PropertyUnitTypeFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PropertyUnitType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsProperty(Builder $query, $property_id)
    {
        return $query->where('property_id', $property_id);
    }

    public function property() {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function units() {
        return $this->hasMany(PropertyUnit::class, 'property_unit_type_id', 'id');
    }

    public function features() {
        return $this->hasMany(PropertyFeature::class, 'property_unit_type_id', 'id');
    }

    public function utilities() {
        return $this->hasMany(PropertyUtility::class, 'property_unit_type_id', 'id');
    }

    public function price() {
        return $this->belongsTo(PropertyUnitTypePricing::class, 'pricing_id', 'id');
    }
}
