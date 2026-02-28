<!-- Modal Pembelian -->
<div id="modal-pembelian" tabindex="-1" aria-hidden="true" x-show="$wire.showPurchaseModal"
    class="fixed inset-0 z-[120] overflow-hidden bg-white dark:bg-gray-900 flex items-center justify-center p-0"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" style="display: none;">

    <div
        class="relative w-full h-full max-w-none max-h-none overflow-hidden bg-white shadow-none dark:bg-gray-800 flex flex-col border-none">
        <!-- Modal header -->
        <div
            class="relative px-6 py-2 border-b dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-between z-[140] shadow-md">

            <!-- LEFT: Search Area -->
            <div class="w-1/3 flex items-center">
                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" fill="none"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchBarangPurchase" autocomplete="off"
                        class="w-full p-2.5 pl-10 text-sm font-bold border-2 border-gray-100 rounded-xl focus:ring-0 focus:border-primary-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all shadow-sm placeholder-gray-300"
                        placeholder="Cari nama atau SKU produk...">

                    <!-- Search Results Dropdown -->
                    @if($searchBarangResults->isNotEmpty())
                        <div
                            class="absolute z-[150] w-[150%] xl:w-[200%] mt-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden divide-y divide-gray-50 dark:divide-gray-700 animate-in fade-in slide-in-from-top-2 duration-200 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            @foreach($searchBarangResults as $res)
                                <button type="button" wire:click="addToPurchaseCart({{ $res->id }})"
                                    class="w-full flex items-center gap-4 p-3 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition text-left group">
                                    @php
                                        $img = $res->gambarBarangs->where('gambar_utama', true)->first() ?? $res->gambarBarangs->first();
                                        $path = $img ? (str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path)) : 'https://ui-avatars.com/api/?name=' . urlencode($res->nama) . '&color=random';
                                    @endphp
                                    <div class="relative h-12 w-12 flex-shrink-0">
                                        <img src="{{ $path }}" alt="{{ $res->nama }}"
                                            class="h-full w-full rounded-xl object-cover shadow-sm border border-gray-100 dark:border-gray-700">
                                        <div
                                            class="absolute -top-1.5 -right-1.5 bg-primary-600 text-white p-1 rounded-full border-2 border-white dark:border-gray-800 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity scale-75 group-hover:scale-100 duration-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight truncate">
                                            {{ $res->nama }}
                                        </p>
                                        <p
                                            class="text-[10px] text-gray-400 font-mono tracking-widest font-bold mt-0.5 truncate">
                                            {{ $res->sku }}
                                        </p>
                                    </div>
                                    <div class="text-right whitespace-nowrap pl-2">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Tersedia</p>
                                        <p class="text-sm font-black text-primary-600">{{ $res->stoks->sum('jumlah') }} <span
                                                class="text-[10px]">{{ $res->satuan->nama ?? 'Unit' }}</span></p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- CENTER: Title (Absolute Centered) -->
            <div
                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center text-center pointer-events-none w-1/3">
                <div class="flex items-center gap-2 mb-1">
                    <div class="p-1.5 bg-primary-100 rounded-lg dark:bg-primary-900/30 text-primary-600 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    @php $totalQtyCart = collect($purchaseCart)->sum('qty'); @endphp
                    <h3
                        class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight flex items-center gap-2">
                        Pembelian Baru
                        @if($totalQtyCart > 0)
                            <span
                                class="text-primary-600 bg-primary-50 dark:bg-primary-900/30 dark:text-primary-400 rounded-full px-2.5 py-0.5 text-xs tracking-widest shadow-sm border border-primary-100 dark:border-primary-800">
                                {{ $totalQtyCart }} ITEM
                            </span>
                        @endif
                    </h3>
                </div>
            </div>

            <!-- RIGHT: Actions -->
            <div class="w-1/3 flex justify-end items-center gap-3">
                <!-- No. Nota Input -->
                <div
                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl px-3 py-2 shadow-sm focus-within:ring-2 focus-within:ring-primary-100 focus-within:border-primary-500 transition-all">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span
                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap hidden sm:inline">No.
                        Nota</span>
                    <input type="text" wire:model.live.debounce.500ms="nomorNota"
                        class="w-24 xl:w-32 bg-transparent border-none p-0 text-xs font-mono font-black text-right focus:ring-0 dark:text-white placeholder-gray-300 dark:placeholder-gray-500 uppercase @error('nomorNota') text-rose-500 @enderror"
                        placeholder="AUTO">
                </div>

                <!-- Close Button -->
                <button type="button" wire:click="$set('showPurchaseModal', false)"
                    class="text-gray-400 bg-gray-50 hover:bg-rose-50 hover:text-rose-600 rounded-xl p-2.5 transition-all outline-none border border-transparent hover:border-rose-100 dark:bg-gray-700 dark:hover:bg-rose-900/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- Modal body -->
        <div class="flex-grow flex overflow-hidden">
            <!-- LEFT COLUMN: Cart & Item Search -->
            <div
                class="w-full md:w-2/3 flex flex-col p-6 overflow-y-auto custom-scrollbar border-r dark:border-gray-700 bg-white dark:bg-gray-800">
                <div class="space-y-6">
                    <!-- Cart Table Area -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Barang di
                                Keranjang</h4>
                            @if(count($purchaseCart) > 0)
                                <button type="button" wire:click="clearPurchaseCart"
                                    class="flex items-center gap-1 text-[9px] font-black text-rose-500 uppercase tracking-widest hover:text-rose-700 transition">
                                    All
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @forelse($purchaseCart as $index => $item)
                                                    <div x-data="{ rowActive: false }"
                                                        class="bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-primary-100 dark:hover:border-primary-900/30 transition-all relative group">

                                                        <!-- Delete Button (Top Right Absolute) -->
                                                        <button type="button" wire:click="removeFromPurchaseCart({{ $index }})"
                                                            title="Hapus Item"
                                                            class="absolute top-4 right-4 text-gray-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-all p-2 rounded-xl">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>

                                                        <!-- Head: Product Info -->
                                                        <div class="flex items-start gap-4 mb-5 pr-12">
                                                            @if(!empty($item['gambar']))
                                                                <div
                                                                    class="w-20 h-20 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center flex-shrink-0 text-gray-400 border border-gray-100 dark:border-gray-700 overflow-hidden relative shadow-sm">
                                                                    <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}"
                                                                        class="w-full h-full object-cover">
                                                                    <div
                                                                        class="absolute inset-0 ring-1 ring-inset ring-black/10 dark:ring-white/10 rounded-2xl">
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="w-20 h-20 rounded-2xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-400 border border-gray-100 dark:border-gray-600">
                                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                                    </svg>
                                                                </div>
                                                            @endif
                                                            <div class="pt-1">
                                                                <p
                                                                    class="font-black text-gray-900 dark:text-white uppercase text-sm leading-tight">
                                                                    {{ $item['nama'] }}
                                                                </p>
                                                                <p
                                                                    class="text-[10px] font-mono font-bold text-gray-400 uppercase tracking-widest mt-1">
                                                                    SKU: {{ $item['sku'] }}</p>
                                                            </div>
                                                        </div>

                                                        <!-- Body: Inputs Grid -->
                                                        <div class="grid grid-cols-12 gap-4 items-start">

                                                            <!-- Column 1: QTY & JUM -->
                                                            <div class="col-span-6 lg:col-span-3 space-y-1.5">
                                                                <label
                                                                    class="block text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Jumlah
                                                                    (Qty)</label>
                                                                <input type="number" wire:model.live="purchaseCart.{{ $index }}.qty" min="1"
                                                                    class="w-full xl:w-3/4 mx-auto block p-2.5 text-center text-sm font-black border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:ring-0 focus:border-primary-500 dark:bg-gray-800 bg-white shadow-sm transition-all hover:border-primary-200">
                                                            </div>

                                                            <!-- Column 2: HARGA -->
                                                            <div class="col-span-6 lg:col-span-4 space-y-1.5">
                                                                <label
                                                                    class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Harga
                                                                    Beli Satuan</label>
                                                                <div class="relative"
                                                                    x-data="{ 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        val: @entangle('purchaseCart.' . $index . '.harga').live,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        format(v) { 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            if (!v) return ''; 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }">
                                                                    <span
                                                                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[11px] font-black text-gray-400">Rp</span>
                                                                    <input type="text" x-bind:value="format(val)"
                                                                        x-on:input="val = $event.target.value.replace(/\D/g, '')"
                                                                        class="w-full p-2.5 pl-9 text-sm font-black border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:ring-0 focus:border-primary-500 dark:bg-gray-800 bg-white shadow-sm transition-all hover:border-primary-200">
                                                                </div>
                                                            </div>

                                                            <!-- Column 3: STATUS & GUDANG -->
                                                            <div class="col-span-12 lg:col-span-5 space-y-3">

                                                                <div class="grid grid-cols-2 gap-3">
                                                                    <div class="space-y-1.5">
                                                                        <label
                                                                            class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</label>
                                                                        <select wire:model.live="purchaseCart.{{ $index }}.status"
                                                                            class="w-full text-xs font-black uppercase border-2 rounded-xl p-2.5 focus:ring-0 transition-all cursor-pointer outline-none shadow-sm appearnce-none
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        {{ $item['status'] === 'Received'
                                ? 'text-emerald-700 bg-emerald-50 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800'
                                : 'text-orange-600 bg-orange-50 border-orange-200 dark:bg-orange-900/20 dark:text-orange-400 dark:border-orange-800' }}">
                                                                            <option value="Received">DITERIMA</option>
                                                                            <option value="PO">PRE-ORDER</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="space-y-1.5">
                                                                        <label
                                                                            class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Tujuan
                                                                            Gudang</label>
                                                                        @if($item['status'] === 'Received')
                                                                            <select wire:model.live="purchaseCart.{{ $index }}.gudang_id"
                                                                                class="w-full text-xs font-black uppercase border-2 border-gray-100 dark:border-gray-700 rounded-xl p-2.5 dark:bg-gray-800 bg-white focus:ring-0 focus:border-primary-500 shadow-sm transition-all cursor-pointer text-gray-700 dark:text-gray-300 @error('purchaseCart.' . $index . '.gudang_id') border-rose-500 focus:border-rose-500 @enderror">
                                                                                <option value="">--Pilih--</option>
                                                                                @foreach($gudangs as $g)
                                                                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('purchaseCart.' . $index . '.gudang_id') <span
                                                                                class="text-[9px] font-bold text-rose-500 mt-1 block">{{ $message }}</span>
                                                                            @enderror
                                                                        @else
                                                                            <div
                                                                                class="h-[44px] flex items-center justify-center px-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 opacity-70">
                                                                                <span
                                                                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest">N/A
                                                                                    (PO)</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- Footer: Catatan & Subtotal -->
                                                        <div
                                                            class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                                            <div class="flex items-center gap-3">
                                                                <div class="flex items-center gap-2"
                                                                    x-data="{ 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    openEditor() {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        $dispatch('open-quill-editor', { 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            index: {{ $index }}, 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            catatan: @js($item['catatan']),
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            catatanInternal: @js($item['catatan_internal'])
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        });
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                }">
                                                                    <button type="button" @click="openEditor()"
                                                                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg transition-all shadow-sm border-2 text-[10px] font-black tracking-widest uppercase
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ ($item['catatan'] || $item['catatan_internal']) ? 'bg-indigo-50 border-indigo-200 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-400 hover:border-indigo-300 hover:text-indigo-500' }}">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                        <span>{{ ($item['catatan'] || $item['catatan_internal']) ? 'Ubah Catatan' : 'Tambah Catatan' }}</span>
                                                                    </button>
                                                                    @if($item['catatan'] || $item['catatan_internal'])
                                                                        <div class="flex gap-1.5">
                                                                            @if($item['catatan'])
                                                                                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"
                                                                            title="Ada Catatan Vendor"></div> @endif
                                                                            @if($item['catatan_internal'])
                                                                                <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"
                                                                            title="Ada Catatan Internal"></div> @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="text-right">
                                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">
                                                                    Subtotal Item</p>
                                                                <p
                                                                    class="font-black text-primary-600 dark:text-primary-400 text-lg leading-none">
                                                                    Rp{{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- Notes Display -->
                                                        @if($item['catatan'] || $item['catatan_internal'])
                                                            <div
                                                                class="mt-4 pt-4 border-t border-dashed border-gray-100 dark:border-gray-700 flex flex-col gap-3">
                                                                @if($item['catatan'])
                                                                    <div
                                                                        class="px-4 py-3 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-xl relative">
                                                                        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400 rounded-l-xl"></div>
                                                                        <div class="flex items-center gap-2 mb-1.5">
                                                                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                            </svg>
                                                                            <span
                                                                                class="text-[9px] font-black uppercase tracking-wider text-amber-600 dark:text-amber-500">Catatan
                                                                                Vendor (Berita Acara)</span>
                                                                        </div>
                                                                        <div
                                                                            class="text-xs text-gray-700 dark:text-gray-300 prose prose-sm prose-amber max-w-none leading-relaxed prose-p:my-1 prose-ul:my-1 prose-ol:my-1">
                                                                            {!! $item['catatan'] !!}
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if($item['catatan_internal'])
                                                                    <div
                                                                        class="px-4 py-3 bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 rounded-xl relative">
                                                                        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500 rounded-l-xl"></div>
                                                                        <div class="flex items-center gap-2 mb-1.5">
                                                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                                            </svg>
                                                                            <span
                                                                                class="text-[9px] font-black uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Catatan
                                                                                Internal (Gudang/Admin)</span>
                                                                        </div>
                                                                        <div
                                                                            class="text-xs text-gray-700 dark:text-gray-300 prose prose-sm prose-indigo max-w-none leading-relaxed prose-p:my-1 prose-ul:my-1 prose-ol:my-1">
                                                                            {!! $item['catatan_internal'] !!}
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif

                                                    </div>
                            @empty
                                <div
                                    class="p-16 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl text-center bg-gray-50/50 dark:bg-gray-800/30">
                                    <div class="flex flex-col items-center gap-4 opacity-30">
                                        <div class="p-5 bg-gray-200 dark:bg-gray-700 rounded-full">
                                            <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-black uppercase tracking-widest text-gray-500">Keranjang
                                            Belanja Masih Kosong</p>
                                        <p class="text-[10px] font-bold text-gray-400 w-2/3 mx-auto">Silakan cari produk
                                            menggunakan kolom pencarian di atas untuk menambahkannya ke dalam keranjang
                                            pembelian.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Metadata, Billing & Files -->
            <div
                class="w-full md:w-1/3 flex flex-col bg-gray-50/50 dark:bg-gray-900/30 p-6 overflow-y-auto custom-scrollbar border-l dark:border-gray-700">
                <div class="space-y-8">
                    <!-- SECTION: VENDOR -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Informasi
                                Vendor</label>
                            @if($selectedVendor)
                                <button type="button" wire:click="$set('selectedVendor', null)"
                                    class="text-[9px] font-black text-amber-600 uppercase tracking-widest hover:underline">Ganti</button>
                            @endif
                        </div>

                        @if($selectedVendor)
                            <div
                                class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-primary-100 dark:border-primary-900/30 shadow-sm flex gap-4">
                                @if(!empty($selectedVendor['gambar']))
                                    <img src="{{ Storage::url($selectedVendor['gambar']) }}"
                                        class="w-16 h-16 rounded-xl object-cover shadow-sm flex-shrink-0">
                                @else
                                    <div
                                        class="w-14 h-14 rounded-xl bg-primary-600 flex items-center justify-center text-white text-xl font-black shadow-md flex-shrink-0">
                                        {{ strtoupper(substr($selectedVendor['nama'], 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p
                                        class="text-sm font-black text-gray-900 dark:text-white uppercase leading-tight truncate">
                                        {{ $selectedVendor['nama'] }}
                                    </p>
                                    @if($selectedVendor['tag']) <span
                                        class="text-[8px] font-bold uppercase py-0.5 px-2 bg-primary-100 text-primary-700 rounded-full mt-1 inline-block">{{ $selectedVendor['tag'] }}</span>
                                    @endif
                                    <p class="text-[10px] text-gray-500 mt-1 truncate">📞
                                        {{ $selectedVendor['kontak'] ?: 'No Contact' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <div class="relative flex-grow" x-data="{ open: false }">
                                    <input type="text" wire:model.live.debounce.250ms="searchVendor"
                                        x-on:focus="open = true" x-on:blur="setTimeout(() => open = false, 150)"
                                        class="w-full p-3.5 pl-10 text-xs font-bold border-2 border-gray-100 rounded-2xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none dark:bg-gray-700 dark:border-gray-600 shadow-sm"
                                        placeholder="Cari atau pilih vendor...">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <!-- Dropdown Picker -->
                                    <div x-show="open"
                                        class="absolute z-[140] w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl max-h-52 overflow-y-auto divide-y dark:divide-gray-700">
                                        @forelse($vendors as $v)
                                            <button type="button" @mousedown.prevent
                                                wire:click="selectVendor({{ $v->id }}, '{{ addslashes($v->nama) }}')"
                                                class="w-full flex items-center gap-3 p-3 hover:bg-primary-50 dark:hover:bg-gray-700 transition text-left">
                                                <div
                                                    class="w-7 h-7 rounded-lg bg-primary-600 flex items-center justify-center text-[10px] font-black text-white uppercase">
                                                    {{ substr($v->nama, 0, 1) }}
                                                </div>
                                                <span
                                                    class="text-xs font-black uppercase text-gray-900 dark:text-white block">{{ $v->nama }}</span>
                                            </button>
                                        @empty
                                            <div
                                                class="p-4 text-center text-gray-400 text-[10px] font-black uppercase tracking-widest italic">
                                                Tidak Ada Data</div>
                                        @endforelse
                                    </div>
                                </div>
                                <button type="button" data-modal-target="modal-vendor" data-modal-toggle="modal-vendor"
                                    class="bg-blue-600 text-white p-3.5 rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                        @error('selectedVendor') <span
                            class="text-[9px] text-rose-500 font-bold uppercase tracking-widest block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- SECTION: META & BILLING -->
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Detail &
                                Tanggal</label>
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-gray-400 uppercase">Tgl Transaksi *</p>
                                <input type="date" wire:model="tanggalPembelian"
                                    class="w-full p-2.5 text-[10px] font-black border border-gray-100 rounded-xl bg-gray-50 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Metode
                                Pembayaran</label>
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" wire:click="$set('metodePembayaran', 'Cash')"
                                        class="p-3 rounded-2xl border-2 text-[10px] font-black uppercase tracking-widest transition-all {{ $metodePembayaran === 'Cash' ? 'bg-primary-600 border-primary-600 text-white shadow-lg shadow-primary-500/20' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-400 hover:border-primary-200' }}">Cash</button>
                                    <button type="button" wire:click="$set('metodePembayaran', 'Kredit')"
                                        class="p-3 rounded-2xl border-2 text-[10px] font-black uppercase tracking-widest transition-all {{ $metodePembayaran === 'Kredit' ? 'bg-rose-600 border-rose-600 text-white shadow-lg shadow-rose-500/20' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-400 hover:border-rose-200' }}">Kredit</button>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-[8px] font-black text-gray-400 uppercase">Akun Kas Pengeluaran</p>
                                    <select wire:model.live="akunKasId" {{ $metodePembayaran === 'Kredit' && ($jumlahDP == 0 || !$jumlahDP) ? 'disabled' : '' }}
                                        class="w-full p-3 text-[10px] font-black uppercase border border-gray-100 rounded-2xl bg-white dark:bg-gray-800 focus:ring-primary-500 shadow-sm disabled:opacity-30 disabled:grayscale">
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($this->userAkunKas as $akun)
                                            <option value="{{ $akun->id }}">{{ $akun->nama }} (Rp.
                                                {{ number_format($akun->saldo_saat_ini, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('akunKasId') <p
                                        class="text-[8px] text-rose-500 font-black uppercase tracking-widest">
                                        {{ $message }}
                                    </p> @enderror
                                </div>

                                @if($metodePembayaran === 'Kredit')
                                    <div
                                        class="p-4 bg-rose-50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-900/30 space-y-3 animate-in slide-in-from-top-2 duration-300">
                                        <div class="space-y-1">
                                            <p class="text-[8px] font-black text-rose-500 uppercase">Uang Muka (DP) -
                                                Opsional</p>
                                            <div class="relative" x-data="{ dp: @entangle('jumlahDP').live }">
                                                <span
                                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-rose-400">Rp</span>
                                                <input type="text" x-model="dp"
                                                    x-on:input="dp = $event.target.value.replace(/\D/g, '')"
                                                    class="w-full p-2.5 pl-9 text-xs font-black border border-rose-200 rounded-xl focus:ring-rose-500 placeholder-rose-200 dark:bg-gray-800 dark:text-rose-400"
                                                    placeholder="0">
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[8px] font-black text-rose-500 uppercase">Tgl Jatuh Tempo *</p>
                                            <input type="date" wire:model="jatuhTempo"
                                                class="w-full p-2.5 text-xs font-black border border-rose-200 rounded-xl bg-white focus:ring-rose-500 dark:bg-gray-800 dark:text-rose-400">
                                            @error('jatuhTempo') <p class="text-[8px] text-rose-500 font-black uppercase">
                                                {{ $message }}
                                            </p> @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- SECTION: COSTS & SUMMARY -->
                        <div class="space-y-4 pt-4 border-t dark:border-gray-700">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Ringkasan
                                Biaya</label>
                            <div class="bg-gray-100/50 dark:bg-gray-800 p-5 rounded-3xl space-y-4 shadow-inner">
                                <div
                                    class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-tighter">
                                    <span>Subtotal Barang</span>
                                    @php $subtotalCart = collect($purchaseCart)->sum(fn($i) => $i['qty'] * $i['harga']); @endphp
                                    <span
                                        class="text-gray-700 dark:text-gray-300">Rp{{ number_format($subtotalCart, 0, ',', '.') }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative" x-data="{ val: @entangle('ongkir').live }">
                                        <span
                                            class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[8px] font-black text-gray-400">ONGKIR</span>
                                        <input type="text" x-on:input="val = $event.target.value.replace(/\D/g, '')"
                                            x-bind:value="val?.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                            class="w-full p-2 pl-12 text-[10px] font-black border-2 border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-700 focus:ring-primary-500"
                                            placeholder="0">
                                    </div>
                                    <div class="relative" x-data="{ val: @entangle('biayaLain').live }">
                                        <span
                                            class="absolute left-2.5 top-1/2 -translate-y-1/2 text-[8px] font-black text-gray-400">LAINNYA</span>
                                        <input type="text" x-on:input="val = $event.target.value.replace(/\D/g, '')"
                                            x-bind:value="val?.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                            class="w-full p-2 pl-12 text-[10px] font-black border-2 border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-700 focus:ring-primary-500"
                                            placeholder="0">
                                    </div>
                                </div>

                                <div class="pt-3 border-t-2 border-dashed border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-end">
                                        <div>
                                            @php $grandTotal = $subtotalCart + (float) ($ongkir ?: 0) + (float) ($biayaLain ?: 0); @endphp
                                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">
                                                Total Tagihan Final</p>
                                            <p
                                                class="text-2xl font-black text-primary-600 tracking-tighter leading-none">
                                                Rp{{ number_format($grandTotal, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            @if($metodePembayaran === 'Kredit')
                                                <p class="text-[8px] font-black text-rose-500 uppercase mb-0.5">Sisa Hutang
                                                </p>
                                                <p class="text-sm font-black text-rose-600 leading-none">
                                                    Rp{{ number_format($grandTotal - (float) ($jumlahDP ?: 0), 0, ',', '.') }}
                                                </p>
                                            @else
                                                <span
                                                    class="inline-block px-3 py-1 bg-green-100 text-green-700 text-[8px] font-black uppercase rounded-full shadow-sm">Lunas
                                                    Tunai</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: DOCUMENT -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Lampiran
                                    Nota <span class="text-rose-500">*</span></label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="auto-compress-nota" class="sr-only peer" checked>
                                    <div
                                        class="relative w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ms-1.5 text-[8px] font-black text-gray-400 uppercase">Auto</span>
                                </label>
                            </div>

                            <div x-data="{ isCompressing: false }" @compression-start.window="isCompressing = true"
                                @compression-end.window="isCompressing = false" class="relative">
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-white dark:hover:bg-gray-800 transition overflow-hidden bg-white/50 dark:bg-gray-800/50 group">
                                    @if($compressedInvoice)
                                        <div class="relative h-full w-full">
                                            <img src="{{ is_string($compressedInvoice) ? $compressedInvoice : $compressedInvoice->temporaryUrl() }}"
                                                class="h-full w-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-primary-600/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                                <button type="button"
                                                    x-on:click.prevent="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'compressedInvoice', $wire)"
                                                    class="p-2 bg-white text-primary-600 rounded-xl shadow-lg hover:scale-110 transition"><svg
                                                        class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg></button>
                                                <button type="button" wire:click="$set('compressedInvoice', null)"
                                                    class="p-2 bg-white text-rose-600 rounded-xl shadow-lg hover:scale-110 transition"><svg
                                                        class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg></button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div
                                                class="p-3 bg-primary-50 rounded-2xl mb-2 text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </div>
                                            <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Pilih
                                                Berkas</p>
                                        </div>
                                    @endif
                                    <input type="file" class="hidden" accept="image/*"
                                        x-on:change="if(document.getElementById('auto-compress-nota').checked) { handleAutoCompress($event.target, 'compressedInvoice', $wire) } else { @this.upload('compressedInvoice', $event.target.files[0]) }" />
                                </label>
                                @error('compressedInvoice') <p
                                    class="text-[8px] text-rose-500 font-black uppercase mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-4">

                                @if($this->isPurchaseReady)
                                    <button type="button" wire:click="confirmPurchase"
                                        class="min-w-[240px] text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-black rounded-2xl text-sm px-8 py-4 text-center dark:bg-primary-600 dark:hover:bg-primary-700 uppercase tracking-widest transition-all active:scale-95 shadow-2xl shadow-primary-500/30">
                                        SIMPAN
                                    </button>
                                @else
                                    <button type="button" disabled
                                        class="min-w-[240px] bg-gray-100 text-gray-400 font-black rounded-2xl text-sm px-8 py-4 opacity-50 cursor-not-allowed uppercase tracking-widest">Lengkapi
                                        Data</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Invoice/Nota -->
<div id="modal-confirm-purchase" tabindex="-1" aria-hidden="true" x-data="{ savingComplete: false }"
    @purchase-saved.window="savingComplete = true; setTimeout(() => { $wire.set('showConfirmPurchaseModal', false); savingComplete = false; }, 3000)"
    x-show="$wire.showConfirmPurchaseModal"
    class="fixed inset-0 z-[160] overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-md flex items-center justify-center p-4 sm:p-6"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
    x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" style="display: none;">

    <!-- Kertas Struk / Invoice Paper Design -->
    <div
        class="relative w-full max-w-xl mx-auto bg-white dark:bg-gray-100 rounded-lg shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5)] flex flex-col overflow-hidden comic-border transform transition-all">
        <!-- Zigzag Top Edge (Decorative) -->
        <div class="absolute top-0 w-full h-3 bg-repeat-x flex opacity-20"
            style="background-image: radial-gradient(circle at 10px 0, transparent 10px, white 11px); background-size: 20px 20px;">
        </div>

        <!-- Header Modal -->
        <div class="px-8 py-6 text-center border-b-2 border-dashed border-gray-300 mt-2">
            <div
                class="mx-auto w-16 h-16 bg-gray-900 rounded-2xl flex items-center justify-center mb-4 text-white shadow-lg rotate-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Konfirmasi Pembelian</h3>
            <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mt-1">Harap periksa ulang draft nota
                di bawah ini</p>
        </div>

        <!-- Body / Rincian -->
        <div class="px-8 py-6 space-y-6 text-gray-800">
            <!-- Info Meta -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                    <p class="text-[9px] font-black tracking-widest uppercase text-gray-400 mb-1">Nota Tagihan</p>
                    <p class="font-mono text-base font-bold text-gray-900 uppercase">{{ $nomorNota ?: '...' }}</p>
                    <p class="text-[10px] font-bold text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($tanggalPembelian)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                    <p class="text-[9px] font-black tracking-widest uppercase text-gray-400 mb-1">Vendor/Pemasok</p>
                    <p class="font-black text-sm text-gray-900 uppercase truncate">
                        {{ $selectedVendor['nama'] ?? '...' }}
                    </p>
                    <p class="text-[10px] font-bold text-primary-600 mt-1 uppercase">{{ $metodePembayaran }}</p>
                </div>
            </div>

            <!-- List Item Struk -->
            <div class="space-y-4 pt-2">
                <p
                    class="text-[10px] font-black tracking-widest uppercase text-gray-400 border-b-2 border-gray-900 pb-2">
                    Rincian Barang</p>
                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                    @foreach($purchaseCart as $item)
                        <div class="flex justify-between items-start text-sm">
                            <div class="w-2/3 pr-4">
                                <p class="font-black text-gray-800 uppercase leading-tight">{{ $item['nama'] }}</p>
                                <p class="text-[10px] font-bold text-gray-500">{{ $item['qty'] }} x Rp
                                    {{ number_format($item['harga'], 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-1/3 text-right">
                                <p class="font-black text-gray-900">Rp
                                    {{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Perhitungan Final -->
            <div class="border-t-2 border-dashed border-gray-300 pt-4 space-y-2">
                @php $subtotalConfirm = collect($purchaseCart)->sum(fn($i) => $i['qty'] * $i['harga']); @endphp
                <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                    <span>Subtotal Barang</span>
                    <span>Rp {{ number_format($subtotalConfirm, 0, ',', '.') }}</span>
                </div>
                @if($ongkir > 0)
                    <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($biayaLain > 0)
                    <div class="flex justify-between items-center text-xs font-bold text-gray-500">
                        <span>Biaya Lainnya</span>
                        <span>Rp {{ number_format($biayaLain, 0, ',', '.') }}</span>
                    </div>
                @endif

                @php $grandTotalConfirm = $subtotalConfirm + (float) ($ongkir ?: 0) + (float) ($biayaLain ?: 0); @endphp
                <div class="flex justify-between items-center pt-3 border-t-2 border-gray-900 mt-2">
                    <span class="text-sm font-black uppercase tracking-widest text-gray-900">Total Akhir</span>
                    <span class="text-xl font-black text-gray-900">Rp
                        {{ number_format($grandTotalConfirm, 0, ',', '.') }}</span>
                </div>

                @if($metodePembayaran === 'Kredit' && $jumlahDP > 0)
                    <div class="flex justify-between items-center text-sm font-bold text-rose-600">
                        <span>- Uang Muka (DP)</span>
                        <span>Rp {{ number_format($jumlahDP, 0, ',', '.') }}</span>
                    </div>
                    <div
                        class="flex justify-between items-center text-sm font-black text-rose-700 bg-rose-50 p-2 rounded-xl mt-1">
                        <span>Sisa Hutang</span>
                        <span>Rp {{ number_format($grandTotalConfirm - $jumlahDP, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

        </div>

        <!-- Footer / Action Area -->
        <div
            class="px-8 py-6 bg-gray-50 border-t border-gray-200 grid grid-cols-2 gap-4 rounded-b-lg relative overflow-hidden">
            <!-- Normal State Buttons -->
            <div class="col-span-2 grid grid-cols-2 gap-4 transition-all duration-300" x-show="!savingComplete"
                x-transition:leave="opacity-0">
                <button type="button" wire:click="$set('showConfirmPurchaseModal', false)"
                    class="px-4 py-3.5 text-xs font-black tracking-widest text-gray-600 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-100 hover:text-gray-900 transition-colors uppercase">
                    Perbaiki Draf
                </button>
                <button type="button" wire:click="savePurchase" wire:loading.attr="disabled"
                    class="px-4 py-3.5 text-xs font-black tracking-widest text-white bg-green-600 rounded-xl hover:bg-green-700 focus:ring-4 focus:ring-green-300 transition-all uppercase shadow-lg shadow-green-500/30 flex items-center justify-center gap-2">
                    <svg wire:loading.remove wire:target="savePurchase" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div wire:loading wire:target="savePurchase"
                        class="animate-spin rounded-full w-4 h-4 border-b-2 border-white"></div>
                    <span wire:loading.remove wire:target="savePurchase">Rekam Transaksi</span>
                    <span wire:loading wire:target="savePurchase">Menyimpan...</span>
                </button>
            </div>

            <!-- Success State Overlay -->
            <div class="absolute inset-0 bg-emerald-500 flex items-center justify-center gap-3 text-white transition-all duration-500 transform translate-y-full"
                x-bind:class="{ 'translate-y-0': savingComplete, 'translate-y-full': !savingComplete }">
                <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-black uppercase tracking-widest">Transaksi Tersimpan!</span>
                <p class="absolute bottom-2 text-[8px] font-bold text-emerald-100 opacity-80 uppercase tracking-widest">
                    Menutup otomatis...</p>
            </div>
        </div>

    </div>
</div>