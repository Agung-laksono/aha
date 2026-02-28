<div id="modal-gudang" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[200] justify-center items-center w-full md:inset-0 h-full max-h-full">

    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div
            class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border-t-4 border-indigo-600 flex flex-col overflow-hidden">

            <div
                class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Tambah Gudang
                    Baru</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-modal-hide="modal-gudang">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="storeGudang">
                <div class="px-6 py-6 space-y-4">
                    <!-- Nama Gudang -->
                    <div>
                        <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400">Nama
                            Gudang <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="namaGudang" required
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Contoh: Gudang Utama / Rak A">
                        @error('namaGudang') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label
                            class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400">Lokasi</label>
                        <input type="text" wire:model="lokasiGudang"
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Contoh: Lantai 1, Area B">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label
                            class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400">Deskripsi
                            Tambahan</label>
                        <textarea wire:model="deskripsiGudang" rows="3"
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Keterangan kondisi atau fungsi gudang..."></textarea>
                    </div>

                    <!-- Foto Gudang -->
                    <div x-data="{ isCompressing: false }" @compression-start.window="isCompressing = true"
                        @compression-end.window="isCompressing = false">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-black uppercase text-gray-500 dark:text-gray-400">Foto
                                Gudang</label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="auto-compress-gudang" class="sr-only peer" checked>
                                <div
                                    class="relative w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span
                                    class="ms-2 text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Auto</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 transition-all">
                                @if($gambarGudang ?? null)
                                    <div class="relative group h-full w-full">
                                        <img src="{{ is_string($gambarGudang) ? $gambarGudang : $gambarGudang->temporaryUrl() }}"
                                            class="h-full w-full object-contain p-2 rounded-xl">
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-xl gap-2">
                                            <button type="button"
                                                x-on:click="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'gambarGudang', $wire)"
                                                class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="$set('gambarGudang', null)"
                                                class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-black">Upload
                                            Foto</p>
                                    </div>
                                @endif
                                <input type="file" class="hidden" accept="image/*" x-on:change="if(document.getElementById('auto-compress-gudang').checked) {
                                        handleAutoCompress($event.target, 'gambarGudang', $wire)
                                    } else {
                                        @this.upload('gambarGudang', $event.target.files[0])
                                    }" />
                            </label>
                        </div>
                        <div x-show="isCompressing" style="display: none;"
                            class="mt-3 text-indigo-500 text-xs text-center animate-pulse font-medium">
                            <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-1"></span> Mengkompresi
                            gambar...
                        </div>
                        <div wire:loading wire:target="gambarGudang"
                            class="mt-3 text-blue-500 text-xs text-center animate-pulse font-medium">
                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-1"></span> Mengunggah foto ke
                            server...
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end space-x-3">
                    <button type="button" data-modal-hide="modal-gudang"
                        class="px-5 py-2.5 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-100">BATAL</button>
                    <button type="submit"
                        class="px-8 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 uppercase tracking-widest transition-all active:scale-95">SIMPAN
                        GUDANG</button>
                </div>
            </form>
        </div>
    </div>
</div>