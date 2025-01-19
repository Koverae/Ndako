<?php

namespace Modules\Properties\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Properties\Database\Factories\Property/PropertyUnitFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Modules\ChannelManager\Models\Booking\Booking;

class PropertyUnit extends Model
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

    public function scopeIsType(Builder $query, $type_id)
    {
        return $query->where('property_unit_type_id', $type_id);
    }

    public function unitType() {
        return $this->belongsTo(PropertyUnitType::class, 'property_unit_type_id', 'id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'property_unit_id', 'id');
    }
}
