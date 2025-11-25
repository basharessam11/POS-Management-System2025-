<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'product_item_id', 'qty', 'price', 'deleted_at', 'total', 'supplier_id'];

    public function invoice()
    {
        return $this->belongsTo(SupplierInvoice::class, 'invoice_id');
    }
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_item_id');
    }
}
