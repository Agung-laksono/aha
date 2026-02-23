<div x-data="{ show: @entangle('showModalGudang') }" x-show="show" x-on:close-modal-gudang.window="show = false"
    class="fixed inset-0 z-[70] overflow-y-auto" style="display: none;">

    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full dark:bg-gray-800 border-t-4 border-indigo-600">

            <div
                class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Tambah Gudang
                    Baru</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-500">
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

                    <!-- Lokasi/Deskripsi -->
                    <div>
                        <label
                            class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400">Deskripsi
                            Tambahan</label>
                        <textarea wire:model="deskripsiGudang" rows="3"
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Keterangan kondisi atau fungsi gudang..."></textarea>
                    </div>

                    <!-- Gambar Gudang -->
                    <div>
                        <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400">Foto
                            Gudang</label>
                        <div class="flex items-center justify-center w-full">
                            <label
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                @if($gambarGudang)
                                    <img src="{{ $gambarGudang->temporaryUrl() }}" class="h-full object-contain p-2">
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400">Upload Foto Gudang</p>
                                    </div>
                                @endif
                                <input type="file" wire:model="gambarGudang" class="hidden" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end space-x-3">
                    <button type="button" @click="show = false"
                        class="px-5 py-2.5 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-100">BATAL</button>
                    <button type="submit"
                        class="px-8 py-2.5 text-sm font-black text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 uppercase tracking-widest">SIMPAN
                        GUDANG</button>
                </div>
            </form>
        </div>
    </div>
</div>