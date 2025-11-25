<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\SoftDeletes;

class ProductItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'size', 'color', 'price', 'sell_price', 'sell_price2', 'qty', 'min_qty', 'photo'];

    public function setImageAttribute($value)
    {


        if (is_array($value)) {
            foreach ($value as $file) {
                if (is_file($file) and !empty($file)) {
                    self::update([
                        $value[1] => $file->store('product', 'product'),
                    ]);
                }
            }
        } elseif (is_file($value)) {
            $this->attributes[$value[1]] = $value->store('product', 'product');
        } else {
            $this->attributes[$value[1]] = $value;
        }
    }

    protected static function booted()
    {
        static::created(function ($item) {
            if (!$item->barcode) {
                $item->barcode =   str_pad($item->id, 6, '0', STR_PAD_LEFT);
                $item->saveQuietly(); // saveQuietly لتجنب تكرار الأحداث
            }
        });
    }



    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
