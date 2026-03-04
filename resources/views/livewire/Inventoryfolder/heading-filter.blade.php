<!-- Heading & Filters: Compact & Modern Layout -->
<div
    class="mb-6 bg-white dark:bg-gray-800 p-3 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm transition-all">
    <div class="flex flex-col lg:flex-row items-center gap-4">

        <!-- Row Left: Search & Categories -->
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-grow">
            <!-- Search Input -->
            <div class="relative w-full sm:w-64 md:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="block w-full py-2.5 pl-10 pr-3 text-sm text-gray-900 border-0 bg-gray-50/50 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:bg-white transition-all dark:bg-gray-700/50 dark:border-gray-600 dark:text-white"
                    placeholder="Cari nama/SKU item...">
            </div>

            <!-- Filter Group -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-grow sm:flex-grow-0">
                    <select wire:model.live="filterKategori"
                        class="w-full bg-gray-50/50 border-0 text-xs font-bold rounded-xl focus:ring-2 focus:ring-primary-500/20 py-2.5 pl-3 pr-8 transition-all dark:bg-gray-700/50 dark:text-white uppercase tracking-wider">
                        <option value="">Kategori: Semua</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative flex-grow sm:flex-grow-0">
                    <select wire:model.live="filterSubKategori"
                        class="w-full bg-gray-50/50 border-0 text-xs font-bold rounded-xl focus:ring-2 focus:ring-primary-500/20 py-2.5 pl-3 pr-8 transition-all disabled:opacity-30 dark:bg-gray-700/50 dark:text-white uppercase tracking-wider"
                        {{ !$filterKategori ? 'disabled' : '' }}>
                        <option value="">Sub: Semua</option>
                        @foreach($subKategorisFilter as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="h-px w-full lg:h-8 lg:w-px bg-gray-100 dark:bg-gray-700 mx-1 hidden sm:block"></div>

        <!-- Row Right: Sort, View & Master Actions -->
        <div class="flex items-center justify-between lg:justify-end gap-3 w-full lg:w-auto pb-1 sm:pb-0">

            <!-- View Controls Group -->
            <div class="flex items-center gap-1.5 bg-gray-50/50 dark:bg-gray-700/50 p-1 rounded-xl">
                <button type="button" wire:click="$set('viewMode', 'grid')"
                    class="p-2 rounded-lg transition-all {{ $viewMode === 'grid' ? 'bg-white text-primary-600 shadow-sm dark:bg-gray-600 dark:text-white' : 'text-gray-400 hover:text-gray-600' }}"
                    title="Grid View">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 4h4v4H4V4Zm6 0h4v4h-4V4Zm6 0h4v4h-4V4ZM4 10h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4ZM4 16h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4Z" />
                    </svg>
                </button>
                <button type="button" wire:click="$set('viewMode', 'list')"
                    class="p-2 rounded-lg transition-all {{ $viewMode === 'list' ? 'bg-white text-primary-600 shadow-sm dark:bg-gray-600 dark:text-white' : 'text-gray-400 hover:text-gray-600' }}"
                    title="List View">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2.5"
                            d="M9 8h10M9 12h10M9 16h10M4 8h.01M4 12h.01M4 16h.01" />
                    </svg>
                </button>

                @if($viewMode === 'grid')
                    <div class="h-4 w-px bg-gray-200 dark:bg-gray-600 mx-1"></div>
                    <select wire:model.live="gridCols"
                        class="bg-transparent border-0 text-[10px] font-black focus:ring-0 px-1 dark:text-white uppercase cursor-pointer">
                        @foreach([1, 2, 3, 4, 5, 6, 8, 10, 12] as $col)
                            <option value="{{ $col }}">{{ $col }}C</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <!-- Sorting -->
            <div class="relative min-w-[100px]">
                <select wire:model.live="sortBy"
                    class="w-full bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-[10px] font-black uppercase rounded-xl focus:ring-primary-500 py-2 transition-all">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="az">Nama A-Z</option>
                    <option value="za">Nama Z-A</option>
                    <option value="stock_high">Stok Terbanyak</option>
                </select>
            </div>

            <!-- Master Actions Group -->
            <div class="flex items-center gap-2">
                @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'member') && !auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'sales'))
                    <!-- riwayat pembelian -->
                    <button data-modal-target="modal-riwayat-pembelian" data-modal-toggle="modal-riwayat-pembelian"
                        type="button"
                        class="relative flex items-center justify-center p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-orange-600 hover:text-white transition-all shadow-sm group"
                        title="Riwayat Pembelian & Retur">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @if($this->pendingReturnsCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span
                                    class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 border-2 border-white dark:border-gray-800 text-[8px] font-black text-white justify-center items-center">{{ $this->pendingReturnsCount }}</span>
                            </span>
                        @endif
                    </button>

                    <!-- riwayat transfer stok -->
                    <button data-modal-target="modal-riwayat-transfer" data-modal-toggle="modal-riwayat-transfer"
                        type="button"
                        class="flex items-center justify-center p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-blue-600 hover:text-white transition-all shadow-sm group"
                        title="Riwayat Transfer Stok">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </button>

                    <!-- log mutasi produk -->
                    <button wire:click="openStockLogModal" type="button"
                        class="flex items-center justify-center p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-emerald-600 hover:text-white transition-all shadow-sm group"
                        title="Log Mutasi Stok">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </button>

                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'editor'))
                        <button data-modal-toggle="modal-barang" data-modal-target="modal-barang" type="button"
                            class="flex items-center justify-center p-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm"
                            title="Tambah Produk Baru">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    @endif



                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'editor') || auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'manage_kas'))
                        <!-- Master Gear -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="flex items-center justify-center p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-900 hover:text-white transition-all border border-gray-200 dark:border-gray-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 z-[60] overflow-hidden"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0">
                                <div class="p-2 space-y-1">
                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik'))
                                        <button wire:click="openTransferModal" @click="open = false"
                                            class="w-full flex items-center px-4 py-3 text-[10px] font-black text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-xl transition uppercase tracking-[0.1em] text-left">
                                            <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                            Transfer Barang
                                        </button>
                                        <div class="h-px bg-gray-100 dark:bg-gray-700 my-1"></div>
                                    @endif
                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'editor'))
                                        <button data-modal-target="modal-vendor" data-modal-toggle="modal-vendor"
                                            @click="open = false"
                                            class="w-full flex items-center px-4 py-3 text-[10px] font-black text-gray-600 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-xl transition uppercase tracking-[0.1em] text-left">
                                            <svg class="w-4 h-4 mr-3 text-primary-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Tambah Vendor
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                        <button data-modal-target="modal-gudang" data-modal-toggle="modal-gudang"
                                            @click="open = false"
                                            class="w-full flex items-center px-4 py-3 text-[10px] font-black text-gray-600 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-gray-700 rounded-xl transition uppercase tracking-[0.1em] text-left">
                                            <svg class="w-4 h-4 mr-3 text-emerald-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            Tambah Gudang
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'editor'))
                                        <div class="h-px bg-gray-100 dark:bg-gray-700 my-1"></div>
                                        <button data-modal-target="modal-satuan" data-modal-toggle="modal-satuan"
                                            @click="open = false"
                                            class="w-full flex items-center px-4 py-3 text-[10px] font-black text-gray-600 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700 rounded-xl transition uppercase tracking-[0.1em] text-left">
                                            <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            Kelola Satuan
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'manage_kas'))
                                        <button data-modal-target="modal-akun-kas" data-modal-toggle="modal-akun-kas"
                                            @click="open = false"
                                            class="w-full flex items-center px-4 py-3 text-[10px] font-black text-gray-600 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-xl transition uppercase tracking-[0.1em] text-left">
                                            <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Tambah Akun Kas
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>