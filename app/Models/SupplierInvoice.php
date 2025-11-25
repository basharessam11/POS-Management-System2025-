<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierInvoice extends Model
{
    use SoftDeletes;


    use HasFactory;

    protected $fillable = ['supplier_id', 'date', 'total', 'remaining', 'paid', 'type'];



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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // المرتجعات المرتبطة بالفاتورة
    public function returns()
    {
        return $this->hasMany(SupplierReturn::class, 'supplier_invoice_id');
    }

    public function items()
    {
        return $this->hasMany(SupplierInvoiceItem::class, 'invoice_id');
    }
    public function returnItems()
    {
        return $this->hasMany(SupplierReturn::class);
    }
}
