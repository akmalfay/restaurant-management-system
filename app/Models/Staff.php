<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs'; // rename table staff to staffs
    protected $fillable = ['name', 'position','active'];

    public function schedules()
    {
      return $this->hasMany(Schedule::class, 'staff_id');
    }

    public function orders()
    {
      return $this->hasMany(Order::class, 'staff_id');
    }
}
