<!-- Modal Tambah Barang -->
<div id="modal-barang" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[50] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Barang Baru
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="modal-barang">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form wire:submit.prevent="storeBarang" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Barang -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                Barang</label>
                            <input type="text" wire:model.live.debounce.500ms="namaBarang"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                required>
                            @error('namaBarang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <!-- SKU -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">SKU</label>
                            <input type="text" wire:model.live.debounce.500ms="skuBarang"
                                class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-400 cursor-not-allowed"
                                placeholder="Otomatis..." disabled>
                            @error('skuBarang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Kategori -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategori</label>
                            <div class="flex gap-2">
                                <select wire:model.live="kategori_id_barang"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kat)
                                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                                    @endforeach
                                </select>
                                <button type="button" data-modal-target="modal-kategori"
                                    data-modal-toggle="modal-kategori"
                                    class="flex-shrink-0 flex items-center justify-center w-10 h-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all active:scale-95 shadow-md">
                                    +
                                </button>
                            </div>
                            @error('kategori_id_barang') <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Sub Kategori -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sub
                                Kategori</label>
                            <div class="flex gap-2">
                                <select wire:model.live="sub_kategori_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    required {{ !$kategori_id_barang ? 'disabled' : '' }}>
                                    <option value="">Pilih Sub Kategori</option>
                                    @foreach($subKategorisFiltered as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                                    @endforeach
                                </select>
                                <button type="button" data-modal-target="modal-subkategori"
                                    data-modal-toggle="modal-subkategori"
                                    class="flex-shrink-0 flex items-center justify-center w-10 h-10 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-bold rounded-lg text-lg dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-all active:scale-95 shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ !$kategori_id_barang ? 'disabled' : '' }}>
                                    +
                                </button>
                            </div>
                            @error('sub_kategori_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <!-- Satuan -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Satuan</label>
                            <div class="flex gap-2">
                                <select wire:model="satuan_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                    required>
                                    <option value="">Pilih Satuan</option>
                                    @foreach($satuans as $sat)
                                        <option value="{{ $sat->id }}">{{ $sat->nama }} ({{ $sat->kode }})</option>
                                    @endforeach
                                </select>
                                <button type="button" data-modal-target="modal-satuan" data-modal-toggle="modal-satuan"
                                    class="flex-shrink-0 flex items-center justify-center w-10 h-10 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-bold rounded-lg text-lg dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 transition-all active:scale-95 shadow-md">
                                    +
                                </button>
                            </div>
                            @error('satuan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Harga Beli -->
                        <div x-data="{ 
                            val: @entangle('harga_beli').live,
                            format(v) { 
                                if (!v) return ''; 
                                return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
                            } 
                        }">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                                Beli</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text" x-bind:value="format(val)"
                                    x-on:input="val = $event.target.value.replace(/\D/g, '')"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-all group-hover:border-blue-400"
                                    placeholder="0" required>
                            </div>
                            @error('harga_beli') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Harga Jual -->
                        <div x-data="{ 
                            val: @entangle('harga_jual').live,
                            format(v) { 
                                if (!v) return ''; 
                                return v.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
                            } 
                        }">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga
                                Jual</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text" x-bind:value="format(val)"
                                    x-on:input="val = $event.target.value.replace(/\D/g, '')"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white transition-all group-hover:border-blue-400"
                                    placeholder="0" required>
                            </div>
                            @error('harga_jual') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Gambar -->
                    <div class="col-span-full" x-data="{ isCompressing: false }" @compression-start.window="isCompressing = true" @compression-end.window="isCompressing = false">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Media Barang</label>
                            <div class="flex items-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="auto-compress-barang" class="sr-only peer" checked>
                                    <div
                                        class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span
                                        class="ms-3 text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Auto-Compress</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                            <!-- Tombol Tambah Gambar (Card Style) -->
                            <div class="relative h-28">
                                <label for="uploadGambars"
                                    class="flex flex-col items-center justify-center h-full w-full border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 transition-all border-spacing-4 group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div
                                            class="bg-blue-100 dark:bg-blue-900 p-2 rounded-full mb-2 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                        <p
                                            class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-tighter text-center px-1">
                                            Tambah Foto</p>
                                    </div>
                                    <input id="uploadGambars" type="file" multiple class="hidden" x-on:change="if(document.getElementById('auto-compress-barang').checked) {
                                            handleAutoCompress($event.target, 'uploadGambars', $wire)
                                        } else {
                                            @this.uploadMultiple('uploadGambars', $event.target.files)
                                        }" />
                                </label>
                            </div>

                            <!-- Preview Gambar yang sudah ada -->
                            @foreach ($gambars as $index => $gambar)
                                <div
                                    class="relative group h-28 w-full border-2 border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden bg-white shadow-sm transition-all hover:border-blue-500 hover:shadow-md">
                                    <img src="{{ is_string($gambar) ? $gambar : $gambar->temporaryUrl() }}"
                                        class="h-full w-full object-cover">

                                    <!-- Overlay Gradient saat Hover -->
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all">
                                    </div>

                                    <!-- Tombol Hapus (Top Right) -->
                                    <button type="button" wire:click="removeGambar({{ $index }})"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-lg w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all transform scale-75 group-hover:scale-100 shadow-lg hover:bg-red-600 z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <!-- Tombol Crop (Top Left) -->
                                    <button type="button"
                                        x-on:click="openCropModal({{ $index }}, $el.closest('.group').querySelector('img').src, 'gambars', $wire)"
                                        class="absolute top-1 left-1 bg-blue-500 text-white rounded-lg w-7 h-7 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all transform scale-75 group-hover:scale-100 shadow-lg hover:bg-blue-600 z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 6l3 1mim0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>

                                    <!-- Badge Utama (Bottom) -->
                                    @if($index === 0)
                                        <div
                                            class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-blue-600 to-blue-500 text-[10px] text-white py-1 text-center font-bold uppercase tracking-widest shadow-inner">
                                            Utama
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div x-show="isCompressing" style="display: none;"
                            class="mt-2 text-indigo-500 text-xs text-center animate-pulse font-medium">
                            <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-1"></span> Mengkompresi gambar...
                        </div>

                        <div wire:loading wire:target="uploadGambars, gambars"
                            class="mt-2 text-blue-500 text-xs text-center animate-pulse font-medium">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-1"></span> Mengunggah foto ke server...
                        </div>

                        @error('uploadGambars.*') <p class="mt-2 text-xs text-red-600 font-medium italic">⚠️
                            {{ $message }}
                        </p> @enderror
                        @error('gambars') <p class="mt-2 text-xs text-red-600 font-medium italic">⚠️ {{ $message }}</p>
                        @enderror
                        @error('gambars.*') <p class="mt-2 text-xs text-red-600 font-medium italic">⚠️ {{ $message }}
                        </p> @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                        <textarea wire:model="deskripsiBarang" rows="3"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white"></textarea>
                        @error('deskripsiBarang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Modal footer -->
                    <div class="flex items-center pt-4 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Simpan Barang
                        </button>
                        <button data-modal-hide="modal-barang" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>