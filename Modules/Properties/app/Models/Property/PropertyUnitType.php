<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Properties\Database\Factories\Property/PropertyUnitTypeFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Modules\App\Traits\Files\HasImages;

class PropertyUnitType extends Model
{
    use HasFactory, SoftDeletes, HasImages;

    protected $guarded = [];


    public static function boot()
    {
        parent::boot();

        static::deleting(function ($property) {
            // Delete related prices
            $property->prices()->delete();
        });
    }
    protected $casts = [
        'images' => 'array',
    ];
    
    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsType(Builder $query, $type)
    {
        return $query->where('id', $type);
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

    public function prices() {
        return $this->hasMany(PropertyUnitTypePricing::class, 'property_unit_type_id', 'id');
    }

    /**
     * Get the default pricing rate for a given unit type.
     *
     * @param int $unitTypeId
     * @return PropertyUnitTypePricing|null
     */
    public function getDefaultRate(int $unitTypeId): ?PropertyUnitTypePricing
    {
        return PropertyUnitTypePricing::where('property_unit_type_id', $unitTypeId)
            ->where('is_default', true)
            // ->select(['id', 'price', 'discounted_price'])
            ->first();
    }
    
    public function getImagesAttribute($value)
    {
        // Decode the JSON stored in the database
        $images = json_decode($value, true) ?? [];

        // Map the images to the full URL path
        return collect($images)->map(function ($path) {
            // Ensure you have a proper URL by prefixing 'storage/'
            return asset('storage/' . ltrim($path, '/'));
        })->toArray();
    }

}
