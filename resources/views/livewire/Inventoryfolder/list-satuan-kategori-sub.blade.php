<!-- Modal from another file -->
<div class="mb-4 grid gap-4 sm:grid-cols-1 md:mb-8 lg:grid-cols-2">
    <!-- Category List Table (Simple) -->
    <div class=" mb-8">
        <h3 class="text-lg font-bold mb-4 dark:text-white">Daftar Kategori Terinput</h3>
        <div class="relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x-auto ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Kode</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $kat)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $kat->nama }}
                            </th>
                            <td class="px-6 py-4">{{ $kat->kode }}</td>
                            <td class="px-6 py-4">{{ $kat->deskripsi }}</td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="3" class="px-6 py-4 text-center">Belum ada kategori yang diinput.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- SubCategory List Table (Simple) -->
    <div class="mb-8">
        <h3 class="text-lg font-bold mb-4 dark:text-white">Daftar Sub Kategori Terinput</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Kode</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subKategoris as $subkat)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $subkat->nama }}
                            </th>
                            <td class="px-6 py-4">{{ $subkat->kode }}</td>
                            <td class="px-6 py-4">{{ $subkat->deskripsi }}</td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="3" class="px-6 py-4 text-center">Belum ada kategori yang diinput.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Satuan List Table (Simple) -->
    <div class="mb-8">
        <h3 class="text-lg font-bold mb-4 dark:text-white">Daftar Satuan Terinput</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Satuan</th>
                        <th scope="col" class="px-6 py-3">Kode</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($satuans as $sat)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $sat->nama }}
                            </th>
                            <td class="px-6 py-4">{{ $sat->kode }}</td>
                            <td class="px-6 py-4">{{ $sat->deskripsi }}</td>
                        </tr>
                    @empty
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="3" class="px-6 py-4 text-center">Belum ada satuan yang diinput.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>