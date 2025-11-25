<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPayment extends Model
{
    use SoftDeletes, HasFactory;


    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'note',
        'created_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
 
    public function creator()
    {
         return $this->belongsTo(User::class, 'created_by');
    }
}
