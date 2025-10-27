<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
  private function isAdmin(): bool
  {
    return Auth::user()->user_type === 'admin';
  }

  private function isCashier(): bool
  {
    return Auth::user()->user_type === 'staff';
  }

  private function canManage(): bool
  {
    return $this->isAdmin() || $this->isCashier();
  }

  public function index(Request $request)
  {
    $user = Auth::user();
    if (!in_array($user->user_type, ['admin', 'staff', 'customer'])) abort(403);

    $categories = ['Indoor', 'Outdoor', 'Terrace', 'VIP'];
    $category = in_array($request->query('category'), $categories) ? $request->query('category') : 'Indoor';

    $week = (int) $request->query('week', 0);
    if ($week < 0) $week = 0;
    if ($week > 4) $week = 4;

    $dayOffset = (int) $request->query('day', 0);
    if ($dayOffset < 0) $dayOffset = 0;
    if ($dayOffset > 5) $dayOffset = 5;

    $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->addWeeks($week);
    $selectedDate = $weekStart->copy()->addDays($dayOffset);

    $isPast = $selectedDate->lt(Carbon::today());

    $tables = Table::where('name', 'LIKE', $category . '-%')->orderBy('name')->get();

    // Ambil SEMUA reservasi di tanggal tersebut (tidak ada limit)
    $reservations = Reservation::where('reservation_date', $selectedDate->toDateString())
      ->with('order.customer.user')
      ->orderBy('created_at', 'asc')
      ->get();

    // Group by table+hour (SIMPAN SEBAGAI ARRAY untuk multiple bookings)
    $indexed = [];
    foreach ($reservations as $res) {
      $hour = Carbon::parse($res->start_time)->format('H');

      // PENTING: Simpan sebagai array, bukan overwrite
      if (!isset($indexed[$res->table_id][$hour])) {
        $indexed[$res->table_id][$hour] = [];
      }

      $indexed[$res->table_id][$hour][] = $res; // Push ke array
    }

    $hours = range(10, 23);

    $rows = [];
    foreach ($tables as $t) {
      $slots = [];
      foreach ($hours as $h) {
        $hStr = str_pad($h, 2, '0', STR_PAD_LEFT);
        // Kembalikan array of reservations (atau null jika kosong)
        $slots[] = $indexed[$t->id][$hStr] ?? null;
      }
      $rows[] = ['table' => $t, 'slots' => $slots];
    }

    // Generate dropdown options
    $dayOptions = [];
    for ($i = 0; $i < 6; $i++) {
      $date = $weekStart->copy()->addDays($i);
      $dayOptions[] = [
        'offset' => $i,
        'label' => \Illuminate\Support\Str::headline($date->locale('id')->dayName) . ', ' . $date->format('d M Y'),
        'date' => $date,
      ];
    }

    return view('reservations.index', [
      'categories' => $categories,
      'category' => $category,
      'week' => $week,
      'dayOffset' => $dayOffset,
      'selectedDate' => $selectedDate,
      'weekStart' => $weekStart,
      'hours' => $hours,
      'rows' => $rows,
      'dayOptions' => $dayOptions,
      'isPast' => $isPast,
      'canManage' => $this->canManage(),
      'isCustomer' => $user->user_type === 'customer',
    ]);
  }

  public function store(Request $request)
  {
    $user = Auth::user();
    if ($user->user_type === 'staff' && !$this->isCashier() && !$this->isAdmin()) abort(403);

    $validated = $request->validate([
      'table_id' => ['required', 'exists:tables,id'],
      'date' => ['required', 'date', 'after_or_equal:today'],
      'hour' => ['required', 'integer', 'between:10,23'],
      'order_id' => ['required', 'exists:orders,id'],
    ]);

    $slotDate = Carbon::parse($validated['date'])->toDateString();
    $start = Carbon::parse("$slotDate {$validated['hour']}:00:00");
    $end = (clone $start)->addHour();

    // HAPUS CHECK DUPLIKAT - allow multiple bookings
    Reservation::create([
      'order_id' => $validated['order_id'],
      'table_id' => $validated['table_id'],
      'reservation_date' => $slotDate,
      'start_time' => $start->format('H:i:s'),
      'end_time' => $end->format('H:i:s'),
      'status' => $this->canManage() ? 'confirmed' : 'pending',
    ]);

    return back()->with('status', 'Reservasi dibuat. Jika ada konflik, cashier akan memilih salah satu.');
  }

  public function approve(Reservation $reservation)
  {
    if (!$this->canManage()) abort(403);

    $reservation->update(['status' => 'confirmed']);

    // AUTO-REJECT konflik lainnya di slot yang sama
    Reservation::where('table_id', $reservation->table_id)
      ->where('reservation_date', $reservation->reservation_date)
      ->where('start_time', $reservation->start_time)
      ->where('id', '!=', $reservation->id)
      ->where('status', 'pending')
      ->update(['status' => 'cancelled']);

    return back()->with('status', 'Reservasi disetujui. Konflik lainnya otomatis dibatalkan.');
  }

  public function complete(Reservation $reservation)
  {
    if (!$this->canManage()) abort(403);
    $reservation->update(['status' => 'completed']);
    return back()->with('status', 'Reservasi selesai.');
  }

  public function cancel(Reservation $reservation)
  {
    if (!$this->canManage()) abort(403);
    $reservation->update(['status' => 'cancelled']);
    return back()->with('status', 'Reservasi dibatalkan.');
  }

  public function history(Request $request)
  {
    $user = Auth::user();
    if (!$this->canManage() && $user->user_type !== 'customer') abort(403);

    $categories = ['Indoor', 'Outdoor', 'Terrace', 'VIP'];
    $category = in_array($request->query('category'), $categories) ? $request->query('category') : 'Indoor';

    $maxDate = Carbon::yesterday()->toDateString();
    $date = Carbon::parse($request->query('date', $maxDate))->toDateString();
    if (Carbon::parse($date)->gte(Carbon::today())) {
      $date = $maxDate;
    }

    $shift = $request->query('shift', 'morning');
    if (!in_array($shift, ['morning', 'night'])) $shift = 'morning';

    $tables = Table::where('name', 'LIKE', $category . '-%')->orderBy('name')->get();

    $reservations = Reservation::where('reservation_date', $date)
      ->where('shift', $shift)
      ->with('order.customer.user')
      ->get()
      ->keyBy('table_id');

    $grid = $tables->map(fn($t) => ['table' => $t, 'reservation' => $reservations->get($t->id)]);

    return view('reservations.history', [
      'categories' => $categories,
      'category' => $category,
      'date' => Carbon::parse($date),
      'shift' => $shift,
      'grid' => $grid,
      'canManage' => $this->canManage(),
    ]);
  }
}
