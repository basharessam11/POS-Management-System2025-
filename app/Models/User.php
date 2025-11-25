<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use SoftDeletes;

    use HasRoles;

    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }




    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function updatedInvoices()
    {
        return $this->hasMany(Invoice::class, 'updated_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }




    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function setImageAttribute($value)
    {
        if (is_array($value)) {
            foreach ($value as $file) {
                if (is_file($file) and !empty($file)) {
                    self::update([
                        'photo' => $file->store('setting', 'setting'),
                    ]);
                }
            }
        } elseif (is_file($value)) {
            $this->attributes['photo'] = $value->store('setting', 'setting');
        } else {
            $this->attributes['photo'] = $value;
        }
    }
}
