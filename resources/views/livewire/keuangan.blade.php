<div class="p-6 space-y-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Header & Summary Cards -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Manajemen Keuangan
            </h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Pantau Hutang & Arus Kas Bisnis
                Anda</p>
        </div>
        <div class="flex flex-wrap gap-4 w-full md:w-auto">
            @if (Auth::user()->currentTeam && (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin') || Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'finance')))
                <!-- Total Hutang Card -->
                <div
                    class="flex-grow md:flex-none p-5 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border-l-8 border-rose-500 min-w-[200px]">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Hutang</p>
                    <h3 class="text-xl font-black text-rose-600 tracking-tighter">
                        Rp{{ number_format((float) $this->totalHutang, 0, ',', '.') }}</h3>
                </div>
            @endif

            <!-- Total Kas Card -->
            <div
                class="flex-grow md:flex-none p-5 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border-l-8 border-emerald-500 min-w-[200px]">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Saldo Kas</p>
                <h3 class="text-xl font-black text-emerald-600 tracking-tighter">
                    Rp{{ number_format($this->totalKas, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Tab Switcher -->
    <div class="flex flex-wrap items-center gap-2 p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl w-fit">
        @if (Auth::user()->currentTeam && (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin') || Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'finance')))
            <button wire:click="setTab('hutang')"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activeTab === 'hutang' ? 'bg-white dark:bg-gray-700 text-rose-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                Hutang
            </button>
        @endif
        <button wire:click="setTab('mutasi')"
            class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activeTab === 'mutasi' ? 'bg-white dark:bg-gray-700 text-emerald-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
            Mutasi Kas
        </button>
        <button wire:click="setTab('transfer')"
            class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activeTab === 'transfer' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
            Transfer Kas
        </button>
        @if (Auth::user()->currentTeam && (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin')))
            <button wire:click="setTab('audit-log')"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activeTab === 'audit-log' ? 'bg-white dark:bg-gray-700 text-amber-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                Audit Log
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- LEFT: Content Area -->
        <div class="xl:col-span-2 space-y-6">
            @if($activeTab === 'hutang' && (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin') || Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'finance')))
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border dark:border-gray-700">
                    <div
                        class="p-6 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-rose-100 rounded-xl">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Daftar
                                Nota Belum Lunas</h4>
                        </div>

                        <div class="relative w-64">
                            <input type="text" wire:model.live="searchHutang"
                                class="w-full pl-9 pr-4 py-2 text-xs font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-rose-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Cari nota atau vendor...">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead
                                class="bg-gray-50 dark:bg-gray-700 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Nota & Tanggal</th>
                                    <th class="px-6 py-4">Vendor</th>
                                    <th class="px-6 py-4 text-right">Total Tagihan</th>
                                    <th class="px-6 py-4 text-right">Sudah Bayar</th>
                                    <th class="px-6 py-4 text-right">Sisa Hutang</th>
                                    <th class="px-6 py-4 text-center">Petugas</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($this->daftarHutang as $h)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all group">
                                        <td class="px-6 py-5">
                                            <p class="text-xs font-black text-gray-900 dark:text-white font-mono">
                                                {{ $h->nomor_nota }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 font-bold mt-1">{{ $h->tanggal_pembelian }}</p>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-2">
                                                <div
                                                    class="w-6 h-6 rounded-lg bg-rose-500 flex items-center justify-center text-[10px] text-white font-black">
                                                    {{ substr($h->vendor->nama ?? 'U', 0, 1) }}
                                                </div>
                                                <p class="text-xs font-black text-gray-700 dark:text-gray-300 uppercase">
                                                    {{ $h->vendor->nama ?? 'Umum' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-right font-bold text-gray-600 dark:text-gray-400">
                                            Rp{{ number_format($h->total_harga + $h->biaya_ongkir + $h->biaya_lain, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-5 text-right font-bold text-emerald-600">
                                            Rp{{ number_format($h->terbayar, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-5 text-right font-black text-rose-600">
                                            Rp{{ number_format($h->sisa_tagihan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-5">
                                            @if($h->user)
                                                <div class="flex items-center gap-2 justify-center">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($h->user->name) }}&background=6366f1&color=fff"
                                                        class="w-5 h-5 rounded-full border border-white shadow-sm">
                                                    <span
                                                        class="text-[10px] font-bold text-gray-500 uppercase">{{ explode(' ', $h->user->name)[0] }}</span>
                                                </div>
                                            @else
                                                <div class="flex justify-center">
                                                    <span
                                                        class="text-[9px] font-black text-gray-300 uppercase italic tracking-widest leading-none">System</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <button wire:click="openPaymentModal({{ $h->id }})"
                                                class="px-4 py-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-rose-100 shadow-sm">
                                                Bayar Hutang
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-20 text-center opacity-30">
                                            <div class="flex flex-col items-center gap-3">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm font-black uppercase tracking-widest">Tidak Ada Hutang Pending
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($activeTab === 'mutasi')
                <!-- MUTASI KAS TABLES -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border dark:border-gray-700">
                    <div
                        class="p-6 border-b dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-gray-50/50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-4">
                            <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3-3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                    Mutasi Kas
                                </h4>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Filter:</span>
                                    <select wire:model.live="filterMutasi"
                                        class="bg-white dark:bg-gray-700 border-2 border-gray-100 dark:border-gray-600 rounded-xl px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-gray-600 dark:text-gray-300 focus:ring-0 focus:border-emerald-500 outline-none shadow-sm cursor-pointer">
                                        <option value="semua">Semua Transaksi</option>
                                        <option value="Masuk">Uang Masuk</option>
                                        <option value="Keluar">Uang Keluar</option>
                                    </select>
                                </div>

                                <!-- Audit Toggle Only for Admin/Finance -->
                                @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'finance'))
                                    <div
                                        class="flex items-center gap-2 bg-gray-100 dark:bg-gray-700 p-1 rounded-xl border border-gray-200 dark:border-gray-600 shadow-inner">
                                        <button wire:click="$set('auditMode', false)"
                                            class="px-2 py-1 rounded-lg text-[9px] font-black uppercase transition-all {{ !$auditMode ? 'bg-white dark:bg-gray-600 text-emerald-600 shadow-sm' : 'text-gray-400' }}">Table</button>
                                        <button wire:click="$set('auditMode', true)"
                                            class="px-2 py-1 rounded-lg text-[9px] font-black uppercase transition-all {{ $auditMode ? 'bg-white dark:bg-gray-600 text-emerald-600 shadow-sm' : 'text-gray-400' }}">Audit
                                            Gallery</button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="relative w-full md:w-64">
                            <input type="text" wire:model.live="searchMutasi"
                                class="w-full pl-9 pr-4 py-2 text-xs font-bold border-2 border-gray-100 rounded-2xl focus:ring-0 focus:border-emerald-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="Cari mutasi, akun, atau petugas...">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Audit Filters Row -->
                    @if($auditMode && (auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'finance')))
                        <div
                            class="px-6 py-3 bg-gray-50/80 dark:bg-gray-800/80 border-b dark:border-gray-700 flex flex-wrap items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" wire:model.live="hanyaTanpaBukti"
                                    class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span
                                    class="text-[10px] font-black text-gray-500 group-hover:text-emerald-600 transition-colors uppercase tracking-widest">Hanya
                                    Tanpa Bukti</span>
                            </label>

                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Min.
                                    Nominal:</span>
                                <input type="number" wire:model.live="minNominalAudit"
                                    class="bg-white dark:bg-gray-700 border-2 border-gray-100 dark:border-gray-600 rounded-lg px-2 py-1 text-[10px] font-bold w-24 outline-none focus:border-emerald-500">
                            </div>
                        </div>
                    @endif

                    @if($auditMode && (auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'finance')))
                        <!-- AUDIT GALLERY VIEW -->
                        <div class="p-6 bg-gray-50/30 dark:bg-gray-900/30">
                            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                                @forelse($this->daftarMutasi as $m)
                                    <div
                                        class="group relative bg-white dark:bg-gray-800 rounded-3xl overflow-hidden border-2 {{ $m->is_verified ? 'border-emerald-500 shadow-emerald-500/10' : 'border-gray-100 dark:border-gray-700' }} shadow-xl transition-all hover:-translate-y-1">
                                        <!-- Photo Area -->
                                        <div class="aspect-[4/3] bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                                            @if($m->foto_bukti)
                                                <img src="{{ $m->foto_url }}"
                                                    class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                                    <svg class="w-12 h-12 mb-2 opacity-20" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <p class="text-[10px] font-black uppercase tracking-widest italic opacity-40">No
                                                        Evidence</p>
                                                </div>
                                            @endif

                                            <!-- Overlay Info -->
                                            <div class="absolute top-3 left-3 flex gap-2">
                                                <span
                                                    class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $m->tipe === 'Masuk' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }} shadow-lg">
                                                    {{ $m->tipe }}
                                                </span>
                                            </div>

                                            @if($m->is_verified)
                                                <div class="absolute top-3 right-3">
                                                    <div class="bg-emerald-500 text-white p-1.5 rounded-full shadow-lg">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Details Area -->
                                        <div class="p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <p
                                                        class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                                                        {{ $m->tanggal->format('d/m/Y') }}
                                                    </p>
                                                    <h5
                                                        class="text-[11px] font-black text-gray-900 dark:text-white uppercase truncate">
                                                        {{ $m->kategori }}
                                                    </h5>
                                                </div>
                                                <p
                                                    class="text-xs font-black {{ $m->tipe === 'Masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    Rp{{ number_format((float) $m->jumlah, 0, ',', '.') }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-2 mt-4 pt-4 border-t dark:border-gray-700">
                                                @if($m->user)
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($m->user->name) }}&background=6366f1&color=fff"
                                                        class="w-5 h-5 rounded-full">
                                                    <span
                                                        class="text-[10px] font-black text-gray-500 uppercase">{{ explode(' ', $m->user->name)[0] }}</span>
                                                @endif
                                                <div class="ml-auto">
                                                    <button wire:click="toggleVerify({{ $m->id }})"
                                                        class="p-2 rounded-xl transition-all active:scale-95 shadow-sm border {{ $m->is_verified ? 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-600 hover:text-white' : 'bg-gray-100 text-gray-400 border-gray-200 hover:bg-emerald-600 hover:text-white hover:border-emerald-600' }}">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full py-20 text-center opacity-30">
                                        <p class="text-sm font-black uppercase tracking-widest italic">Gallery Kosong</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead
                                    class="bg-gray-50 dark:bg-gray-700 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Akun Kas</th>
                                        <th class="px-6 py-4">Kategori & Memo</th>
                                        <th class="px-6 py-4 text-right">Jumlah</th>
                                        <th class="px-6 py-4 text-center">Petugas</th>
                                        @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'finance'))
                                            <th class="px-6 py-4 text-center">Verify</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($this->daftarMutasi as $m)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all">
                                            <td class="px-6 py-4 text-xs font-bold text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($m->tanggal)->format('d/m/Y') }}
                                                <span
                                                    class="block text-[9px] font-normal text-gray-400 tracking-tighter">{{ $m->created_at->format('H:i') }}
                                                    WIB</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="text-[10px] font-black text-gray-800 dark:text-gray-200 uppercase tracking-tight bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg border border-gray-200 dark:border-gray-600">
                                                    {{ $m->akunKas->nama ?? 'Unknown' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div>
                                                        <p
                                                            class="text-xs font-black text-gray-800 dark:text-gray-100 uppercase leading-none">
                                                            {{ $m->kategori }}
                                                        </p>
                                                        @if($m->keterangan)
                                                            <p class="text-[9px] text-gray-400 mt-1 italic font-medium">
                                                                {{ $m->keterangan }}
                                                            </p>
                                                        @endif
                                                    </div>

                                                    @if($m->foto_bukti)
                                                        <div class="relative group/photo" x-data="{ show: false }">
                                                            <button @mouseenter="show = true" @mouseleave="show = false"
                                                                class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 hover:text-emerald-500 transition-colors">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                            </button>

                                                            <!-- Smart Tooltip -->
                                                            <div x-show="show" x-transition
                                                                class="absolute bottom-full left-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border dark:border-gray-700 overflow-hidden z-[60] pointer-events-none"
                                                                style="display: none;">
                                                                <img src="{{ $m->foto_url }}" class="w-full aspect-[4/3] object-cover">
                                                                <div
                                                                    class="p-2 bg-gray-50 dark:bg-gray-800 text-[9px] font-black uppercase text-center text-gray-400">
                                                                    Pratinjau Bukti</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <p
                                                    class="text-sm font-black {{ $m->tipe === 'Masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $m->tipe === 'Masuk' ? '+' : '-' }}
                                                    Rp{{ number_format((float) $m->jumlah, 0, ',', '.') }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($m->user)
                                                    <div class="flex items-center gap-2 justify-center">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($m->user->name) }}&background=6366f1&color=fff"
                                                            class="w-5 h-5 rounded-full border border-white shadow-sm">
                                                        <span
                                                            class="text-[9px] font-black text-gray-500 uppercase">{{ explode(' ', $m->user->name)[0] }}</span>
                                                    </div>
                                                @else
                                                    <div class="flex justify-center text-[9px] font-black text-gray-300 italic">SYSTEM
                                                    </div>
                                                @endif
                                            </td>
                                            @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'finance'))
                                                <td class="px-6 py-4 text-center">
                                                    <button wire:click="toggleVerify({{ $m->id }})" class="group/verify relative">
                                                        @if($m->is_verified)
                                                            <div
                                                                class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center border border-emerald-200 shadow-sm transition-all hover:bg-emerald-600 hover:text-white">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div
                                                                class="w-7 h-7 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center border border-gray-100 transition-all hover:bg-emerald-600 hover:text-white hover:border-emerald-600">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-20 text-center opacity-30">
                                                <p class="text-xs font-black uppercase tracking-widest italic">Tidak ada transaksi
                                                    ditemukan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @elseif($activeTab === 'transfer')
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border dark:border-gray-700">
                    <div
                        class="p-6 border-b dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-indigo-50/50 dark:bg-gray-800/50">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 text-indigo-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                Riwayat & Persetujuan Transfer Kas
                            </h4>
                        </div>

                        <!-- Summary Cards Point 4 -->
                        <div class="flex flex-wrap gap-3">
                            <div
                                class="px-4 py-2 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-2xl flex items-center gap-3 shadow-sm">
                                <div
                                    class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pending</p>
                                    <p class="text-xs font-black text-gray-900 dark:text-white">
                                        {{ $this->pendingTransferCount }} Transaksi
                                    </p>
                                </div>
                            </div>
                            <div
                                class="px-4 py-2 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-2xl flex items-center gap-3 shadow-sm">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Dana Mengambang
                                    </p>
                                    <p class="text-xs font-black text-indigo-600 dark:text-indigo-400">
                                        Rp{{ number_format((float) $this->floatingBalance, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Panel Transfer -->
                    <div class="px-6 pb-6">
                        <div
                            class="grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Cari
                                    Keterangan</label>
                                <div class="relative group">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <input type="text" wire:model.live.debounce.300ms="filterTransferSearch"
                                        placeholder="Cari..."
                                        class="w-full pl-9 pr-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-xs font-bold focus:border-indigo-500 outline-none transition-all">
                                </div>
                            </div>

                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Petugas
                                    (Pengirim/Penerima)</label>
                                <select wire:model.live="filterTransferUser"
                                    class="w-full px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-xs font-bold focus:border-indigo-500 outline-none transition-all">
                                    <option value="">-- Semua Petugas --</option>
                                    @foreach($this->allUsers as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-3">
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Rentang
                                    Tanggal</label>
                                <div class="flex items-center gap-2">
                                    <input type="date" wire:model.live="filterTransferStartDate"
                                        class="flex-grow px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none transition-all">
                                    <span class="text-gray-400 font-black">TO</span>
                                    <input type="date" wire:model.live="filterTransferEndDate"
                                        class="flex-grow px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none transition-all">

                                    <button wire:click="resetTransferFilters" title="Reset Filter"
                                        class="p-2 transition-all bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 text-gray-400 hover:text-rose-500 hover:border-rose-100 rounded-xl shadow-sm active:scale-90 h-[38px]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead
                                class="bg-gray-50 dark:bg-gray-700 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Pengirim & Penerima</th>
                                    <th class="px-6 py-4 text-right">Jumlah</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($this->daftarTransferKas as $t)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-all">
                                        <td class="px-6 py-4 text-xs font-bold text-gray-600 dark:text-gray-400">
                                            {{ $t->tanggal_transfer->format('d/m/Y') }}
                                            <span
                                                class="block text-[9px] font-normal text-gray-400 tracking-tighter">{{ $t->tanggal_transfer->format('H:i') }}
                                                WIB</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="text-[9px] font-black text-gray-400 uppercase w-12 text-right">DARI:</span>
                                                    <span
                                                        class="text-[10px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 dark:bg-rose-900/30 dark:border-rose-800">{{ $t->pengirimAkun->nama }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="text-[9px] font-black text-gray-400 uppercase w-12 text-right">KE:</span>
                                                    <span
                                                        class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 dark:bg-emerald-900/30 dark:border-emerald-800">{{ $t->penerimaAkun->nama }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    @if($t->foto_bukti)
                                                        <a href="{{ $t->foto_url }}" target="_blank" class="group/img relative">
                                                            <img src="{{ $t->foto_url }}"
                                                                class="w-8 h-8 rounded-lg object-cover border-2 border-white dark:border-gray-600 shadow-sm transition-transform group-hover/img:scale-110">
                                                            <div
                                                                class="absolute inset-0 bg-black/20 rounded-lg flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-opacity">
                                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                            </div>
                                                        </a>
                                                    @endif
                                                    @if($t->keterangan)
                                                        <span class="text-[10px] italic text-gray-500">{{ $t->keterangan }}</span>
                                                    @endif
                                                </div>
                                                @if($t->status === 'rejected' && $t->alasan_penolakan)
                                                    <div
                                                        class="mt-1 p-2 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-lg">
                                                        <span
                                                            class="text-[9px] font-black text-rose-600 uppercase block leading-none mb-1">Alasan
                                                            Ditolak:</span>
                                                        <span
                                                            class="text-[10px] text-rose-700 dark:text-rose-300 font-medium">{{ $t->alasan_penolakan }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="text-sm font-black text-gray-800 dark:text-gray-200">
                                                Rp{{ number_format((float) $t->jumlah, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($t->status === 'pending')
                                                <span
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-widest bg-amber-100 text-amber-700 uppercase border border-amber-200">Menunggu</span>
                                            @elseif($t->status === 'completed')
                                                <span
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-widest bg-emerald-100 text-emerald-700 uppercase border border-emerald-200">Selesai</span>
                                            @elseif($t->status === 'rejected')
                                                <span
                                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-widest bg-rose-100 text-rose-700 uppercase border border-rose-200">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(
                                                    $t->status === 'pending' && (
                                                        (auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'finance'))
                                                        || ($t->penerimaAkun->user_id === auth()->id() && $t->pengirim_user_id !== auth()->id())
                                                    )
                                                )
                                                <div class="flex items-center justify-center gap-2">
                                                    <button wire:click="prosesTransfer({{ $t->id }}, 'approve')"
                                                        wire:confirm="Sistem akan menambahkan dana kasir tujuan. Setujui transfer ini?"
                                                        title="Terima Transfer"
                                                        class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="openModalReject({{ $t->id }})" title="Tolak & Beri Alasan"
                                                        class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center opacity-30">
                                            <p class="text-xs font-black uppercase tracking-widest italic">Tidak ada riwayat
                                                transfer</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($activeTab === 'audit-log' && Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border dark:border-gray-700">
                    <div
                        class="p-6 border-b dark:border-gray-700 bg-amber-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Jejak
                                Audit Keuangan</h4>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Filter Panel -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                                <input type="text" wire:model.live.debounce.300ms="filterLogSearch"
                                    placeholder="Cari aktivitas..."
                                    class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-xs font-bold focus:border-indigo-500 outline-none transition-all">
                            </div>

                            <select wire:model.live="filterLogUser"
                                class="w-full px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-xs font-bold focus:border-indigo-500 outline-none transition-all">
                                <option value="">-- Semua Petugas --</option>
                                @foreach($this->allUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>

                            <div class="flex items-center gap-2 md:col-span-2">
                                <input type="date" wire:model.live="filterLogStartDate"
                                    class="flex-grow px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none transition-all">
                                <span class="text-gray-400 font-black">TO</span>
                                <input type="date" wire:model.live="filterLogEndDate"
                                    class="flex-grow px-4 py-2 bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl text-[10px] font-black uppercase tracking-widest focus:border-indigo-500 outline-none transition-all">

                                <button wire:click="resetLogFilters" title="Reset Filter"
                                    class="p-2 transition-all bg-white dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 text-gray-400 hover:text-rose-500 hover:border-rose-100 rounded-xl shadow-sm active:scale-90">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @forelse($this->daftarLogs as $log)
                            <div
                                class="flex gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/30 border border-gray-100 dark:border-gray-700 transition-all hover:shadow-md">
                                <div class="flex-none">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name ?? 'System') }}&background=6366f1&color=fff"
                                        class="w-10 h-10 rounded-2xl shadow-sm">
                                </div>
                                <div class="flex-grow space-y-2">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-xs font-black text-gray-900 dark:text-white uppercase">
                                                {{ $log->user->name ?? 'System' }}
                                            </p>
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">
                                                {{ $log->created_at->format('d M Y • H:i') }} WIB
                                            </p>
                                        </div>
                                        @php
                                            $typeMap = [
                                                'MutasiKas' => 'Mutasi',
                                                'TransferKas' => 'Transfer',
                                                'PenutupanKas' => 'Tutup Kas',
                                                'PembayaranPembelian' => 'Pelunasan Hutang',
                                                'AkunKas' => 'Akun Kas',
                                            ];
                                            $baseType = str_replace('App\\Models\\', '', $log->subject_type);
                                            $displayType = $typeMap[$baseType] ?? $baseType;
                                        @endphp
                                        <span
                                            class="px-2 py-0.5 bg-white dark:bg-gray-800 border dark:border-gray-600 rounded-lg text-[8px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 shadow-sm">
                                            {{ $displayType }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-600 dark:text-gray-300 font-medium leading-relaxed">
                                        {{ $log->description }}
                                    </p>

                                    @if($log->properties && isset($log->properties['old']) || isset($log->properties['attributes']))
                                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @if(isset($log->properties['old']))
                                                <div
                                                    class="p-3 bg-rose-50 dark:bg-rose-900/10 rounded-xl border border-rose-100 dark:border-rose-800/30">
                                                    <p class="text-[8px] font-black text-rose-600 uppercase mb-2 tracking-widest">
                                                        Sebelum</p>
                                                    <div class="space-y-1">
                                                        @foreach($log->properties['old'] as $key => $val)
                                                            @if(!in_array($key, ['created_at', 'updated_at', 'id']))
                                                                <div class="flex justify-between items-center text-[9px]">
                                                                    <span
                                                                        class="text-gray-400 font-black uppercase tracking-widest">{{ str_replace('_', ' ', $key) }}:</span>
                                                                    <span
                                                                        class="text-gray-600 dark:text-gray-400 font-bold">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if(isset($log->properties['attributes']))
                                                <div
                                                    class="p-3 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                                                    <p class="text-[8px] font-black text-emerald-600 uppercase mb-2 tracking-widest">
                                                        Sesudah</p>
                                                    <div class="space-y-1">
                                                        @foreach($log->properties['attributes'] as $key => $val)
                                                            @if(!in_array($key, ['created_at', 'updated_at', 'id']))
                                                                <div class="flex justify-between items-center text-[9px]">
                                                                    <span
                                                                        class="text-gray-400 font-black uppercase tracking-widest">{{ str_replace('_', ' ', $key) }}:</span>
                                                                    <span
                                                                        class="text-emerald-700 dark:text-emerald-400 font-black">{{ is_array($val) ? json_encode($val) : $val }}</span>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center opacity-30">
                                <p class="text-sm font-black uppercase tracking-widest italic">Belum ada aktivitas yang tercatat
                                </p>
                            </div>
                        @endforelse

                        <div class="mt-8 flex justify-center">
                            @if(count($this->daftarLogs) >= $limitLogs)
                                <button wire:click="loadMoreLogs" wire:loading.attr="disabled"
                                    class="px-8 py-3 bg-white dark:bg-gray-700 border-2 border-amber-100 dark:border-gray-600 text-amber-600 dark:text-amber-400 text-xs font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-all flex items-center gap-3 shadow-sm active:scale-95">
                                    <span wire:loading.remove wire:target="loadMoreLogs">Muat Lebih Banyak</span>
                                    <span wire:loading wire:target="loadMoreLogs">Memuat...</span>
                                    <svg wire:loading.remove wire:target="loadMoreLogs" class="w-4 h-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                    <div wire:loading wire:target="loadMoreLogs"
                                        class="w-4 h-4 rounded-full border-2 border-amber-600/30 border-t-amber-600 animate-spin">
                                    </div>
                                </button>
                            @else
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">— Akhir Riwayat
                                    —</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT: Wallet/Accounts Summary (Integrated) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border dark:border-gray-700">
                <div
                    class="px-6 py-5 border-b dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-xl">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3-3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">Akun Kas
                        </h4>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    @foreach($this->akunKas as $akun)
                        <div wire:click="openModalKas({{ $akun->id }})"
                            class="p-4 bg-gray-50/50 dark:bg-gray-700/30 rounded-2xl flex justify-between items-center group hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all border border-transparent hover:border-emerald-100 dark:hover:border-emerald-800/30 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-xl bg-white dark:bg-gray-800 flex items-center justify-center text-xs font-black text-emerald-600 shadow-sm border border-gray-100 dark:border-gray-700">
                                    {{ substr($akun->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-900 dark:text-white uppercase leading-none">
                                        {{ $akun->nama }}
                                    </p>
                                    <p class="text-[8px] text-gray-400 font-bold uppercase mt-1">
                                        {{ $akun->user->name ?? 'System' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <p class="text-xs font-black text-emerald-600">
                                    {{ number_format($akun->saldo_saat_ini, 0, ',', '.') }}
                                </p>
                                @if($this->isClosedToday($akun->id))
                                    <span
                                        class="text-[8px] font-black text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 uppercase tracking-widest">Closed</span>
                                @else
                                    <button wire:click.stop="openModalTutupKas({{ $akun->id }})"
                                        class="text-[8px] font-black text-white bg-amber-500 hover:bg-amber-600 px-2 py-1 rounded-lg uppercase tracking-widest shadow-sm transition-all active:scale-95">
                                        Tutup Kas
                                    </button>
                                @endif
                                <button wire:click="openModalTransfer({{ $akun->id }})" wire:click.stop
                                    class="text-[9px] font-black uppercase bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full hover:bg-indigo-200 transition">
                                    Transfer
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <button data-modal-target="modal-akun-kas" data-modal-toggle="modal-akun-kas"
                        class="w-full py-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-[9px] font-black text-gray-400 uppercase tracking-widest hover:border-emerald-500 hover:text-emerald-500 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Baru
                    </button>
                </div>
            </div>

            <!-- Kartu Info -->
            <div class="p-6 bg-gray-900 rounded-3xl shadow-xl text-white relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-400">Arus Kas Real-time
                        </p>
                    </div>
                    <p class="text-[10px] opacity-70 leading-relaxed font-bold italic">"Kesehatan keuangan dimulai dari
                        pencatatan yang disiplin setiap hari."</p>
                </div>
                <div class="absolute -right-8 -bottom-8 opacity-10">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="$wire.showPaymentModal" x-cloak
        class="fixed inset-0 z-[150] overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm shadow-inner"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden relative border dark:border-gray-700"
            @click.away="if (!document.getElementById('modal-crop').classList.contains('flex')) $wire.showPaymentModal = false">
            <div class="p-8 space-y-6">
                <div class="text-center space-y-2">
                    <div class="inline-flex p-4 bg-rose-100 rounded-3xl mb-2">
                        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Pelunasan
                        Hutang</h3>
                    @if($selectedPembelian)
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nota
                            #{{ $selectedPembelian->nomor_nota }} • {{ $selectedPembelian->vendor->nama ?? 'Umum' }}</p>
                    @endif
                </div>

                <div class="space-y-4">
                    <!-- Amount Input -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Jumlah
                                Pembayaran</label>
                            @if($selectedPembelian)
                                <button type="button"
                                    wire:click="$set('jumlahBayar', {{ $selectedPembelian->sisa_tagihan }})"
                                    class="text-[9px] font-black text-rose-600 uppercase hover:underline">Max:
                                    Rp{{ number_format($selectedPembelian->sisa_tagihan, 0, ',', '.') }}</button>
                            @endif
                        </div>
                        <div class="relative group" x-data="{ bayar: @entangle('jumlahBayar').live }">
                            <span
                                class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black text-rose-400">Rp</span>
                            <input type="text" x-on:input="bayar = $event.target.value.replace(/\D/g, '')"
                                x-bind:value="bayar?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                class="w-full pl-12 pr-5 py-4 text-lg font-black bg-gray-50 dark:bg-gray-700 border-0 rounded-2xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-900/20 text-rose-600 transition-all font-mono shadow-inner outline-none"
                                placeholder="0">
                        </div>
                        @error('jumlahBayar') <p class="text-[9px] font-bold text-rose-500 uppercase mt-1 px-1">
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <!-- Payment Source -->
                    <div class="space-y-1.5 text-center">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Pilih Sumber
                            Dana (Akun Kas)</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($this->akunKas as $akun)
                                <button type="button" wire:click="$set('selectedAkunKasId', {{ $akun->id }})"
                                    class="p-3 rounded-2xl border-2 text-[10px] font-black uppercase tracking-widest transition-all text-center
                                                                                                    {{ $selectedAkunKasId == $akun->id ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'bg-white dark:bg-gray-700 border-gray-100 dark:border-gray-600 text-gray-400 hover:border-emerald-200' }}">
                                    {{ $akun->nama }}
                                    <span class="block text-[8px] opacity-70 mt-0.5">Saldo:
                                        Rp{{ number_format((float) $akun->saldo_saat_ini, 0) }}</span>
                                </button>
                            @endforeach
                        </div>
                        @error('selectedAkunKasId') <p class="text-[9px] font-bold text-rose-500 uppercase mt-1">
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <!-- SECTION: DOCUMENT BUKTI -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label
                                class="block text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Unggah
                                Bukti Pembayaran <span class="text-rose-500">*</span></label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="auto-compress-bukti-keuangan" class="sr-only peer" checked>
                                <div
                                    class="relative w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-rose-300 dark:peer-focus:ring-rose-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-rose-600">
                                </div>
                                <span class="ms-1.5 text-[8px] font-black text-gray-400 uppercase">Auto</span>
                            </label>
                        </div>

                        <div x-data="{ isCompressing: false }" x-on:compression-start.window="isCompressing = true"
                            x-on:compression-end.window="isCompressing = false" class="relative">
                            <label
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-white dark:hover:bg-gray-800 transition overflow-hidden bg-white/50 dark:bg-gray-800/50 group">
                                @if ($buktiPembayaran)
                                    <div class="relative h-full w-full">
                                        <img src="{{ is_string($buktiPembayaran) ? $buktiPembayaran : $buktiPembayaran->temporaryUrl() }}"
                                            class="h-full w-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-rose-600/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                            <button type="button"
                                                x-on:click.prevent="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'buktiPembayaran', $wire)"
                                                class="p-2 bg-white text-rose-600 rounded-xl shadow-lg hover:scale-110 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="$set('buktiPembayaran', null)"
                                                class="p-2 bg-white text-rose-600 rounded-xl shadow-lg hover:scale-110 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div
                                            class="p-3 bg-rose-50 rounded-2xl mb-2 text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition">
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
                                    @change="if(document.getElementById('auto-compress-bukti-keuangan').checked) { handleAutoCompress($event.target, 'buktiPembayaran', $wire) } else { @this.upload('buktiPembayaran', $event.target.files[0]) }" />
                            </label>

                            <div x-show="isCompressing"
                                class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex items-center justify-center rounded-2xl z-10">
                                <span
                                    class="text-[9px] font-bold text-rose-500 animate-pulse uppercase tracking-widest text-center px-4">Sedang
                                    mengompres & mengunggah...</span>
                            </div>
                        </div>
                        @error('buktiPembayaran') <p class="text-[9px] font-bold text-rose-500 uppercase mt-1 px-1">
                            {{ $message }}
                        </p> @enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="$set('showPaymentModal', false)"
                        class="flex-1 px-6 py-4 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-gray-200 transition-all active:scale-95">
                        Batal
                    </button>
                    <button type="button" wire:click="processPayment"
                        class="flex-1 px-6 py-4 bg-rose-600 text-white font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-rose-700 transition-all active:scale-95 shadow-xl shadow-rose-500/30">
                        Konfirmasi Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.Inventoryfolder.modal-kas')

    <!-- Modal Transfer Kas -->
    @if($showModalTransfer)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-gray-900/60 backdrop-blur-sm transition-opacity"
            role="dialog" aria-modal="true">
            <div class="relative w-full max-w-lg mx-auto p-4 sm:my-8 sm:p-0">
                <div
                    class="relative flex flex-col w-full bg-white dark:bg-gray-800 border-0 rounded-3xl shadow-2xl outline-none focus:outline-none overflow-hidden">
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-gray-700 bg-indigo-50/50 dark:bg-gray-800">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                                Transfer Kas
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pindahkan dana antar rekening kas</p>
                        </div>
                        <button wire:click="$set('showModalTransfer', false)"
                            class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-xl transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="ajukanTransfer" class="p-6 space-y-5">

                        <div
                            class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-2xl flex items-center gap-3">
                            <div
                                class="p-2 bg-indigo-100 dark:bg-indigo-800 text-indigo-600 dark:text-indigo-300 rounded-xl">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-xs text-indigo-700 dark:text-indigo-300 font-medium">Beban dana ditahan pada kas
                                pengirim terlebih dahulu hingga disetujui oleh penerima atau finance.</p>
                        </div>

                        <!-- Dari Akun (Readonly Visual) -->
                        <div x-data="{ saldo: {{ \App\Models\AkunKas::find($transferPengirimId)->saldo_saat_ini ?? 0 }} }">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Kas Asal
                                    (Pengirim)</label>
                                <span
                                    class="text-[10px] font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full border border-indigo-100 dark:border-indigo-800 uppercase">Saldo:
                                    Rp{{ number_format((float) (\App\Models\AkunKas::find($transferPengirimId)->saldo_saat_ini ?? 0), 0, ',', '.') }}</span>
                            </div>
                            @php $pengirimName = \App\Models\AkunKas::find($transferPengirimId)->nama ?? '-'; @endphp
                            <div
                                class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-2xl text-sm font-bold text-gray-600 dark:text-gray-300 cursor-not-allowed">
                                {{ $pengirimName }}
                            </div>
                        </div>

                        <!-- Ke Akun -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kas
                                Tujuan (Penerima)</label>
                            <select wire:model="transferPenerimaId" required
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 {{ $errors->has('transferPenerimaId') ? 'border-rose-500' : 'border-gray-200 dark:border-gray-700' }} rounded-2xl text-sm font-bold text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                                <option value="">-- Pilih Akun Penerima --</option>
                                @foreach(\App\Models\AkunKas::where('id', '!=', $transferPengirimId)->get() as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->nama }} ({{ $akun->user->name ?? 'Sistem' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('transferPenerimaId') <span
                            class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Jumlah Nominal -->
                        <div x-data="{ 
                                                nominal: @entangle('transferJumlah').live,
                                                saldo: {{ \App\Models\AkunKas::find($transferPengirimId)->saldo_saat_ini ?? 0 }}
                                            }">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jumlah
                                Transfer (Rp)</label>
                            <div class="relative group">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-sm font-black transition-colors"
                                    :class="nominal > saldo ? 'text-rose-500' : 'text-indigo-400'">Rp</span>
                                <input type="text" x-on:input="nominal = $event.target.value.replace(/\D/g, '')"
                                    x-bind:value="nominal?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                                    class="w-full pl-12 pr-5 py-4 text-lg font-black bg-white dark:bg-gray-800 border-2 rounded-2xl transition-all font-mono shadow-inner outline-none"
                                    :class="nominal > saldo || {{ $errors->has('transferJumlah') ? 'true' : 'false' }} ? 'border-rose-500 focus:ring-rose-100' : 'border-gray-200 dark:border-gray-700 focus:ring-indigo-100'"
                                    placeholder="0" required>
                            </div>

                            <!-- Real-time Warning -->
                            <template x-if="nominal > saldo">
                                <div
                                    class="mt-2 p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-100 dark:border-rose-800 rounded-xl flex items-center gap-2 animate-shake">
                                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p
                                        class="text-[10px] font-black text-rose-700 dark:text-rose-300 uppercase tracking-tight">
                                        Saldo tidak cukup untuk transfer ini!</p>
                                </div>
                            </template>

                            @error('transferJumlah') <span
                            class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Bukti Foto -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between px-1">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Foto
                                    Bukti / Fisik Uang (Opsional)</label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="auto-compress-transfer" class="sr-only peer" checked>
                                    <div
                                        class="relative w-7 h-3.5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-2.5 after:w-2.5 after:transition-all peer-checked:bg-indigo-600">
                                    </div>
                                    <span class="ms-1.5 text-[8px] font-black text-gray-400 uppercase">Auto</span>
                                </label>
                            </div>

                            <div x-data="{ isCompressing: false }" x-on:compression-start.window="isCompressing = true"
                                x-on:compression-end.window="isCompressing = false" class="relative">
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition overflow-hidden bg-white/50 dark:bg-gray-800/50 group">
                                    @if ($transferFoto)
                                        <div class="relative h-full w-full">
                                            <img src="{{ is_string($transferFoto) ? $transferFoto : $transferFoto->temporaryUrl() }}"
                                                class="h-full w-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-indigo-600/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                                <button type="button"
                                                    x-on:click.prevent="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'transferFoto', $wire)"
                                                    class="p-2 bg-white text-indigo-600 rounded-xl shadow-lg hover:scale-110 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                                <button type="button" wire:click="$set('transferFoto', null)"
                                                    class="p-2 bg-white text-rose-600 rounded-xl shadow-lg hover:scale-110 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div
                                                class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl mb-2 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                </svg>
                                            </div>
                                            <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Lampirkan
                                                Bukti</p>
                                        </div>
                                    @endif
                                    <input type="file" class="hidden" accept="image/*"
                                        @change="if(document.getElementById('auto-compress-transfer').checked) { handleAutoCompress($event.target, 'transferFoto', $wire) } else { @this.upload('transferFoto', $event.target.files[0]) }" />
                                </label>

                                <div x-show="isCompressing"
                                    class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex flex-col items-center justify-center rounded-2xl z-10">
                                    <div
                                        class="w-8 h-8 rounded-full border-4 border-indigo-100 border-t-indigo-600 animate-spin mb-2">
                                    </div>
                                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                                        Processing...</p>
                                </div>
                            </div>
                            @error('transferFoto') <span class="text-xs font-bold text-rose-500 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Berita
                                / Keterangan (Opsional)</label>
                            <textarea wire:model="transferKeterangan" rows="2"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl text-sm text-gray-900 dark:text-white focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none"
                                placeholder="Contoh: Setoran harian shift pagi, Minta Petty Cash"></textarea>
                        </div>

                        <!-- Footer -->
                        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3" x-data="{ 
                                                    nominal: @entangle('transferJumlah').live,
                                                    saldo: {{ \App\Models\AkunKas::find($transferPengirimId)->saldo_saat_ini ?? 0 }}
                                                 }">
                            <button type="button" wire:click="$set('showModalTransfer', false)"
                                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-black rounded-xl transition-all">
                                Batal
                            </button>
                            <button type="submit" :disabled="nominal > saldo"
                                :class="nominal > saldo ? 'opacity-50 cursor-not-allowed bg-gray-400 dark:bg-gray-700' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/30'"
                                class="px-8 py-3 focus:ring-4 focus:ring-indigo-500/50 text-white text-sm font-black rounded-xl transition-all flex items-center gap-2">
                                <span>Ajukan Transfer</span>
                                <div wire:loading wire:target="ajukanTransfer"
                                    class="ml-2 w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin">
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Alasan Penolakan -->
    @if($showModalReject)
        <div class="fixed inset-0 z-[70] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                    wire:click="$set('showModalReject', false)"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-700">
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Alasan
                                Penolakan Transfer</h3>
                            <button wire:click="$set('showModalReject', false)"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <p class="text-xs text-gray-500 font-medium">Berikan alasan mengapa transfer ini ditolak agar
                                pengirim dapat memperbaikinya.</p>

                            <div>
                                <label
                                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pesan
                                    Penolakan</label>
                                <textarea wire:model="alasanPenolakan" rows="3"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 {{ $errors->has('alasanPenolakan') ? 'border-rose-500' : 'border-gray-200 dark:border-gray-700' }} rounded-2xl text-sm text-gray-900 dark:text-white focus:ring-4 focus:ring-rose-500/20 focus:border-rose-500 transition-all outline-none"
                                    placeholder="Contoh: Jumlah fisik uang yang saya terima kurang Rp50.000..."></textarea>
                                @error('alasanPenolakan') <span
                                class="text-xs font-bold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" wire:click="$set('showModalReject', false)"
                                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-black rounded-xl transition-all">
                                Batal
                            </button>
                            <button type="button" wire:click="rejectTransfer"
                                class="px-8 py-3 bg-rose-600 hover:bg-rose-700 text-white text-sm font-black rounded-xl shadow-lg shadow-rose-200 dark:shadow-none transition-all active:scale-95">
                                Tolak Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('livewire.Inventoryfolder.modal-akun-kas')
    @include('livewire.Inventoryfolder.modal-tutup-kas')

    @script
    <script>
        $wire.on('close-modal', (event) => {
            const modalId = event.modalId;
            if (window.FlowbiteInstances) {
                const modal = window.FlowbiteInstances.getInstance('Modal', modalId);
                if (modal) modal.hide();
            } else {
                const modalEl = document.getElementById(modalId);
                if (modalEl) {
                    modalEl.classList.add('hidden');
                    modalEl.classList.remove('flex');
                    document.querySelector('[modal-backdrop]')?.remove();
                    document.body.classList.remove('overflow-hidden');
                }
            }
        });

        // Global delegated listener for Flowbite modals
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-modal-target]');
            if (!btn) return;
            const modalId = btn.getAttribute('data-modal-target');
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            if (window.FlowbiteInstances) {
                const modal = window.FlowbiteInstances.getInstance('Modal', modalId);
                if (modal) modal.show();
            }
        });
    </script>
    @endscript
</div>