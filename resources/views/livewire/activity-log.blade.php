<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Timeline Aktivitas</h3>
        <div class="relative">
            <input type="text" wire:model.live="search"
                class="pl-10 pr-4 py-2 border border-gray-200 dark:border-gray-700 rounded-xl dark:bg-gray-900 dark:text-white text-sm focus:ring-primary-500"
                placeholder="Cari aktivitas...">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-4 font-black">Waktu</th>
                    <th class="px-6 py-4 font-black">User</th>
                    <th class="px-6 py-4 font-black text-center">Aksi</th>
                    <th class="px-6 py-4 font-black">Deskripsi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($logs as $log)
                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-bold text-gray-900 dark:text-white">
                                {{ $log->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-gray-400 font-mono">{{ $log->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-xs uppercase">
                                    {{ substr($log->user->name ?? '?', 0, 1) }}
                                </div>
                                <span
                                    class="font-bold text-gray-700 dark:text-gray-300">{{ $log->user->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $color = match ($log->action) {
                                    'CREATE', 'STORE', 'RECEIVE_PO' => 'bg-green-100 text-green-700 border-green-200',
                                    'UPDATE', 'EDIT' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'DELETE' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'RETURN' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $color }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 min-w-[300px]">
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $log->description }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada aktivitas
                                    yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>
</div>