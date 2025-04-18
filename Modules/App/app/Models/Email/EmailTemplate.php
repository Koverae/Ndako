<?php

namespace Modules\App\Models\Email;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\App\Database\Factories\Email/EmailTemplateFactory;

class EmailTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): Email/EmailTemplateFactory
    // {
    //     // return Email/EmailTemplateFactory::new();
    // }
}
