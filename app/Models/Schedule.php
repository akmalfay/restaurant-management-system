<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
  protected $fillable = ['staff_id', 'schedule_date', 'shift'];

  public function staffDetail()
  {
    return $this->belongsTo(StaffDetail::class, 'staff_id');
  }
}
