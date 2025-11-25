<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $guarded = [];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * الوصول لعناصر الفواتير مباشرة
     *  -> Invoice -> InvoiceItem
     */

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    /**
     * لو عندك جدول منتجات المورد
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function returnitem()
    {
        return $this->hasMany(ReturnItem::class);
    }

    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }

    // رصيد العميل = مجموع عليه - مجموع دفعه
    public function getBalanceAttribute()
    {
        $total_pay = $this->payments()->where('type', 'pay')->sum('amount');
        $total_receive = $this->payments()->where('type', 'receive')->sum('amount');

        return $total_receive - $total_pay;
    }
}
