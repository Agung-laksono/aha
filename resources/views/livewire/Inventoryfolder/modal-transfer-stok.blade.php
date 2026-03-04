<div x-data="{ show: @entangle('showTransferModal') }" x-show="show" x-cloak
    class="fixed inset-0 z-[80] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm shadow-2xl transition-all duration-300">
    <div @click.away="show = false"
        class="bg-white dark:bg-gray-800 w-full max-w-4xl rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div
            class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Transfer Stok
                    Barang</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mt-1">Pindahkan
                    Stok Antar Gudang</p>
            </div>
            <button @click="show = false"
                class="p-2 hover:bg-white dark:hover:bg-gray-700 rounded-full transition-all group">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-rose-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-8 flex-grow overflow-y-auto custom-scrollbar">
            <!-- Alert Error -->
            @if (session()->has('error_transfer'))
                <div
                    class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 animate-shake">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-xs font-bold">{{ session('error_transfer') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Gudang Asal -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Gudang Asal
                        (Sumber)</label>
                    <div class="relative">
                        <select wire:model.live="gudangAsalId"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-2xl text-xs font-black uppercase tracking-wider focus:ring-4 focus:ring-blue-100 transition-all dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Gudang Asal</option>
                            @php
                                $accessibleIds = auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin')
                                    ? $gudangs->pluck('id')
                                    : auth()->user()->accessibleGudangIds();
                            @endphp
                            @foreach($gudangs->whereIn('id', $accessibleIds) as $g)
                                <option value="{{ $g->id }}">{{ $g->nama }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Gudang Tujuan -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Gudang
                        Tujuan (Destinasi)</label>
                    <div class="relative">
                        <select wire:model.live="gudangTujuanId"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-2xl text-xs font-black uppercase tracking-wider focus:ring-4 focus:ring-blue-100 transition-all dark:bg-gray-700 dark:text-white @error('gudangTujuanId') ring-2 ring-rose-500 @enderror">
                            <option value="">Pilih Gudang Tujuan</option>
                            @foreach($gudangs as $g)
                                @if($g->id != $gudangAsalId)
                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    @error('gudangTujuanId') <span class="text-[9px] font-bold text-rose-500 uppercase mt-1 block pl-2">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Tanggal -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Tanggal
                        Transfer</label>
                    <input type="date" wire:model.live="tanggalTransfer"
                        class="w-full px-4 py-3 bg-gray-50 border-0 rounded-2xl text-xs font-bold focus:ring-4 focus:ring-blue-100 transition-all dark:bg-gray-700 dark:text-white @error('tanggalTransfer') ring-2 ring-rose-500 @enderror">
                    @error('tanggalTransfer') <span class="text-[9px] font-bold text-rose-500 uppercase mt-1 block pl-2">{{ $message }}</span> @enderror
                </div>
                <!-- Keterangan -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Keterangan
                        (Opsional)</label>
                    <input type="text" wire:model="keteranganTransfer"
                        placeholder="Contoh: Stok Menipis, Barang Display, dll"
                        class="w-full px-4 py-3 bg-gray-50 border-0 rounded-2xl text-xs font-bold focus:ring-4 focus:ring-blue-100 transition-all dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <!-- Item Selection List -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Daftar Barang
                        yang Dipindahkan</h4>
                    <span
                        class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase">{{ count($transferItems) }}
                        Items</span>
                </div>

                <!-- Add Item Search -->
                <div class="mb-6 relative" x-data="{ openSearch: @entangle('showSuggestions') }" wire:ignore.self>
                    <div class="relative">
                        <input type="text" placeholder="Cari barang untuk ditambahkan..." 
                            @focus="openSearch = true"
                            wire:model.live.debounce.300ms="searchTransferItem"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-bold focus:ring-4 focus:ring-blue-50 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    @if(count($this->searchBarangs) > 0)
                    <div x-show="openSearch" @click.away="openSearch = false"
                        class="absolute z-[90] mt-2 w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 max-h-60 overflow-y-auto custom-scrollbar p-2">
                        @foreach($this->searchBarangs as $barang)
                        <button type="button" wire:click="addToTransferCart({{ $barang->id }})"
                            class="w-full flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-all text-left">
                            <div class="flex-grow">
                                <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">{{ $barang->nama }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">SKU: {{ $barang->sku }}</p>
                            </div>
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                @if(count($transferItems) > 0)
                    <div class="space-y-3">
                        @foreach($transferItems as $index => $item)
                            <div wire:key="trf-item-{{ $index }}"
                                class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-2xl border @error('transferItems.'.$index.'.qty') border-rose-500 bg-rose-50/30 @else border-gray-100 dark:border-gray-600 @enderror">
                                <div class="flex items-center gap-4">
                                    <div class="flex-grow">
                                        <div
                                            class="text-[11px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                            {{ $item['nama'] }}</div>
                                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">SKU:
                                            {{ $item['sku'] }} | Stok Tersedia: {{ $item['stok_asal'] }}</div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <label class="text-[10px] font-black text-gray-400 uppercase">Qty:</label>
                                        <input type="number" wire:model.live.debounce.500ms="transferItems.{{ $index }}.qty" min="1"
                                            class="w-20 px-2 py-1.5 text-center text-xs font-black bg-white dark:bg-gray-800 border-0 rounded-lg focus:ring-2 @error('transferItems.'.$index.'.qty') focus:ring-rose-500 @else focus:ring-blue-500 @enderror shadow-sm transition-all">
                                    </div>
                                    <button wire:click="removeFromTransferCart({{ $index }})"
                                        class="p-2 text-gray-300 hover:text-rose-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                @error('transferItems.'.$index.'.qty')
                                    <span class="text-[9px] font-bold text-rose-600 uppercase mt-2 block">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="py-12 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-[2rem] flex flex-col items-center justify-center">
                        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <p class="text-[10px] font-black uppercase text-gray-300 tracking-widest">Belum ada barang dipilih
                        </p>
                        <p class="text-[9px] font-medium text-gray-400 mt-1 italic">Klik ikon transfer pada kartu barang
                            untuk menambahkan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div
            class="p-8 border-t border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex gap-4 justify-end">
            <button @click="show = false"
                class="px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-white transition-all">
                Batal
            </button>
            <button wire:click="saveTransfer" wire:loading.attr="disabled"
                class="px-10 py-3 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-0.5 transition-all disabled:opacity-50">
                <span wire:loading.remove wire:target="saveTransfer">Proses Transfer</span>
                <span wire:loading wire:target="saveTransfer">Memproses...</span>
            </button>
        </div>
    </div>
</div>