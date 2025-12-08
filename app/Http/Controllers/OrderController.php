<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  private function isAdminOrCashier(): bool
  {
    $u = Auth::user();
    return $u && ($u->user_type === 'admin' || ($u->user_type === 'staff' && optional($u->staffDetail)->role === 'cashier'));
  }

  public function index(Request $request)
  {
    if (! $this->isAdminOrCashier()) abort(403);

    $q = trim($request->query('q', ''));
    $type = $request->query('type', '');
    $status = $request->query('status', '');

    // Prioritize pending -> preparing -> ready -> cancel; newest within each group
    $query = Order::with(['customer.user', 'orderItems.menuItem'])
      ->orderByRaw("
        CASE status
          WHEN 'pending' THEN 0
          WHEN 'preparing' THEN 1
          WHEN 'ready' THEN 2
          WHEN 'cancel' THEN 3
          ELSE 4
        END ASC
      ")
      ->orderBy('created_at', 'desc');

    if ($type) {
      $query->where('type', $type);
    }

    if ($status) {
      $query->where('status', $status);
    }

    if ($q !== '') {
      $query->where(function ($wr) use ($q) {
        if (is_numeric($q)) {
          $wr->where('id', intval($q));
        }
        $wr->orWhere('id', 'like', "%{$q}%")
          ->orWhereHas('customer.user', fn($u) => $u->where('name', 'like', "%{$q}%"));
      });
    }

    $orders = $query->paginate(25)->appends($request->query());

    return view('orders.index', [
      'orders' => $orders,
      'q' => $q,
      'type' => $type,
      'status' => $status,
    ]);
  }

  /**
   * Return order detail as JSON for popover (items, type, status, customer)
   */
  public function show(Order $order)
  {
    if (! $this->isAdminOrCashier()) abort(403);

    $order->loadMissing(['orderItems.menuItem', 'customer.user']);

    return response()->json([
      'id' => $order->id,
      'type' => $order->type,
      'status' => $order->status,
      'total' => $order->total,
      'order_time' => optional($order->order_time)->toDateTimeString(),
      'customer' => $order->customer ? [
        'id' => optional($order->customer->user)->id,
        'name' => optional($order->customer->user)->name,
      ] : null,
      'items' => $order->orderItems->map(fn($it) => [
        'name' => $it->menuItem->name ?? ($it->menu->name ?? 'Item'),
        'qty' => $it->quantity,
        'price' => $it->price,
      ]),
    ]);
  }

  /**
   * Change order status (admin/cashier) — validate allowed transitions
   */
  public function updateStatus(Request $request, Order $order)
  {
    if (! $this->isAdminOrCashier()) abort(403);

    $allowed = ['pending', 'preparing', 'ready', 'cancel'];
    $s = $request->validate(['status' => ['required', 'in:' . implode(',', $allowed)]])['status'];

    // Define allowed transitions
    $transitions = [
      // cancel allowed only while still pending
      'pending' => ['preparing', 'cancel'],
      // once preparing, only allow moving to ready
      'preparing' => ['ready'],
      'ready' => [],
      'cancel' => [],
    ];

    $from = $order->status;
    if (! isset($transitions[$from]) || ! in_array($s, $transitions[$from])) {
      return back()->withErrors(['status' => "Transisi tidak diperbolehkan dari {$from} ke {$s}."]);
    }

    $order->update(['status' => $s]);

    return back()->with('status', "Order #{$order->id} status diubah ke {$s}.");
  }
}
