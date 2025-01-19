<?php

namespace Modules\ChannelManager\Models\Guest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\ChannelManager\Models\Booking\Booking;

class Guest extends Model
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

    public function bookings() {
        return $this->hasMany(Booking::class, 'guest_id', 'id');
    }
}
