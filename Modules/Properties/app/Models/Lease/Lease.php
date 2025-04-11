<?php

namespace Modules\Properties\Models\Lease;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Tenant\Tenant;

// use Modules\Properties\Database\Factories\Lease/LeaseFactory;

class Lease extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Lease::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::now()->year;
            $month = Carbon::now()->month;
            $model->reference = make_reference_with_month_id('ND/LS', $number, $year, $month);

            // Lease unique code
            do {
                $uniqueCode = 'LS-' . Str::upper(Str::random(14)); // Total length: 16
            } while (self::where('code', $uniqueCode)->exists());

            $model->code = $uniqueCode;
        });

    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsUnit(Builder $query, $property_unit_id)
    {
        return $query->where('property_unit_id', $property_unit_id);
    }

    public function invoices() {
        return $this->hasMany(LeaseInvoice::class);
    }

    public function unit() {
        return $this->belongsTo(PropertyUnit::class, 'property_unit_id', 'id');
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
