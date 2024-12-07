<?php

namespace Modules\RevenueManager\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer_detail_invoices';
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function sale() {
        return $this->belongsTo(Invoice::class, 'customer_invoice_id', 'id');
    }
    
    public function invoice() {
        return $this->belongsTo(Invoice::class, 'customer_invoice_id', 'id');
    }
}
