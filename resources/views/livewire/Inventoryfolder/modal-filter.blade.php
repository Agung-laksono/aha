<!-- Filter Modal -->
<div id="filterModal" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    Filter Pencarian
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="filterModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-5 space-y-4">
                <!-- Dropdown Kategori -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Kategori</label>
                    <select wire:model.live="filterKategori"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Sub Kategori (Mengacu ke Kategori) -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Sub Kategori</label>
                    <select wire:model.live="filterSubKategori"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:opacity-50"
                        {{ !$filterKategori ? 'disabled' : '' }}>
                        <option value="">Semua Sub Kategori</option>
                        @foreach($subKategorisFilter as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="pt-4 flex gap-3">
                    <button type="button" wire:click="$set('filterKategori', ''); $set('filterSubKategori', '');"
                        class="w-full text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-700 dark:text-gray-300">
                        Reset Filter
                    </button>
                    <button type="button" data-modal-hide="filterModal"
                        class="w-full text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>