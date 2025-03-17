<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class PropertyUnitTypePricing extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsPropertyUnit(Builder $query, $property_id)
    {
        return $query->where('property_unit_type_id', $property_id);
    }

    public function scopeIsProperty(Builder $query, $property_id)
    {
        return $query->where('property_id', $property_id);
    }

    public function scopeIsDefault(Builder $query)
    {
        return $query->where('is_default', true);
    }

    public function lease() {
        return $this->belongsTo(LeaseTerm::class, 'lease_term_id', 'id');
    }

    public function propertyUnit() {
        return $this->belongsToMany(PropertyUnitType::class);
    }

    public function property() {
        return $this->belongsToMany(Property::class);
    }
}
