<!-- Modal Pembelian -->
<div id="modal-pembelian" tabindex="-1" aria-hidden="true" x-show="$wire.showPurchaseModal"
    class="fixed inset-0 z-[120] overflow-hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-0 md:p-4"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">

    <div
        class="relative w-full max-w-6xl max-h-[92vh] overflow-hidden bg-white rounded-3xl shadow-2xl dark:bg-gray-800 flex flex-col border dark:border-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 rounded-lg dark:bg-primary-900/30">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Transaksi
                        Pembelian Baru</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Catat stok masuk dari vendor</p>
                </div>
            </div>
            <button type="button" wire:click="$set('showPurchaseModal', false)"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-6 overflow-y-auto flex-grow space-y-6 custom-scrollbar">
            <!-- Row 1: Vendor & Meta -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vendor Selection -->
                <div class="md:col-span-2 relative">
                    <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">
                        Informasi Vendor <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        @if($selectedVendor)
                            <div
                                class="p-3 bg-gradient-to-r from-primary-50 to-blue-50 dark:from-primary-900/20 dark:to-blue-900/10 border border-primary-200 dark:border-primary-700 rounded-xl">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        {{-- Avatar / Gambar --}}
                                        @if(!empty($selectedVendor['gambar']))
                                            <img src="{{ Storage::url($selectedVendor['gambar']) }}"
                                                class="w-20 h-20 rounded-xl object-cover border-2 border-white shadow-md flex-shrink-0"
                                                alt="{{ $selectedVendor['nama'] }}">
                                        @else
                                            <div
                                                class="w-12 h-12 rounded-xl bg-primary-600 flex items-center justify-center text-white text-lg font-black shadow-md flex-shrink-0">
                                                {{ strtoupper(substr($selectedVendor['nama'], 0, 1)) }}
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <p class="text-sm font-black text-gray-900 dark:text-white">
                                                    {{ $selectedVendor['nama'] }}
                                                </p>
                                                @if(!empty($selectedVendor['tag']))
                                                    <span
                                                        class="text-[9px] font-bold uppercase tracking-widest bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-300 px-2 py-0.5 rounded-full">{{ $selectedVendor['tag'] }}</span>
                                                @endif
                                            </div>
                                            @if(!empty($selectedVendor['kontak']))
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    {{ $selectedVendor['kontak'] }}
                                                </p>
                                            @endif
                                            @if(!empty($selectedVendor['alamat']))
                                                <p
                                                    class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-start gap-1 line-clamp-2">
                                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $selectedVendor['alamat'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" wire:click="$set('selectedVendor', null)"
                                        class="text-gray-400 hover:text-red-500 transition flex-shrink-0 p-1 hover:bg-red-50 rounded-lg"
                                        title="Ganti Vendor">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-[9px] text-primary-500 font-bold uppercase tracking-widest mt-2">✓ Vendor
                                    Terpilih</p>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <div class="relative flex-grow" x-data="{ open: false }">
                                    <input type="text" wire:model.live.debounce.250ms="searchVendor"
                                        x-on:focus="open = true" x-on:blur="setTimeout(() => open = false, 150)"
                                        class="w-full p-3 pl-10 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none bg-gray-50 dark:bg-gray-700 dark:border-gray-600"
                                        placeholder="Cari atau pilih vendor...">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>

                                    <!-- Dropdown Vendor Picker -->
                                    <div x-show="open"
                                        class="absolute z-[60] w-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl max-h-60 overflow-y-auto"
                                        style="display: none;">
                                        @forelse($vendors as $v)
                                            <button type="button" @mousedown.prevent
                                                wire:click="selectVendor({{ $v->id }}, '{{ addslashes($v->nama) }}')"
                                                class="w-full flex items-center gap-3 p-3 hover:bg-primary-50 dark:hover:bg-gray-700 transition text-left border-b last:border-0 dark:border-gray-700">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-xs font-bold text-primary-600">
                                                    {{ substr($v->nama, 0, 1) }}
                                                </div>
                                                <div>
                                                    <span
                                                        class="text-sm font-medium dark:text-white block">{{ $v->nama }}</span>
                                                    @if($v->tag)
                                                        <span class="text-[10px] text-gray-400">{{ $v->tag }}</span>
                                                    @endif
                                                </div>
                                            </button>
                                        @empty
                                            <div class="p-4 text-center text-gray-500 text-sm italic">
                                                @if($searchVendor)
                                                    Vendor "{{ $searchVendor }}" tidak ditemukan.
                                                @else
                                                    Belum ada vendor. Tambah dulu !
                                                @endif
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <button data-modal-target="modal-vendor" data-modal-toggle="modal-vendor" @click="open = false"
                                    class="bg-blue-50 text-blue-600 p-3 rounded-xl hover:bg-blue-600 hover:text-white transition shadow-sm"
                                    title="Tambah Vendor Baru">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    @error('selectedVendor') <span
                    class="text-[10px] text-red-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Meta Info (Nota & Tanggal) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">
                            Nomor Nota <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="nomorNota"
                            class="w-full p-3 text-sm border border-gray-200 rounded-xl bg-gray-50 dark:bg-gray-700 dark:border-gray-600 font-mono font-bold @error('nomorNota') border-rose-500 ring-1 ring-rose-500 @enderror">
                        @error('nomorNota') <span class="text-[9px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                    </div>
                <div>
                    <label class="block mb-2 text-xs font-black text-gray-400 uppercase tracking-widest">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" wire:model="tanggalPembelian"
                        class="w-full p-3 text-sm border border-gray-200 rounded-xl bg-gray-50 dark:bg-gray-700 dark:border-gray-600 @error('tanggalPembelian') border-rose-500 ring-1 ring-rose-500 @enderror">
                    @error('tanggalPembelian') <span class="text-[9px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                </div>
                </div>
            </div>

            <!-- Row 1.5: Financials & Documents (PRO) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50/50 dark:bg-gray-700/20 rounded-2xl border border-gray-100 dark:border-gray-700">
                <!-- Additional Costs -->
                <div class="space-y-4">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Biaya Tambahan</label>
                    <div class="space-y-3">
                        <div class="relative" x-data="{ ongkir: @entangle('ongkir').live }">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">ONGKIR</span>
                            <input type="text" x-model="ongkir" x-on:input="ongkir = $event.target.value.replace(/\D/g, '')"
                                class="w-full p-3 pl-16 text-sm font-bold border border-gray-100 rounded-xl focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600"
                                placeholder="0">
                        </div>
                        <div class="relative" x-data="{ biayaLain: @entangle('biayaLain').live }">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">LAINNYA</span>
                            <input type="text" x-model="biayaLain" x-on:input="biayaLain = $event.target.value.replace(/\D/g, '')"
                                class="w-full p-3 pl-16 text-sm font-bold border border-gray-100 rounded-xl focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600"
                                placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="space-y-4 px-6 border-x border-gray-100 dark:border-gray-700">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Metode & Akun</label>
                    <div class="grid grid-cols-1 gap-3">
                        <div class="relative">
                            <select wire:model.live="metodePembayaran" class="w-full p-2.5 text-xs font-bold border border-gray-100 rounded-xl bg-white dark:bg-gray-800 dark:border-gray-600 focus:ring-primary-500 shadow-sm @error('metodePembayaran') border-rose-500 @enderror">
                                <option value="Cash">Tunai (Cash)</option>
                                <option value="Kredit">Kredit (Tempo)</option>
                            </select>
                            @error('metodePembayaran') <span class="text-[9px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="relative" x-data="{ method: @entangle('metodePembayaran'), dp: @entangle('jumlahDP') }">
                            <select wire:model.live="akunKasId" 
                                :disabled="method === 'Kredit' && (dp == 0 || !dp)"
                                class="w-full p-2.5 text-xs font-bold border border-gray-100 rounded-xl bg-white dark:bg-gray-800 dark:border-gray-600 focus:ring-primary-500 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed @error('akunKasId') border-rose-500 ring-1 ring-rose-500 @enderror">
                                <option value="">-- Pilih Akun Kas --</option>
                                @foreach($this->userAkunKas as $akun)
                                    <option value="{{ $akun->id }}">{{ $akun->nama }}</option>
                                @endforeach
                            </select>
                            @error('akunKasId') <span class="text-[9px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @if($metodePembayaran === 'Kredit')
                            <div class="space-y-3 pt-1">
                                <div class="relative" x-data="{ dp: @entangle('jumlahDP').live }">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[8px] font-black text-emerald-500">DP / UANG MUKA</span>
                                    <input type="text" x-model="dp" x-on:input="dp = $event.target.value.replace(/\D/g, '')"
                                        class="w-full p-2.5 pl-24 text-xs font-bold border border-emerald-100 rounded-xl bg-emerald-50/30 dark:bg-emerald-900/10 dark:border-emerald-900/30 text-emerald-600 shadow-sm focus:ring-emerald-500 placeholder-emerald-300"
                                        placeholder="0 (Opsional)">
                                </div>

                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[8px] font-black text-rose-500">JATUH TEMPO *</span>
                                    <input type="date" wire:model="jatuhTempo" class="w-full p-2.5 pl-24 text-xs font-bold border border-gray-100 rounded-xl dark:bg-gray-800 dark:border-gray-600 text-rose-600 shadow-sm @error('jatuhTempo') border-rose-500 ring-1 ring-rose-500 @enderror">
                                    @error('jatuhTempo') <span class="text-[9px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Document Upload with Compression -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">
                            Upload Nota <span class="text-rose-500">*</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="auto-compress-nota" class="sr-only peer" checked>
                            <div
                                class="relative w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                            </div>
                            <span
                                class="ms-2 text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Auto</span>
                        </label>
                    </div>
                    
                    <div x-data="{ isCompressing: false }" @compression-start.window="isCompressing = true" @compression-end.window="isCompressing = false" class="relative">
                        <div class="flex flex-col items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-2xl cursor-pointer hover:bg-white dark:hover:bg-gray-700 transition relative overflow-hidden bg-gray-50">
                                @if($compressedInvoice)
                                    <div class="relative group h-full w-full">
                                        <img src="{{ is_string($compressedInvoice) ? $compressedInvoice : $compressedInvoice->temporaryUrl() }}" class="h-full w-full object-contain p-2 rounded-xl">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-xl gap-2 z-20">
                                            <button type="button" x-on:click.prevent="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'compressedInvoice', $wire)" class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </button>
                                            <button type="button" wire:click="$set('compressedInvoice', null)" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-6 h-6 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Upload Bukti</p>
                                    </div>
                                @endif
                                
                                <input type="file" class="hidden" accept="image/*" x-on:change="if(document.getElementById('auto-compress-nota').checked) {
                                        handleAutoCompress($event.target, 'compressedInvoice', $wire)
                                    } else {
                                        @this.upload('compressedInvoice', $event.target.files[0])
                                    }" />
                            </label>

                            <div x-show="isCompressing" style="display: none;" class="mt-3 text-indigo-500 text-xs text-center animate-pulse font-medium w-full">
                                <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-1"></span> Memproses gambar...
                            </div>
                            <div wire:loading wire:target="compressedInvoice" class="mt-3 text-blue-500 text-xs text-center animate-pulse font-medium w-full">
                                <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-1"></span> Mengunggah foto ke server...
                            </div>

                            @error('compressedInvoice') 
                                <span class="text-[10px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-700">

            <!-- Row 2: Item Search & Cart -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">
                        Item Pembelian <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative w-full max-w-sm">
                        <input type="text" wire:model.live.debounce.300ms="searchBarangPurchase"
                            class="w-full p-2 pl-8 text-xs border border-gray-200 rounded-lg focus:ring-primary-500 outline-none dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Ketik nama barang untuk menambah...">
                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>

                        @if($searchBarangResults->isNotEmpty())
                            <div
                                class="absolute z-[60] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden">
                                @foreach($searchBarangResults as $res)
                                    <button type="button" wire:click="addToPurchaseCart({{ $res->id }})"
                                        class="w-full flex items-center gap-3 p-2 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition text-left border-b last:border-0 dark:border-gray-700">
                                        @php
                                            $img = $res->gambarBarangs->where('gambar_utama', true)->first() ?? $res->gambarBarangs->first();
                                            $path = $img ? (str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path)) : 'https://ui-avatars.com/api/?name=' . $res->nama;
                                        @endphp
                                        <img src="{{ $path }}" class="w-8 h-8 rounded object-cover shadow-sm">
                                        <div>
                                            <p class="text-xs font-bold dark:text-white">{{ $res->nama }}</p>
                                            <p class="text-[9px] text-gray-400 font-mono">{{ $res->sku }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @error('purchaseCart') <span class="text-[10px] text-rose-500 font-bold uppercase mt-1 block">{{ $message }}</span> @enderror

                <!-- Cart Table -->
                <div class="overflow-visible rounded-xl border border-gray-100 dark:border-gray-700">
                    <table class="w-full text-left text-sm">
                        <thead
                            class="bg-gray-50 dark:bg-gray-700/50 text-[10px] uppercase font-black tracking-widest text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3">Harga Beli</th>
                                <th class="px-4 py-3">Gudang Tujuan</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Catatan/Instruksi</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($purchaseCart as $index => $item)
                                <tr x-data="{ rowActive: false }" :class="{'z-[160] relative': rowActive, 'z-0': !rowActive}" class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-4">
                                        <p class="font-bold text-gray-900 dark:text-white leading-tight">{{ $item['nama'] }}
                                        </p>
                                        <p class="text-[10px] font-mono text-gray-400">{{ $item['sku'] }}</p>
                                    </td>
                                    <td class="px-4 py-4 w-24">
                                        <input type="number" wire:model.live="purchaseCart.{{ $index }}.qty" min="1"
                                            class="w-full p-2 text-center text-sm font-bold border border-gray-200 rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
                                    </td>
                                    <td class="px-4 py-4 w-40">
                                        <div class="relative group" x-data="{ 
                                                    val: @entangle('purchaseCart.' . $index . '.harga').live,
                                                    format(v) { 
                                                        if (!v) return ''; 
                                                        return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
                                                    } 
                                                }">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-primary-600">Rp</span>
                                            <input type="text" x-bind:value="format(val)"
                                                x-on:input="val = $event.target.value.replace(/\D/g, '')"
                                                class="w-full p-2 pl-8 text-sm font-bold border border-gray-200 rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 transition-all group-hover:border-primary-400">
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($item['status'] === 'Received')
                                            <div class="flex gap-1 items-center">
                                                <select wire:model.live="purchaseCart.{{ $index }}.gudang_id"
                                                    class="flex-grow text-xs border border-gray-200 rounded-lg p-2 dark:bg-gray-700 dark:border-gray-600">
                                                    @foreach($gudangs as $g)
                                                        <option value="{{ $g->id }}">{{ $g->nama }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" wire:click="$set('showModalGudang', true)"
                                                    class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition shadow-sm"
                                                    title="Tambah Gudang Baru">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-dashed border-gray-200 dark:border-gray-700">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase italic">Belum dialokasikan</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <select wire:model.live="purchaseCart.{{ $index }}.status"
                                            class="text-[10px] font-bold uppercase rounded-lg p-2 border-0 focus:ring-0
                                                                                             {{ $item['status'] === 'Received' ? 'bg-green-100 text-green-700' : ($item['status'] === 'PO' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                            <option value="Received">DITERIMA</option>
                                            <option value="PO">PRE-ORDER</option>
                                            <option value="Pending">PENDING</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2" x-data="{ 
                                            openEditor() {
                                                $dispatch('open-quill-editor', { 
                                                    index: {{ $index }}, 
                                                    catatan: @js($item['catatan']),
                                                    catatanInternal: @js($item['catatan_internal'])
                                                });
                                            }
                                        }">
                                            <!-- Edit Button -->
                                            <button type="button" @click="openEditor()"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-50 text-gray-500 border border-gray-200 rounded-lg hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all shadow-sm group"
                                                title="Tambah/Edit Catatan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>

                                            <!-- Previews -->
                                            @if($item['catatan'])
                                                <div x-data="{ open: false }" class="relative inline-block">
                                                    <button @mouseenter="open = true; rowActive = true" @mouseleave="open = false; rowActive = false" @click="openEditor()"
                                                        class="w-6 h-6 flex items-center justify-center bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition-all shadow-sm border border-amber-200">
                                                        <span class="text-[10px] font-black">V</span>
                                                    </button>
                                                    <div x-show="open" x-cloak
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                        class="absolute left-1/2 -translate-x-1/2 bottom-full mb-3 z-[170] w-72 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-amber-400">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <p class="text-[9px] font-black uppercase text-amber-600">Catatan Vendor (Preview)</p>
                                                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" /></svg>
                                                        </div>
                                                        <div class="text-[11px] text-gray-700 dark:text-gray-300 prose prose-sm leading-relaxed max-h-48 overflow-y-auto custom-scrollbar">
                                                            {!! $item['catatan'] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($item['catatan_internal'])
                                                <div x-data="{ open: false }" class="relative inline-block">
                                                    <button @mouseenter="open = true; rowActive = true" @mouseleave="open = false; rowActive = false" @click="openEditor()"
                                                        class="w-6 h-6 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm border border-indigo-200">
                                                        <span class="text-[10px] font-black">I</span>
                                                    </button>
                                                    <div x-show="open" x-cloak
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                        class="absolute left-1/2 -translate-x-1/2 bottom-full mb-3 z-[170] w-72 p-4 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-2xl shadow-2xl border-t-4 border-indigo-400">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <p class="text-[9px] font-black uppercase text-indigo-600">Catatan Internal (Preview)</p>
                                                            <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" /></svg>
                                                        </div>
                                                        <div class="text-[11px] text-gray-700 dark:text-gray-300 prose prose-sm leading-relaxed max-h-48 overflow-y-auto custom-scrollbar">
                                                            {!! $item['catatan_internal'] !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-right font-black text-gray-900 dark:text-white">
                                        Rp{{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <button type="button" wire:click="removeFromPurchaseCart({{ $index }})"
                                            class="text-gray-300 hover:text-red-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2 opacity-30">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-sm font-bold uppercase tracking-widest">Keranjang Kosong</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(collect($purchaseCart)->isNotEmpty())
                             <tfoot class="bg-gray-50/50 dark:bg-gray-700/30">
                                @php
                                    $subtotalCart = collect($purchaseCart)->sum(fn($i) => $i['qty'] * $i['harga']);
                                    $extraCosts = (float)($ongkir ?: 0) + (float)($biayaLain ?: 0);
                                    $grandTotal = $subtotalCart + $extraCosts;
                                @endphp
                                @if($extraCosts > 0)
                                    <tr class="border-t border-gray-100 dark:border-gray-700">
                                        <td colspan="5" class="px-4 py-2 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Subtotal Barang</td>
                                        <td class="px-4 py-2 text-right text-sm font-bold text-gray-600">Rp{{ number_format($subtotalCart, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="px-4 py-1 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Biaya Tambahan (Ongkir + Lain)</td>
                                        <td class="px-4 py-1 text-right text-sm font-bold text-gray-600">Rp{{ number_format($extraCosts, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-4 text-right text-xs font-black uppercase tracking-widest text-gray-400">
                                        Total Tagihan</td>
                                    <td class="px-4 py-4 text-right text-lg font-black text-primary-600">
                                        Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                @if($metodePembayaran === 'Kredit' && $jumlahDP > 0)
                                    <tr class="bg-emerald-50/50 dark:bg-emerald-900/10">
                                        <td colspan="5" class="px-4 py-2 text-right text-[10px] font-black uppercase tracking-widest text-emerald-600">DP (Dibayar Sekarang)</td>
                                        <td class="px-4 py-2 text-right text-sm font-black text-emerald-600">- Rp{{ number_format($jumlahDP, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                    <tr class="bg-rose-50/50 dark:bg-rose-900/10">
                                        <td colspan="5" class="px-4 py-2 text-right text-[10px] font-black uppercase tracking-widest text-rose-600">Sisa Hutang</td>
                                        <td class="px-4 py-2 text-right text-sm font-black text-rose-600">Rp{{ number_format($grandTotal - $jumlahDP, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>


        <!-- Modal footer -->
        <div class="flex items-center p-6 space-x-3 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
            @if(count($purchaseCart) > 0)
                <button type="button" wire:click="clearPurchaseCart"
                    class="text-red-600 bg-red-50 hover:bg-red-100 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-xl border border-red-200 text-xs font-bold px-5 py-2.5 transition">
                    BERSIHKAN
                </button>
            @endif
            <div class="flex-grow"></div>
            <button type="button" wire:click="$set('showPurchaseModal', false)"
                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-xl border border-gray-200 text-sm font-bold px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-600">
                Close
            </button>
            @if($this->isPurchaseReady)
                <button type="button" wire:click="savePurchase"
                    class="sm:min-w-[200px] text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-black rounded-xl text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-primary-500/30">
                    SIMPAN TRANSAKSI
                </button>
            @endif
        </div>
    </div>
</div>