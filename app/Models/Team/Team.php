<?php

namespace App\Models\Team;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Koverae\KoveraeBilling\Models\PlanSubscription;
use Koverae\KoveraeBilling\Traits\HasSubscriptions;
use Modules\App\Models\Kredit\Kredit;

class Team extends Model
{
    use HasFactory, Notifiable, HasSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($team): void {
            $team->uuid = (string) Str::uuid();
        });
    }

    public function subscribed()
    {
        return $this->hasOne(PlanSubscription::class, 'subscriber_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne(Kredit::class, 'team_id', 'id');
    }

}
