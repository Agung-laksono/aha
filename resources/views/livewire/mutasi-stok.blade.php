<div class="py-12 min-h-screen bg-gray-50 dark:bg-gray-900/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Flash Messages -->
        <div x-data="{ showModal: @entangle('showTransferModal') }" x-show="!showModal && !@js($showReceiveModal)">
            @if (session()->has('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center gap-3 text-emerald-700 animate-bounce-short shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-xs font-bold">{{ session('success') }}</p>
                </div>
            @endif
            @if (session()->has('error'))
                <div
                    class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-3xl flex items-center gap-3 text-rose-700 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-xs font-bold">{{ session('error') }}</p>
                </div>
            @endif
        </div>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h2
                    class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-none italic">
                    Mutasi Stok
                </h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="h-1 w-8 bg-blue-600 rounded-full"></span>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">
                        Logistics Distribution Center
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative flex-grow md:w-64">
                    <input type="text" wire:model.live.debounce.300ms="searchTransfer"
                        placeholder="Cari ID Mutasi atau Gudang..."
                        class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border-0 rounded-2xl text-[11px] font-bold shadow-sm focus:ring-4 focus:ring-blue-100 transition-all dark:text-white dark:placeholder-gray-500">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <button wire:click="$set('showTransferModal', true)"
                    class="px-8 py-3.5 bg-blue-600 text-white rounded-2xl font-black uppercase text-[11px] tracking-widest shadow-2xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 active:scale-95 transition-all flex items-center gap-2 group">
                    <div class="p-1 bg-blue-500 rounded-lg group-hover:rotate-90 transition-all">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    Buat Mutasi
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 mb-8">
            <button wire:click="$set('currentTab', 'all')"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $currentTab === 'all' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-400 hover:bg-gray-100' }}">
                Semua Riwayat
            </button>
            <button wire:click="$set('currentTab', 'pending')"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $currentTab === 'pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-100' : 'bg-white text-gray-400 hover:bg-gray-100' }}">
                Dalam Perjalanan
            </button>
            <button wire:click="$set('currentTab', 'received')"
                class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $currentTab === 'received' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-100' : 'bg-white text-gray-400 hover:bg-gray-100' }}">
                Sudah Sampai
            </button>
        </div>

        <!-- Main Content (Table) -->
        <div
            class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                            <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Info
                                Mutasi</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Rute
                                Distribusi</th>
                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Status
                                & Qty</th>
                            <th
                                class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right uppercase tracking-widest">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($this->transfers as $trf)
                            <tr wire:click="showDetail({{ $trf->id }})"
                                class="group hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all cursor-pointer">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p
                                                class="text-[13px] font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-none">
                                                #{{ $trf->nomor_transfer }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">
                                                {{ \Carbon\Carbon::parse($trf->tanggal)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="text-center">
                                            <span
                                                class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-[9px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-300 block">{{ $trf->gudangAsal->nama }}</span>
                                            <p class="text-[8px] font-bold text-gray-400 uppercase mt-1">Origin</p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                        <div class="text-center">
                                            <span
                                                class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400 block">{{ $trf->gudangTujuan->nama }}</span>
                                            <p class="text-[8px] font-bold text-gray-400 uppercase mt-1">Destination</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-4">
                                        <div>
                                            @if($trf->status === 'pending')
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 ring-4 ring-amber-50">SHIPPING</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 ring-4 ring-emerald-50">RECEIVED</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-900 dark:text-white leading-none">
                                                {{ $trf->total_qty }} Items
                                            </p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">
                                                Total Mutasi</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    <div class="flex justify-end items-center gap-2">
                                        <button wire:click.stop="downloadSuratJalan({{ $trf->id }})"
                                            title="Cetak Surat Jalan"
                                            class="p-2.5 bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-gray-900 hover:text-white dark:hover:bg-white dark:hover:text-gray-900 rounded-xl transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </button>

                                        <button wire:click.stop="showDetail({{ $trf->id }})" title="Lihat Detail"
                                            class="p-2.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all active:scale-90">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        @if ($trf->status === 'pending')
                                            <button wire:click.stop="selectForReceive({{ $trf->id }})"
                                                class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 hover:bg-emerald-700 active:scale-95 transition-all">
                                                Konfirmasi Terima
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="inline-block p-6 bg-gray-50 dark:bg-gray-900/50 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Tidak ada record
                                        mutasi</h3>
                                    <p class="text-xs text-gray-400 mt-2">Coba sesuaikan filter atau cari nomor transfer
                                        yang berbeda</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($this->transfers->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-800/50 border-t dark:border-gray-700">
                    {{ $this->transfers->links() }}
                </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- MODAL: BUAT MUTASI BARU -->
        <!-- ========================================== -->
        <div x-data="{ show: @entangle('showTransferModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm shadow-2xl transition-all duration-300">
            <div @click.away="show = false"
                class="bg-white dark:bg-gray-800 w-full max-w-4xl rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[95vh] animate-in fade-in zoom-in slide-in-from-bottom duration-300">

                <!-- Modal Header -->
                <div
                    class="px-10 py-8 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/30 dark:bg-gray-800/50">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Form
                            Mutasi Stok</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Sistem
                            Perpindahan Logistik Antar Unit</p>
                    </div>
                    <button @click="show = false"
                        class="p-3 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-full transition-all group">
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-rose-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-10 flex-grow overflow-y-auto custom-scrollbar">
                    <!-- Internal Flash Messages for Modal -->
                    @if (session()->has('success') || session()->has('error'))
                        <div class="mb-8">
                            @if (session()->has('success'))
                                <div
                                    class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p class="text-xs font-bold">{{ session('success') }}</p>
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div
                                    class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <p class="text-xs font-bold">{{ session('error') }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <!-- Origin -->
                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Gudang
                                Asal (Pengirim)</label>
                            <div class="relative">
                                <select wire:model.live="gudangAsalId"
                                    class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700 border-0 rounded-2xl text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-blue-100 transition-all dark:text-white">
                                    <option value="">Pilih Origin</option>
                                    @foreach($accessibleGudangs as $g)
                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Gudang
                                Tujuan (Penerima)</label>
                            <div class="relative">
                                <select wire:model.live="gudangTujuanId"
                                    class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-700 border-0 rounded-2xl text-[11px] font-black uppercase tracking-widest focus:ring-4 focus:ring-blue-100 transition-all dark:text-white @error('gudangTujuanId') ring-2 ring-rose-500 @enderror">
                                    <option value="">Pilih Destinasi</option>
                                    @foreach($gudangs as $g)
                                        @if($g->id != $gudangAsalId)
                                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            @error('gudangTujuanId') <p class="text-[9px] font-bold text-rose-500 uppercase mt-1 pl-2">
                                {{ $message }}
                            </p> @enderror
                        </div>
                    </div>

                    <!-- Date & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Tanggal
                                & Waktu</label>
                            <input type="date" wire:model.live="tanggalTransfer"
                                class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-700 border-0 rounded-2xl text-[11px] font-black focus:ring-4 focus:ring-blue-100 transition-all dark:text-white">
                        </div>
                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Catatan
                                Pengiriman</label>
                            <textarea wire:model="keteranganTransfer"
                                placeholder="Contoh: Stok Kurang, Kirim Cepat, dll"
                                class="w-full px-5 py-4 bg-gray-50 dark:bg-gray-700 border-0 rounded-2xl text-[11px] font-bold focus:ring-4 focus:ring-blue-100 transition-all dark:text-white min-h-[100px]"></textarea>
                        </div>
                    </div>

                    <!-- Item Selector -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-white">
                                Isi Keranjang Mutasi</h4>
                            <span
                                class="px-4 py-1.5 bg-blue-600 text-white rounded-full text-[9px] font-black uppercase tracking-widest">{{ count($transferItems) }}
                                ITEMS</span>
                        </div>

                        <!-- Search Item -->
                        <div class="relative" x-data="{ open: @entangle('showSuggestions') }">
                            <input type="text" wire:model.live.debounce.300ms="searchTransferItem" @focus="open = true"
                                placeholder="Cari Kode atau Nama Barang..."
                                class="w-full pl-12 pr-4 py-5 bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 rounded-[2rem] text-[11px] font-bold shadow-xl shadow-gray-100 focus:ring-0 focus:border-blue-600 transition-all dark:text-white">
                            <div
                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            @if($this->searchBarangResults->count() > 0)
                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-60 mt-3 w-full bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-gray-700 p-3 max-h-60 overflow-y-auto custom-scrollbar animate-in fade-in slide-in-from-top-2 duration-200">
                                    <div class="px-4 py-2 border-b border-gray-50 dark:border-gray-700 mb-2">
                                        <p class="text-[8px] font-black text-blue-600 uppercase tracking-widest">Ditemukan {{ $this->searchBarangResults->count() }} Hasil</p>
                                    </div>
                                    @foreach($this->searchBarangResults as $b)
                                        <button type="button" wire:click="addToTransferCart({{ $b->id }})"
                                            class="w-full flex items-center gap-4 p-4 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-2xl transition-all text-left group">
                                            <div class="grow">
                                                <p
                                                    class="text-[12px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                                    {{ $b->nama }}
                                                </p>
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                                    SKU: {{ $b->sku }}</p>
                                            </div>
                                            <div
                                                class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-lg group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Cart Items List -->
                        <div class="space-y-3 mt-6">
                            @forelse($transferItems as $idx => $item)
                                <div
                                    class="p-6 bg-gray-50/50 dark:bg-gray-700/30 rounded-3xl border {{ $errors->has('transferItems.' . $idx . '.qty') ? 'border-rose-300 bg-rose-50/20' : 'border-gray-50 dark:border-gray-700' }} flex items-center gap-6 group hover:border-blue-200 transition-all shadow-sm">
                                    <div class="grow">
                                        <h5
                                            class="text-[12px] font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                            {{ $item['nama'] }}
                                        </h5>
                                        <div class="flex items-center gap-2 mt-1.5">
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">SKU:
                                                {{ $item['sku'] }}</span>
                                            <span class="h-1 w-1 bg-gray-300 rounded-full"></span>
                                            <span
                                                class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Tersedia:
                                                {{ $item['stok_asal'] }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <label
                                                class="block text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Kirim
                                                Qty</label>
                                            <input type="number"
                                                wire:model.live.debounce.500ms="transferItems.{{ $idx }}.qty"
                                                class="w-24 px-4 py-2.5 bg-white dark:bg-gray-800 border-0 rounded-xl text-center text-xs font-black focus:ring-2 focus:ring-blue-600 shadow-sm">
                                        </div>
                                        <button wire:click="removeFromTransferCart({{ $idx }})"
                                            class="p-3 text-gray-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @error('transferItems.' . $idx . '.qty') <p
                                    class="text-[9px] font-bold text-rose-600 uppercase mt-1 pl-4">{{ $message }}</p>
                                @enderror
                            @empty
                                <div
                                    class="py-16 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-[3rem] flex flex-col items-center justify-center">
                                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em]">Keranjang
                                        Masih Kosong</p>
                                    <p class="text-[9px] font-medium text-gray-400 mt-2 italic px-8 text-center">Silakan
                                        ketik nama barang di kotak pencarian di atas untuk menambahkan ke daftar mutasi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="p-10 border-t border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/80 flex justify-between items-center">
                    <button @click="show = false"
                        class="px-8 py-3.5 text-[11px] font-black uppercase tracking-widest text-gray-400 hover:bg-white rounded-2xl transition-all">Batalkan</button>
                    <button wire:click="saveTransfer" wire:loading.attr="disabled"
                        class="px-12 py-4 bg-gray-900 dark:bg-blue-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl hover:bg-blue-600 transition-all active:scale-95 disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveTransfer">Proses Distribusi</span>
                        <span wire:loading wire:target="saveTransfer">Menghubungkan Database...</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: KONFIRMASI TERIMA -->
        <!-- ========================================== -->
        <div x-data="{ show: @entangle('showReceiveModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md transition-opacity duration-300">
            <div @click.away="show = false"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden animate-in zoom-in duration-300">

                <div class="p-10">
                    <!-- Internal Flash Messages for Modal -->
                    @if (session()->has('error'))
                        <div
                            class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <p class="text-xs font-bold">{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="text-center mb-10">
                        <div
                            class="inline-block p-5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 rounded-3xl mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">
                            Konfirmasi Kedatangan</h3>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-2 leading-relaxed">
                            Pastikan barang yang diterima sesuai dengan fisik sebelum menekan tombol terima</p>
                    </div>

                    <!-- Photo Upload -->
                    <div class="space-y-4">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-gray-400 text-center mb-4">Bukti
                            Foto Kedatangan (Opsional)</label>

                        <div class="relative group">
                            @if ($fotoBukti)
                                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                                    <img src="{{ $fotoBukti }}" class="w-full h-56 object-cover aspect-video">
                                    <button wire:click="$set('fotoBukti', null)"
                                        class="absolute top-3 right-3 p-2 bg-rose-500 text-white rounded-full shadow-lg hover:rotate-90 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <div onclick="document.getElementById('input-foto-bukti').click()"
                                    class="w-full h-56 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-[2rem] flex flex-col items-center justify-center cursor-pointer hover:border-emerald-300 hover:bg-emerald-50/10 transition-all group">
                                    <div
                                        class="p-4 bg-gray-50 dark:bg-gray-700 rounded-2xl mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Klik Untuk
                                        Ambil Foto</p>
                                </div>
                            @endif
                            <!-- Input File tersembunyi untuk auto-compress dari app.blade.php -->
                            <input type="file" id="input-foto-bukti" class="hidden" accept="image/*"
                                onchange="handleAutoCompress(this, 'fotoBukti', @this)">
                        </div>
                    </div>

                    <!-- Receiver Notes -->
                    <div class="mt-8 space-y-4">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Catatan Penerimaan (Opsional)</label>
                        <textarea wire:model="keteranganPenerima" placeholder="Contoh: Kondisi barang baik, Ada sedikit penyok di kardus, dll..."
                            class="w-full px-6 py-4 bg-gray-50 dark:bg-gray-900 border-0 rounded-3xl text-[11px] font-bold focus:ring-4 focus:ring-emerald-100 transition-all dark:text-white dark:placeholder-gray-500 min-h-[100px]"></textarea>
                    </div>
                </div>

                <div class="p-8 bg-gray-50/80 dark:bg-gray-800/80 border-t dark:border-gray-700 flex gap-4">
                    <button @click="show = false"
                        class="grow py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:bg-white rounded-2xl transition-all">Nanti
                        Saja</button>
                    <button wire:click="confirmReceive" wire:loading.attr="disabled"
                        class="grow px-8 py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-100 hover:bg-emerald-700 active:scale-95 transition-all">
                        Konfirmasi Barang Sampai
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL: DETAIL MUTASI -->
        <!-- ========================================== -->
        <div x-data="{ show: @entangle('showDetailModal') }" x-show="show" x-cloak
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all duration-300">
            <div @click.away="show = false"
                class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in duration-300">

                @if ($selectedTransfer)
                    <!-- Header -->
                    <div
                        class="px-10 py-8 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center bg-gray-50/30 dark:bg-gray-800/50">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                    Detail Mutasi
                                </h3>
                                @if ($selectedTransfer->status === 'pending')
                                    <span
                                        class="px-3 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase tracking-widest rounded-full">Shipping</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-full">Received</span>
                                @endif
                            </div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">
                                #{{ $selectedTransfer->nomor_transfer }} • {{ $selectedTransfer->tanggal->format('d M Y') }}
                            </p>
                        </div>
                        <button @click="show = false"
                            class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-all">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-10 overflow-y-auto custom-scrollbar">
                        <!-- Route Info -->
                        <div
                            class="grid grid-cols-3 items-center gap-4 mb-10 p-6 bg-gray-50 dark:bg-gray-900/40 rounded-[2rem] border border-gray-100 dark:border-gray-700">
                            <div class="text-center">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Asal</p>
                                <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase">
                                    {{ $selectedTransfer->gudangAsal->nama }}</p>
                            </div>
                            <div class="flex flex-col items-center">
                                <div class="w-full h-px bg-gray-200 dark:bg-gray-700 relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="bg-blue-600 p-1 rounded-full text-white">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Tujuan</p>
                                <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase">
                                    {{ $selectedTransfer->gudangTujuan->nama }}</p>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="space-y-4 mb-8">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Daftar Barang
                            </h4>
                            <div class="border border-gray-50 dark:border-gray-700 rounded-3xl overflow-hidden">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50 dark:bg-gray-900/20">
                                        <tr>
                                            <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400">Nama Barang
                                            </th>
                                            <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400 text-center">
                                                Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                        @foreach ($selectedTransfer->details as $det)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <p class="text-[11px] font-black text-gray-900 dark:text-white uppercase">
                                                        {{ $det->barang->nama }}</p>
                                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                                                        {{ $det->barang->sku }}
                                                    </p>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <span
                                                        class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-[10px] font-black">{{ $det->jumlah }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Evidence Photo & Notes -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($selectedTransfer->foto_bukti)
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1 mb-3">
                                        Bukti Diterima</h4>
                                    <div
                                        class="rounded-3xl overflow-hidden border-4 border-white dark:border-gray-700 shadow-lg">
                                        <img src="{{ asset('storage/' . $selectedTransfer->foto_bukti) }}"
                                            class="w-full h-40 object-cover">
                                    </div>
                                </div>
                            @endif
                            <div class="{{ $selectedTransfer->foto_bukti ? '' : 'col-span-2' }}">
                                <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1 mb-3">Catatan
                                    / Keterangan</h4>
                                <div
                                    class="p-6 bg-gray-50 dark:bg-gray-900/40 rounded-3xl border border-gray-100 dark:border-gray-700 min-h-[100px]">
                                    <p class="text-[11px] font-medium text-gray-600 dark:text-gray-300 italic">
                                        {{ $selectedTransfer->keterangan ?: 'Tidak ada catatan tambahan.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div
                        class="px-10 py-8 border-t border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/80 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-[10px] font-black text-gray-500">
                                {{ substr($selectedTransfer->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-900 dark:text-white uppercase leading-none">
                                    {{ $selectedTransfer->user->name }}</p>
                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Petugas
                                    Operasional</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click.stop="downloadSuratJalan({{ $selectedTransfer->id }})"
                                class="px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95">
                                Cetak PDF
                            </button>
                            @if ($selectedTransfer->status === 'pending')
                                <button wire:click="selectForReceive({{ $selectedTransfer->id }})"
                                    class="px-6 py-3 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100 transition-all active:scale-95">
                                    Terima Barang
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>