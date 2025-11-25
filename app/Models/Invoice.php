<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{ 
    use SoftDeletes;

    use HasFactory;

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


    protected $fillable = [
        'branch_id',
        'customer_id',
        'user_id',
        'total',
        'remaining',
        'paid',
        'discount',
        'note',
        'type',
        'created_by',
        'income',
        'updated_by'
    ];

    // الفرع المرتبط بالفاتورة
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // العميل المرتبط بالفاتورة
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // المستخدم الأساسي المرتبط بالفاتورة (الكاشير أو المسؤول)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // مين اللي عمل إنشاء للفاتورة
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // مين اللي عمل تعديل على الفاتورة
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function returnItems()
    {
        return $this->hasMany(ReturnItem::class);
    }
}
