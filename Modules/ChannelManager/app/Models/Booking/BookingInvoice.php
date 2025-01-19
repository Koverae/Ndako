<?php

namespace Modules\ChannelManager\Models\Booking;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\ChannelManager\Models\Guest\Guest;

class BookingInvoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = BookingInvoice::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->date)->year;
            $month = Carbon::parse($model->date)->month;
            $model->reference = make_reference_with_month_id('ND/INV', $number, $year, $month);
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function agent() {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function guest() {
        return $this->belongsTo(Guest::class);
    }

    public function payments() {
        return $this->hasMany(BookingPayment::class);
    }
}
