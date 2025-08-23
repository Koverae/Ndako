<?php

namespace Modules\Pos\Models\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Pos\Models\Product\Product;

// use Modules\Pos\Database\Factories\Order/PosOrderDetailFactory;

class PosOrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected $casts = [
        'kds_preparing_at' => 'datetime',
        'kds_ready_at'     => 'datetime',
        'kds_delivered_at' => 'datetime',
    ];

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeIsPos(Builder $query, $pos_id)
    {
        return $query->where('pos_id', $pos_id);
    }

    public function order() {
        return $this->belongsTo(PosOrder::class, 'pos_order_id', 'id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // KDS
    public function scopeForStation($q, $station)
    {
        return $station ? $q->where('kds_station', $station) : $q;
    }

    public function scopeOpen($q)
    {
        return $q->whereIn('kds_status', ['queued','preparing','ready']);
    }
}
