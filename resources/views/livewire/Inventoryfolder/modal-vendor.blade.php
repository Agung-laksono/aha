<div id="modal-vendor" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[130]    justify-center items-center w-full md:inset-0 h-full max-h-full">

    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div
            class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border-t-4 border-primary-600 flex flex-col overflow-hidden">

            <div
                class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Tambah Vendor
                    Baru</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-modal-hide="modal-vendor">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="storeVendor">
                <div class="px-6 py-6 overflow-y-auto max-h-[75vh] space-y-6 scrollbar-thin scrollbar-thumb-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-5" x-data="{ isCompressing: false }"
                            @compression-start.window="isCompressing = true"
                            @compression-end.window="isCompressing = false">
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
                                <div class="flex items-center justify-between mb-2 px-1">
                                    <label
                                        class="block text-[10px] font-black uppercase text-gray-400 tracking-widest">Logo
                                        / Foto Vendor</label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="auto-compress-vendor" class="sr-only peer" checked>
                                        <div
                                            class="relative w-8 h-4 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                        <span
                                            class="ms-2 text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Auto</span>
                                    </label>
                                </div>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-200 border-dashed rounded-2xl cursor-pointer bg-white dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:hover:border-gray-500 transition-all">
                                        @if($gambarVendor ?? null)
                                            <div class="relative group h-full w-full p-2">
                                                <img src="{{ is_string($gambarVendor) ? $gambarVendor : $gambarVendor->temporaryUrl() }}"
                                                    class="h-full w-full object-contain rounded-xl">
                                                <div
                                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-2xl gap-2">
                                                    <button type="button"
                                                        x-on:click="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'gambarVendor', $wire)"
                                                        class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors shadow-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>
                                                    <button type="button" wire:click="$set('gambarVendor', null)"
                                                        class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shadow-lg">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
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
                                        <input type="file" class="hidden" accept="image/*" x-on:change="if(document.getElementById('auto-compress-vendor').checked) {
                                                handleAutoCompress($event.target, 'gambarVendor', $wire)
                                            } else {
                                                @this.upload('gambarVendor', $event.target.files[0])
                                            }" />
                                    </label>
                                </div>
                                <div x-show="isCompressing" style="display: none;"
                                    class="mt-3 text-indigo-500 text-xs text-center animate-pulse font-medium">
                                    <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-1"></span>
                                    Mengkompresi gambar...
                                </div>
                                <div wire:loading wire:target="gambarVendor"
                                    class="mt-3 text-blue-500 text-xs text-center animate-pulse font-medium">
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-1"></span> Mengunggah
                                    logo ke server...
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
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
                                    </div>
                                    @error('province_id') <span
                                        class="text-red-500 text-[10px] font-bold uppercase">{{ $message }}</span>
                                    @enderror

                                    <!-- Kota/Kab -->
                                    <div class="relative">
                                        <select wire:model.live="regency_id" {{ empty($regencies) ? 'disabled' : '' }}
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm appearance-none">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            @foreach($regencies as $r)
                                                <option value="{{ $r['code'] }}">{{ $r['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kecamatan -->
                                    <div class="relative">
                                        <select wire:model.live="district_id" {{ empty($districts) ? 'disabled' : '' }}
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm appearance-none {{ empty($districts) ? 'opacity-50' : '' }}">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach($districts as $d)
                                                <option value="{{ $d['code'] }}">{{ $d['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Kelurahan/Desa -->
                                    <div class="relative">
                                        <select wire:model.live="village_id" {{ empty($villages) ? 'disabled' : '' }}
                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm appearance-none {{ empty($villages) ? 'opacity-50' : '' }}">
                                            <option value="">Pilih Kelurahan/Desa</option>
                                            @foreach($villages as $v)
                                                <option value="{{ $v['code'] }}">{{ $v['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <label
                                    class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest">Detail
                                    Alamat / Deskripsi</label>
                                <textarea wire:model="alamatVendor" rows="4"
                                    class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                                    placeholder="Jl. Merdeka No. 123..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" data-modal-hide="modal-vendor"
                        class="px-5 py-2.5 text-xs font-black text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm uppercase tracking-widest">BATAL</button>
                    <button type="submit"
                        class="px-8 py-2.5 text-xs font-black text-white bg-primary-600 rounded-xl hover:bg-primary-700 shadow-lg shadow-primary-500/30 uppercase tracking-widest transition-all active:scale-95">SIMPAN
                        VENDOR</button>
                </div>
            </form>
        </div>
    </div>
</div>