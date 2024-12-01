<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

// use Modules\Properties\Database\Factories\PropertyFactory;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsType(Builder $query, $property_type_id)
    {
        return $query->where('property_type_id', $property_type_id);
    }

    public function propertyType() {
        return $this->belongsToMany(PropertyType::class);
    }
}
