<?php

namespace Modules\ChannelManager\Models\Booking;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Booking::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->created_at)->year;
            $month = Carbon::parse($model->created_at)->month;
            $model->reference = make_reference_with_month_id('ND/BK', $number, $year, $month);
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function invoice() {
        return $this->hasOne(BookingInvoice::class);
    }
}
