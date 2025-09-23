<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['staff_id','date','start_time','end_time','shift'];

    public function staff(){
      return $this->belongsTo(Staff::class, 'staff_id');
    }
}
