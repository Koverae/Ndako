<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class PropertyAmenity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected $with = ['property'];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsProperty(Builder $query, $property_id)
    {
        return $query->where('property_id', $property_id);
    }

    public function scopeIsAmenity(Builder $query, $amenity_id)
    {
        return $query->where('amenity_id', $amenity_id);
    }

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function amenity() {
        return $this->belongsTo(Amenity::class);
    }
}
