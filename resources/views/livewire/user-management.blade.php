<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if (session('success'))
            <div id="alert-success" class="flex items-center p-4 mb-6 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                <div class="ms-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            
            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="sm:flex sm:items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                            Daftar Pengguna Tim: {{ auth()->user()->currentTeam->name }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Kelola anggota tim Anda. Hanya Administrator yang dapat menambahkan anggota baru.
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <x-button class="ms-4 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900" wire:click="$set('showCreateModal', true)">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Tambah Staf Baru') }}
                        </x-button>
                    </div>
                </div>

                <div class="mt-8 relative overflow-x-auto shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">Nama</th>
                                <th scope="col" class="px-6 py-4">Email</th>
                                <th scope="col" class="px-6 py-4">Peran (Role)</th>
                                <th scope="col" class="px-6 py-4">Bergabung</th>
                                <th scope="col" class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white flex items-center gap-3">
                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 ml-2">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $userRoleName = 'Owner';
                                            if(auth()->user()->currentTeam->owner->id !== $user->id) {
                                                $roleKey = $user->membership->role ?? 'none';
                                                $userRoleName = $roles[$roleKey] ?? $roleKey;
                                            }
                                        @endphp
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            {{ $userRoleName }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->created_at->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->id !== $ownerId)
                                            <div class="flex gap-2">
                                                <button wire:click="openEditModal({{ $user->id }})"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-lg transition-all border border-blue-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button wire:click="openGudangModal({{ $user->id }})"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white rounded-lg transition-all border border-emerald-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                    </svg>
                                                    Akses Gudang
                                                </button>
                                                <button wire:click="openKasModal({{ $user->id }})"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-cyan-600 bg-cyan-50 hover:bg-cyan-600 hover:text-white rounded-lg transition-all border border-cyan-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    </svg>
                                                    Akses Kas
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Owner</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada pengguna lain di tim ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <x-dialog-modal wire:model.live="showCreateModal" maxWidth="lg">
        <x-slot name="title">
            <h3 class="text-xl font-bold border-b pb-4 dark:text-gray-200 dark:border-gray-700">Penambahan Anggota Tim Baru</h3>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Masukkan detail untuk membuat akun pengguna baru. Pengguna otomatis akan tergabung dalam tim <b>{{ auth()->user()->currentTeam->name }}</b> dengan peran yang Anda pilih.
            </p>

            <form wire:submit="createUser" class="space-y-4">
                
                <div>
                    <x-label for="name" value="{{ __('Nama Lengkap') }}" />
                    <x-input id="name" wire:model="name" class="block mt-1 w-full" type="text" placeholder="Misal: Budi Santoso" required autofocus autocomplete="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-label for="email" value="{{ __('Alamat Email') }}" />
                    <x-input id="email" wire:model="email" class="block mt-1 w-full" type="email" placeholder="Misal: budisesang@toko.com" required autocomplete="username" />
                    <x-input-error for="email" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="password" value="{{ __('Password Awal') }}" />
                        <x-input id="password" wire:model="password" class="block mt-1 w-full" type="password" required autocomplete="new-password" />
                        <x-input-error for="password" class="mt-2" />
                    </div>

                    <div>
                        <x-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" />
                        <x-input id="password_confirmation" wire:model="password_confirmation" class="block mt-1 w-full" type="password" required autocomplete="new-password" />
                    </div>
                </div>

                <div class="pt-2">
                    <x-label for="role" value="{{ __('Peran / Akses') }}" />
                    <select id="role" wire:model="role" class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="role" class="mt-2" />
                    
                    <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 p-3 rounded border border-gray-200 dark:border-gray-700">
                        <ul class="list-disc pl-4 space-y-1">
                            <li><b>Administrator:</b> Akses penuh, dapat menghapus dan merombak data sistem.</li>
                            <li><b>Editor:</b> Dapat melakukan input data produk dan transaksi harian.</li>
                            <li><b>Member:</b> Hanya dapat membaca laporan dan melihat inventaris (Read Only).</li>
                        </ul>
                    </div>
                </div>

                <button type="submit" class="hidden" x-ref="submitForm"></button>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showCreateModal', false)" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900" 
                      x-on:click="$refs.submitForm.click()" wire:loading.attr="disabled" wire:target="createUser">
                <svg wire:loading wire:target="createUser" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('Simpan & Buat Akun') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Edit User Modal -->
    <x-dialog-modal wire:model.live="showEditModal" maxWidth="lg">
        <x-slot name="title">
            <h3 class="text-xl font-bold border-b pb-4 dark:text-gray-200 dark:border-gray-700">Edit Data Pengguna</h3>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Perbarui nama, email, peran, atau kata sandi pengguna ini. Kosongkan kolom password jika tidak ingin diubah.
            </p>

            <form wire:submit="updateUser" class="space-y-4">
                <div>
                    <x-label for="editName" value="{{ __('Nama Lengkap') }}" />
                    <x-input id="editName" wire:model="editName" class="block mt-1 w-full" type="text" required />
                    <x-input-error for="editName" class="mt-2" />
                </div>

                <div>
                    <x-label for="editEmail" value="{{ __('Alamat Email') }}" />
                    <x-input id="editEmail" wire:model="editEmail" class="block mt-1 w-full" type="email" required />
                    <x-input-error for="editEmail" class="mt-2" />
                </div>

                <div>
                    <x-label for="editRole" value="{{ __('Peran / Akses') }}" />
                    <select id="editRole" wire:model="editRole" class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="editRole" class="mt-2" />
                </div>

                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-400 mb-3">Kosongkan jika tidak ingin mengubah kata sandi.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-label for="editPassword" value="{{ __('Password Baru') }}" />
                            <x-input id="editPassword" wire:model="editPassword" class="block mt-1 w-full" type="password" autocomplete="new-password" />
                            <x-input-error for="editPassword" class="mt-2" />
                        </div>
                        <div>
                            <x-label for="editPasswordConfirmation" value="{{ __('Konfirmasi Password') }}" />
                            <x-input id="editPasswordConfirmation" wire:model="editPasswordConfirmation" class="block mt-1 w-full" type="password" autocomplete="new-password" />
                        </div>
                    </div>
                </div>

                <button type="submit" class="hidden" x-ref="submitEditForm"></button>
            </form>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showEditModal', false)" wire:loading.attr="disabled">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-button class="ms-3 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900"
                      x-on:click="$refs.submitEditForm.click()" wire:loading.attr="disabled" wire:target="updateUser">
                <svg wire:loading wire:target="updateUser" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ __('Simpan Perubahan') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Gudang Assignment Modal -->
    <x-dialog-modal wire:model.live="showGudangModal" maxWidth="md">
        <x-slot name="title">
            <h3 class="text-xl font-bold border-b pb-4 dark:text-gray-200 dark:border-gray-700">
                🏠 Akses Gudang — {{ $gudangUserName }}
            </h3>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                Centang gudang yang boleh diakses oleh staf ini untuk menerima barang. Perubahan langsung tersimpan secara real-time.
            </p>

            @if($gudangs->isEmpty())
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <p class="text-sm font-medium">Belum ada gudang terdaftar.</p>
                    <p class="text-xs">Tambahkan gudang terlebih dahulu di menu Master Gudang.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($gudangs as $gudang)
                        <label wire:click="toggleGudangAccess({{ $gudang->id }})" class="flex items-center gap-4 p-3 rounded-xl border cursor-pointer transition-all
                            {{ in_array($gudang->id, $assignedGudangIds) ? 'bg-emerald-50 border-emerald-300 dark:bg-emerald-900/20' : 'bg-white border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700' }}">
                            <div class="flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                {{ in_array($gudang->id, $assignedGudangIds) ? 'bg-emerald-500 border-emerald-500' : 'border-gray-300' }}">
                                @if(in_array($gudang->id, $assignedGudangIds))
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm {{ in_array($gudang->id, $assignedGudangIds) ? 'text-emerald-700 dark:text-emerald-400' : 'text-gray-800 dark:text-gray-200' }}">
                                    {{ $gudang->nama }}
                                </p>
                                @if($gudang->lokasi)
                                    <p class="text-xs text-gray-400 truncate">📍 {{ $gudang->lokasi }}</p>
                                @endif
                            </div>
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full
                                {{ in_array($gudang->id, $assignedGudangIds) ? 'bg-emerald-200 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ in_array($gudang->id, $assignedGudangIds) ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showGudangModal', false)">
                {{ __('Selesai') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Kas Assignment Modal -->
    <x-dialog-modal wire:model.live="showKasModal" maxWidth="md">
        <x-slot name="title">
            <h3 class="text-xl font-bold border-b pb-4 dark:text-gray-200 dark:border-gray-700">
                💰 Akses Kas — {{ $kasUserName }}
            </h3>
        </x-slot>

        <x-slot name="content">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                Centang akun kas yang tanggung jawabnya dipegang (ditugaskan) ke staf ini. Perhatikan: 1 Kas hanya bisa dimiliki/dipegang oleh 1 PIC. Mengekstrak akses akan memindahkan kepemilikan.
            </p>

            @if(!isset($allKas) || $allKas->isEmpty())
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">Belum ada Akun Kas terdaftar.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($allKas as $kas)
                        @php
                            $isAssignedHere = in_array($kas->id, $assignedKasIds);
                            $isAssignedElsewhere = !$isAssignedHere && $kas->user_id !== null;
                        @endphp
                        <label wire:click="toggleKasAccess({{ $kas->id }})" class="flex items-center gap-4 p-3 rounded-xl border cursor-pointer transition-all
                            {{ $isAssignedHere ? 'bg-cyan-50 border-cyan-300 dark:bg-cyan-900/20' : 'bg-white border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700' }}">
                            <div class="flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                {{ $isAssignedHere ? 'bg-cyan-500 border-cyan-500' : 'border-gray-300' }}">
                                @if($isAssignedHere)
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm {{ $isAssignedHere ? 'text-cyan-700 dark:text-cyan-400' : 'text-gray-800 dark:text-gray-200' }}">
                                    {{ $kas->nama }} <span class="text-[10px] font-black uppercase text-gray-500 tracking-wider">({{ $kas->kode }})</span>
                                </p>
                                @if($isAssignedElsewhere)
                                    <p class="text-xs text-rose-500 truncate mt-0.5">⚠️ Dipegang: {{ optional($kas->user)->name }}</p>
                                @elseif(!$isAssignedHere)
                                    <p class="text-xs text-gray-400 truncate mt-0.5">Tidak ada PIC</p>
                                @else
                                    <p class="text-xs text-cyan-600 truncate mt-0.5">PIC saat ini</p>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-xl
                                {{ $isAssignedHere ? 'bg-cyan-200 text-cyan-800' : 'bg-gray-100 text-gray-400' }}">
                                {{ $isAssignedHere ? 'TERHUBUNG' : 'TIDAK TERHUBUNG' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showKasModal', false)">
                {{ __('Selesai') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
</div>
