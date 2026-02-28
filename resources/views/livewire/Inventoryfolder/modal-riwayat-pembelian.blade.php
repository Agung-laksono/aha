<!-- Modal Riwayat Pembelian (Full PRO Version) -->
<div id="modal-riwayat-pembelian" tabindex="-1" aria-hidden="true" wire:ignore.self
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
            <div class="flex flex-grow overflow-hidden">
                <!-- Sidebar: List Transaksi -->
                <div
                    class="w-1/3 border-r dark:border-gray-700 flex flex-col bg-gray-50/50 dark:bg-gray-800/50 relative z-[1]">
                    <div class="p-4 border-b dark:border-gray-700 space-y-3 bg-white dark:bg-gray-800">
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="searchHistory"
                                class="w-full pl-10 pr-4 py-2.5 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white font-bold"
                                placeholder="Cari NOMOR NOTA atau VENDOR...">
                        </div>
                    </div>

                    <div class="flex-grow overflow-y-auto custom-scrollbar">
                        @forelse($this->pembelians as $p)
                            <button wire:click="selectPembelian({{ $p->id }})"
                                class="w-full p-4 border-b dark:border-gray-700 text-left transition-all hover:bg-white dark:hover:bg-gray-700 {{ $selectedPembelianId == $p->id ? 'bg-white dark:bg-gray-700 border-l-4 border-l-emerald-500 shadow-sm' : '' }}">
                                <div class="flex justify-between items-start mb-1">
                                    <span
                                        class="text-xs font-black font-mono text-gray-900 dark:text-white uppercase">{{ $p->nomor_nota }}</span>
                                    <span
                                        class="text-[9px] font-bold text-gray-400">{{ $p->created_at->format('d/m/H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-[11px] font-bold text-emerald-600 truncate max-w-[150px]">
                                        {{ $p->vendor->nama ?? 'Umum' }}</p>
                                    <span
                                        class="text-[10px] font-black {{ $p->status === 'Received' ? 'text-green-500' : ($p->status === 'Cancelled' ? 'text-rose-500' : 'text-orange-500') }}">
                                        {{ strtoupper($p->status) }}
                                    </span>
                                </div>
                                <p class="text-[10px] font-black text-gray-900 dark:text-white mt-1">
                                    Rp{{ number_format($p->total_harga, 0, ',', '.') }}</p>
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
                <div class="flex-grow flex flex-col bg-white dark:bg-gray-800 relative z-[10]">
                    @if($this->selectedPembelian)
                        <div class="flex-grow overflow-y-auto p-6 md:p-8 space-y-8 custom-scrollbar">
                            <!-- Header Detail -->
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <h2
                                            class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                            {{ $this->selectedPembelian->nomor_nota }}</h2>
                                        <span
                                            class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-[10px] font-black uppercase tracking-widest text-gray-500">{{ $this->selectedPembelian->status_pembayaran }}</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-xs font-bold text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $this->selectedPembelian->created_at->translatedFormat('d F Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $this->selectedPembelian->created_at->format('H:i') }} WIB
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    @if($this->selectedPembelian->status === 'PO')
                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                        <button wire:click="markAsReceived({{ $this->selectedPembelian->id }})"
                                            wire:confirm="Konfirmasi: Anda akan menerima seluruh barang dalam pesanan ini dan menambahkannya ke stok. Lanjutkan?"
                                            class="flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-600 rounded-2xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 group">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="text-xs font-black uppercase tracking-widest">Terima Semua</span>
                                        </button>
                                    @endif
                                @endif

                                @if($this->selectedPembelian->status !== 'Cancelled')
                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                        <button wire:click="cancelPembelian({{ $this->selectedPembelian->id }})"
                                            wire:confirm="PERINGATAN KRITIS: Anda akan membatalkan seluruh nota ini. Stok akan dikurangi kembali dan saldo kas akan dikembalikan. Lanjutkan?"
                                            class="flex items-center gap-2 px-5 py-2.5 bg-rose-50 text-rose-600 rounded-2xl hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100 group">
                                            <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span class="text-xs font-black uppercase tracking-widest">Batalkan Nota</span>
                                        </button>
                                    @endif
                                @endif

                                    <a href="/print-po/{{ $this->selectedPembelian->id }}" target="_blank"
                                        class="p-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-2xl hover:bg-gray-200 transition-colors flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Dashboard Mini Info -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div
                                    class="p-5 bg-gradient-to-br from-gray-50 to-white dark:from-gray-700/50 dark:to-gray-800 border dark:border-gray-700 rounded-3xl shadow-sm">
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Pihak
                                        Vendor</p>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white text-lg font-black shadow-lg shadow-emerald-500/20">
                                            {{ strtoupper(substr($this->selectedPembelian->vendor->nama ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-black text-gray-900 dark:text-white leading-tight uppercase">
                                                {{ $this->selectedPembelian->vendor->nama ?? 'Umum' }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                                {{ $this->selectedPembelian->vendor->kontak ?? 'Tidak Ada Kontak' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="p-5 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-900/10 dark:to-gray-800 border border-emerald-100 dark:border-emerald-800/30 rounded-3xl shadow-sm">
                                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-3">Total
                                        Investasi</p>
                                    <h4 class="text-2xl font-black text-gray-900 dark:text-white">
                                        Rp{{ number_format($this->selectedPembelian->total_harga, 0, ',', '.') }}</h4>
                                    <p class="text-[10px] font-bold text-emerald-600 uppercase mt-1 italic">
                                        {{ $this->selectedPembelian->metode_pembayaran }}</p>
                                </div>

                                <div
                                    class="p-5 bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-900/10 dark:to-gray-800 border border-indigo-100 dark:border-indigo-800/30 rounded-3xl shadow-sm">
                                    <p class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mb-3">Status
                                        Logistik</p>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 rounded-xl flex items-center justify-center">
                                            <svg class="w-4 h-4 font-bold" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2M4 13H6m4 0h4" />
                                            </svg>
                                        </div>
                                        <p
                                            class="text-[11px] font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            {{ $this->selectedPembelian->status }}</p>
                                    </div>
                                    @if($this->selectedPembelian->status === 'PO' && $this->selectedPembelian->jatuh_tempo)
                                        <p class="text-[10px] font-bold text-rose-500 mt-2">Jatuh Tempo:
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
                                <div class="border border-gray-100 dark:border-gray-700 rounded-3xl overflow-visible">
                                    <table class="w-full text-left text-sm">
                                        <thead
                                            class="bg-gray-50 dark:bg-gray-700/50 text-[10px] uppercase font-black tracking-widest text-gray-400">
                                            <tr>
                                                <th class="px-6 py-4">Item & SKU</th>
                                                <th class="px-4 py-4 text-center">Qty Pesan</th>
                                                <th class="px-4 py-4 text-center">Qty Terima</th>
                                                <th class="px-6 py-4">Harga Satuan</th>
                                                <th class="px-6 py-4">Gudang & Penerimaan</th>
                                                <th class="px-6 py-4 text-right">Subtotal</th>
                                                <th class="px-6 py-4 text-center">Aksi</th>
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
                                                            {{ $d->barang->nama }}</p>
                                                        <p
                                                            class="text-[10px] font-black font-mono text-gray-400 mt-1 uppercase">
                                                            {{ $d->barang->sku }}</p>
                                                        
                                                        @if($d->status_item === 'Received')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase rounded-full mt-2">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                                Lengkap
                                                            </span>
                                                        @elseif($d->status_item === 'Partial')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-600 text-[8px] font-black uppercase rounded-full mt-2">
                                                                Parsial
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[8px] font-black uppercase rounded-full mt-2">
                                                                Belum Diterima
                                                            </span>
                                                        @endif

                                                        <div class="flex gap-2 mt-2">
                                                            @if($d->catatan)
                                                                <div x-data="{ open: false }" class="relative inline-block">
                                                                    <button @mouseenter="open = true; rowActive = true" @mouseleave="open = false; rowActive = false"
                                                                        class="w-6 h-6 flex items-center justify-center bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all shadow-sm border border-amber-200">
                                                                        <span class="text-[10px] font-black">V</span>
                                                                    </button>
                                                                    <div x-show="open" x-cloak
                                                                        x-transition:enter="transition ease-out duration-200"
                                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                        class="absolute left-0 bottom-full mb-3 z-[170] w-72 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-amber-400">
                                                                        <div class="flex justify-between items-center mb-2">
                                                                            <p class="text-[9px] font-black uppercase text-amber-600">Catatan Vendor</p>
                                                                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" /></svg>
                                                                        </div>
                                                                        <div class="text-[11px] text-gray-700 dark:text-gray-300 prose prose-sm leading-relaxed max-h-48 overflow-y-auto custom-scrollbar">
                                                                            {!! $d->catatan !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            
                                                            @if($d->catatan_internal)
                                                                <div x-data="{ open: false }" class="relative inline-block">
                                                                    <button @mouseenter="open = true; rowActive = true" @mouseleave="open = false; rowActive = false"
                                                                        class="w-6 h-6 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-200">
                                                                        <span class="text-[10px] font-black">I</span>
                                                                    </button>
                                                                    <div x-show="open" x-cloak
                                                                        x-transition:enter="transition ease-out duration-200"
                                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                                        class="absolute left-0 bottom-full mb-3 z-[170] w-72 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-indigo-400">
                                                                        <div class="flex justify-between items-center mb-2">
                                                                            <p class="text-[9px] font-black uppercase text-indigo-600">Catatan Internal</p>
                                                                            <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" /></svg>
                                                                        </div>
                                                                        <div class="text-[11px] text-gray-700 dark:text-gray-300 prose prose-sm leading-relaxed max-h-48 overflow-y-auto custom-scrollbar">
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
                                                    <td class="px-6 py-4 font-bold text-gray-600 dark:text-gray-400">
                                                        Rp{{ number_format($d->harga_beli, 0, ',', '.') }}</td>
                                                    <td class="px-6 py-4">
                                                        @if($d->status_item !== 'Received' && $this->selectedPembelian->status !== 'Cancelled')
                                                            <div x-data="{ open: false }" class="relative inline-block">
                                                                    <button @click="open = !open; rowActive = !rowActive"
                                                                        class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-emerald-100 shadow-sm">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
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
                                                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Gudang Tujuan</p>
                                                                        <div class="flex gap-1 items-center">
                                                                            <select wire:model="gudangReceived.{{ $d->id }}" 
                                                                                class="flex-grow text-[10px] font-black uppercase border-gray-100 rounded-lg focus:ring-emerald-500 bg-gray-50 py-1.5 px-2 outline-none">
                                                                                <option value="">-- Pilih --</option>
                                                                                @foreach(App\Models\Gudang::all() as $g)
                                                                                    <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <button type="button" data-modal-target="modal-gudang" data-modal-toggle="modal-gudang"
                                                                                class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all border border-indigo-100 shadow-sm"
                                                                                title="Gudang Baru">
                                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div>
                                                                        <div class="flex justify-between items-center mb-1.5">
                                                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Jumlah Terima</p>
                                                                            <span class="text-[8px] font-bold text-emerald-600 tracking-tight uppercase">Sisa: {{ $d->qty_pesan - $d->qty_terima }}</span>
                                                                        </div>
                                                                        <input type="number" wire:model="qtyReceived.{{ $d->id }}" 
                                                                            class="w-full text-center text-xs font-black border-gray-100 rounded-lg bg-gray-50 py-2 outline-none focus:ring-emerald-500"
                                                                            max="{{ $d->qty_pesan - $d->qty_terima }}" min="1">
                                                                    </div>
                                                                    
                                                                    <button wire:click="receiveItem({{ $d->id }})" @click="open = false; rowActive = false"
                                                                        class="w-full bg-emerald-600 text-white text-[10px] font-black uppercase py-2.5 rounded-xl hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-95">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                                        Konfirmasi Terima
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center gap-1.5">
                                                                <div class="w-2 h-2 rounded-full {{ $d->qty_terima > 0 ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                                                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-500">{{ $d->gudang->nama ?? 'N/A' }}</span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 text-right font-black text-gray-900 dark:text-white italic tracking-tighter">
                                                        Rp{{ number_format($d->qty_pesan * $d->harga_beli, 0, ',', '.') }}</td>
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
                                                                    <div class="space-y-2">
                                                                        <div class="flex justify-between items-center">
                                                                            <label
                                                                                class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Jml
                                                                                Retur</label>
                                                                            <span
                                                                                class="text-[9px] font-bold text-rose-500 uppercase tracking-widest">Maks:
                                                                                {{ $d->qty_terima }}</span>
                                                                        </div>
                                                                        <div class="relative">
                                                                            <input type="number" wire:model="qtyRetur.{{ $d->id }}"
                                                                                class="w-full p-2.5 text-sm font-black border border-gray-100 rounded-xl dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-rose-500 focus:border-rose-500 outline-none transition-all"
                                                                                min="1" max="{{ $d->qty_terima }}" placeholder="0">
                                                                            <div
                                                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-400 uppercase">
                                                                                Unit</div>
                                                                        </div>
                                                                        <p class="text-[8px] text-gray-400 leading-tight italic">
                                                                            Nilai refund akan otomatis dikalkulasi dan masuk ke
                                                                            saldo kas.</p>
                                                                    </div>
                                                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                                                    <button wire:click="submitRetur({{ $d->id }})"
                                                                        @click="open = false"
                                                                        class="w-full bg-rose-600 text-white py-2.5 text-[10px] font-black rounded-xl shadow-lg shadow-rose-500/30 hover:bg-rose-700 active:scale-95 transition-all uppercase tracking-widest">
                                                                        Konfirmasi Retur
                                                                    </button>
                                                                    @else
                                                                    <p class="text-[9px] text-center text-gray-400 italic">Hanya Admin yang dapat memproses Retur.</p>
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
                            </div>

                            @if($this->selectedPembelian->keterangan)
                                <div class="p-3 bg-gray-50/50 dark:bg-gray-700/50 rounded-2xl border border-dashed dark:border-gray-700 flex items-start gap-3">
                                    <div class="p-1.5 bg-indigo-100 text-indigo-600 rounded-lg">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div class="flex-grow">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Catatan Umum / Instruksi Khusus</p>
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