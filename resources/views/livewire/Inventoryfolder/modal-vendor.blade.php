<div x-data="{ show: @entangle('showModalVendor') }" x-show="show" x-on:close-modal-vendor.window="show = false"
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
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full dark:bg-gray-800 border-t-4 border-primary-600">

            <div
                class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Tambah Vendor
                    Baru</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="storeVendor">
                <div class="px-6 py-6 overflow-y-auto max-h-[75vh] space-y-6 scrollbar-thin scrollbar-thumb-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Identity & Contact -->
                        <div class="space-y-5">
                            <div
                                class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <label
                                    class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest">Informasi
                                    Utama</label>
                                <div class="space-y-4">
                                    <div>
                                        <input type="text" wire:model="namaVendor" required
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                                            placeholder="Nama Perusahaan / Toko *">
                                        @error('namaVendor') <span
                                            class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </span>
                                        <input type="text" wire:model="kontakVendor"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                                            placeholder="No. HP / WhatsApp (Kontak)">
                                    </div>

                                    <div class="relative">
                                        <span
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </span>
                                        <input type="text" wire:model="tagVendor"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                                            placeholder="Tag (Contoh: Prioritas, Grosir)">
                                    </div>
                                </div>
                            </div>

                            <div
                                class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <label
                                    class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest">Logo
                                    / Foto Vendor</label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-white dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:hover:border-gray-500 transition-all">
                                        @if($gambarVendor)
                                            <img src="{{ $gambarVendor->temporaryUrl() }}"
                                                class="h-full w-full object-contain p-2 rounded-xl">
                                        @else
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-full mb-3">
                                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <p
                                                    class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-widest">
                                                    Upload Logo</p>
                                            </div>
                                        @endif
                                        <input type="file" wire:model="gambarVendor" class="hidden" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Regional & Address -->
                        <div class="space-y-5">
                            <div
                                class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <label
                                    class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest">Wilayah
                                    (Internal System)</label>
                                <div class="space-y-3">
                                    <!-- Provinsi -->
                                    <div class="relative">
                                        <select wire:model.live="province_id"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm appearance-none">
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces as $p)
                                                <option value="{{ $p['code'] }}">{{ $p['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('province_id') <span
                                        class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span>
                                    @enderror

                                    <!-- Kota/Kab -->
                                    <div class="relative">
                                        <select wire:model.live="regency_id" {{ empty($regencies) ? 'disabled' : '' }}
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm disabled:opacity-50 disabled:cursor-not-allowed appearance-none">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            @foreach($regencies as $r)
                                                <option value="{{ $r['code'] }}">{{ $r['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div wire:loading wire:target="province_id"
                                            class="absolute inset-y-0 right-8 flex items-center pr-3">
                                            <svg class="animate-spin h-4 w-4 text-primary-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('regency_id') <span
                                        class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span>
                                    @enderror

                                    <!-- Kecamatan -->
                                    <div class="relative">
                                        <select wire:model.live="district_id" {{ empty($districts) ? 'disabled' : '' }}
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm disabled:opacity-50 disabled:cursor-not-allowed appearance-none">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach($districts as $d)
                                                <option value="{{ $d['code'] }}">{{ $d['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div wire:loading wire:target="regency_id"
                                            class="absolute inset-y-0 right-8 flex items-center pr-3">
                                            <svg class="animate-spin h-4 w-4 text-primary-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('district_id') <span
                                        class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span>
                                    @enderror

                                    <!-- Kelurahan -->
                                    <div class="relative">
                                        <select wire:model="village_id" {{ empty($villages) ? 'disabled' : '' }}
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm disabled:opacity-50 disabled:cursor-not-allowed appearance-none">
                                            <option value="">Pilih Kelurahan</option>
                                            @foreach($villages as $v)
                                                <option value="{{ $v['code'] }}">{{ $v['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div wire:loading wire:target="district_id"
                                            class="absolute inset-y-0 right-8 flex items-center pr-3">
                                            <svg class="animate-spin h-4 w-4 text-primary-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('village_id') <span
                                        class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <label
                                    class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest">Detail
                                    Alamat / Deskripsi</label>
                                <textarea wire:model="alamatVendor" rows="4"
                                    class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                                    placeholder="Jl. Merdeka No. 123, Lantai 2..."></textarea>
                                @error('alamatVendor') <span
                                class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" @click="show = false"
                        class="px-5 py-2.5 text-xs font-black text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm uppercase tracking-widest">BATAL</button>
                    <button type="submit"
                        class="px-8 py-2.5 text-xs font-black text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 uppercase tracking-widest transition-all active:scale-95">SIMPAN
                        VENDOR</button>
                </div>
            </form>
        </div>
    </div>
</div>