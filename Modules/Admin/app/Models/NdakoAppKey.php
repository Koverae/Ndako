<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class NdakoAppKey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'app_key',
        'status'
    ];

    /**
     * Get the user that owns the APP key.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
