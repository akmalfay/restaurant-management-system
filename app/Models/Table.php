<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ["number", "capacity", "status"];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, "table_id");
    }

    public function orders()
    {
        return $this->hasMany(Order::class, "table_id");
    }
}
