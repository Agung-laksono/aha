<!-- Modal Pembelian -->
<div id="modal-pembelian" tabindex="-1" aria-hidden="true" x-show="$wire.showPurchaseModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">

    <div
        class="relative w-full max-w-5xl max-h-[90vh] overflow-hidden bg-white rounded-2xl shadow-2xl dark:bg-gray-800 flex flex-col">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Transaksi
                        Pembelian Baru</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Catat stok masuk dari vendor</p>
                </div>
            </div>
            <button type="button" wire:click="$set('showPurchaseModal', false)"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-6 overflow-y-auto flex-grow space-y-6 custom-scrollbar">
            <!-- Row 1: Vendor & Meta -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vendor Selection -->
                <div class="md:col-span-2 relative">
                    <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Informasi
                        Vendor</label>
                    <div class="relative">
                        @if($selectedVendor)
                            <div
                                class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800 rounded-xl transition-all">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold">
                                        {{ substr($selectedVendor['nama'], 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $selectedVendor['nama'] }}</p>
                                        <p class="text-[10px] text-primary-600 dark:text-primary-400 font-bold uppercase">
                                            Vendor Terpilih</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="$set('selectedVendor', null)"
                                    class="text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <div class="relative flex-grow">
                                    <input type="text" wire:model.live="searchVendor" x-on:focus="$wire.showVendorPicker = true"
                                        class="w-full p-3 pl-10 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none bg-gray-50 dark:bg-gray-700 dark:border-gray-600"
                                        placeholder="Cari atau pilih vendor...">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>

                                    <!-- Dropdown Vendor Picker -->
                                    <div x-show="$wire.showVendorPicker" x-on:click.outside="$wire.showVendorPicker = false"
                                        class="absolute z-[60] w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl max-h-60 overflow-y-auto"
                                        style="display: none;">
                                        @forelse($this->vendors as $v)
                                            <button type="button" wire:click="selectVendor({{ $v->id }}, '{{ $v->nama }}')"
                                                class="w-full flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-left border-b last:border-0 dark:border-gray-700">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-xs font-bold">
                                                    {{ substr($v->nama, 0, 1) }}</div>
                                                <span class="text-sm font-medium dark:text-white">{{ $v->nama }}</span>
                                            </button>
                                        @empty
                                            <div class="p-4 text-center text-gray-500 text-sm italic">Vendor tidak ditemukan.</div>
                                        @endforelse
                                    </div>
                                </div>
                                <button type="button" wire:click="$set('showModalVendor', true)"
                                    class="bg-blue-50 text-blue-600 p-3 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm"
                                    title="Tambah Vendor Baru">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    @error('selectedVendor') <span
                    class="text-[10px] text-red-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Meta Info (Nota & Tanggal) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Nomor
                            Nota</label>
                        <input type="text" wire:model="nomorNota"
                            class="w-full p-3 text-sm border border-gray-200 rounded-xl bg-gray-50 dark:bg-gray-700 dark:border-gray-600 font-mono font-bold">
                    </div>
                    <div>
                        <label
                            class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">Tanggal</label>
                        <input type="date" wire:model="tanggalPembelian"
                            class="w-full p-3 text-sm border border-gray-200 rounded-xl bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-700">

            <!-- Row 2: Item Search & Cart -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Item
                        Pembelian</label>
                    <div class="relative w-full max-w-sm">
                        <input type="text" wire:model.live.debounce.300ms="searchBarangPurchase"
                            class="w-full p-2 pl-8 text-xs border border-gray-200 rounded-lg focus:ring-primary-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Ketik nama barang untuk menambah...">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>

                        <!-- Search Results -->
                        @if($searchBarangResults->isNotEmpty())
                            <div
                                class="absolute z-[60] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden">
                                @foreach($searchBarangResults as $res)
                                    <button type="button" wire:click="addToPurchaseCart({{ $res->id }})"
                                        class="w-full flex items-center gap-3 p-2 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition text-left border-b last:border-0 dark:border-gray-700">
                                        @php
                                            $img = $res->gambarBarangs->where('gambar_utama', true)->first() ?? $res->gambarBarangs->first();
                                            $path = $img ? (str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path)) : 'https://ui-avatars.com/api/?name=' . $res->nama;
                                        @endphp
                                        <img src="{{ $path }}" class="w-8 h-8 rounded object-cover shadow-sm">
                                        <div>
                                            <p class="text-xs font-bold dark:text-white">{{ $res->nama }}</p>
                                            <p class="text-[9px] text-gray-400 font-mono">{{ $res->sku }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cart Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50 dark:bg-gray-700/50 text-[10px] uppercase font-black tracking-widest text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3">Harga Beli</th>
                                <th class="px-4 py-3">Gudang Tujuan</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($purchaseCart as $index => $item)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-4">
                                        <p class="font-bold text-gray-900 dark:text-white leading-tight">{{ $item['nama'] }}
                                        </p>
                                        <p class="text-[10px] font-mono text-gray-400">{{ $item['sku'] }}</p>
                                    </td>
                                    <td class="px-4 py-4 w-24">
                                        <input type="number" wire:model.live="purchaseCart.{{ $index }}.qty" min="1"
                                            class="w-full p-2 text-center text-sm font-bold border border-gray-200 rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
                                    </td>
                                    <td class="px-4 py-4 w-40">
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-primary-600">Rp</span>
                                            <input type="number" wire:model.live="purchaseCart.{{ $index }}.harga"
                                                class="w-full p-2 pl-8 text-sm font-bold border border-gray-200 rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex gap-1 items-center">
                                            <select wire:model.live="purchaseCart.{{ $index }}.gudang_id"
                                                class="flex-grow text-xs border border-gray-200 rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600">
                                                @foreach($gudangs as $g)
                                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" wire:click="$set('showModalGudang', true)"
                                                class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition shadow-sm"
                                                title="Tambah Gudang Baru">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <select wire:model.live="purchaseCart.{{ $index }}.status"
                                            class="text-[10px] font-bold uppercase rounded-lg p-2 border-0 focus:ring-0
                                            {{ $item['status'] === 'Received' ? 'bg-green-100 text-green-700' : ($item['status'] === 'PO' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                            <option value="Received">DITERIMA</option>
                                            <option value="PO">PRE-ORDER</option>
                                            <option value="Pending">PENDING</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-4 text-right font-black text-gray-900 dark:text-white">
                                        Rp{{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <button type="button" wire:click="removeFromPurchaseCart({{ $index }})"
                                            class="text-gray-300 hover:text-red-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2 opacity-30">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-sm font-bold uppercase tracking-widest">Keranjang Kosong</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(collect($purchaseCart)->isNotEmpty())
                            <tfoot class="bg-gray-50/50 dark:bg-gray-700/30">
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-4 text-right text-xs font-black uppercase tracking-widest text-gray-400">
                                        Total Pembelian</td>
                                    <td class="px-4 py-4 text-right text-lg font-black text-primary-600">
                                        Rp{{ number_format(collect($purchaseCart)->sum(fn($i) => $i['qty'] * $i['harga']), 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="flex items-center p-6 space-x-3 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            @if(count($purchaseCart) > 0)
            <button type="button" wire:click="clearPurchaseCart"
                class="text-red-600 bg-red-50 hover:bg-red-100 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-xl border border-red-200 text-xs font-bold px-5 py-2.5 transition">
                BERSIHKAN
            </button>
            @endif
            <div class="flex-grow"></div>
            <button type="button" wire:click="$set('showPurchaseModal', false)"
                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-xl border border-gray-200 text-sm font-bold px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600">
                BATAL
            </button>
            <button type="button" wire:click="savePurchase"
                class="sm:min-w-[200px] text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-black rounded-xl text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 uppercase tracking-widest transition-all active:scale-95">
                SIMPAN TRANSAKSI
            </button>
        </div>
    </div>
</div>