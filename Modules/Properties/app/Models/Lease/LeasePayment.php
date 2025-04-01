<?php

namespace Modules\Properties\Models\Lease;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Modules\RevenueManager\Models\Accounting\Journal;

class LeasePayment extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {

            $journal = Journal::find($model->journal_id);
            $number = LeaseInvoice::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->date)->year;
            $month = Carbon::parse($model->date)->month;
            if(!$model->reference){
                $model->reference = make_reference_with_month_id('P'.$journal->short_code, $number, $year, $month);
            }
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function invoice() {
        return $this->belongsTo(LeaseInvoice::class, 'lease_invoice_id', 'id');
    }
}
