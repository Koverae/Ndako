<?php

namespace Modules\RevenueManager\Models\Expenses;

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
