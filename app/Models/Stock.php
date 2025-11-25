<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $fillable = ['warehouse_id', 'branch_id', 'product_item_id', 'qty', 'product_id'];

    // علاقات
    public function productItem()
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
