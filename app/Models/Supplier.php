<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(SupplierInvoice::class);
    }

    /**
     * الوصول لعناصر الفواتير مباشرة
     * Supplier -> SupplierInvoice -> SupplierInvoiceItem
     */

    public function invoiceItems()
    {
        return $this->hasMany(SupplierInvoiceItem::class);
    }
    /**
     * لو عندك جدول منتجات المورد
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function payment()
    {
        return $this->hasMany(SupplierPayment::class);
    }
    public function returnitem()
    {
        return $this->hasMany(ReturnItem::class);
    }
}
