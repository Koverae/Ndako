<?php

namespace Modules\Settings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Properties\Models\Property\PropertyUnit;

// use Modules\Settings\Database\Factories\WorkItemFactory;

class WorkItem extends Model
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

    public function scopeIsTasks($query)
    {
        return $query->where('type', 'task');
    }

    public function scopeIsActive($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeIsSituations($query)
    {
        return $query->where('type', 'situation');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function room()
    {
        return $this->belongsTo(PropertyUnit::class, 'room_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
