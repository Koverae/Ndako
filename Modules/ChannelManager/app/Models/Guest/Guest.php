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
    protected $fillable = [
        'company_id',
        'user_id',
        'avatar',
        'name',
        'company_name',
        'language_id',
    
        // Address
        'street',
        'street2',
        'city',
        'state',
        'country_id',
        'zip',
    
        // Contact Info
        'identity_proof',
        'identity',
        'phone',
        'mobile',
        'email',
        'website',
        'tags',
    
        // Individual
        'job',
        'has_receipt_reminder',
        'days_before',
    
        // MISC
        'companyID',
        'reference',
        'note',
        'type',
        'status',
    ];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'guest_id', 'id');
    }

}
