<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnItem extends Model
{
    use SoftDeletes;

    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_item_id',
        'qty',
        'price',
        'total',

        'customer_id',
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





    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }
}
