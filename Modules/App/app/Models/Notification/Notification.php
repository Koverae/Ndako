<?php

namespace Modules\App\Models\Notification;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */protected $fillable = ['user_id', 'company_id', 'type', 'data', 'read_at'];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Company::class);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function scopeIsRead(Builder $query){
        $query->where('read_at', '<>', null);
    }

    public function scopeIsUnread(Builder $query){
        $query->where('read_at', null);
    }
}
