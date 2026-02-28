<!-- Heading & Filters -->
<div class="mb-4 space-y-3 md:mb-8">
    <!-- Row 1: Search & Categories -->
    <div class="flex flex-wrap items-center gap-2">
        <!-- Search Input -->
        <div class="relative flex-grow md:flex-grow-0 md:min-w-[300px]">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                class="block w-full p-2 pl-9 text-sm text-gray-900 border border-gray-200 rounded-lg bg-white focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                placeholder="Cari nama/SKU...">
        </div>

        <!-- Dropdowns Group -->
        <div class="flex flex-wrap items-center gap-2 flex-grow md:flex-grow-0">
            <select wire:model.live="filterKategori"
                class="bg-white border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white flex-grow sm:flex-grow-0 min-w-[120px]">
                <option value="">Kategori: Semua</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterSubKategori"
                class="bg-white border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white flex-grow sm:flex-grow-0 min-w-[120px] disabled:opacity-50"
                {{ !$filterKategori ? 'disabled' : '' }}>
                <option value="">Sub: Semua</option>
                @foreach($subKategorisFilter as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2 flex-grow sm:flex-grow-0">
            @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'member') && !auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'sales'))
                <button wire:click="openPurchaseModal" type="button"
                    class="flex-1 sm:flex-none flex items-center justify-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700 transition-all relative">
                    <svg class="-ms-0.5 me-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Pembelian
                    @if(count($purchaseCart) > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white shadow-lg animate-bounce">
                            {{ count($purchaseCart) }}
                        </span>
                    @endif
                </button>
            @endif

            <button data-modal-target="modal-riwayat-pembelian" data-modal-toggle="modal-riwayat-pembelian"
                type="button"
                class="flex-1 sm:flex-none flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 px-4 py-2 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                <svg class="-ms-0.5 me-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat
            </button>

            <button data-modal-toggle="modal-barang" data-modal-target="modal-barang" type="button"
                class="flex-1 sm:flex-none flex items-center justify-center rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="-ms-0.5 me-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7-7v14" />
                </svg>
                Tambah
            </button>

            <!-- Master Data Dropdown -->
            <div class="relative flex-1 sm:flex-none" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="w-full flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 p-2 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 z-[60] overflow-hidden"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100">
                    <div class="p-2 space-y-1">
                        <button data-modal-target="modal-vendor" data-modal-toggle="modal-vendor" @click="open = false"
                            class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left">
                            <svg class="w-4 h-4 mr-3 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Tambah Vendor
                        </button>
                        <button data-modal-target="modal-gudang" data-modal-toggle="modal-gudang" @click="open = false"
                            class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left">
                            <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Tambah Gudang
                        </button>
                        <button data-modal-target="modal-satuan" data-modal-toggle="modal-satuan" @click="open = false"
                            class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left border-t dark:border-gray-700 mt-1 pt-3">
                            <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Kelola Satuan
                        </button>
                        <button data-modal-target="modal-akun-kas" data-modal-toggle="modal-akun-kas"
                            @click="open = false"
                            class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left">
                            <svg class="w-4 h-4 mr-3 text-emerald-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tambah Akun Kas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Sort & View Controls -->
    <div
        class="flex flex-wrap items-center justify-between gap-3 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-[10px] uppercase font-bold text-gray-400 hidden sm:block">Sorting:</span>
            <select wire:model.live="sortBy"
                class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="az">Nama A-Z</option>
                <option value="za">Nama Z-A</option>
                <option value="price_high">Termahal</option>
                <option value="price_low">Termurah</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <!-- View Toggle -->
            <div class="inline-flex rounded-lg shadow-sm bg-gray-50 dark:bg-gray-700 p-1">
                <button type="button" wire:click="$set('viewMode', 'grid')"
                    class="p-1.5 rounded-md transition-colors {{ $viewMode === 'grid' ? 'bg-white dark:bg-gray-600 text-primary-700 shadow-sm' : 'text-gray-500' }}">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 4h4v4H4V4Zm6 0h4v4h-4V4Zm6 0h4v4h-4V4ZM4 10h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4ZM4 16h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4Z" />
                    </svg>
                </button>
                <button type="button" wire:click="$set('viewMode', 'list')"
                    class="p-1.5 rounded-md transition-colors {{ $viewMode === 'list' ? 'bg-white dark:bg-gray-600 text-primary-700 shadow-sm' : 'text-gray-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                            d="M9 8h10M9 12h10M9 16h10M4 8h.01M4 12h.01M4 16h.01" />
                    </svg>
                </button>
            </div>

            <!-- Grid Adj -->
            @if($viewMode === 'grid')
                <div class="flex items-center gap-1 border-l pl-3 border-gray-200 dark:border-gray-600">
                    <select wire:model.live="gridCols"
                        class="bg-transparent border-0 text-[10px] font-black focus:ring-0 p-1 dark:text-white uppercase cursor-pointer">
                        @foreach([1, 2, 3, 4, 5, 6, 8, 10, 12] as $col)
                            <option value="{{ $col }}">{{ $col }} Cols</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>
</div>