<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
  protected $fillable = ['user_id', 'points'];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function loyaltyPoints()
  {
    return $this->hasMany(LoyaltyPoint::class, 'customer_id');
  }

  // Helper untuk menambah poin
  public function addPoints(int $points, string $type, ?int $orderId = null, ?string $description = null)
  {
    $this->increment('points', $points);

    return $this->loyaltyPoints()->create([
      'order_id' => $orderId,
      'points' => $points,
      'type' => $type,
      'description' => $description,
    ]);
  }

  // Helper untuk kurangi poin
  public function deductPoints(int $points, string $type, ?int $orderId = null, ?string $description = null)
  {
    $this->decrement('points', $points);

    return $this->loyaltyPoints()->create([
      'order_id' => $orderId,
      'points' => -$points,
      'type' => $type,
      'description' => $description,
    ]);
  }
}
