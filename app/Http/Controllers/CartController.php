<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ... (method addToCart yang sudah ada tetap dibiarkan) ...

    public function addToCart(Request $request, $id)
    {
        // ... (kode lama Anda) ...
        $menu = MenuItem::findOrFail($id);
        $cart = session()->get('cart', []);
        
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $cart[$id] = [
                "name" => $menu->name, // Pastikan ini sesuai kolom di DB (name vs nama_menu)
                "quantity" => $request->quantity,
                "price" => $menu->price, // Pastikan ini sesuai kolom di DB (price vs harga)
                "photo" => $menu->image // Pastikan ini sesuai kolom di DB (image vs gambar)
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menampilkan halaman keranjang belanja.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $user = Auth::user();
        $customer = $user ? $user->customerDetail : null;
        return view('cart.index', compact('cart', 'customer'));
    }

    /**
     * Update quantity for an item in the cart.
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return redirect()->back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        $action = $request->input('action');

        if ($action === 'increment') {
            $cart[$id]['quantity'] = intval($cart[$id]['quantity']) + 1;
        } elseif ($action === 'decrement') {
            $newQty = intval($cart[$id]['quantity']) - 1;
            // jika akan jadi 0 maka tetap 1 (atau Anda bisa menghapus item)
            $cart[$id]['quantity'] = max(1, $newQty);
        } else {
            // direct set via quantity input
            $quantity = intval($request->input('quantity', $cart[$id]['quantity']));
            if ($quantity < 1) {
                return redirect()->back()->with('error', 'Jumlah harus minimal 1.');
            }
            $cart[$id]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Jumlah keranjang diperbarui.');
    }

    /**
     * Memproses checkout dari keranjang menjadi Order.
     */
    public function checkout(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'type' => 'required|in:dine_in,takeaway,delivery',
            'points_to_redeem' => 'nullable|integer|min:0',
        ]);

        $cart = session()->get('cart', []);

        // Cek jika keranjang kosong
        if(empty($cart)) {
            return redirect()->route('menu-items.index')->with('error', 'Keranjang Anda kosong.');
        }

        // 2. Hitung Total Harga
        $totalAmount = 0;
        foreach($cart as $id => $details) {
            $totalAmount += $details['price'] * $details['quantity'];
        }

        // 3. Database Transaction
        DB::beginTransaction();

        try {
            $user = Auth::user();
            
            // Asumsi: User customer memiliki relasi customerDetail
            // Jika user adalah admin/staff, mungkin perlu penanganan khusus atau validasi
            $customer = $user->customerDetail ?? null;
            $customerId = $customer ? $customer->id : null;

            if (!$customerId && $user->user_type === 'customer') {
                 // Fallback atau error jika data customer tidak lengkap
                 throw new \Exception('Data profil customer tidak ditemukan.');
            }
            
            // Buat Order Utama
            $order = Order::create([
                'customer_id' => $customerId, 
                'type' => $request->type,     // dine_in, takeaway, delivery
                'status' => 'pending',        // Default status awal
                'total' => $totalAmount,
                'order_time' => now(),
                'created_at' => now(),
            ]);

            // Handle loyalty points redemption (1 point = 10 rupiah)
            $pointsToRedeem = intval($request->input('points_to_redeem', 0));
            if ($pointsToRedeem > 0) {
                if (! $customer) {
                    throw new \Exception('Customer data not found for redemption.');
                }

                $available = intval($customer->points ?? 0);
                $maxByTotal = (int) floor($totalAmount / 10);
                $maxRedeemable = min($available, $maxByTotal);

                if ($pointsToRedeem > $maxRedeemable) {
                    throw new \Exception('Jumlah poin untuk ditukarkan melebihi yang diperbolehkan.');
                }

                // Deduct points from customer (this will create a LoyaltyPoint record)
                $customer->deductPoints($pointsToRedeem, 'redeem', $order->id, "Redeemed {$pointsToRedeem} points for order #{$order->id}");

                // Update order total and store points_redeemed
                $discount = $pointsToRedeem * 10; // 10 rupiah per point
                $newTotal = max(0, $totalAmount - $discount);
                $order->update(['total' => $newTotal, 'points_redeemed' => $pointsToRedeem]);
                // reflect change for any further logic
                $totalAmount = $newTotal;
            }

            // Simpan setiap item di keranjang ke tabel order_items
            foreach($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'], // Harga saat transaksi terjadi
                ]);
            }

            // 4. Hapus Session Keranjang setelah sukses
            session()->forget('cart');

            DB::commit();

            // Redirect ke halaman menu items dengan pesan sukses
            return redirect()->route('menu-items.index')->with('success', 'Pesanan berhasil dibuat! Mohon tunggu konfirmasi.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);

            if (count($cart) > 0) {
                session()->put('cart', $cart);
            } else {
                session()->forget('cart');
            }

            return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Item tidak ditemukan di keranjang.');
    }
}