<?php

namespace Modules\Settings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Settings\Database\Factories\WorkItemFactory;

class WorkItem extends Model
{
    use HasFactory;

    public function scopeTasks($query)
    {
        return $query->where('type', 'task');
    }

    public function scopeSituations($query)
    {
        return $query->where('type', 'situation');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
