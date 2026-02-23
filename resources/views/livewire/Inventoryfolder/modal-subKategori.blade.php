<!-- Modal toggle -->
<!-- <button data-modal-target="modal-subkategori" data-modal-toggle="modal-subkategori"
    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
    type="button">
    Tambah Sub Kategori
</button> -->

<!-- Main modal -->
<div id="modal-subkategori" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Sub Kategori Barang
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="modal-subkategori">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <div class="grid grid-cols-1">
                    <label for="kategori_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pilih
                        Kategori Induk</label>
                    <div class="flex gap-2">
                        <select wire:model="kategori_id" id="kategori_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                            @endforeach
                        </select>
                        <div class="flex-shrink-0">
                            <button type="button" data-modal-target="modal-kategori" data-modal-toggle="modal-kategori"
                                class="flex items-center justify-center w-10 h-10 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all active:scale-95 shadow-md">
                                +
                            </button>
                        </div>
                    </div>
                    @error('kategori_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="namaSubKategori"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Sub
                        Kategori</label>
                    <input type="text" wire:model="namaSubKategori" id="namaSubKategori"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Contoh: Laptop" required>
                    @error('namaSubKategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="kodeSubKategori"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Sub
                        Kategori</label>
                    <input type="text" wire:model="kodeSubKategori" id="kodeSubKategori"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Contoh: LPT" required>
                    @error('kodeSubKategori') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="deskripsiSubKategori"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                    <textarea wire:model="deskripsiSubKategori" id="deskripsiSubKategori" rows="4"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Tulis deskripsi sub kategori di sini..."></textarea>
                    @error('deskripsiSubKategori') <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" wire:click="storeSubKategori"
                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Simpan Sub Kategori
                </button>
                <button data-modal-hide="modal-subkategori" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
            </div>
        </div>
    </div>
</div>