<?php

namespace Modules\Properties\Models\Property\ExtraService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Properties\Database\Factories\Property/ExtraService/GuestServiceItemFactory;

class GuestServiceItem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    // protected static function newFactory(): Property/ExtraService/GuestServiceItemFactory
    // {
    //     // return Property/ExtraService/GuestServiceItemFactory::new();
    // }

}
