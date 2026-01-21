<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    // Update reservation statuses based on time (bulk, safe)
    $now = Carbon::now();

    // All dates before today -> finished
    Reservation::where('reservation_date', '<', $now->toDateString())
      ->where('status', '!=', 'finished')
      ->update(['status' => 'finished']);

    // Today: finished if end_time <= now
    Reservation::where('reservation_date', $now->toDateString())
      ->where('end_time', '<=', $now->format('H:i:s'))
      ->where('status', '!=', 'finished')
      ->update(['status' => 'finished']);

    // Today: ongoing if start_time <= now < end_time
    Reservation::where('reservation_date', $now->toDateString())
      ->where('start_time', '<=', $now->format('H:i:s'))
      ->where('end_time', '>', $now->format('H:i:s'))
      ->where('status', '!=', 'ongoing')
      ->update(['status' => 'ongoing']);

    // Future slots -> upcoming
    Reservation::where('reservation_date', '>', $now->toDateString())
      ->where('status', '!=', 'upcoming')
      ->update(['status' => 'upcoming']);

    // Ambil SEMUA reservasi di tanggal tersebut (tidak ada limit)
    $reservations = Reservation::where('reservation_date', $selectedDate->toDateString())
      ->with('orders.customer.user')
      ->orderBy('created_at', 'asc')
      ->get();

    // Group by table+hour (SIMPAN SEBAGAI ARRAY untuk multiple bookings)
    $indexed = [];
    foreach ($reservations as $res) {
      $hour = Carbon::parse($res->start_time)->format('H');
      if (!isset($indexed[$res->table_id][$hour])) $indexed[$res->table_id][$hour] = [];
      $indexed[$res->table_id][$hour][] = $res;
    }

    $hours = range(10, 23);

    $rows = [];
    foreach ($tables as $t) {
      $slots = [];
      foreach ($hours as $h) {
        $hStr = str_pad($h, 2, '0', STR_PAD_LEFT);
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

    $validated = $request->validate([
      'table_id' => ['required', 'exists:tables,id'],
      'date' => ['required', 'date', 'after_or_equal:today'],
      'hour' => ['required', 'integer', 'between:10,23'],
    ]);

    // Guard: if table is in maintenance do not allow booking
    $table = Table::find($validated['table_id']);
    if (! $table || $table->status !== 'available') {
      return back()->withErrors(['slot' => 'Meja sedang maintenance / tidak tersedia. Hapus atau pindahkan reservasi yang ada, atau tandai meja tersedia terlebih dahulu.']);
    }

    $slotDate = Carbon::parse($validated['date'])->toDateString();
    $start = Carbon::parse("$slotDate {$validated['hour']}:00:00");
    $end = (clone $start)->addHour();

    // Decide status by time: upcoming / ongoing (allow booking current hour) 
    $now = Carbon::now();
    if ($end->lte($now)) {
      // booking in the past — reject
      return back()->withErrors(['slot' => 'Tidak bisa memesan slot yang sudah lewat.']);
    }
    $status = ($start->lte($now) && $end->gt($now)) ? 'ongoing' : 'upcoming';

    // Race-safe insert: transaction + DB unique constraint
    try {
      DB::beginTransaction();

      $exists = Reservation::where('table_id', $validated['table_id'])
        ->where('reservation_date', $slotDate)
        ->where('start_time', $start->format('H:i:s'))
        ->exists();

      if ($exists) {
        DB::rollBack();
        return back()->withErrors(['slot' => 'Slot sudah terisi. Silakan pilih jadwal lain.']);
      }

      Reservation::create([
        'table_id' => $validated['table_id'],
        'reservation_date' => $slotDate,
        'start_time' => $start->format('H:i:s'),
        'end_time' => $end->format('H:i:s'),
        'status' => $status,
      ]);

      DB::commit();
    } catch (\Throwable $e) {
      DB::rollBack();
      return back()->withErrors(['slot' => 'Gagal membuat reservasi — slot kemungkinan sudah diambil. Silakan coba lagi.']);
    }

    return back()->with('status', 'Reservasi berhasil dibuat. Silakan lanjutkan dengan membuat pesanan.');
  }

  public function cancel(Reservation $reservation)
  {
    $user = Auth::user();

    // Allow cashier/admin OR reservation owner (any customer with order on this reservation) to cancel
    $isOwner = $reservation->orders()->where('customer_id', $user->id)->exists();

    if (!$this->canManage() && !$isOwner) abort(403);

    // Delete record to free slot (no separate cancelled status)
    $reservation->delete();

    return back()->with('status', 'Reservasi dibatalkan dan dihapus.');
  }

  public function complete(Reservation $reservation)
  {
    if (!$this->canManage()) abort(403);
    $reservation->update(['status' => 'finished']);
    return back()->with('status', 'Reservasi ditandai selesai.');
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

  public function myReservations(Request $request)
  {
    $user = Auth::user();
    if ($user->user_type !== 'customer') abort(403);

    // Get customer detail
    $customerDetail = $user->customerDetail;
    if (!$customerDetail) {
      return view('reservations.my', ['reservations' => [], 'filter' => 'all']);
    }

    $filter = $request->query('filter', 'all');
    if (!in_array($filter, ['all', 'upcoming', 'ongoing', 'finished'])) {
      $filter = 'all';
    }

    // Get reservations that have orders from this customer
    $query = Reservation::whereHas('orders', function ($q) use ($customerDetail) {
      $q->where('customer_id', $customerDetail->id);
    })->with(['table', 'orders' => function ($q) use ($customerDetail) {
      $q->where('customer_id', $customerDetail->id)->with('orderItems.menuItem');
    }])->orderBy('reservation_date', 'desc')->orderBy('start_time', 'desc');

    if ($filter !== 'all') {
      $query->where('status', $filter);
    }

    $reservations = $query->paginate(10);

    return view('reservations.my', [
      'reservations' => $reservations,
      'filter' => $filter,
    ]);
  }
}
