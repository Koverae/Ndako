<?php

namespace Modules\Settings\Models\Language;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Language extends Model
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

    public function scopeNotInstalled(Builder $query)
    {
        return $query->where('is_active', false);
    }

    public function scopeInstalled(Builder $query)
    {
        return $query->where('is_active', true);
    }
}
