<?php

namespace Modules\Properties\Models\Lease;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Modules\Properties\Models\Tenant\Tenant;

// use Modules\Properties\Database\Factories\Lease/LeaseInvoiceFactory;

class LeaseInvoice extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = LeaseInvoice::isCompany(current_company()->id)->max('id') + 1;

            // Get the year and month
            $yearMonth = getYearAndMonthFromCode($model->code); //code: 2025_04

            $year = $yearMonth['year'];
            $month = $yearMonth['month'];

            $model->reference = make_reference_with_month_id('ND/LS/INV', $number, $year, $month);
        });
    }


    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function lease() {
        return $this->belongsTo(Lease::class);
    }

    public function agent() {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }

    public function payments() {
        return $this->hasMany(LeasePayment::class);
    }
}
