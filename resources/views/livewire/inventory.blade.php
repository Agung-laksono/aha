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
            <div class="mb-4 items-center justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8 gap-4">
                <!-- Left Side: Search & Filter Actions -->
                <div class="flex flex-wrap flex-1 items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative w-full max-w-[240px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                            class="block w-full p-2 pl-9 text-sm text-gray-900 border border-gray-200 rounded-lg bg-white focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white" 
                            placeholder="Cari nama/SKU...">
                    </div>

                    <!-- Dropdown Kategori -->
                    <select wire:model.live="filterKategori" 
                        class="bg-white border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white min-w-[140px]">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>

                    <!-- Dropdown Sub Kategori -->
                    <select wire:model.live="filterSubKategori" 
                        class="bg-white border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white min-w-[140px] disabled:opacity-50"
                        {{ !$filterKategori ? 'disabled' : '' }}>
                        <option value="">Semua Sub</option>
                        @foreach($subKategorisFilter as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                        @endforeach
                    </select>                    
                </div>

                <!-- Right Side: Sort & View Controls -->
                <div class="flex items-center space-x-2">
                    <!-- Sort Selection -->
                    <div class="flex items-center">
                        <span class="text-[10px] uppercase font-bold text-gray-400 mr-2 hidden md:block">Urut:</span>
                        <select wire:model.live="sortBy" class="bg-white border border-gray-200 text-gray-900 text-xs rounded-lg focus:ring-primary-500 focus:border-primary-500 block p-2 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="az">Nama A-Z</option>
                            <option value="za">Nama Z-A</option>
                            <option value="price_high">Termahal</option>
                            <option value="price_low">Termurah</option>
                        </select>
                    </div>

                    <!-- View Mode Toggle -->
                    <div class="inline-flex rounded-lg shadow-sm">
                        <button type="button" wire:click="$set('viewMode', 'grid')" 
                            class="px-3 py-2 text-sm font-medium border border-gray-200 rounded-s-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-800 dark:border-gray-600 dark:text-white {{ $viewMode === 'grid' ? 'bg-gray-100 text-primary-700 dark:bg-gray-700' : 'bg-white' }}">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 4h4v4H4V4Zm6 0h4v4h-4V4Zm6 0h4v4h-4V4ZM4 10h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4ZM4 16h4v4H4v-4Zm6 0h4v4h-4v-4Zm6 0h4v4h-4v-4Z"/>
                            </svg>
                        </button>
                        <button type="button" wire:click="$set('viewMode', 'list')" 
                            class="px-3 py-2 text-sm font-medium border-t border-b border-r border-gray-200 rounded-e-lg hover:bg-gray-100 focus:z-10 focus:ring-2 focus:ring-primary-700 focus:text-primary-700 dark:bg-gray-800 dark:border-gray-600 dark:text-white {{ $viewMode === 'list' ? 'bg-gray-100 text-primary-700 dark:bg-gray-700' : 'bg-white' }}">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M9 8h10M9 12h10M9 16h10M4 8h.01M4 12h.01M4 16h.01"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Grid Adjustment -->
                    @if($viewMode === 'grid')
                    <div class="flex items-center space-x-1 border-l pl-2 border-gray-200 dark:border-gray-600">
                        <select wire:model.live="gridCols" class="bg-transparent border-0 text-[10px] font-black focus:ring-0 p-1 dark:text-white uppercase">
                            <option value="1">1 Col</option>
                            <option value="2">2 Cols</option>
                            <option value="3">3 Cols</option>
                            <option value="4">4 Cols</option>
                            <option value="5">5 Cols</option>
                            <option value="6">6 Cols</option>
                            <option value="8">8 Cols</option>
                            <option value="10">10 Cols</option>
                            <option value="12">12 Cols</option>
                        </select>
                    </div>
                    @endif
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
                                <button type="button" class="p-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors">
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
    @include('livewire.Inventoryfolder.modal-filter')
    @include('livewire.Inventoryfolder.modal-barang')
    @include('livewire.Inventoryfolder.modal-kategori')
    @include('livewire.Inventoryfolder.modal-subKategori')
    @include('livewire.Inventoryfolder.modal-satuan')

    @script
    <script>
        $wire.on('close-modal', () => {
            // Menutup modal Flowbite secara manual jika diperlukan
            const modalEl = document.getElementById('modal-barang');
            if (window.FlowbiteInstances) {
                const modal = window.FlowbiteInstances.getInstance('Modal', 'modal-barang');
                if (modal) {
                    modal.hide();
                }
            } else {
                // Fallback jika Instance tidak ditemukan
                modalEl.classList.add('hidden');
                modalEl.classList.remove('flex');
                document.querySelector('[modal-backdrop]')?.remove();
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
    @endscript
</div>