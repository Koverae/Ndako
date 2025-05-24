<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Admin\Database\Factories\NdakoAppKeyFactory;

class NdakoAppKey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'app_key'
    ];

    // protected static function newFactory(): NdakoAppKeyFactory
    // {
    //     // return NdakoAppKeyFactory::new();
    // }
}
