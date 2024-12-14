<?php

namespace Modules\FrontDesk\Models\Desk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class DeskSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsDesk(Builder $query, $front_desk_id)
    {
        return $query->where('front_desk_id', $front_desk_id);
    }

    public function scopeIsSetting(Builder $query, $setting_id)
    {
        return $query->where('setting_id', $setting_id);
    }
    
    public function desk() {
        return $this->belongsTo(FrontDesk::class, 'front_desk_id', 'id');
    }
}
