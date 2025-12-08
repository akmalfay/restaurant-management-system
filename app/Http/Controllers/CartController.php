<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem; // Pastikan model Menu di-import

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $menu = MenuItem::findOrFail($id);
        
        // Ambil keranjang dari session, atau buat array baru jika kosong
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            // Jika belum ada, masukkan data produk baru
            $cart[$id] = [
                "name" => $menu->nama_menu, // Sesuaikan dengan nama kolom di DB kamu
                "quantity" => $request->quantity,
                "price" => $menu->harga,
                "photo" => $menu->gambar
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }
}