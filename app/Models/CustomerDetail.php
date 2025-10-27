<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'points',
  ];

  protected $casts = [
    'points' => 'integer',
  ];

  // Relasi ke User
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  // Relasi ke Orders
  public function orders()
  {
    return $this->hasMany(Order::class, 'customer_id');
  }

  // Relasi ke LoyaltyPoints
  public function loyaltyPoints()
  {
    return $this->hasMany(LoyaltyPoint::class, 'customer_id');
  }

  // Method untuk tambah poin
  public function addPoints(int $points, string $type, ?int $orderId = null, ?string $description = null)
  {
    $this->increment('points', $points);

    LoyaltyPoint::create([
      'customer_id' => $this->id,
      'order_id' => $orderId,
      'points' => $points,
      'type' => $type,
      'description' => $description ?? "Earned {$points} points",
    ]);
  }

  // Method untuk kurangi poin
  public function deductPoints(int $points, string $type, ?int $orderId = null, ?string $description = null)
  {
    if ($this->points < $points) {
      throw new \Exception('Insufficient points');
    }

    $this->decrement('points', $points);

    LoyaltyPoint::create([
      'customer_id' => $this->id,
      'order_id' => $orderId,
      'points' => -$points,
      'type' => $type,
      'description' => $description ?? "Redeemed {$points} points",
    ]);
  }
}
