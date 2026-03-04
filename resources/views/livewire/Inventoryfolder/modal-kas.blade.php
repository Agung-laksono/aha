<div id="modal-kas" tabindex="-1" aria-hidden="true" x-data="{ show: @entangle('showModalKas') }" x-show="show"
    class="bg-black/70 fixed inset-0 z-[150] flex justify-center items-center w-full h-full p-4 overflow-y-auto"
    style="display: none;">

    <div class="relative w-full max-w-5xl max-h-full" @click.away="show = false">
        <!-- Modal content -->
        <div
            class="relative bg-white rounded-3xl shadow-2xl dark:bg-gray-900 border-t-8 border-emerald-500 overflow-hidden flex flex-col">

            <!-- Header -->
            @php
                $akun = \App\Models\AkunKas::find($selectedAkunKasId);
            @endphp
            <div
                class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <div class="flex items-center gap-5">
                    <div class="p-3 bg-emerald-500 text-white rounded-2xl shadow-lg shadow-emerald-500/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3-3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">
                            {{ $akun->nama ?? 'Detail Akun Kas' }}
                        </h3>
                        <div class="flex items-center gap-3">
                            <span
                                class="text-[10px] font-black text-gray-400 border border-gray-200 dark:border-gray-700 px-2 py-0.5 rounded-full uppercase tracking-widest text-nowrap">Saldo
                                Saat Ini</span>
                            <p class="text-2xl font-black text-emerald-600 italic tracking-tight">
                                <span
                                    class="text-xs font-bold mr-0.5">Rp</span>{{ number_format($akun->saldo_saat_ini ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <button type="button" @click="show = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-2 bg-gray-100 dark:bg-gray-800 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 overflow-hidden">
                <!-- Left: Form Mutasi -->
                <div
                    class="lg:col-span-5 p-8 bg-gray-50/30 dark:bg-gray-900 border-r dark:border-gray-800 h-full overflow-y-auto max-h-[70vh]">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Input Mutasi Kas
                    </h4>

                    <form wire:submit.prevent="storeMutasiKas" class="space-y-6">
                        <!-- Tipe Mutasi Toggle -->
                        <div class="p-1 bg-gray-100 dark:bg-gray-800 rounded-2xl flex gap-1">
                            <button type="button" wire:click="$set('tipeKas', 'Masuk')"
                                class="flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $tipeKas === 'Masuk' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'text-gray-500 hover:text-emerald-500' }}">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    MASUK (Top-Up)
                                </span>
                            </button>
                            <button type="button" wire:click="$set('tipeKas', 'Keluar')"
                                class="flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $tipeKas === 'Keluar' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-gray-500 hover:text-rose-500' }}">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M20 12H4" />
                                    </svg>
                                    KELUAR (Tarik)
                                </span>
                            </button>
                        </div>

                        <!-- Nominal -->
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Nominal
                                Saldo</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 font-bold text-sm">Rp</span>
                                <input type="number" wire:model="jumlahKas" required
                                    class="bg-white border-2 border-gray-100 text-gray-900 text-xl font-black rounded-2xl focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 p-4 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm"
                                    placeholder="0">
                            </div>
                        </div>

                        <!-- Kategori & Tanggal -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label
                                    class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Kategori</label>
                                <select wire:model="kategoriKas" required
                                    class="bg-white border-2 border-gray-100 text-gray-900 text-xs font-bold rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm">
                                    <option value="">Pilih Kategori</option>
                                    @if($tipeKas === 'Masuk')
                                        <option value="Setoran Modal">Setoran Modal</option>
                                        <option value="Pendapatan Lain">Pendapatan Lain</option>
                                        <option value="Koreksi Saldo Positif">Koreksi Saldo (+)</option>
                                    @else
                                        <option value="Penarikan Tunai">Penarikan Tunai</option>
                                        <option value="Biaya Admin">Biaya Admin</option>
                                        <option value="Pengeluaran Operasional">Pengeluaran Operasional</option>
                                        <option value="Koreksi Saldo Negatif">Koreksi Saldo (-)</option>
                                    @endif
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Tanggal</label>
                                <input type="date" wire:model="tanggalKas" required
                                    @if(!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin'))
                                    max="{{ date('Y-m-d') }}" @endif
                                    class="bg-white border-2 border-gray-100 text-gray-900 text-xs font-bold rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm">
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Keterangan
                                / Memo</label>
                            <textarea wire:model="keteranganKas" rows="2"
                                class="bg-white border-2 border-gray-100 text-gray-900 text-sm font-medium rounded-xl focus:ring-primary-500 focus:border-primary-500 block w-full p-3 dark:bg-gray-800 dark:border-gray-700 dark:text-white shadow-sm"
                                placeholder="Opsional..."></textarea>
                        </div>

                        <!-- Upload Bukti -->
                        <div class="space-y-2" x-data="{ isCompressing: false }">
                            <label class="text-[9px] font-black uppercase text-gray-400 tracking-widest ml-1">Foto Bukti
                                (Gunakan Kompres Otomatis)</label>
                            <div class="relative group">
                                <label
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all overflow-hidden relative">
                                    @if ($fotoMutasi)
                                        <img src="{{ $fotoMutasi->temporaryUrl() }}"
                                            class="absolute inset-0 w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <p class="text-[10px] font-black text-white uppercase tracking-widest">Ganti
                                                Foto</p>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Klik
                                                untuk Upload</p>
                                        </div>
                                    @endif
                                    <input type="file" class="hidden" accept="image/*"
                                        @change="isCompressing = true; handleAutoCompress($event.target, 'fotoMutasi', $wire).finally(() => isCompressing = false)" />
                                </label>

                                <div x-show="isCompressing"
                                    class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm flex flex-col items-center justify-center rounded-2xl z-10"
                                    style="display: none;">
                                    <div
                                        class="w-8 h-8 rounded-full border-4 border-emerald-100 border-t-emerald-600 animate-spin mb-2">
                                    </div>
                                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                                        Processing...</p>
                                </div>
                            </div>
                            @error('fotoMutasi') <span
                            class="text-xs font-bold text-rose-500 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit"
                            class="w-full py-4 text-xs font-black text-white rounded-2xl shadow-xl transition-all active:scale-95 uppercase tracking-[0.2em]
                            {{ $tipeKas === 'Masuk' ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/30' : 'bg-rose-600 hover:bg-rose-700 shadow-rose-500/30' }}">
                            SIMPAN MUTASI
                        </button>
                    </form>
                </div>

                <!-- Right: Riwayat Mutasi -->
                <div class="lg:col-span-7 p-8 h-full flex flex-col max-h-[70vh]">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">20 Riwayat Terakhir
                    </h4>

                    <div
                        class="overflow-y-auto flex-grow scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-700 pr-2">
                        <div class="space-y-3">
                            @forelse($this->mutasiKasList as $mutasi)
                                <div
                                    class="p-4 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between group hover:border-blue-200 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="p-2 rounded-xl {{ $mutasi->tipe === 'Masuk' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                            @if($mutasi->tipe === 'Masuk')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                                                {{ $mutasi->tanggal->format('d M Y') }}
                                            </p>
                                            <h5 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase">
                                                {{ $mutasi->kategori }}
                                            </h5>
                                            @if($mutasi->keterangan)
                                                <p class="text-[10px] text-gray-400 mt-0.5 italic">{{ $mutasi->keterangan }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-sm font-black {{ $mutasi->tipe === 'Masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $mutasi->tipe === 'Masuk' ? '+' : '-' }}
                                            Rp{{ number_format($mutasi->jumlah, 0, ',', '.') }}
                                        </p>
                                        <div class="flex items-center gap-1.5 justify-end">
                                            @if($mutasi->user)
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($mutasi->user->name) }}&background=6366f1&color=fff"
                                                    class="w-3.5 h-3.5 rounded-full">
                                            @endif
                                            <span
                                                class="text-[8px] font-bold bg-gray-50 dark:bg-gray-700 px-1.5 py-0.5 rounded border border-gray-100 dark:border-gray-600 text-gray-400 uppercase tracking-tighter">
                                                {{ $mutasi->user->name ?? 'System' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 text-center flex flex-col items-center justify-center">
                                    <div class="p-5 bg-gray-50 dark:bg-gray-800 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Belum ada mutasi
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-4 bg-gray-100 dark:bg-gray-800 flex justify-end">
                <button type="button" @click="show = false"
                    class="px-8 py-3 text-xs font-black text-gray-500 bg-white border-2 border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-sm uppercase tracking-widest active:scale-95">Tutup
                    Dialog</button>
            </div>
        </div>
    </div>
</div>