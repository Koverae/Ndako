<?php

namespace Modules\App\Models\Kredit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kredit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'team_id',
        'balance'
    ];

    public function transactions(){
        return $this->hasMany(KreditTransaction::class, 'kredit_id', 'id');
    }
}
