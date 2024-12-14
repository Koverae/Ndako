<?php

namespace Modules\RevenueManager\Models\Tax;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    // If the sales belong to the company
    public function scopeIsActive(Builder $query)
    {
        return $query->where('status', '<>', 'disabled');
    }

    public function scopeIsType(Builder $query, $type)
    {
        return $query->where('type', $type);
    }
}
