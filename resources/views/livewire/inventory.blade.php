<div class="m-5 max-w-full">
    <!-- Flash Message Notification -->
    @if (session()->has('success'))
        <div id="alert-3"
            class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-3" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif



    <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-5">
        <div class="mx-auto px-4">
            <!-- Heading & Filters -->
            <div class="mb-4 space-y-3 md:mb-8">
                <!-- Row 1: Search & Categories -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Search Input -->
                    <div class="relative flex-grow md:flex-grow-0 md:min-w-[300px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
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
                        <button wire:click="openPurchaseModal" type="button"
                            class="flex-1 sm:flex-none flex items-center justify-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700 transition-all relative">
                            <svg class="-ms-0.5 me-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Pembelian
                            @if(count($purchaseCart) > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white shadow-lg animate-bounce">
                                    {{ count($purchaseCart) }}
                                </span>
                            @endif
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 z-[60] overflow-hidden"
                                x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100">
                                <div class="p-2 space-y-1">
                                    <button wire:click="$set('showModalVendor', true)" @click="open = false" class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left">
                                        <svg class="w-4 h-4 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        Tambah Vendor
                                    </button>
                                    <button wire:click="$set('showModalGudang', true)" @click="open = false" class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left">
                                        <svg class="w-4 h-4 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Tambah Gudang
                                    </button>
                                    <button data-modal-target="modal-satuan" data-modal-toggle="modal-satuan" @click="open = false" class="w-full flex items-center px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition uppercase tracking-widest text-left border-t dark:border-gray-700 mt-1 pt-3">
                                        <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        Kelola Satuan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Sort & View Controls -->
                <div class="flex flex-wrap items-center justify-between gap-3 bg-white dark:bg-gray-800 p-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] uppercase font-bold text-gray-400 hidden sm:block">Sorting:</span>
                        <select wire:model.live="sortBy" class="bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-1.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
                                    <path d="M4 4h4v4H4V4Zm6 0h4v4h-4V4Zm6 0h4v4h-4V4ZM4 10h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4ZM4 16h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4Z"/>
                                </svg>
                            </button>
                            <button type="button" wire:click="$set('viewMode', 'list')" 
                                class="p-1.5 rounded-md transition-colors {{ $viewMode === 'list' ? 'bg-white dark:bg-gray-600 text-primary-700 shadow-sm' : 'text-gray-500' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 8h10M9 12h10M9 16h10M4 8h.01M4 12h.01M4 16h.01"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Grid Adj -->
                        @if($viewMode === 'grid')
                            <div class="flex items-center gap-1 border-l pl-3 border-gray-200 dark:border-gray-600">
                                 <select wire:model.live="gridCols" class="bg-transparent border-0 text-[10px] font-black focus:ring-0 p-1 dark:text-white uppercase cursor-pointer">
                                    @foreach([1, 2, 3, 4, 5, 6, 8, 10, 12] as $col)
                                        <option value="{{ $col }}">{{ $col }} Cols</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($viewMode === 'grid')
                <div wire:key="product-grid-{{ $this->barangs->count() }}-{{ $gridCols }}"
                    class="mb-4 grid gap-2 md:gap-4 md:mb-8"
                    style="grid-template-columns: repeat({{ $gridCols }}, minmax(0, 1fr));">
                    @forelse($this->barangs as $barang)
                        @php
                            $gambarUtama = $barang->gambarBarangs->where('gambar_utama', true)->first()
                                ?? $barang->gambarBarangs->first();

                            $pathRaw = $gambarUtama ? $gambarUtama->path : '';
                            $pathGambar = str_starts_with($pathRaw, 'http')
                                ? $pathRaw
                                : ($pathRaw ? asset('storage/' . $pathRaw) : 'https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg');

                            $hargaJual = $barang->hargaJualTerakhir ? number_format($barang->hargaJualTerakhir->harga, 0, ',', '.') : '0';
                        @endphp
                        <!-- Single Product Card -->
                        <div wire:key="barang-{{ $barang->id }}"
                            x-data="{ 
                                            activeImage: '{{ $pathGambar }}',
                                            images: [
                                                @foreach($barang->gambarBarangs as $img)
                                                    '{{ asset('storage/' . $img->path) }}'{{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                            ]
                                        }"
                            class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 hover:shadow-xl transition-all duration-300 group flex flex-col h-full
                            {{ $gridCols > 6 ? 'p-2' : ($gridCols > 4 ? 'p-3' : 'p-5') }}">

                            <!-- Image Container with Gallery Logic -->
                            <div class="{{ $gridCols > 6 ? 'h-24' : ($gridCols > 4 ? 'h-32' : 'h-56') }} w-full overflow-hidden rounded-lg bg-gray-50 relative mb-4">
                                <img class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-700 shadow-inner"
                                    x-bind:src="activeImage" alt="{{ $barang->nama }}" />

                                <!-- Category Badge -->
                                <div class="absolute top-3 left-3 flex flex-col gap-1 z-10">
                                    <span
                                        class="bg-blue-600/90 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg">
                                        {{ $barang->kategori->nama ?? 'Umum' }}
                                    </span>
                                </div>

                                <!-- Floating Thumbnails (Overlay) -->
                                @if($barang->gambarBarangs->count() > 1)
                                    <div
                                        class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 p-1.5 bg-black/30 backdrop-blur-md rounded-xl border border-white/20 shadow-2xl z-20 group-hover:bg-black/40 transition-colors">
                                        @foreach($barang->gambarBarangs as $img)
                                                                                    @php 
                                                                                                                                                                                    $imgPath = str_starts_with($img->path, 'http')
                                                                                        ? $img->path
                                                                                        : asset('storage/' . $img->path); 
                                                                                    @endphp
                                                                                    <button 
                                                                                        x-on:click="activeImage = '{{ $imgPath }}'"
                                             type="button"
                                                                                        class="w-8 h-8 flex-shrink-0 rounded-lg overflow-hidden border-2 transition-all duration-300"
                                                                                        x-bind:class="activeImage === '{{ $imgPath }}' ? 'border-white scale-110 shadow-lg' : 'border-transparent opacity-50 hover:opacity-100 hover:scale-105'">
                                                                                        <img src="{{ $imgPath }}" class="w-full h-full object-cover">
                                                                                    </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col flex-grow">
                                <div class="mb-2 flex items-center justify-between gap-4">
                                    <span
                                        class="text-[10px] font-mono font-medium text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 px-2 py-0.5 rounded uppercase tracking-wider">
                                        SKU: {{ $barang->sku }}
                                    </span>
                                </div>

                                <h3
                                    class="text-base font-bold leading-tight text-gray-900 line-clamp-2 min-h-[2.5rem] group-hover:text-blue-600 transition-colors dark:text-white">
                                    {{ $barang->nama }}
                                </h3>

                                <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <p class="text-[10px] text-gray-400 font-medium uppercase mb-0.5">Harga Jual</p>
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-xl font-black leading-tight text-gray-900 dark:text-white">
                                            <span class="text-xs font-bold text-blue-600 mr-0.5">Rp</span>{{ $hargaJual }}
                                        </p>

                                        <div class="flex gap-1">
                                            @php
                                                $isInCart = collect($purchaseCart)->contains('barang_id', $barang->id);
                                            @endphp
                                            <button type="button" wire:click="addToPurchaseCart({{ $barang->id }})"
                                                class="flex items-center justify-center w-10 h-10 rounded-lg transition-all 
                                                {{ $isInCart ? 'bg-green-100 text-green-600 cursor-default' : 'bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white shadow-sm active:scale-95' }}">
                                                @if($isInCart)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                @endif
                                            </button>
                                            
                                            <button type="button"
                                                class="p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors dark:hover:bg-gray-700">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full py-20 flex flex-col items-center justify-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 dark:bg-gray-800/50 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada barang terdaftar.</p>
                            <p class="text-gray-400 text-sm mt-1">Gunakan tombol "Tambah Barang" untuk memulai.</p>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- List View -->
                <div wire:key="product-list-{{ $this->barangs->count() }}" class="mb-4 flex flex-col gap-2 md:mb-8">
                    @forelse($this->barangs as $barang)
                        <div wire:key="list-barang-{{ $barang->id }}" class="flex items-center gap-4 bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                            @php
                                $gambarUtama = $barang->gambarBarangs->where('gambar_utama', true)->first() ?? $barang->gambarBarangs->first();
                                $pathRaw = $gambarUtama ? $gambarUtama->path : '';
                                $pathGambar = str_starts_with($pathRaw, 'http') ? $pathRaw : ($pathRaw ? asset('storage/' . $pathRaw) : 'https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg');
                                $hargaJual = $barang->hargaJualTerakhir ? number_format($barang->hargaJualTerakhir->harga, 0, ',', '.') : '0';
                            @endphp
                            <img src="{{ $pathGambar }}" class="w-16 h-16 object-cover rounded-md">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $barang->nama }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider">SKU: {{ $barang->sku }} | {{ $barang->kategori->nama ?? 'Umum' }}</p>
                            </div>
                            <div class="text-right px-4">
                                <p class="text-xs text-gray-400 uppercase font-bold text-[9px]">Harga</p>
                                <p class="text-sm font-black text-gray-900 dark:text-white">Rp{{ $hargaJual }}</p>
                            </div>
                            <div class="flex gap-1">
                                @php
                                    $isInCart = collect($purchaseCart)->contains('barang_id', $barang->id);
                                @endphp
                                <button type="button" wire:click="addToPurchaseCart({{ $barang->id }})"
                                    class="flex items-center justify-center w-8 h-8 rounded-lg transition-all 
                                    {{ $isInCart ? 'bg-green-100 text-green-600' : 'bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white' }}">
                                    @if($isInCart)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    @endif
                                </button>
                                
                                <button type="button" class="p-1 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-500">Barang tidak ditemukan.</div>
                    @endforelse
                </div>
            @endif

            @if ($this->barangs->count() < $this->totalBarang)
                <div class="w-full text-center mt-8">
                    <button type="button" wire:click="loadMore" wire:loading.attr="disabled"
                        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                        <span wire:loading.remove wire:target="loadMore">Show more</span>
                        <span wire:loading wire:target="loadMore" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-900 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading...
                        </span>
                    </button>
                </div>
            @endif
        </div>
    </section>

    <!-- Floating Cart Button -->
    @if(count($purchaseCart) > 0)
    <div class="fixed bottom-6 right-6 z-[40]">
        <button type="button" wire:click="openPurchaseModal"
            class="group relative flex items-center justify-center w-16 h-16 bg-orange-600 rounded-full shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-black min-w-[24px] h-6 flex items-center justify-center rounded-full border-2 border-white shadow-lg">
                {{ count($purchaseCart) }}
            </span>
            
            <!-- Tooltip -->
            <div class="absolute right-full mr-3 bg-gray-900/90 text-white text-[10px] uppercase font-black py-2 px-3 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                Lihat Nota ({{ count($purchaseCart) }} Item)
            </div>
        </button>
    </div>
    @endif
    @include('livewire.Inventoryfolder.modal-filter')
    @include('livewire.Inventoryfolder.modal-barang')
    @include('livewire.Inventoryfolder.modal-kategori')
    @include('livewire.Inventoryfolder.modal-subKategori')
    @include('livewire.Inventoryfolder.modal-satuan')
    @include('livewire.Inventoryfolder.modal-pembelian')
    @include('livewire.Inventoryfolder.modal-vendor')
    @include('livewire.Inventoryfolder.modal-gudang')

    @script
    <script>
        $wire.on('close-modal', () => {
             // Existing Flowbite logic
             const modalEl = document.getElementById('modal-barang');
             if (window.FlowbiteInstances) {
                 const modal = window.FlowbiteInstances.getInstance('Modal', 'modal-barang');
                 if (modal) {
                     modal.hide();
                 }
             } else {
                 modalEl.classList.add('hidden');
                 modalEl.classList.remove('flex');
                 document.querySelector('[modal-backdrop]')?.remove();
                 document.body.classList.remove('overflow-hidden');
             }
        });

        $wire.on('item-added-to-cart', (event) => {
            // Simple logic for toast feedback
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-24 right-6 bg-gray-900/90 text-white px-6 py-3 rounded-2xl shadow-2xl z-[100] border border-white/10 animate-fade-in-up flex items-center gap-3 overflow-hidden';
            toast.innerHTML = `
                <div class="p-1.5 bg-green-500 rounded-lg">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 leading-none mb-1">Berhasil Ditambahkan</p>
                    <p class="text-xs font-bold leading-none">${event[0].name}</p>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2', 'scale-95', 'transition-all', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        });
    </script>
    @endscript
</div>