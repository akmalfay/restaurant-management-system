<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['date', 'time', 'guests', 'status'];

    public function customer(){
      return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function table(){
      return $this->belongsTo(Table::class, 'table_id');
    }
}
