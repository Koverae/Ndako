<?php

namespace Modules\Properties\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Eloquent\Builder;
use Modules\Properties\Models\Lease\Lease;
use Modules\Properties\Models\Property\PropertyUnit;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function lease() {
        return $this->hasOne(Lease::class, 'tenant_id', 'id');
    }

}
