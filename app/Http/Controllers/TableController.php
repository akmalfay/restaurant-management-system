<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
  public function rename(Request $request, Table $table)
  {
    if (!$this->canManage()) abort(403);

    $validated = $request->validate([
      'category' => ['required', 'in:Indoor,Outdoor,Terrace,VIP'],
      'number' => ['required', 'integer', 'min:1', 'max:99'],
    ]);

    $newName = $validated['category'] . '-' . str_pad($validated['number'], 2, '0', STR_PAD_LEFT);

    // Auto-set capacity based on category
    $capacity = Table::getCapacityByCategory($validated['category']);

    $table->update([
      'name' => $newName,
      'capacity' => $capacity,
    ]);

    return back()->with('status', "Meja diperbarui menjadi {$newName} (Kapasitas: {$capacity} kursi)");
  }

  public function updateCapacity(Request $request, Table $table)
  {
    if (!$this->canManage()) abort(403);

    $validated = $request->validate([
      'capacity' => ['required', 'integer', 'min:1', 'max:20'],
    ]);

    $table->update(['capacity' => $validated['capacity']]);

    return back()->with('status', "Kapasitas {$table->name} diperbarui menjadi {$validated['capacity']} kursi");
  }
  private function canManage(): bool
  {
    $u = Auth::user();
    return $u && ($u->user_type === 'admin' || $u->user_type === 'staff');
  }

  public function setMaintenance(Request $request, Table $table)
  {
    if (! $this->canManage()) abort(403);
    $request->validate(['reason' => ['nullable', 'string', 'max:255']]);

    // Only affect reservations that are today (not finished) and future
    $today = Carbon::today()->toDateString();
    $nowTime = Carbon::now()->format('H:i:s');

    $futureReservations = Reservation::where('table_id', $table->id)
      ->where(function ($q) use ($today, $nowTime) {
        $q->where('reservation_date', '>', $today)
          ->orWhere(function ($q2) use ($today, $nowTime) {
            $q2->where('reservation_date', $today)
              ->where('end_time', '>', $nowTime);
          });
      })
      ->where('status', '!=', 'finished') // exclude already finished
      ->with('order.customer.user')
      ->orderBy('reservation_date')
      ->orderBy('start_time')
      ->get();

    // Set maintenance
    $table->update(['status' => 'maintenance']);

    // Prepare affected list (readable)
    $affected = $futureReservations->map(function ($r) {
      return [
        'id' => $r->id,
        'date' => Carbon::parse($r->reservation_date)->format('d M Y'),
        'time' => substr($r->start_time, 0, 5),
        'order_id' => $r->order_id,
        'customer' => optional($r->order->customer->user)->name ?? 'Walk-in',
      ];
    })->toArray();

    // Store notifications per-table so multiple maintenance operations accumulate
    $notifications = session()->get('maintenance_notifications', []);
    $notifications[$table->id] = [
      'table' => ['id' => $table->id, 'name' => $table->name],
      'affected' => $affected,
      'count' => count($affected),
      'reason' => $request->input('reason'),
    ];
    session()->put('maintenance_notifications', $notifications);

    if (!empty($affected)) {
      return back()->with('status', "Meja {$table->name} ditandai Maintenance. Terdapat " . count($affected) . " reservasi terpengaruh.");
    }

    return back()->with('status', "Meja {$table->name} ditandai Maintenance.");
  }

  /**
   * Delete affected reservations for a specific table (table_id via POST).
   */
  public function deleteAffectedReservations(Request $request)
  {
    if (! $this->canManage()) abort(403);

    $tableId = $request->input('table_id');
    $notifications = session()->get('maintenance_notifications', []);

    if ($tableId) {
      $entry = $notifications[$tableId] ?? null;
      $ids = collect($entry['affected'] ?? [])->pluck('id')->filter()->all();
      if (!empty($ids)) Reservation::whereIn('id', $ids)->delete();
      // remove that table's notification
      unset($notifications[$tableId]);
    } else {
      // delete all affected reservations from all notifications
      $ids = collect($notifications)->flatMap(fn($n) => collect($n['affected'])->pluck('id'))->filter()->all();
      if (!empty($ids)) Reservation::whereIn('id', $ids)->delete();
      $notifications = [];
    }

    session()->put('maintenance_notifications', $notifications);
    return back()->with('status', 'Reservasi terdampak telah dihapus.');
  }

  /**
   * Clear maintenance notice for a specific table or all (table_id optional).
   */
  public function clearMaintenanceNotice(Request $request)
  {
    if (! $this->canManage()) abort(403);

    $tableId = $request->input('table_id');
    $notifications = session()->get('maintenance_notifications', []);

    if ($tableId) {
      unset($notifications[$tableId]);
    } else {
      $notifications = [];
    }

    session()->put('maintenance_notifications', $notifications);
    return back()->with('status', 'Notifikasi maintenance dibersihkan.');
  }

  /**
   * Show tables grid for admin/cashier.
   */
  public function index()
  {
    $user = Auth::user();
    if (! $user || ! ($user->user_type === 'admin' || ($user->user_type === 'staff' && optional($user->staffDetail)->role === 'cashier'))) {
      abort(403);
    }

    $categories = ['VIP', 'Terrace', 'Outdoor', 'Indoor'];
    $tablesByCategory = [];
    foreach ($categories as $cat) {
      $tablesByCategory[$cat] = Table::where('name', 'like', $cat . '-%')->orderBy('name')->get();
    }

    return view('reservations.tables_grid', [
      'tablesByCategory' => $tablesByCategory,
    ]);
  }
}
