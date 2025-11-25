<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'category_id', 'brand_id'];

    // علاقة المنتج بالفئة
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // علاقة المنتج بالبراند
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // علاقة المنتج بالمتغيرات
    public function items()
    {
        return $this->hasMany(ProductItem::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    

   
}
