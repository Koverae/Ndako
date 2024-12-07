<?php

namespace Modules\ChannelManager\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\ChannelManager\Database\Factories\Booking/BookingPaymentFactory;

class BookingPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): Booking/BookingPaymentFactory
    // {
    //     // return Booking/BookingPaymentFactory::new();
    // }
}
