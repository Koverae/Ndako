<?php

namespace Modules\FrontDesk\Models\Desk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class FrontDesk extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function activeSession(){
        return $this->hasOne(DeskSession::class, 'id', 'active_session_id');
    }
    public function setting(){
        return $this->hasOne(DeskSetting::class, 'id', 'front_desk_id');
    }
}
