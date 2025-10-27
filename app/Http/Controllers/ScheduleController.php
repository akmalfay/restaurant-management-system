<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    if ($user->user_type === 'customer') {
      abort(403, 'Unauthorized');
    }

    $today = Carbon::now();
    $currentWeekStart = $today->copy()->startOfWeek(Carbon::MONDAY);
    $currentWeekEnd = $currentWeekStart->copy()->endOfWeek(Carbon::SUNDAY);
    $nextWeekStart = $currentWeekStart->copy()->addWeek();
    $nextWeekEnd = $nextWeekStart->copy()->endOfWeek(Carbon::SUNDAY);

    // Fetch schedules untuk 2 minggu (current + next)
    $schedules = DB::table('schedules')
      ->join('staff_details', 'schedules.staff_id', '=', 'staff_details.id')
      ->join('users', 'staff_details.user_id', '=', 'users.id')
      ->whereBetween('schedule_date', [$currentWeekStart->toDateString(), $nextWeekEnd->toDateString()])
      ->select(
        'schedules.id as schedule_id',
        'schedules.schedule_date',
        'schedules.shift',
        'schedules.is_holiday',
        'users.id as user_id',
        'users.name as user_name',
        'staff_details.role as role'
      )
      ->orderBy('schedule_date')
      ->orderBy('shift')
      ->orderBy('user_name')
      ->get();

    // Group by date and shift
    $assignmentMap = [];
    foreach ($schedules as $s) {
      $assignmentMap[$s->schedule_date][$s->shift][] = [
        'schedule_id' => $s->schedule_id,
        'user_id' => $s->user_id,
        'name' => $s->user_name,
        'role' => $s->role,
        'is_holiday' => $s->is_holiday,
      ];
    }

    // Build week arrays
    $currentWeek = [];
    $nextWeek = [];

    for ($i = 0; $i < 7; $i++) {
      $date = $currentWeekStart->copy()->addDays($i);
      $currentWeek[] = [
        'date' => $date,
        'dateStr' => $date->toDateString(),
        'dayName' => $date->format('l'),
        'morning' => $assignmentMap[$date->toDateString()]['morning'] ?? [],
        'night' => $assignmentMap[$date->toDateString()]['night'] ?? [],
        'is_holiday' => !empty($assignmentMap[$date->toDateString()]['morning'][0]['is_holiday'] ?? false)
          || !empty($assignmentMap[$date->toDateString()]['night'][0]['is_holiday'] ?? false),
      ];

      $nextDate = $nextWeekStart->copy()->addDays($i);
      $nextWeek[] = [
        'date' => $nextDate,
        'dateStr' => $nextDate->toDateString(),
        'dayName' => $nextDate->format('l'),
        'morning' => $assignmentMap[$nextDate->toDateString()]['morning'] ?? [],
        'night' => $assignmentMap[$nextDate->toDateString()]['night'] ?? [],
        'is_holiday' => !empty($assignmentMap[$nextDate->toDateString()]['morning'][0]['is_holiday'] ?? false)
          || !empty($assignmentMap[$nextDate->toDateString()]['night'][0]['is_holiday'] ?? false),
      ];
    }

    // Staff list untuk assign
    $allStaff = collect();
    if ($user->user_type === 'admin') {
      $allStaff = DB::table('staff_details')
        ->join('users', 'staff_details.user_id', '=', 'users.id')
        ->where('users.user_type', 'staff')
        ->where('staff_details.is_active', true)
        ->select(
          'staff_details.id as staff_id',
          'users.id as user_id',
          'users.name',
          'staff_details.role'
        )
        ->orderBy('users.name')
        ->get();
    }

    return view('schedules.index', [
      'currentWeek' => $currentWeek,
      'nextWeek' => $nextWeek,
      'allStaff' => $allStaff,
      'canEdit' => $user->user_type === 'admin',
      'currentWeekLabel' => $currentWeekStart->format('M d') . ' - ' . $currentWeekEnd->format('M d, Y'),
      'nextWeekLabel' => $nextWeekStart->format('M d') . ' - ' . $nextWeekEnd->format('M d, Y'),
    ]);
  }

  public function assign(Request $request)
  {
    $user = Auth::user();
    if ($user->user_type !== 'admin') {
      abort(403, 'Unauthorized');
    }

    $validated = $request->validate([
      'staff_id' => ['required', 'exists:staff_details,id'],
      'schedule_date' => ['required', 'date'],
      'shift' => ['required', 'in:morning,night'],
    ]);

    Schedule::firstOrCreate([
      'staff_id' => $validated['staff_id'],
      'schedule_date' => $validated['schedule_date'],
      'shift' => $validated['shift'],
    ]);

    return back()->with('status', 'Shift ditambahkan.');
  }

  public function toggleHoliday(Request $request)
  {
    $user = Auth::user();
    if ($user->user_type !== 'admin') {
      abort(403, 'Unauthorized');
    }

    $validated = $request->validate([
      'schedule_date' => ['required', 'date'],
    ]);

    // Toggle semua schedule di tanggal tersebut
    $schedules = Schedule::where('schedule_date', $validated['schedule_date'])->get();

    if ($schedules->isEmpty()) {
      // Jika belum ada schedule, buat holiday placeholder
      Schedule::create([
        'staff_id' => DB::table('staff_details')->first()->id ?? 1,
        'schedule_date' => $validated['schedule_date'],
        'shift' => 'morning',
        'is_holiday' => true,
      ]);
    } else {
      $newStatus = !$schedules->first()->is_holiday;
      Schedule::where('schedule_date', $validated['schedule_date'])
        ->update(['is_holiday' => $newStatus]);
    }

    return back()->with('status', 'Status libur diubah.');
  }

  public function destroy(Schedule $schedule)
  {
    $user = Auth::user();
    if ($user->user_type !== 'admin') {
      abort(403, 'Unauthorized');
    }

    $schedule->delete();
    return back()->with('status', 'Shift dihapus.');
  }

  public function history(Request $request)
  {
    $user = Auth::user();
    if ($user->user_type === 'customer') {
      abort(403, 'Unauthorized');
    }

    $today = Carbon::now();

    // Get tahun dan bulan dari request atau default ke bulan lalu
    $selectedYear = $request->query('year', $today->copy()->subMonth()->year);
    $selectedMonth = $request->query('month', $today->copy()->subMonth()->month);

    // Validasi input
    $selectedYear = (int) $selectedYear;
    $selectedMonth = (int) $selectedMonth;

    if ($selectedMonth < 1 || $selectedMonth > 12) {
      $selectedMonth = $today->month;
    }

    // Generate available years (dari 2 tahun lalu sampai tahun ini)
    $availableYears = [];
    for ($y = $today->year; $y >= $today->year - 2; $y--) {
      $availableYears[] = $y;
    }

    // Generate available months
    $availableMonths = [
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember'
    ];

    // Create carbon instance untuk bulan yang dipilih
    $selectedDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
    $startOfMonth = $selectedDate->copy()->startOfMonth();
    $endOfMonth = $selectedDate->copy()->endOfMonth();

    // Fetch schedules untuk bulan yang dipilih
    $schedules = DB::table('schedules')
      ->join('staff_details', 'schedules.staff_id', '=', 'staff_details.id')
      ->join('users', 'staff_details.user_id', '=', 'users.id')
      ->whereBetween('schedule_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
      ->select(
        'schedules.schedule_date',
        'schedules.shift',
        'users.id as user_id',
        'users.name as user_name',
        'staff_details.role as role'
      )
      ->orderBy('schedule_date')
      ->orderBy('shift')
      ->get();

    // Group by date
    $assignmentMap = [];
    foreach ($schedules as $s) {
      $assignmentMap[$s->schedule_date][$s->shift][] = [
        'user_id' => $s->user_id,
        'name' => $s->user_name,
        'role' => $s->role,
      ];
    }

    // Build calendar grid (start from Monday before first day)
    $gridStart = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
    $gridEnd = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

    $monthData = [];
    $currentDate = $gridStart->copy();

    while ($currentDate->lte($gridEnd)) {
      $dateStr = $currentDate->toDateString();
      $inMonth = $currentDate->between($startOfMonth, $endOfMonth);
      $isSunday = $currentDate->isSunday();

      $monthData[] = [
        'date' => $currentDate->copy(),
        'dateStr' => $dateStr,
        'dayName' => $currentDate->format('D'),
        'dayNumber' => $currentDate->format('j'),
        'inMonth' => $inMonth,
        'isSunday' => $isSunday,
        'morning' => $assignmentMap[$dateStr]['morning'] ?? [],
        'night' => $assignmentMap[$dateStr]['night'] ?? [],
      ];

      $currentDate->addDay();
    }

    // Calculate statistics
    $totalShifts = $schedules->count();
    $chefShifts = $schedules->where('role', 'chef')->count();
    $waiterShifts = $schedules->where('role', 'waiter')->count();
    $cashierShifts = $schedules->where('role', 'cashier')->count();

    return view('schedules.history', [
      'availableYears' => $availableYears,
      'availableMonths' => $availableMonths,
      'selectedYear' => $selectedYear,
      'selectedMonth' => $selectedMonth,
      'monthLabel' => $availableMonths[$selectedMonth] . ' ' . $selectedYear,
      'monthData' => $monthData,
      'totalShifts' => $totalShifts,
      'chefShifts' => $chefShifts,
      'waiterShifts' => $waiterShifts,
      'cashierShifts' => $cashierShifts,
    ]);
  }
  // ...existing code...
}
