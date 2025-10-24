<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoyaltyPointController extends Controller
{
    public function edit(LoyaltyPoint $loyaltyPoint)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $loyaltyPoint->load('customer.user');

        return view('loyalty-point.edit', compact('loyaltyPoint'));
    }

    public function update(Request $request, LoyaltyPoint $loyaltyPoint)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'points' => ['required', 'integer', 'not_in:0'],
            'type' => ['required', 'in:earn,redeem,bonus,refund,adjustment,compensation,loyalty_reward,referral,promotion,cashback,expired,penalty'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $oldPoints = $loyaltyPoint->points;
            $newPoints = $validated['points'];
            $diff = $newPoints - $oldPoints;

            // Update loyalty point record
            $loyaltyPoint->update([
                'points' => $newPoints,
                'type' => $validated['type'],
                'description' => $validated['description'],
            ]);

            // Adjust customer total points
            $customerDetail = $loyaltyPoint->customer;
            if ($diff > 0) {
                $customerDetail->increment('points', $diff);
            } elseif ($diff < 0) {
                $customerDetail->decrement('points', abs($diff));
            }

            DB::commit();

            return redirect()->route('customer.show', $customerDetail->user_id)
                ->with('success', 'Riwayat poin berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate riwayat poin: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(LoyaltyPoint $loyaltyPoint)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        DB::beginTransaction();
        try {
            $customerDetail = $loyaltyPoint->customer;
            $points = $loyaltyPoint->points;

            // Kurangi/tambah total poin customer
            if ($points > 0) {
                $customerDetail->decrement('points', $points);
            } else {
                $customerDetail->increment('points', abs($points));
            }

            // Hapus record
            $loyaltyPoint->delete();

            DB::commit();

            return redirect()->route('customer.show', $customerDetail->user_id)
                ->with('success', 'Riwayat poin berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus riwayat poin: ' . $e->getMessage());
        }
    }
}
