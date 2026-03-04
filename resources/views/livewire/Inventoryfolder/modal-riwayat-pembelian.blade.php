<div id="modal-riwayat-pembelian" tabindex="-1" aria-hidden="true" wire:ignore.self wire:poll.10s
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-full max-h-full">
    <div class="relative p-0 w-full max-w-7xl h-full md:h-[95vh] flex items-center justify-center">
        <!-- Modal content -->
        <div
            class="relative bg-white md:rounded-3xl shadow-2xl dark:bg-gray-800 w-full h-full md:h-full flex flex-col overflow-hidden border dark:border-gray-700">

            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Riwayat
                            Pembelian</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Manajemen Transaksi PRO
                            & Stok Masuk</p>
                    </div>
                </div>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-2xl text-sm w-10 h-10 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-all"
                    data-modal-hide="modal-riwayat-pembelian">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="flex flex-col lg:flex-row flex-grow overflow-hidden">
                <!-- Sidebar/Header: List Transaksi (Responsive Switch) -->
                <div
                    class="w-full lg:w-[160px] lg:h-full h-auto border-b lg:border-b-0 lg:border-r dark:border-gray-700 flex flex-col bg-gray-50/50 dark:bg-gray-800/50 relative z-[1] flex-shrink-0">
                    <div
                        class="p-1.5 sm:p-2 border-b dark:border-gray-700 space-y-1.5 bg-white dark:bg-gray-800 flex-shrink-0">
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none text-gray-400">
                                <svg wire:loading.remove wire:target="searchHistory" class="w-3 h-3" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <svg wire:loading wire:target="searchHistory"
                                    class="w-3 h-3 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="searchHistory"
                                class="w-full pl-6 pr-2 py-1.5 text-[9px] border border-gray-100 rounded-lg focus:ring-1 focus:ring-emerald-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold"
                                placeholder="Cari...">
                        </div>
                    </div>

                    <div
                        class="flex flex-row lg:flex-col flex-grow overflow-x-auto lg:overflow-y-auto custom-scrollbar lg:divide-y divide-x lg:divide-x-0 divide-gray-100 dark:divide-gray-700">
                        @forelse($this->pembelians as $p)
                            <button wire:click="selectPembelian({{ $p->id }})"
                                class="flex-shrink-0 w-[120px] sm:w-[140px] lg:w-full p-2 sm:p-2.5 text-left transition-all hover:bg-white dark:hover:bg-gray-700 {{ $selectedPembelianId == $p->id ? 'bg-white dark:bg-gray-700 border-b-2 lg:border-b-0 lg:border-l-2 border-emerald-500 shadow-sm' : '' }}">
                                <div class="mb-0.5 max-w-full">
                                    <p
                                        class="text-[7px] sm:text-[9px] font-black font-mono text-gray-900 dark:text-white uppercase truncate">
                                        {{ $p->nomor_nota }}</p>
                                </div>
                                <div class="flex justify-between items-center gap-1">
                                    <p class="text-[8px] sm:text-[10px] font-bold text-emerald-600 truncate flex-1">
                                        {{ $p->vendor->nama ?? 'Umum' }}
                                    </p>
                                    <span
                                        class="text-[7px] sm:text-[8px] font-black shrink-0 {{ $p->status === 'Received' ? 'text-green-500' : ($p->status === 'Cancelled' ? 'text-rose-500' : 'text-orange-500') }}">
                                        {{ substr(strtoupper($p->status), 0, 3) }}
                                    </span>
                                </div>
                                @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                    <p
                                        class="text-[8px] sm:text-[9px] font-black text-gray-900 dark:text-white mt-0.5 opacity-70">
                                        {{ number_format($p->total_harga, 0, ',', '.') }}
                                    </p>
                                @endif

                                @php
                                    $pendingRequestsQty = $p->details->sum('qty_retur_request');
                                @endphp
                                @if($pendingRequestsQty > 0)
                                    <div
                                        class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-amber-50 text-amber-600 rounded-lg border border-amber-200 shadow-sm animate-pulse w-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-[9px] font-black uppercase tracking-widest">{{ $pendingRequestsQty }}
                                            Unit Retur Pending</span>
                                    </div>
                                @endif
                            </button>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Baris riwayat
                                    masih kosong</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Main Content: Detail Transaksi -->
                <div class="flex-grow flex flex-col min-h-0 bg-white dark:bg-gray-800 relative z-[10] overflow-hidden">
                    <!-- Loading Overlay for Detail Selection -->
                    <div wire:loading wire:target="selectPembelian"
                        class="absolute inset-0 z-[100] flex items-center justify-center backdrop-blur-sm bg-white/50 dark:bg-gray-800/50">
                        <div class="flex flex-col items-center gap-4">
                            <div class="relative w-16 h-16">
                                <div
                                    class="absolute inset-0 rounded-full border-4 border-emerald-100 dark:border-emerald-900/30">
                                </div>
                                <div
                                    class="absolute inset-0 rounded-full border-4 border-emerald-500 border-t-transparent animate-spin">
                                </div>
                            </div>
                            <p
                                class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-[0.3em] animate-pulse">
                                Memuat Transaksi...</p>
                        </div>
                    </div>

                    @if($this->selectedPembelian)
                        <div class="flex-grow overflow-y-auto p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 custom-scrollbar">
                            <!-- Header Detail -->
                            <div class="flex flex-col xl:flex-row justify-between items-start gap-4">
                                <div class="space-y-2 w-full xl:w-auto">
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                        <h2
                                            class="text-xl sm:text-2xl md:text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter truncate">
                                            {{ $this->selectedPembelian->nomor_nota }}
                                        </h2>
                                        <span
                                            class="px-2 sm:px-3 py-0.5 sm:py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-[8px] sm:text-[10px] font-black uppercase tracking-widest text-gray-500 whitespace-nowrap">{{ $this->selectedPembelian->status_pembayaran }}</span>
                                        <span
                                            class="px-2 sm:px-3 py-0.5 sm:py-1 {{ $this->selectedPembelian->metode_pembayaran === 'Kredit' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }} rounded-full text-[8px] sm:text-[10px] font-black uppercase tracking-widest whitespace-nowrap">{{ $this->selectedPembelian->metode_pembayaran }}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $this->selectedPembelian->tanggal->translatedFormat('d F Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $this->selectedPembelian->created_at->format('H:i') }} WIB
                                        </div>
                                        <div class="h-4 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>
                                        <div class="flex items-center gap-2">
                                            @if($this->selectedPembelian->user)
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($this->selectedPembelian->user->name) }}&background=6366f1&color=fff"
                                                    class="w-5 h-5 rounded-full border border-white shadow-sm">
                                                <span class="text-gray-500 uppercase">Oleh: {{ $this->selectedPembelian->user->name }}</span>
                                            @else
                                                <span class="italic text-[10px]">Oleh: System</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 w-full xl:w-auto">
                                    @if($this->selectedPembelian->status === 'PO')
                                        {{-- Logistik only sees warehouses they have access to. Admin sees all. Filtered in
                                        render() --}}
                                        @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                            <button wire:click="markAsReceived({{ $this->selectedPembelian->id }})"
                                                wire:confirm="Konfirmasi: Anda akan menerima seluruh barang dalam pesanan ini dan menambahkannya ke stok. Lanjutkan?"
                                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-3 sm:px-5 py-2 sm:py-2.5 bg-emerald-50 text-emerald-600 rounded-xl sm:rounded-2xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 group">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span
                                                    class="text-[10px] sm:text-xs font-black uppercase tracking-widest whitespace-nowrap">Terima
                                                    Semua</span>
                                            </button>
                                        @endif
                                    @endif

                                    @if($this->selectedPembelian->status !== 'Cancelled')
                                        @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                            <button wire:click="cancelPembelian({{ $this->selectedPembelian->id }})"
                                                wire:confirm="PERINGATAN KRITIS: Anda akan membatalkan seluruh nota ini. Stok akan dikurangi kembali dan saldo kas akan dikembalikan. Lanjutkan?"
                                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-3 sm:px-5 py-2 sm:py-2.5 bg-rose-50 text-rose-600 rounded-xl sm:rounded-2xl hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100 group">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 group-hover:animate-pulse" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span
                                                    class="text-[10px] sm:text-xs font-black uppercase tracking-widest whitespace-nowrap">Batalkan
                                                    Nota</span>
                                            </button>
                                        @endif
                                    @endif

                                    <a href="/print-po/{{ $this->selectedPembelian->id }}" target="_blank"
                                        class="p-2 sm:p-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl sm:rounded-2xl hover:bg-gray-200 transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>

                                    @if($this->selectedPembelian->metode_pembayaran === 'Kredit' && $this->selectedPembelian->sisa_tagihan > 0)
                                        <button wire:click="openPelunasanModal({{ $this->selectedPembelian->id }})"
                                            class="flex items-center gap-2 px-4 py-2 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-500/20 active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest whitespace-nowrap">Pelunasan</span>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Dashboard Mini Info -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                                <div
                                    class="p-4 sm:p-5 bg-gradient-to-br from-gray-50 to-white dark:from-gray-700/50 dark:to-gray-800 border dark:border-gray-700 rounded-2xl sm:rounded-3xl shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Pihak
                                        Vendor</p>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-600 rounded-xl sm:rounded-2xl flex items-center justify-center text-white text-base sm:text-lg font-black shadow-lg shadow-emerald-500/20 flex-shrink-0">
                                            {{ strtoupper(substr($this->selectedPembelian->vendor->nama ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-xs sm:text-sm font-black text-gray-900 dark:text-white leading-tight uppercase truncate">
                                                {{ $this->selectedPembelian->vendor->nama ?? 'Umum' }}
                                            </p>
                                            <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase truncate">
                                                {{ $this->selectedPembelian->vendor->kontak ?? 'No Contact' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                    <div
                                        class="p-4 sm:p-5 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-900/10 dark:to-gray-800 border border-emerald-100 dark:border-emerald-800/30 rounded-2xl sm:rounded-3xl shadow-sm">
                                        <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-3">Total
                                            Investasi</p>
                                        <h4 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white truncate">
                                            Rp{{ number_format($this->selectedPembelian->total_harga, 0, ',', '.') }}</h4>
                                        <p class="text-[10px] font-bold text-emerald-600 uppercase mt-1 italic">
                                            {{ $this->selectedPembelian->metode_pembayaran }}
                                        </p>
                                    </div>
                                @endif

                                <div
                                    class="p-4 sm:p-5 bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-900/10 dark:to-gray-800 border border-indigo-100 dark:border-indigo-800/30 rounded-2xl sm:rounded-3xl shadow-sm sm:col-span-2 lg:col-span-1">
                                    <p class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mb-3">Status
                                        Logistik</p>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 font-bold" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2M4 13H6m4 0h4" />
                                            </svg>
                                        </div>
                                        <p
                                            class="text-[10px] sm:text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            {{ $this->selectedPembelian->status }}
                                        </p>
                                    </div>
                                    @if($this->selectedPembelian->status === 'PO' && $this->selectedPembelian->jatuh_tempo)
                                        <p class="text-[9px] sm:text-[10px] font-bold text-rose-500 mt-2">Jatuh Tempo:
                                            {{ \Carbon\Carbon::parse($this->selectedPembelian->jatuh_tempo)->translatedFormat('d F Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Daftar Item -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between px-2">
                                    <h4 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest">
                                        Detail Item Pembelian</h4>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ count($this->selectedPembelian->details) }}
                                        Jenis Barang</span>
                                </div>
                                <div
                                    class="border border-gray-100 dark:border-gray-700 rounded-2xl sm:rounded-3xl overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-left text-xs sm:text-sm min-w-[800px] lg:min-w-0">
                                        <thead
                                            class="bg-gray-50 dark:bg-gray-700/50 text-[8px] sm:text-[10px] uppercase font-black tracking-widest text-gray-400">
                                            <tr>
                                                <th class="px-4 sm:px-6 py-3 sm:py-4">Item & SKU</th>
                                                <th class="px-2 sm:px-4 py-3 sm:py-4 text-center">Pesan</th>
                                                <th class="px-2 sm:px-4 py-3 sm:py-4 text-center">Terima</th>
                                                @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4">Harga</th>
                                                @endif
                                                <th class="px-4 sm:px-6 py-3 sm:py-4">Gudang</th>
                                                @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-right">Subtotal</th>
                                                @endif
                                                <th class="px-4 sm:px-6 py-3 sm:py-4 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                            @foreach($this->selectedPembelian->details as $idx => $d)
                                                <tr x-data="{ rowActive: false }"
                                                    :class="{'z-[160] relative': rowActive, 'z-0': !rowActive}"
                                                    class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-all">
                                                    <td class="px-6 py-4">
                                                        <p
                                                            class="font-bold text-gray-900 dark:text-white leading-tight underline decoration-gray-200 underline-offset-4">
                                                            {{ $d->barang->nama }}
                                                        </p>
                                                        <p
                                                            class="text-[10px] font-black font-mono text-gray-400 mt-1 uppercase">
                                                            {{ $d->barang->sku }}
                                                        </p>

                                                        @if($d->status_item === 'Received')
                                                            <span
                                                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded-full mt-2">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="3" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Lengkap
                                                            </span>
                                                        @elseif($d->status_item === 'Partial')
                                                            <span
                                                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-600 text-[8px] font-black uppercase rounded-full mt-2">
                                                                Parsial
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[8px] font-black uppercase rounded-full mt-2">
                                                                Belum Diterima
                                                            </span>
                                                        @endif

                                                        <div class="flex gap-2 mt-2">
                                                            @if($d->catatan)
                                                                <div x-data="{ open: false }" class="relative inline-block">
                                                                    <button @mouseenter="open = true; rowActive = true"
                                                                        @mouseleave="open = false; rowActive = false"
                                                                        class="w-6 h-6 flex items-center justify-center bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all shadow-sm border border-amber-200">
                                                                        <span class="text-[10px] font-black">V</span>
                                                                    </button>
                                                                    <div x-show="open" x-cloak
                                                                        x-transition:enter="transition ease-out duration-200"
                                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                        class="absolute left-0 bottom-full mb-3 z-[170] w-72 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-amber-400">
                                                                        <div class="flex justify-between items-center mb-2">
                                                                            <p
                                                                                class="text-[9px] font-black uppercase text-amber-600">
                                                                                Catatan Vendor</p>
                                                                            <svg class="w-3 h-3 text-amber-400" fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                                                                            </svg>
                                                                        </div>
                                                                        <div
                                                                            class="text-[11px] text-gray-700 dark:text-gray-300 prose prose-sm leading-relaxed max-h-48 overflow-y-auto custom-scrollbar">
                                                                            {!! $d->catatan !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($d->catatan_internal)
                                                                <div x-data="{ open: false }" class="relative inline-block">
                                                                    <button @mouseenter="open = true; rowActive = true"
                                                                        @mouseleave="open = false; rowActive = false"
                                                                        class="w-6 h-6 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-200">
                                                                        <span class="text-[10px] font-black">I</span>
                                                                    </button>
                                                                    <div x-show="open" x-cloak
                                                                        x-transition:enter="transition ease-out duration-200"
                                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                        class="absolute left-0 bottom-full mb-3 z-[170] w-72 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-indigo-400">
                                                                        <div class="flex justify-between items-center mb-2">
                                                                            <p
                                                                                class="text-[9px] font-black uppercase text-indigo-600">
                                                                                Catatan Internal</p>
                                                                            <svg class="w-3 h-3 text-indigo-400" fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                                                                            </svg>
                                                                        </div>
                                                                        <div
                                                                            class="text-[11px] text-gray-700 dark:text-gray-300 prose prose-sm leading-relaxed max-h-48 overflow-y-auto custom-scrollbar">
                                                                            {!! $d->catatan_internal !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span
                                                            class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg font-black text-gray-700 dark:text-gray-300">{{ $d->qty_pesan }}</span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center">
                                                        <span
                                                            class="px-3 py-1 {{ $d->qty_terima >= $d->qty_pesan ? 'bg-green-50 text-green-600' : 'bg-rose-50 text-rose-600' }} rounded-lg font-black">{{ $d->qty_terima }}</span>
                                                    </td>
                                                    @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                                        <td class="px-6 py-4 font-bold text-gray-600 dark:text-gray-400">
                                                            Rp{{ number_format($d->harga_beli, 0, ',', '.') }}</td>
                                                    @endif
                                                    <td class="px-6 py-4 font-black">
                                                        @if($this->selectedPembelian->status !== 'Cancelled' && $d->status_item !== 'Received' && (auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik')))
                                                            <div x-data="{ open: false }" class="relative inline-block">
                                                                <button @click="open = !open; rowActive = !rowActive"
                                                                    class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                                    </svg>
                                                                    Terima Item
                                                                </button>

                                                                <div x-show="open" x-cloak
                                                                    x-transition:enter="transition ease-out duration-200"
                                                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                    @click.away="open = false"
                                                                    class="absolute left-0 top-full mt-2 z-[170] w-64 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-emerald-500 space-y-3">

                                                                    <div>
                                                                        <p
                                                                            class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                                                                            Gudang Tujuan</p>
                                                                        <div class="flex gap-1 items-center">
                                                                            <select wire:model="gudangReceived.{{ $d->id }}"
                                                                                class="flex-grow text-[10px] font-black uppercase border-gray-100 rounded-lg focus:ring-emerald-500 bg-gray-50 py-1.5 px-2 outline-none">
                                                                                <option value="">-- Pilih --</option>
                                                                                @foreach($gudangs as $g)
                                                                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <button type="button" data-modal-target="modal-gudang"
                                                                                data-modal-toggle="modal-gudang"
                                                                                class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all border border-indigo-100 shadow-sm"
                                                                                title="Gudang Baru">
                                                                                <svg class="w-3.5 h-3.5" fill="none"
                                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round" stroke-width="2"
                                                                                        d="M12 4v16m8-8H4" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <div>
                                                                        <div class="flex justify-between items-center mb-1.5">
                                                                            <p
                                                                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                                                                Jumlah Terima</p>
                                                                            <span
                                                                                class="text-[8px] font-bold text-emerald-600 tracking-tight uppercase">Sisa:
                                                                                {{ $d->qty_pesan - $d->qty_terima }}</span>
                                                                        </div>
                                                                        <input type="number" wire:model="qtyReceived.{{ $d->id }}"
                                                                            class="w-full text-center text-xs font-black border-gray-100 rounded-lg bg-gray-50 py-2 outline-none focus:ring-emerald-500"
                                                                            max="{{ $d->qty_pesan - $d->qty_terima }}" min="1">
                                                                    </div>

                                                                    <button wire:click="receiveItem({{ $d->id }})"
                                                                        @click="open = false; rowActive = false"
                                                                        class="w-full bg-emerald-600 text-white text-[10px] font-black uppercase py-2.5 rounded-xl hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-95">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="3" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                        Konfirmasi Terima
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @else
                                                            @php
                                                                $receiveLogs = \App\Models\ActivityLog::where('subject_type', \App\Models\PembelianDetail::class)
                                                                    ->where('subject_id', $d->id)
                                                                    ->where('action', 'RECEIVE_ITEM')
                                                                    ->oldest()
                                                                    ->get();
                                                            @endphp

                                                            @if($receiveLogs->isNotEmpty())
                                                                <div class="space-y-1.5">
                                                                    @foreach($receiveLogs as $log)
                                                                        <div class="flex items-center gap-1.5 group">
                                                                            <div
                                                                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]">
                                                                            </div>
                                                                            <p
                                                                                class="text-[9px] font-black uppercase tracking-tight text-gray-600 dark:text-gray-400">
                                                                                <span
                                                                                    class="text-emerald-600 dark:text-emerald-400">{{ $log->properties['qty'] ?? 0 }}</span>
                                                                                <span class="text-[7px] text-gray-300">→</span>
                                                                                {{ $log->properties['gudang_nama'] ?? 'Unknown' }}
                                                                            </p>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="flex items-center gap-1.5">
                                                                    <div
                                                                        class="w-2 h-2 rounded-full {{ $d->qty_terima > 0 ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                                                    </div>
                                                                    <span
                                                                        class="text-[10px] font-black uppercase tracking-wider text-gray-500">{{ $d->gudang->nama ?? 'N/A' }}</span>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                                        <td
                                                            class="px-6 py-4 text-right font-black text-gray-900 dark:text-white italic tracking-tighter">
                                                            Rp{{ number_format($d->qty_pesan * $d->harga_beli, 0, ',', '.') }}</td>
                                                    @endif
                                                    <td class="px-6 py-4 text-center">
                                                        @if($this->selectedPembelian->status !== 'Cancelled' && $d->qty_terima > 0)
                                                            <div x-data="{ open: false }" class="relative flex justify-center"
                                                                :class="{'z-[120]': open, 'z-[10]': !open}">
                                                                <button @click="open = !open"
                                                                    class="flex items-center gap-2 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-rose-100 shadow-sm">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16" />
                                                                    </svg>
                                                                    Retur
                                                                </button>

                                                                <div x-show="open"
                                                                    x-transition:enter="transition ease-out duration-200"
                                                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                    x-transition:leave="transition ease-in duration-150"
                                                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                                                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                                                    @click.away="open = false"
                                                                    class="absolute right-0 top-full z-[130] mt-2 w-56 bg-white dark:bg-gray-800 border dark:border-gray-700 p-4 rounded-2xl shadow-2xl space-y-4 border-t-4 border-t-rose-500"
                                                                    style="display: none;">
                                                                    <!-- Pending Requests Indicator (Visible to Both) -->
                                                                    @if($d->qty_retur_request > 0)
                                                                        <div
                                                                            class="mb-3 p-3 bg-amber-50 border border-amber-200 rounded-xl relative overflow-hidden">
                                                                            <div
                                                                                class="absolute right-0 top-0 w-8 h-full bg-amber-400 opacity-20 transform skew-x-12 translate-x-2">
                                                                            </div>
                                                                            <p
                                                                                class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-1">
                                                                                @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                                                                    Pengajuan Logistik
                                                                                @else
                                                                                    Menunggu Eksekusi Admin
                                                                                @endif
                                                                            </p>
                                                                            <p class="text-xl font-black text-rose-600">
                                                                                {{ $d->qty_retur_request }} <span
                                                                                    class="text-[10px] text-gray-500 font-bold uppercase">Unit
                                                                                    diminta</span></p>
                                                                        </div>
                                                                    @endif

                                                                    <div class="space-y-2">
                                                                        <div class="flex justify-between items-center">
                                                                            <label
                                                                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                                                                @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                                                                Input Jml Eksekusi @else Jml Pengajuan @endif
                                                                            </label>
                                                                            <span
                                                                                class="text-[9px] font-bold text-rose-500 uppercase tracking-widest">Maks:
                                                                                {{ $d->qty_terima - $d->qty_retur_request }}</span>
                                                                        </div>
                                                                        <div class="relative">
                                                                            <input type="number" wire:model="qtyRetur.{{ $d->id }}"
                                                                                class="w-full p-2.5 text-sm font-black border border-gray-100 rounded-xl dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all"
                                                                                min="1"
                                                                                max="{{ $d->qty_terima - $d->qty_retur_request }}"
                                                                                placeholder="{{ $d->qty_retur_request > 0 ? $d->qty_retur_request : '0' }}">
                                                                            <div
                                                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400 uppercase">
                                                                                Unit</div>
                                                                        </div>
                                                                        @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                                                            <p class="text-[8px] text-gray-400 leading-tight italic">
                                                                                Biarkan kosong/0 untuk menyetujui sesuai jumlah
                                                                                pengajuan logistik (jika ada).</p>
                                                                        @endif
                                                                    </div>

                                                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                                                        <button wire:click="submitRetur({{ $d->id }})"
                                                                            @click="open = false"
                                                                            class="w-full mt-4 bg-rose-600 text-white py-2.5 text-[10px] font-black rounded-xl shadow-lg shadow-rose-500/30 hover:bg-rose-700 active:scale-95 transition-all uppercase tracking-widest flex justify-center items-center gap-2">
                                                                            Eksekusi & Refund
                                                                        </button>
                                                                    @else
                                                                        <button wire:click="askRetur({{ $d->id }})"
                                                                            @click="open = false"
                                                                            class="w-full mt-4 bg-amber-500 text-white py-2.5 text-[10px] font-black rounded-xl shadow-lg shadow-amber-500/30 hover:bg-amber-600 active:scale-95 transition-all uppercase tracking-widest">
                                                                            Kirim Pengajuan
                                                                        </button>
                                                                        <p class="text-[9px] mt-2 text-center text-gray-400 italic">
                                                                            Admin akan mengeksekusi retur berdasarkan pengajuan ini.</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="flex justify-center">
                                                                <span
                                                                    class="text-[9px] font-black text-gray-300 uppercase italic tracking-widest">N/A</span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if($this->selectedPembelian->pembayarans->count() > 0)
                                    <div class="mt-8 space-y-4">
                                        <div class="flex items-center justify-between px-1">
                                            <h4 class="text-[10px] font-black uppercase text-gray-400 tracking-[0.2em] flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Riwayat Pembayaran & Pelunasan
                                            </h4>
                                            <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[9px] font-black uppercase">Total Terbayar: Rp{{ number_format($this->selectedPembelian->terbayar, 0, ',', '.') }}</span>
                                        </div>

                                        <div class="overflow-hidden bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                            <table class="w-full text-left border-collapse">
                                                <thead>
                                                    <tr class="bg-gray-50/50 dark:bg-gray-700/30">
                                                        <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400 tracking-widest">Waktu</th>
                                                        <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400 tracking-widest">Akun Kas</th>
                                                        <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400 tracking-widest text-right">Nominal</th>
                                                        <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400 tracking-widest text-center">Oleh</th>
                                                        <th class="px-6 py-4 text-[9px] font-black uppercase text-gray-400 tracking-widest text-center">Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                                    @foreach($this->selectedPembelian->pembayarans as $bayar)
                                                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-700/20 transition-colors">
                                                            <td class="px-6 py-4">
                                                                <p class="text-[11px] font-black text-gray-900 dark:text-white leading-none mb-1 uppercase tracking-tighter">{{ $bayar->tanggal_bayar->translatedFormat('d M Y') }}</p>
                                                                <p class="text-[9px] font-bold text-gray-400 uppercase leading-none">{{ $bayar->created_at->format('H:i') }} WIB</p>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-wider">
                                                                    {{ $bayar->akunKas->nama ?? 'N/A' }}
                                                                </span>
                                                            </td>
                                                            <td class="px-6 py-4 text-right">
                                                                <p class="text-sm font-black text-rose-600">Rp{{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</p>
                                                            </td>
                                                            <td class="px-6 py-4 text-center">
                                                                @if($bayar->user)
                                                                    <div class="flex items-center gap-2 justify-center">
                                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($bayar->user->name) }}&background=6366f1&color=fff"
                                                                            class="w-5 h-5 rounded-full">
                                                                        <span class="text-[9px] font-bold text-gray-500 uppercase">{{ explode(' ', $bayar->user->name)[0] }}</span>
                                                                    </div>
                                                                @else
                                                                    <span class="text-[8px] font-black text-gray-300 uppercase italic">System</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 text-center">
                                                                @if($bayar->bukti_pembayaran)
                                                                    <a href="{{ asset('storage/' . $bayar->bukti_pembayaran) }}" target="_blank" class="inline-flex p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-blue-100 group" title="Lihat Bukti">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                    </a>
                                                                @else
                                                                    <span class="text-[8px] font-black text-gray-300 uppercase italic">No File</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if($this->selectedPembelian->keterangan)
                                <div
                                    class="p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-2xl border border-dashed dark:border-gray-700 flex items-start gap-3">
                                    <div class="p-1.5 bg-indigo-100 text-indigo-600 rounded-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Catatan
                                            Umum / Instruksi Khusus</p>
                                        <p class="text-[11px] text-gray-600 dark:text-gray-300 leading-relaxed italic">
                                            "{{ $this->selectedPembelian->keterangan }}"</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center flex-grow p-20 opacity-20">
                            <svg class="w-24 h-24 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-xl font-black uppercase tracking-widest">Pilih Nota untuk Detail</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>