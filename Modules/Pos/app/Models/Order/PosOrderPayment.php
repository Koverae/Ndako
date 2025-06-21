<?php

namespace Modules\Pos\Models\Order;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\RevenueManager\Models\Accounting\Journal;

// use Modules\Pos\Database\Factories\Order/PosOrderPaymentFactory;

class PosOrderPayment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {

            $journalCode = Journal::isCompany(current_company()->id)->isType($model->payment_method)->first()->short_code ?? 'ND';
            $number = PosOrder::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->date)->year;
            $month = Carbon::parse($model->date)->month;
            if(!$model->reference){
                $model->reference = make_reference_with_month_id('P'.$journalCode, $number, $year, $month);
            }
        });
    }


    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsPos(Builder $query, $pos_id)
    {
        return $query->where('pos_id', $pos_id);
    }

    public function order() {
        return $this->belongsTo(PosOrder::class, 'pos_order_id', 'id');
    }

    public function guest() {
        return $this->belongsTo(PosOrder::class, 'guest_id', 'id');
    }
}
