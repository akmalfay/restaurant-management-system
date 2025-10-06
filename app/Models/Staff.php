<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $table = "staffs"; // rename table staff to staffs
    protected $fillable = ["name", "position", "active", "phone", "image"];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, "staff_id");
    }

    public function orders()
    {
        return $this->hasMany(Order::class, "staff_id");
    }
}
