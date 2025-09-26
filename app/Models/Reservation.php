<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        "date",
        "time",
        "status",
        "customer_id",
        "table_id",
        "started_at",
        "ended_at",
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, "customer_id");
    }

    public function table()
    {
        return $this->belongsTo(Table::class, "table_id");
    }
}
