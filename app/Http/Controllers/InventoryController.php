<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{


    public function store(Request $request)
    {
        // Hanya admin dan staff
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:inventories,name'],
            'stock' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:kg,liter,pcs,pack,bottle'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        DB::beginTransaction();
        try {
            Inventory::create($validated);

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Item inventory berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan item: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function edit(Inventory $inventory)
    {
        // Hanya admin dan staff
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        return view('inventory.edit', compact('inventory'));
    }

    public function index(Request $request)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        $search = $request->get('search');

        $inventories = Inventory::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->appends(['search' => $search]);

        // Stats untuk alert - hitung dari batch
        $lowStock = Inventory::whereColumn('stock', '<=', 'min_stock')->count();

        // Expired: cek dari batch yang masih ada stock
        $expired = Inventory::whereHas('batches', function ($q) {
            $q->where('quantity', '>', 0)
                ->where('expires_at', '<', now());
        })->count();

        // Near expiry: 7 hari ke depan
        $nearExpiry = Inventory::whereHas('batches', function ($q) {
            $q->where('quantity', '>', 0)
                ->whereBetween('expires_at', [now(), now()->addDays(7)]);
        })->count();

        return view('inventory.index', compact('inventories', 'lowStock', 'expired', 'nearExpiry', 'search'));
    }

    public function show(Inventory $inventory)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        // Load batches yang masih ada stock dan movements
        $inventory->load([
            'batches' => function ($query) {
                $query->where('quantity', '>', 0) // Hanya batch yang masih ada
                    ->orderBy('expires_at', 'asc');
            },
            'stockMovements' => function ($query) {
                $query->orderBy('created_at', 'desc')
                    ->with('batch');
            }
        ]);

        return view('inventory.show', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:inventories,name,' . $inventory->id],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'in:kg,liter,pcs,pack,bottle'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            // Jangan update expires_at manual, akan auto dari batch
        ]);

        DB::beginTransaction();
        try {
            $inventory->update($validated);

            // Update total stock dari batch
            $totalStock = $inventory->batches()->where('quantity', '>', 0)->sum('quantity');
            $inventory->update(['stock' => $totalStock]);

            // Update expires_at ke batch terdekat
            $nextBatch = $inventory->batches()
                ->where('quantity', '>', 0)
                ->orderBy('expires_at', 'asc')
                ->first();

            if ($nextBatch) {
                $inventory->update(['expires_at' => $nextBatch->expires_at]);
            }

            DB::commit();
            return redirect()->route('inventory.show', $inventory)
                ->with('success', 'Item inventory berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Inventory $inventory)
    {
        // Hanya admin dan staff
        if (!in_array(Auth::user()->user_type, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access');
        }

        DB::beginTransaction();
        try {
            $inventory->delete();

            DB::commit();
            return redirect()->route('inventory.index')
                ->with('success', 'Item inventory berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus item: ' . $e->getMessage());
        }
    }
}
