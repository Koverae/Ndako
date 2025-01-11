<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class PropertyUtility extends Model
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

    public function scopeIsUtility(Builder $query, $property_id)
    {
        return $query->where('utility_id', $property_id);
    }

    public function scopeIsUnitType(Builder $query, $type_id)
    {
        return $query->where('property_unit_type_id', $type_id);
    }
    public function utility() {
        return $this->belongsTo(Utility::class);
    }
}
