<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CustomerDetail;
use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $search = $request->get('search');

        $customers = User::where('user_type', 'customer')
            ->with('customerDetail')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('customer.index', compact('customers'));
    }

    public function show(User $user)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Pastikan user adalah customer
        if ($user->user_type !== 'customer') {
            return redirect()->route('customer.index')
                ->with('error', 'User bukan customer');
        }

        $user->load(['customerDetail.loyaltyPoints' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('customer.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Pastikan user adalah customer
        if ($user->user_type !== 'customer') {
            return redirect()->route('customer.index')
                ->with('error', 'User bukan customer');
        }

        $user->load('customerDetail');

        return view('customer.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            DB::commit();
            return redirect()->route('customer.show', $user)
                ->with('success', 'Data customer berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate customer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Pastikan user adalah customer
        if ($user->user_type !== 'customer') {
            return redirect()->route('customer.index')
                ->with('error', 'User bukan customer');
        }

        DB::beginTransaction();
        try {
            // Hapus customer detail (loyalty points cascade otomatis)
            $user->customerDetail()->delete();

            // Hapus user
            $user->delete();

            DB::commit();
            return redirect()->route('customer.index')
                ->with('success', 'Customer berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus customer: ' . $e->getMessage());
        }
    }

    // Adjust points (tambah/kurangi manual)
    public function adjustPoints(Request $request, User $user)
    {
        // Hanya admin
        if (Auth::user()->user_type !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'points' => ['required', 'integer', 'not_in:0'],
            'type' => ['required', 'in:bonus,adjustment,refund,compensation,loyalty_reward,referral,promotion,cashback,expired,penalty'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $customerDetail = $user->customerDetail;

            if ($validated['points'] > 0) {
                // Tambah poin
                $customerDetail->addPoints(
                    $validated['points'],
                    $validated['type'],
                    null,
                    $validated['description']
                );
            } else {
                // Kurangi poin
                $customerDetail->deductPoints(
                    abs($validated['points']),
                    $validated['type'],
                    null,
                    $validated['description']
                );
            }

            DB::commit();
            return redirect()->route('customer.show', $user)
                ->with('success', 'Poin berhasil disesuaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyesuaikan poin: ' . $e->getMessage())
                ->withInput();
        }
    }
}
