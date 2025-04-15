<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Modules\App\Traits\Files\HasImages;
use Modules\Settings\Models\Localization\Country;

// use Modules\Properties\Database\Factories\PropertyFactory;

class Property extends Model
{
    use HasFactory, SoftDeletes, HasImages;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($property) {
            // Delete related floors and amenities
            $property->floors()->delete();
            $property->propertyAmenities()->delete();
            $property->unitTypes()->delete();
            $property->units()->delete();
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsType(Builder $query, $property_type_id)
    {
        return $query->where('property_type_id', $property_type_id);
    }

    public function scopeIsHospitality(Builder $query)
    {
        return $query->where('property_type_group', 'hospitality');
    }

    public function scopeIsRental(Builder $query)
    {
        return $query->where('property_type_group', 'hospitality');
    }

    public function type() {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function propertyType() {
        return $this->belongsToMany(PropertyType::class);
    }

    public function propertyAmenities() {
        return $this->hasMany(PropertyAmenity::class);
    }

    public function units() {
        return $this->hasMany(PropertyUnit::class);
    }

    public function unitTypes() {
        return $this->hasMany(PropertyUnitType::class);
    }

    public function floors() {
        return $this->hasMany(PropertyFloor::class);
    }

    public function amenities() {
        return $this->hasMany(PropertyAmenity::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
