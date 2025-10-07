<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
  protected $fillable = ['name', 'status'];

  public function reservations()
  {
    return $this->belongsTo(Reservation::class, 'table_id');
  }
}
