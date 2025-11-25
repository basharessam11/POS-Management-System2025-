<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierReturn extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'supplier_invoice_id',
        'product_item_id',
        'qty',
        'price',
        'total',

        'supplier_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (auth()->check()) {
                $invoice->created_by = auth()->id();
            }
        });

        static::updating(function ($invoice) {
            if (auth()->check()) {
                $invoice->updated_by = auth()->id();
            }
        });
    }





    public function supplierInvoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'supplier_invoice_id');
    }

    // المورد
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // المنتج
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }
}
