<div id="modal-akun-kas" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="bg-black bg-opacity-70 hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[160] justify-center items-center w-full md:inset-0 h-full max-h-full">

    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div
            class="relative bg-white rounded-2xl shadow-2xl dark:bg-gray-800 border-t-4 border-emerald-600 flex flex-col overflow-hidden">

            <div
                class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-widest">Tambah Akun Kas
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-modal-hide="modal-akun-kas">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="storeAkunKas">
                <div class="p-6 space-y-4">
                    <div>
                        <label
                            class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Nama
                            Akun (e.g. Kas Utama)</label>
                        <input type="text" wire:model="namaAkunKas" required
                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                            placeholder="Contoh: Kas Kecil">
                        @error('namaAkunKas') <span
                        class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label
                            class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Kode
                            Unik</label>
                        <input type="text" wire:model="kodeAkunKas" required
                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                            placeholder="Contoh: KAS-01">
                        @error('kodeAkunKas') <span
                        class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label
                            class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Saldo
                            Awal</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 font-bold text-sm">Rp</span>
                            <input type="number" wire:model="saldoAwal" required
                                class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm"
                                placeholder="0">
                        </div>
                        @error('saldoAwal') <span
                        class="text-red-500 text-[10px] mt-1 font-bold uppercase">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label
                            class="block mb-2 text-[10px] font-black uppercase text-gray-400 tracking-widest ml-1">Penanggung
                            Jawab (Responsible)</label>
                        <select wire:model="pjUserKasId"
                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 dark:bg-gray-700 dark:border-gray-600 dark:text-white shadow-sm appearance-none cursor-pointer">
                            <option value="">-- Pilih User (Default Anda) --</option>
                            @foreach($this->teamUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-100 dark:bg-gray-700 flex justify-end space-x-3 rounded-b-2xl">
                    <button type="button" data-modal-hide="modal-akun-kas"
                        class="px-5 py-2.5 text-xs font-black text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition shadow-sm uppercase tracking-widest">BATAL</button>
                    <button type="submit"
                        class="px-8 py-2.5 text-xs font-black text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 uppercase tracking-widest transition-all active:scale-95">SIMPAN
                        AKUN</button>
                </div>
            </form>
        </div>
    </div>
</div>