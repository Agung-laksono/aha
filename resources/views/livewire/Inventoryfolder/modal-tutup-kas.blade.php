<div x-show="$wire.showModalTutupKas" class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">

    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="$wire.showModalTutupKas = false"></div>

    <div
        class="relative bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <!-- Header -->
        <div class="p-8 pb-4 flex justify-between items-start">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Tutup Kas
                        Harian</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Verifikasi Saldo
                        Fisik di Tangan</p>
                </div>
            </div>
            <button @click="$wire.showModalTutupKas = false"
                class="text-gray-400 hover:text-gray-600 transition-colors bg-gray-50 dark:bg-gray-700 p-2 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form wire:submit.prevent="tutupKas" class="p-8 pt-4 space-y-6">
            <!-- Saldo Aplikasi Info -->
            <div
                class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Saldo di Aplikasi</p>
                    <p class="text-lg font-black text-gray-900 dark:text-white">
                        @php
                            $akun = \App\Models\AkunKas::find($selectedAkunTutupId);
                        @endphp
                        Rp{{ number_format((float) ($akun->saldo_saat_ini ?? 0), 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Input Saldo Fisik -->
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Saldo Fisik
                        Sebenarnya</label>
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">Rp</span>
                        <input type="text" x-data="{ mask: '99.999.999.999' }"
                            x-on:input="$wire.saldoFisik = $event.target.value.replace(/\D/g, '')"
                            class="bg-white border-2 border-gray-100 text-gray-900 text-xl font-black rounded-2xl focus:ring-amber-500 focus:border-amber-500 block w-full pl-12 p-4 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm"
                            placeholder="0">
                    </div>
                </div>

                <!-- Selisih Preview -->
                <div class="flex items-center gap-2 ml-1"
                    x-data="{ aplikasi: {{ (float) ($akun->saldo_saat_ini ?? 0) }} }">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Estimasi Selisih:</span>
                    <span class="text-[9px] font-black uppercase tracking-widest"
                        :class="$wire.saldoFisik - aplikasi > 0 ? 'text-emerald-500' : ($wire.saldoFisik - aplikasi < 0 ? 'text-rose-500' : 'text-gray-400')">
                        <template x-if="$wire.saldoFisik - aplikasi > 0">+</template>
                        Rp <span x-text="Math.abs($wire.saldoFisik - aplikasi).toLocaleString('id-ID')"></span>
                    </span>
                </div>
            </div>

            <!-- Upload Bukti Fisik -->
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest">Foto Bukti Fisik
                        (Uang/Catatan)</label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="auto-compress-tutup-kas" class="sr-only peer" checked>
                        <div
                            class="relative w-7 h-3.5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-2.5 after:w-2.5 after:transition-all peer-checked:bg-amber-600">
                        </div>
                        <span class="ms-1.5 text-[8px] font-black text-gray-400 uppercase">Auto</span>
                    </label>
                </div>

                <div x-data="{ isCompressing: false }" x-on:compression-start.window="isCompressing = true"
                    x-on:compression-end.window="isCompressing = false" class="relative group">
                    <label
                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all overflow-hidden relative">
                        @if ($fotoBuktiFisik)
                            <div class="relative h-full w-full">
                                <img src="{{ is_string($fotoBuktiFisik) ? $fotoBuktiFisik : $fotoBuktiFisik->temporaryUrl() }}"
                                    class="absolute inset-0 w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-amber-600/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                                    <button type="button"
                                        x-on:click.prevent="openCropModal(0, $el.closest('.relative').querySelector('img').src, 'fotoBuktiFisik', $wire)"
                                        class="p-2 bg-white text-amber-600 rounded-xl shadow-lg hover:scale-110 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button type="button" wire:click="$set('fotoBuktiFisik', null)"
                                        class="p-2 bg-white text-rose-600 rounded-xl shadow-lg hover:scale-110 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div
                                class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-400 group-hover:text-amber-500 transition">
                                <svg class="w-8 h-8 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="text-[9px] font-black uppercase tracking-widest">Klik untuk Foto Bukti</p>
                            </div>
                        @endif
                        <input type="file" class="hidden" accept="image/*"
                            @change="if(document.getElementById('auto-compress-tutup-kas').checked) { handleAutoCompress($event.target, 'fotoBuktiFisik', $wire) } else { @this.upload('fotoBuktiFisik', $event.target.files[0]) }" />
                    </label>

                    <div x-show="isCompressing"
                        class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex flex-col items-center justify-center rounded-3xl z-10">
                        <div
                            class="w-8 h-8 rounded-full border-4 border-amber-100 border-t-amber-600 animate-spin mb-2">
                        </div>
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Processing...</p>
                    </div>
                </div>
                @error('fotoBuktiFisik') <span
                class="text-[10px] font-bold text-rose-500 block ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Keterangan -->
            <div class="space-y-2">
                <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Keterangan / Alasan
                    Selisih</label>
                <textarea wire:model="keteranganTutup" rows="2"
                    class="bg-white border-2 border-gray-100 text-gray-900 text-sm font-medium rounded-2xl focus:ring-amber-500 focus:border-amber-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm"
                    placeholder="Wajib diisi jika ada selisih..."></textarea>
            </div>

            <button type="submit"
                class="w-full py-5 text-xs font-black text-white bg-amber-600 hover:bg-amber-700 rounded-3xl shadow-xl shadow-amber-500/30 transition-all active:scale-95 uppercase tracking-[0.2em]">
                SIMPAN & TUTUP KAS
            </button>
        </form>
    </div>
</div>