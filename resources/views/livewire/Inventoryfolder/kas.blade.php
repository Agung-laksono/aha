@if($this->userAkunKas->isNotEmpty())
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($this->userAkunKas as $akun)
            <div wire:click="openModalKas({{ $akun->id }})"
                class="p-4 bg-white dark:bg-gray-800 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl shadow-sm flex items-center gap-4 group hover:shadow-md transition-all cursor-pointer hover:border-emerald-500">
                <div
                    class="p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">PJ:
                        {{ $akun->user->name ?? 'System' }}</p>
                    <h4 class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase truncate max-w-[150px] mb-1">
                        {{ $akun->nama }}</h4>
                    <p class="text-base font-black text-emerald-600 italic tracking-tight leading-none">
                        <span
                            class="text-[10px] font-bold mr-0.5">Rp</span>{{ number_format($akun->saldo_saat_ini, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endif