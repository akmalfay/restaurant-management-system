<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        "name",
        "phone",
        "email",
        "points",
        "password",
        "image",
    ];

    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class, "customer_id");
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, "customer_id");
    }

    public function orders()
    {
        return $this->hasMany(Order::class, "customer_id");
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($customer) {
    //         if (empty($customer->image)) {
    //             $customer->image =
    //                 "https://ui-avatars.com/api/?name=" .
    //                 urlencode($customer->name) .
    //                 "&background=random&color=fff";
    //         }
    //     });
    // }
}
