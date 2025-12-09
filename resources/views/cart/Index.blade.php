<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        {{ session('error') }}
                    </div>
                @endif

                @if(count($cart) > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-4">
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $details)
                                @php
                                    $subtotal = $details['price'] * $details['quantity'];
                                    $total += $subtotal;
                                @endphp

                                <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg shadow-sm">
                                    <div class="w-20 h-20 flex-shrink-0 rounded overflow-hidden bg-gray-50">
                                        @if(!empty($details['photo']))
                                            <img src="{{ asset('storage/' . $details['photo']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 15h18v6H3z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $details['name'] }}</div>
                                                @if(isset($details['notes']))
                                                    <div class="text-sm text-gray-500 mt-1">{{ $details['notes'] }}</div>
                                                @endif
                                                <div class="text-sm text-gray-600 mt-2">Rp {{ number_format($details['price'], 0, ',', '.') }}</div>
                                            </div>

                                            <div class="text-right">
                                                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 text-sm hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex items-center gap-3">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" name="action" value="decrement" class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded text-gray-700 hover:bg-gray-200">-</button>
                                                <input type="text" name="quantity" value="{{ $details['quantity'] }}" class="w-12 text-center rounded border border-gray-200" />
                                                <button type="submit" name="action" value="increment" class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 rounded text-gray-700 hover:bg-gray-200">+</button>
                                            </form>

                                            <div class="ml-auto text-sm text-gray-500">SKU: {{ $id }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <aside class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Ringkasan Pesanan</h3>

                            <div class="flex justify-between mb-2">
                                <div class="text-sm text-gray-600">Subtotal</div>
                                <div class="font-medium">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            </div>

                            @php
                                // Jika ada biaya pengiriman atau pajak, tambahkan di sini nanti
                                $shipping = 0;
                                $tax = 0;
                                $grandTotal = $total + $shipping + $tax;
                            @endphp

                            <div class="flex justify-between text-sm text-gray-600">
                                <div>Biaya Pengiriman</div>
                                <div>Rp {{ number_format($shipping, 0, ',', '.') }}</div>
                            </div>

                            <div class="flex justify-between text-sm text-gray-600 mb-4">
                                <div>Pajak</div>
                                <div>Rp {{ number_format($tax, 0, ',', '.') }}</div>
                            </div>

                            <div class="border-t border-gray-200 pt-3 mt-3 flex items-center justify-between">
                                <div class="text-sm font-medium">Total</div>
                                <div class="text-xl font-semibold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
                            </div>

                            @php
                                $availablePoints = optional($customer)->points ?? 0;
                                $maxPointsByTotal = (int) floor($grandTotal / 10);
                                $maxRedeemablePoints = min($availablePoints, $maxPointsByTotal);
                            @endphp

                            <form action="{{ route('cart.checkout') }}" method="POST" class="mt-4">
                                @csrf
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Pesanan <span class="text-red-500">*</span></label>
                                <select name="type" id="type" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm mb-4">
                                    <option value="" disabled selected>-- Pilih Metode --</option>
                                    <option value="dine_in">🍽️ Makan di Tempat (Dine In)</option>
                                    <option value="takeaway">🥡 Bawa Pulang (Takeaway)</option>
                                    <option value="delivery">🛵 Pesan Antar (Delivery)</option>
                                </select>
                                @if($availablePoints > 0)
                                <div class="mb-3 p-3 border rounded bg-white">
                                    <div class="text-sm text-gray-600">Poin Anda: <span class="font-semibold text-indigo-600">{{ $availablePoints }}</span></div>
                                    <div class="text-xs text-gray-500">1 poin = Rp 10</div>

                                    <div class="mt-2">
                                        <label class="text-xs text-gray-700">Gunakan Poin (maks {{ $maxRedeemablePoints }})</label>
                                        <input type="number" name="points_to_redeem" min="0" max="{{ $maxRedeemablePoints }}" value="0" class="w-full mt-1 rounded border-gray-300 px-2 py-1 text-sm" />
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-center justify-between gap-3">
                                    <a href="{{ route('menu-items.index') }}" class="text-gray-600 hover:text-gray-900 underline text-sm">
                                        Tambah Menu Lain
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Buat Pesanan
                                    </button>
                                </div>
                            </form>
                        </aside>
                    </div>

                @else
                    <div class="text-center py-16">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keranjang Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500">Anda belum menambahkan menu apapun.</p>
                        <div class="mt-6">
                            <a href="{{ route('menu-items.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Lihat Menu
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>