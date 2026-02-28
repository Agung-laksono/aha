<div class="m-5 max-w-full overflow-y-scroll" id="layarPenuh">

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



    <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-5 " >
        <!-- Tombol Toggle -->
        <button id="fsToggle" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
            Buka Layar Penuh
        </button>

        <div class="mx-auto px-4">
            <!-- Pro Financial Deck -->
            @include('livewire.Inventoryfolder.kas')        

            <!-- Heading & Filters -->
            @include('livewire.Inventoryfolder.heading-filter')
            

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
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <span
                                        class="text-[10px] font-mono font-medium text-gray-500 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 px-2 py-0.5 rounded uppercase tracking-wider">
                                        SKU: {{ $barang->sku }}
                                    </span>

                                    <!-- Stock Badge with Distribution -->
                                    <div x-data="{ showDist: false }" class="relative">
                                        <button @mouseenter="showDist = true" @mouseleave="showDist = false"
                                            class="flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tighter transition-all
                                            {{ $barang->total_stok > 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                            <span>{{ $barang->total_stok }}</span>
                                        </button>

                                        <!-- Distribution Popover -->
                                        <div x-show="showDist" 
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="absolute bottom-full right-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 p-3 z-50 pointer-events-none">
                                            <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest border-b pb-1.5 mb-2 dark:border-gray-700">Sebaran Stok</p>
                                            <div class="space-y-1.5">
                                                @forelse($barang->stoks as $stok)
                                                    <div class="flex justify-between items-center text-[11px]">
                                                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ $stok->gudang->nama }}</span>
                                                        <span class="font-bold text-gray-900 dark:text-white">{{ $stok->jumlah }}</span>
                                                    </div>
                                                @empty
                                                    <p class="text-[11px] text-gray-400 italic">Kosong</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
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
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[9px] text-gray-400 uppercase tracking-wider font-medium">SKU: {{ $barang->sku }} | {{ $barang->kategori->nama ?? 'Umum' }}</p>
                                    <div class="flex gap-1">
                                        @foreach($barang->stoks as $stok)
                                            <span class="text-[8px] px-1.5 py-0.25 bg-gray-100 dark:bg-gray-700 text-gray-500 rounded border border-gray-200 dark:border-gray-600">
                                                {{ $stok->gudang->nama }}: {{ $stok->jumlah }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="text-center px-4 border-x border-gray-100 dark:border-gray-700">
                                <p class="text-xs text-gray-400 uppercase font-bold text-[9px] mb-0.5">Stok</p>
                                <p class="text-sm font-black {{ $barang->total_stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $barang->total_stok }}</p>
                            </div>
                            <div class="text-right px-4">
                                <p class="text-xs text-gray-400 uppercase font-bold text-[9px] mb-0.5">Harga</p>
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
    @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'member') && !auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'sales'))
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
    @endif
    @include('livewire.Inventoryfolder.modal-filter')
    @include('livewire.Inventoryfolder.modal-barang')
    @include('livewire.Inventoryfolder.modal-kategori')
    @include('livewire.Inventoryfolder.modal-subKategori')
    @include('livewire.Inventoryfolder.modal-satuan')
    @include('livewire.Inventoryfolder.modal-pembelian')
    
    @include('livewire.Inventoryfolder.modal-riwayat-pembelian')
    @include('livewire.Inventoryfolder.modal-vendor')
    @include('livewire.Inventoryfolder.modal-gudang')
    @include('livewire.Inventoryfolder.modal-kas')
    @include('livewire.Inventoryfolder.modal-akun-kas')

    <!-- Quill Editor Modal (Shared) -->
    <div x-data="{ 
        show: false, 
        index: null, 
        activeTab: 'vendor',
        contentVendor: '',
        contentInternal: '',
        historyNotes: [],
        searchQuery: '',
        showDropdown: false,
        customPresets: {
            vendor: [],
            internal: []
        },
        editor: null,
        defaultPresets: {
            vendor: ['Segera dikirim', 'Fragile / Pecah Belah', 'Packing Kayu', 'Cek Kualitas'],
            internal: ['Urgent! Prioritaskan.', 'Perlu Review Atasan', 'Tunggu Gudang', 'Barang Mahal']
        },
        init() {
            this.editor = new Quill(this.$refs.quillEditor, {
                theme: 'snow',
                placeholder: 'Tulis instruksi di sini...',
                modules: {
                    toolbar: [
                        ['bold', 'italic'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });
            
            window.addEventListener('open-quill-editor', async (e) => {
                this.index = e.detail.index;
                
                // Get most recent data directly from Livewire to avoid stale data from event
                const currentData = $wire.purchaseCart[this.index];
                this.contentVendor = currentData.catatan || '';
                this.contentInternal = currentData.catatan_internal || '';
                this.activeTab = 'vendor';
                
                // Fetch History & Custom Presets from Server
                const barangId = currentData.barang_id;
                this.historyNotes = await $wire.getHistoryNotes(barangId);
                this.customPresets.vendor = await $wire.getPresets('vendor');
                this.customPresets.internal = await $wire.getPresets('internal');

                // Set content to editor root
                this.editor.root.innerHTML = this.contentVendor;
                this.show = true;
                
                // Force refocus and clear search
                this.searchQuery = '';
                this.showDropdown = false;
            });

            window.addEventListener('preset-saved', (e) => {
                this.customPresets[e.detail.tipe] = e.detail.presets;
            });
        },
        switchTab(tab) {
            if (this.activeTab === tab) return;
            
            if (this.activeTab === 'vendor') {
                this.contentVendor = this.editor.root.innerHTML;
            } else {
                this.contentInternal = this.editor.root.innerHTML;
            }
            
            this.activeTab = tab;
            this.editor.root.innerHTML = (tab === 'vendor' ? this.contentVendor : this.contentInternal) || '';
        },
        addPreset(text) {
            const range = this.editor.getSelection();
            if (range) {
                this.editor.insertText(range.index, text + ' ');
            } else {
                const length = this.editor.getLength();
                this.editor.insertText(length - 1, text + ' ');
            }
        },
        save() {
            if (this.activeTab === 'vendor') {
                this.contentVendor = this.editor.root.innerHTML;
            } else {
                this.contentInternal = this.editor.root.innerHTML;
            }
            
            let finalVendor = (this.contentVendor === '<p><br></p>' || this.contentVendor === '') ? '' : this.contentVendor;
            let finalInternal = (this.contentInternal === '<p><br></p>' || this.contentInternal === '') ? '' : this.contentInternal;
            
            $wire.set('purchaseCart.' + this.index + '.catatan', finalVendor);
            $wire.set('purchaseCart.' + this.index + '.catatan_internal', finalInternal);
            
            // Notification or visual feedback
            const btn = document.getElementById('save-quill-btn');
            if(btn) {
                const oldText = btn.innerHTML;
                btn.innerHTML = '✓ Tersimpan';
                setTimeout(() => btn.innerHTML = oldText, 2000);
            }
        },
        closeModal() {
            this.save();
            this.show = false;
        },
        selectSuggestion(content) {
            this.editor.root.innerHTML = content;
            this.showDropdown = false;
            this.searchQuery = '';
        },
        get filteredHistory() {
            if (!this.searchQuery) return this.historyNotes;
            return this.historyNotes.filter(n => n.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },
        get filteredCustomPresets() {
            const list = this.customPresets[this.activeTab] || [];
            if (!this.searchQuery) return list;
            return list.filter(p => p.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },
        get filteredDefaultPresets() {
            const list = this.defaultPresets[this.activeTab] || [];
            if (!this.searchQuery) return list;
            return list.filter(p => p.toLowerCase().includes(this.searchQuery.toLowerCase()));
        }
    }" x-show="show" x-cloak x-on:keydown.escape.window="show = false"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        style="display: none;">
        <div @click.away="show = false" class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden relative z-[10000]">
            <div class="flex border-b border-gray-100 dark:border-gray-700">
                <button @click="switchTab('vendor')" 
                    :class="activeTab === 'vendor' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent text-gray-500 hover:bg-gray-50'"
                    class="flex-1 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                    Catatan Vendor
                </button>
                <button @click="switchTab('internal')" 
                    :class="activeTab === 'internal' ? 'border-amber-600 text-amber-600 bg-amber-50/50' : 'border-transparent text-gray-500 hover:bg-gray-50'"
                    class="flex-1 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                    Catatan Internal
                </button>
            </div>

            <div class="p-6">
                <!-- Search & Info Row -->
                <div class="mb-4 flex flex-col md:flex-row gap-3 relative" @click.away="showDropdown = false">
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <input type="text" x-model="searchQuery" 
                            @focus="showDropdown = true"
                            @input="showDropdown = true"
                            placeholder="Cari saran atau preset..." 
                            class="w-full pl-9 pr-4 py-2.5 text-xs font-bold border border-gray-100 rounded-xl dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-indigo-500 outline-none shadow-sm transition-all focus:border-indigo-500">
                        
                        <!-- Suggestions Dropdown -->
                        <div x-show="showDropdown && (filteredHistory.length > 0 || filteredCustomPresets.length > 0 || filteredDefaultPresets.length > 0)" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-2xl z-[10001] max-h-64 overflow-y-auto custom-scrollbar" style="display: none;">
                            
                            <!-- History Section -->
                            <template x-if="filteredHistory.length > 0">
                                <div class="p-2 border-b border-gray-50 dark:border-gray-700">
                                    <p class="text-[8px] font-black uppercase text-indigo-500 tracking-widest px-2 mb-1">Saran Riwayat Barang</p>
                                    <div class="grid grid-cols-1 gap-1">
                                        <template x-for="(note, i) in filteredHistory" :key="'h-'+i">
                                            <button @click="selectSuggestion(note)" 
                                                class="w-full text-left px-3 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-all group">
                                                <div class="text-[10px] font-bold text-gray-700 dark:text-gray-300 line-clamp-2" x-html="note"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Custom Presets -->
                            <template x-if="filteredCustomPresets.length > 0">
                                <div class="p-2 border-b border-gray-50 dark:border-gray-700">
                                    <p class="text-[8px] font-black uppercase text-emerald-500 tracking-widest px-2 mb-1">Preset Anda (★)</p>
                                    <div class="grid grid-cols-1 gap-1">
                                        <template x-for="(preset, i) in filteredCustomPresets" :key="'c-'+i">
                                            <button @click="selectSuggestion(preset)" 
                                                class="w-full text-left px-3 py-2 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl transition-all group">
                                                <div class="text-[10px] font-bold text-gray-700 dark:text-gray-300 line-clamp-1" x-html="preset"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Default Presets -->
                            <template x-if="filteredDefaultPresets.length > 0">
                                <div class="p-2">
                                    <p class="text-[8px] font-black uppercase text-gray-400 tracking-widest px-2 mb-1">Preset Bawaan</p>
                                    <div class="grid grid-cols-1 gap-1">
                                        <template x-for="(preset, i) in filteredDefaultPresets" :key="'d-'+i">
                                            <button @click="selectSuggestion(preset)" 
                                                class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-xl transition-all group">
                                                <div class="text-[10px] font-bold text-gray-600 dark:text-gray-400 line-clamp-1" x-text="preset"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div :class="activeTab === 'vendor' ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700'" class="mb-4 px-4 py-2 rounded-xl text-[10px] font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span x-text="activeTab === 'vendor' ? 'Catatan ini akan tampil pada hasil cetak PO untuk vendor.' : 'Catatan ini hanya untuk tim internal dan tidak akan tampil di cetak PO.'"></span>
                </div>

                <div class="bg-white rounded-xl overflow-hidden border border-gray-200" wire:ignore>
                    <div x-ref="quillEditor" style="height: 180px;" class="dark:text-white text-gray-900"></div>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-[9px] font-black uppercase text-gray-400 tracking-widest italic">Tips: Simpan catatan yang sering dipakai sebagai preset</p>
                        <button @click="$wire.savePreset(editor.root.innerHTML, activeTab)" 
                            class="text-[9px] font-black uppercase text-indigo-600 hover:text-indigo-700 tracking-widest flex items-center gap-1 transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                            Simpan ke Preset
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="save()" id="save-quill-btn"
                        :class="activeTab === 'vendor' ? 'bg-indigo-600 shadow-indigo-200' : 'bg-amber-600 shadow-amber-200'"
                        class="flex-grow py-3 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:opacity-90 shadow-lg transition-all">
                        Simpan Catatan
                    </button>
                    <button type="button" @click="closeModal()" 
                        class="px-8 py-3 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all shadow-xl active:scale-95">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

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

        $wire.on('close-modal-akun-kas', () => {
             const modalEl = document.getElementById('modal-akun-kas');
             if (window.FlowbiteInstances) {
                 const modal = window.FlowbiteInstances.getInstance('Modal', 'modal-akun-kas');
                 if (modal) modal.hide();
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

        $wire.on('success-retur', (event) => {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-24 right-6 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-[120] border border-emerald-400/30 animate-fade-in-up flex items-center gap-4';
            toast.innerHTML = `
                <div class="p-2 bg-white/20 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-emerald-100 leading-none mb-1">Retur Berhasil</p>
                    <p class="text-xs font-bold leading-tight">${event[0].message}</p>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2', 'scale-95', 'transition-all', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        });

        $wire.on('error-retur', (event) => {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-24 right-6 bg-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl z-[120] border border-rose-400/30 animate-fade-in-up flex items-center gap-4';
            toast.innerHTML = `
                <div class="p-2 bg-white/20 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-rose-100 leading-none mb-1">Retur Gagal</p>
                    <p class="text-xs font-bold leading-tight">${event[0].message}</p>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2', 'scale-95', 'transition-all', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        });

        // Global delegated listener for Flowbite modals in dynamic content
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-modal-target]');
            if (!btn) return;
            
            const modalId = btn.getAttribute('data-modal-target');
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;

            // Trigger modal explicitly if normal data-attribute fails
            if (window.FlowbiteInstances) {
                const modal = window.FlowbiteInstances.getInstance('Modal', modalId);
                if (modal && modal.isHidden) {
                    modal.show();
                }
            } else if (modalEl.classList.contains('hidden')) {
                modalEl.classList.remove('hidden');
                modalEl.classList.add('flex');
            }
        });

        //layar penuh
        const targetElement = document.getElementById('layarPenuh');
        const btn = document.getElementById('fsToggle');

        btn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            // Masuk ke Full Screen pada ELEMEN SPESIFIK
            targetElement.requestFullscreen()
            .catch(err => alert(`Gagal: ${err.message}`));
        } else {
            // KELUAR dari Full Screen (selalu melalui document)
            document.exitFullscreen();
        }
        });

        // Update tampilan tombol saat status berubah
        document.addEventListener('fullscreenchange', () => {
        if (document.fullscreenElement) {
            btn.innerText = "Keluar Layar Penuh";
            btn.classList.replace('bg-indigo-600', 'bg-red-600');
        } else {
            btn.innerText = "Buka Layar Penuh";
            btn.classList.replace('bg-red-600', 'bg-indigo-600');
        }
        });

    </script>
    @endscript
</div>