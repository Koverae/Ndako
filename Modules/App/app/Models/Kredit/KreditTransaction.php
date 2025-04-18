<?php

namespace Modules\App\Models\Kredit;

use App\Models\Team\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\App\Database\Factories\Kredit/KreditTransactionFactory;

class KreditTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'team_id',
        'user_id',
        'reference',
        'type',
        'action',
        'amount',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

}
