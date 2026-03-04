<div x-data="{ show: @entangle('showStockLogModal') }" x-show="show" x-cloak
    class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm shadow-2xl transition-all duration-300">
    <div @click.away="show = false"
        class="bg-white dark:bg-gray-800 w-full max-w-5xl rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[90vh]">

        <!-- Header -->
        <div
            class="relative z-[20] px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Log Mutasi Stok
                </h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mt-1">Lacak
                    Pergerakan Keluar/Masuk Barang</p>
            </div>
            <button @click="show = false"
                class="p-2 hover:bg-white dark:hover:bg-gray-700 rounded-full transition-all group">
                <svg class="w-6 h-6 text-gray-400 group-hover:text-rose-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Filters & Search -->
        <div
            class="relative z-[15] p-6 border-b border-gray-50 dark:border-gray-700 flex flex-wrap gap-4 items-center bg-white dark:bg-gray-800">
            <div class="flex-grow relative">
                <div class="absolute inset-y-0 left-0 pl-1 px-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="searchStockLog"
                    placeholder="Cari Nama Barang atau SKU..."
                    class="w-full pl-12 pr-4 py-3 text-xs font-bold border border-gray-100 rounded-2xl dark:bg-gray-700 dark:border-gray-600 focus:ring-4 focus:ring-blue-100 outline-none shadow-sm transition-all focus:border-blue-500">
            </div>

            <div class="w-48">
                <select wire:model.live="filterGudangLog"
                    class="w-full px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-gray-100 rounded-2xl dark:bg-gray-700 dark:border-gray-600 focus:ring-4 focus:ring-blue-100 outline-none transition-all focus:border-blue-500">
                    <option value="">Semua Gudang</option>
                    @foreach($gudangs as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Table Container -->
        <div class="flex-grow overflow-auto custom-scrollbar relative">
            <table class="w-full text-left border-separate border-spacing-0">
                <thead class="sticky top-0 z-10">
                    <tr>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100 dark:border-gray-700">
                            Waktu</th>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100 dark:border-gray-700">
                            Barang</th>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100 dark:border-gray-700">
                            Gudang</th>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center border-b border-gray-100 dark:border-gray-700">
                            Tipe</th>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center border-b border-gray-100 dark:border-gray-700">
                            Jumlah</th>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100 dark:border-gray-700">
                            Aksi</th>
                        <th
                            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md py-4 px-6 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100 dark:border-gray-700 whitespace-nowrap">
                            Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($this->stockMovements as $move)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="text-[10px] font-black text-gray-900 dark:text-white leading-none mb-1">
                                    {{ $move->created_at->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[9px] font-bold text-gray-400 leading-none">
                                    {{ $move->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div
                                    class="text-[11px] font-black text-gray-900 dark:text-white leading-none mb-1 uppercase tracking-tighter">
                                    {{ $move->barang->nama }}
                                </div>
                                <div class="text-[9px] font-bold text-gray-400 leading-none uppercase tracking-widest">
                                    SKU: {{ $move->barang->sku }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-[10px] font-black uppercase text-gray-600 dark:text-gray-300">
                                    {{ $move->gudang->nama }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($move->type === 'Masuk')
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] font-black uppercase text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                        In
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 text-[10px] font-black uppercase text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                        Out
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-sm font-black text-gray-900 dark:text-white">{{ $move->quantity }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <div
                                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest leading-none mb-1">
                                    {{ $move->action_type }}
                                </div>
                                <div class="text-[9px] font-medium text-gray-400 line-clamp-1 max-w-[150px]">
                                    {{ $move->description }}
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($move->user->name) }}&background=6366f1&color=fff"
                                        class="w-5 h-5 rounded-full border border-white">
                                    <span
                                        class="text-[10px] font-bold text-gray-600 dark:text-gray-400">{{ $move->user->name }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-100 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-xs font-black uppercase text-gray-300 tracking-widest">Belum ada
                                        aktivitas mutasi barang</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div
            class="p-6 bg-gray-50/50 dark:bg-gray-800/50 border-t border-gray-50 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-900">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Menampilkan {{ $this->stockMovements->count() }} aktivitas terakhir
            </div>
            @if($this->stockMovements->hasMorePages())
                <button wire:click="loadMoreStockLog"
                    class="px-6 py-2 bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-300 hover:bg-gray-100 transition-all shadow-sm">
                    Muat Lebih Banyak
                </button>
            @endif
        </div>
    </div>
</div>