<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Inventory;
use App\Models\InventoryBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function store(Request $request, Inventory $inventory)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) abort(403);

        $validated = $request->validate([
            'type' => ['required', 'in:purchase,usage,waste,adjustment'],
            'quantity' => ['required', 'numeric', 'not_in:0'],
            'expires_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        return DB::transaction(function () use ($inventory, $validated) {
            $type = $validated['type'];
            $qty  = (float)$validated['quantity'];

            if ($type === 'purchase') {
                // Buat batch baru
                $batch = InventoryBatch::create([
                    'inventory_id' => $inventory->id,
                    'quantity'     => $qty,
                    'expires_at'   => $validated['expires_at'] ?? null,
                    'cost_per_unit' => $inventory->cost_per_unit,
                ]);

                StockMovement::create([
                    'inventory_id' => $inventory->id,
                    'batch_id'     => $batch->id,
                    'type'         => 'purchase',
                    'quantity'     => $qty,
                    'notes'        => $validated['notes'] ?? 'Pembelian batch baru',
                ]);
            } elseif (in_array($type, ['usage', 'waste'])) {
                // FEFO: kurangi dari batch terdekat expired
                $remain = abs($qty);
                $batches = $inventory->batches()
                    ->where('quantity', '>', 0)
                    ->orderByRaw('expires_at IS NULL')
                    ->orderBy('expires_at')
                    ->lockForUpdate()
                    ->get();

                foreach ($batches as $batch) {
                    if ($remain <= 0) break;

                    $take = min($batch->quantity, $remain);
                    $batch->decrement('quantity', $take);
                    $remain -= $take;

                    StockMovement::create([
                        'inventory_id' => $inventory->id,
                        'batch_id'     => $batch->id,
                        'type'         => $type,
                        'quantity'     => -$take,
                        'notes'        => $validated['notes'] ?? ucfirst($type) . ' dari batch',
                    ]);
                }

                if ($remain > 0) {
                    throw new \RuntimeException('Stok tidak mencukupi. Tersisa: ' . $remain . ' ' . $inventory->unit);
                }
            } else { // adjustment
                $delta = (float)$qty;
                if ($delta > 0) {
                    // Tambah stock
                    $batch = InventoryBatch::create([
                        'inventory_id' => $inventory->id,
                        'quantity'     => $delta,
                        'expires_at'   => null,
                        'cost_per_unit' => $inventory->cost_per_unit,
                    ]);

                    StockMovement::create([
                        'inventory_id' => $inventory->id,
                        'batch_id'     => $batch->id,
                        'type'         => 'adjustment',
                        'quantity'     => $delta,
                        'notes'        => $validated['notes'] ?? 'Adjustment penambahan',
                    ]);
                } else {
                    // Kurangi stock
                    $remain = abs($delta);
                    $batches = $inventory->batches()
                        ->where('quantity', '>', 0)
                        ->orderByRaw('expires_at IS NULL')
                        ->orderBy('expires_at')
                        ->lockForUpdate()
                        ->get();

                    foreach ($batches as $batch) {
                        if ($remain <= 0) break;

                        $take = min($batch->quantity, $remain);
                        $batch->decrement('quantity', $take);
                        $remain -= $take;

                        StockMovement::create([
                            'inventory_id' => $inventory->id,
                            'batch_id'     => $batch->id,
                            'type'         => 'adjustment',
                            'quantity'     => -$take,
                            'notes'        => $validated['notes'] ?? 'Adjustment pengurangan',
                        ]);
                    }

                    if ($remain > 0) {
                        throw new \RuntimeException('Adjustment melebihi stok tersedia');
                    }
                }
            }

            // UPDATE INVENTORY STOCK & EXPIRES_AT
            $this->updateInventoryFromBatches($inventory);

            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Pergerakan stok berhasil disimpan');
        });
    }

    public function update(Request $request, StockMovement $stockMovement)
    {
        // Hanya admin dan staff
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'type' => ['required', 'in:purchase,usage,waste,adjustment'],
            'quantity' => ['required', 'numeric', 'not_in:0'],
        ]);

        DB::beginTransaction();
        try {
            $inventory = $stockMovement->inventory;
            $oldQuantity = (float) $stockMovement->quantity;
            $newQuantity = (float) $validated['quantity'];
            $oldType = $stockMovement->type;
            $newType = $validated['type'];

            // Kembalikan stock lama
            if (in_array($oldType, ['purchase']) || ($oldType === 'adjustment' && $oldQuantity > 0)) {
                $inventory->decrement('stock', abs($oldQuantity));
            } else {
                $inventory->increment('stock', abs($oldQuantity));
            }

            // Terapkan stock baru
            if (in_array($newType, ['purchase']) || ($newType === 'adjustment' && $newQuantity > 0)) {
                $inventory->increment('stock', abs($newQuantity));
            } else {
                $inventory->decrement('stock', abs($newQuantity));
            }

            // Update record
            $stockMovement->update([
                'type' => $newType,
                'quantity' => $newQuantity,
            ]);

            DB::commit();
            return redirect()->route('inventory.show', $inventory)
                ->with('success', 'Riwayat pergerakan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(StockMovement $stockMovement)
    {
        // Hanya admin dan staff
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        DB::beginTransaction();
        try {
            $inventory = $stockMovement->inventory;
            $batch = $stockMovement->batch;
            $quantity = (float) $stockMovement->quantity;

            // Kembalikan stock ke batch
            if ($quantity > 0) {
                // Purchase/adjustment+ → kurangi batch
                $batch->decrement('quantity', $quantity);
            } else {
                // Usage/waste/adjustment- → tambah batch
                $batch->increment('quantity', abs($quantity));
            }

            $stockMovement->delete();

            // Update inventory
            $this->updateInventoryFromBatches($inventory);

            DB::commit();
            return redirect()
                ->route('inventory.show', $inventory)
                ->with('success', 'Riwayat pergerakan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Update inventory stock dan expires_at dari batch
     */
    private function updateInventoryFromBatches(Inventory $inventory)
    {
        // Hitung total stock dari batch yang > 0
        $totalStock = $inventory->batches()
            ->where('quantity', '>', 0)
            ->sum('quantity');

        // Ambil expires_at terdekat
        $nextBatch = $inventory->batches()
            ->where('quantity', '>', 0)
            ->orderBy('expires_at', 'asc')
            ->first();

        $inventory->update([
            'stock' => $totalStock,
            'expires_at' => $nextBatch?->expires_at,
        ]);
    }
}
