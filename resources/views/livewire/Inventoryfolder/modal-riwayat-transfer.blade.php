<div id="modal-riwayat-transfer" tabindex="-1" aria-hidden="true" wire:ignore.self x-data="{ 
    expanded: null,
    tab: 'all'
}"
    class="fixed inset-0 z-[80] hidden w-full h-full bg-black/70 backdrop-blur-sm flex justify-center items-center p-4 overflow-x-hidden overflow-y-auto">
    <div class="relative w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div
            class="relative bg-white/95 backdrop-blur-xl rounded-[2.5rem] shadow-[0_32px_120px_-20px_rgba(0,0,0,0.2)] dark:bg-gray-800/95 border border-white/40 dark:border-gray-700 overflow-hidden flex flex-col h-[85vh]">

            <!-- Animated Background Glows -->
            <div
                class="absolute -top-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-[100px] pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-400/10 rounded-full blur-[100px] pointer-events-none">
            </div>

            <!-- Header -->
            <div class="relative flex items-center justify-between p-8 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-5">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                        </div>
                        <div
                            class="relative p-3.5 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-200/50">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3
                            class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-none mb-1">
                            Riwayat
                            Transfer</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Inventory Intelligence Hub
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Global Search inside Modal -->
                    <div class="hidden md:flex relative group w-64">
                        <div
                            class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="searchTransfer"
                            placeholder="Cari Nomor atau Gudang..."
                            class="w-full pl-10 pr-4 py-2.5 text-xs font-bold border-none bg-gray-100 dark:bg-gray-700/50 rounded-xl focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-gray-400 placeholder:italic">
                    </div>

                    <button type="button"
                        class="p-2.5 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all hover:rotate-90"
                        data-modal-hide="modal-riwayat-transfer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="px-8 pt-6">
                <div
                    class="flex items-center gap-1.5 p-1.5 bg-gray-100/50 dark:bg-gray-700/50 backdrop-blur-md rounded-2xl w-fit border border-gray-100 dark:border-gray-600">
                    <button @click="tab = 'all'"
                        :class="tab === 'all' ? 'bg-white shadow-lg text-blue-600 scale-100' : 'text-gray-500 hover:text-gray-800 scale-95 opacity-60'"
                        class="px-6 py-2.5 text-[10px] font-black uppercase rounded-xl transition-all duration-300">Semua</button>
                    <button @click="tab = 'pending'"
                        :class="tab === 'pending' ? 'bg-white shadow-lg text-amber-600 scale-100' : 'text-gray-500 hover:text-amber-600 scale-95 opacity-60'"
                        class="px-6 py-2.5 text-[10px] font-black uppercase rounded-xl transition-all duration-300 flex items-center gap-2">
                        Pending
                        @if($this->pendingTransfers->where('status', 'pending')->count() > 0)
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                        @endif
                    </button>
                    <button @click="tab = 'received'"
                        :class="tab === 'received' ? 'bg-white shadow-lg text-emerald-600 scale-100' : 'text-gray-500 hover:text-emerald-600 scale-95 opacity-60'"
                        class="px-6 py-2.5 text-[10px] font-black uppercase rounded-xl transition-all duration-300">Diterima</button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-hidden flex flex-col p-8 pt-4">
                <div
                    class="flex-1 overflow-y-auto custom-scrollbar rounded-[2rem] border border-gray-100 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 shadow-inner">
                    <table class="w-full text-sm text-left border-separate border-spacing-y-2 px-4">
                        <thead
                            class="text-[10px] font-black uppercase text-gray-400 sticky top-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md z-30">
                            <tr>
                                <th class="px-6 py-4">Status & Waktu</th>
                                <th class="px-6 py-4">Detail Transfer</th>
                                <th class="px-6 py-4">Alur Distribusi</th>
                                <th class="px-6 py-4 text-center">Volume</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="space-y-4">
                            @forelse($this->pendingTransfers as $trf)
                                <tr x-show="tab === 'all' || tab === '{{ $trf->status }}'" x-data="{ isHovered: false }"
                                    @mouseenter="isHovered = true" @mouseleave="isHovered = false"
                                    @click="expanded = (expanded === '{{ $trf->id }}' ? null : '{{ $trf->id }}')"
                                    class="group cursor-pointer transition-all duration-500 relative">

                                    <!-- Status & Waktu -->
                                    <td
                                        class="px-6 py-4 first:rounded-l-3xl bg-white dark:bg-gray-800 border-y border-l border-gray-100 dark:border-gray-700 shadow-sm transition-all group-hover:bg-blue-50/10">
                                        <div class="flex flex-col gap-2">
                                            @if($trf->status === 'pending')
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black uppercase w-fit">
                                                    <span class="relative flex h-2 w-2">
                                                        <span
                                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                        <span
                                                            class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                    </span>
                                                    En Route
                                                </span>
                                            @elseif($trf->status === 'received')
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase w-fit">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Arrived
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-50 text-gray-500 text-[10px] font-black uppercase w-fit">Cancelled</span>
                                            @endif
                                            <p class="text-[10px] text-gray-400 font-bold uppercase">
                                                {{ $trf->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>

                                    <!-- Detail Transfer -->
                                    <td
                                        class="px-6 py-4 bg-white dark:bg-gray-800 border-y border-gray-100 dark:border-gray-700 shadow-sm transition-all group-hover:bg-blue-50/10">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-black text-xs text-gray-900 dark:text-white tracking-widest">#{{ $trf->nomor_transfer }}</span>
                                            <span
                                                class="text-[10px] text-indigo-500 font-black uppercase group-hover:underline">{{ $trf->details->count() }}
                                                Jenis Produk</span>
                                            <span
                                                class="text-[10px] text-gray-400 font-bold uppercase">{{ $trf->tanggal->format('d M Y') }}</span>
                                        </div>
                                    </td>

                                    <!-- Alur Distribusi -->
                                    <td
                                        class="px-6 py-4 bg-white dark:bg-gray-800 border-y border-gray-100 dark:border-gray-700 shadow-sm transition-all group-hover:bg-blue-50/10">
                                        <div class="flex items-center gap-4">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[9px] font-black text-gray-400 uppercase leading-none mb-1">Origin</span>
                                                <span
                                                    class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $trf->gudangAsal->nama }}</span>
                                            </div>

                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="h-px w-12 bg-gradient-to-r from-gray-200 via-blue-400 to-gray-200 relative group-hover:w-16 transition-all duration-700">
                                                    <div class="absolute -top-1 right-0">
                                                        <svg class="w-2 h-2 text-blue-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M8.59,16.59L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.59Z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[9px] font-black text-blue-400 uppercase leading-none mb-1">Destination</span>
                                                <span
                                                    class="text-xs font-bold text-blue-600">{{ $trf->gudangTujuan->nama }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Volume -->
                                    <td
                                        class="px-6 py-4 text-center bg-white dark:bg-gray-800 border-y border-gray-100 dark:border-gray-700 shadow-sm transition-all group-hover:bg-blue-50/10">
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-lg font-black text-gray-900 leading-none">{{ $trf->total_qty }}</span>
                                            <span
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Unit</span>
                                        </div>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 last:rounded-r-3xl bg-white dark:bg-gray-800 border-y border-r border-gray-100 dark:border-gray-700 shadow-sm transition-all group-hover:bg-blue-50/10 text-right">
                                        <div class="flex justify-end gap-2" @click.stop>
                                            @if($trf->status === 'pending')
                                                <button wire:click="confirmReceive({{ $trf->id }})"
                                                    wire:confirm="Konfirmasi penerimaan barang untuk transfer #{{ $trf->nomor_transfer }}?"
                                                    class="p-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-md active:scale-95 flex items-center justify-center"
                                                    title="Konfirmasi Penerimaan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            <button wire:click="downloadSuratJalan({{ $trf->id }})"
                                                class="p-2.5 bg-gray-900 text-white rounded-xl hover:bg-black transition-all shadow-md active:scale-95 flex items-center justify-center"
                                                title="Cetak Surat Jalan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            </button>

                                            <button 
                                                class="p-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-md active:scale-95 flex items-center justify-center"
                                                @click="expanded = (expanded === '{{ $trf->id }}' ? null : '{{ $trf->id }}')"
                                                title="Lihat Detail Item">
                                                <svg class="w-5 h-5 transition-transform duration-300" :class="expanded === '{{ $trf->id }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Expanded Detail Panel -->
                                <tr x-show="expanded === '{{ $trf->id }}'"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 -translate-y-4"
                                    x-transition:enter-end="opacity-100 translate-y-0" class="z-10">
                                    <td colspan="5" class="px-8 pb-4 -mt-2">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-900/50 rounded-b-3xl border-x border-b border-gray-100 dark:border-gray-700 p-6 shadow-xl relative overflow-hidden">
                                            <!-- Visual accents -->
                                            <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-600/30"></div>

                                            <div class="flex items-center justify-between mb-4">
                                                <h4
                                                    class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] flex items-center gap-2">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M4 6h16M4 12h16M4 18h16" />
                                                    </svg>
                                                    Daftar Item Transfer
                                                </h4>
                                                <div
                                                    class="bg-blue-600 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase truncate max-w-[200px]">
                                                    Admin: {{ $trf->user->name ?? 'Sistem' }}
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach($trf->details as $detail)
                                                    <div
                                                        class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm group/item hover:border-blue-200 transition-colors">
                                                        <div
                                                            class="w-10 h-10 rounded-xl bg-gray-50 flex-shrink-0 overflow-hidden">
                                                            @php
                                                                $gambar = $detail->barang->gambarBarangs->where('gambar_utama', true)->first() ?? $detail->barang->gambarBarangs->first();
                                                                $path = $gambar ? asset('storage/' . $gambar->path) : 'https://ui-avatars.com/api/?name=' . urlencode($detail->barang->nama);
                                                            @endphp
                                                            <img src="{{ $path }}" class="w-full h-full object-cover">
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span
                                                                class="text-xs font-black text-gray-900 dark:text-white truncate group-hover/item:text-blue-600 transition-colors">{{ $detail->barang->nama }}</span>
                                                            <div class="flex items-center gap-2">
                                                                <span
                                                                    class="text-[9px] font-mono text-gray-400 font-bold tracking-tight uppercase">{{ $detail->barang->sku }}</span>
                                                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                                                <span
                                                                    class="text-[10px] font-black text-blue-600 uppercase">{{ $detail->jumlah }}
                                                                    {{ $detail->barang->satuan->nama ?? 'Unit' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if($trf->catatan)
                                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                                    <p
                                                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1.5">
                                                        Catatan</p>
                                                    <p class="text-xs text-gray-600 italic">"{{ $trf->catatan }}"</p>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center">
                                        <div class="flex justify-center mb-6">
                                            <div class="relative">
                                                <div
                                                    class="absolute -inset-4 bg-blue-100 rounded-full blur-2xl opacity-50 animate-pulse">
                                                </div>
                                                <div
                                                    class="relative p-6 bg-white rounded-full shadow-2xl border border-blue-50">
                                                    <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tighter">
                                            Database Kosong</h3>
                                        <p class="text-sm text-gray-400 font-medium max-w-xs mx-auto">Kami tidak dapat
                                            menemukan data transfer yang Anda cari. Coba gunakan kata kunci lain.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div
                class="relative p-8 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/80 backdrop-blur-md">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-3 w-3">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                            </span>
                            <div class="flex flex-col">
                                <span
                                    class="text-[10px] font-black uppercase text-gray-900 dark:text-white leading-none mb-1">Pending</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">In
                                    Transit</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="p-1 bg-emerald-500 rounded-full">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span
                                    class="text-[10px] font-black uppercase text-gray-900 dark:text-white leading-none mb-1">Received</span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Inventory
                                    Restocked</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hidden md:inline">Total
                            Record: {{ $this->pendingTransfers->count() }}</span>
                        <button type="button" data-modal-hide="modal-riwayat-transfer"
                            class="px-10 py-3.5 bg-gray-900 hover:bg-black text-white rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-gray-200 active:scale-95">Tutup
                            Panel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>