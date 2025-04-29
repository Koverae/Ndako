<?php

namespace Modules\RevenueManager\Models\Expenses;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'expense_category_id',
        'property_id',
        'property_unit_id',
        'title',
        'amount',
        'note',
        'is_recurrent',
        'recurrence',
        'next_due_at',
        'status',
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Expense::isCompany(current_company()->id)->max('id') + 1;
            $year = Carbon::parse($model->date)->year;
            $month = Carbon::parse($model->date)->month;
            $model->reference = make_reference_with_month_id('ND/EXP', $number, $year, $month);
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function category() {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id', 'id');
    }

    // protected static function newFactory(): Expenses/ExpenseFactory
    // {
    //     // return Expenses/ExpenseFactory::new();
    // }
}
