<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{
  private function canManage(): bool
  {
    $u = Auth::user();
    if ($u->user_type === 'admin') return true;
    return $u->user_type === 'staff' && optional($u->staffDetail)->role === 'cashier';
  }

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
}
