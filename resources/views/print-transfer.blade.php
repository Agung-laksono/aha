<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan - {{ $transfer->nomor_transfer }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: white !important;
            }

            #printable-area {
                margin: 0 !important;
                border: 4px solid #111 !important;
                width: 210mm !important;
                height: 297mm !important;
                max-width: none !important;
                box-sizing: border-box !important;
                background: white !important;
            }

            .no-print {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .print-break-inside-avoid {
                page-break-inside: avoid;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background: white;
            color: #111;
        }

        .border-dashed-custom {
            border-style: dashed;
            border-width: 1px;
            border-color: #d1d5db;
        }
    </style>
</head>

<body class="p-4 md:p-8">
    <!-- Action Buttons (Hidden on Print) -->
    <div class="no-print mb-10 flex justify-center gap-4">
        <button onclick="window.print()"
            class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black shadow-2xl hover:bg-blue-700 transition-all uppercase tracking-[0.2em] text-xs flex items-center gap-3 active:scale-95">
            <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Dokumen (A4)
        </button>
        <button onclick="window.close()"
            class="bg-gray-100 text-gray-500 px-10 py-4 rounded-2xl font-black hover:bg-gray-200 transition-all uppercase tracking-[0.2em] text-xs active:scale-95">
            Tutup
        </button>
    </div>

    <!-- Main Letter Content -->
    <div id="printable-area"
        class="max-w-4xl mx-auto border-4 border-gray-900 p-6 bg-white relative overflow-hidden flex flex-col">
        <!-- Top Section -->
        <div class="flex-none">
            <!-- Logo/Header Accent -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-gray-900 clip-path-header opacity-5"></div>

            <!-- Header -->
            <div class="flex justify-between items-start border-b-2 border-gray-900 pb-4 mb-6">
                <div>
                    <h1 class="text-3xl font-black uppercase tracking-tighter text-gray-900 leading-none">Surat Jalan
                    </h1>
                    <div class="mt-2 flex flex-col gap-0.5">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">No. Transfer: <span
                                class="text-gray-900 font-mono tracking-normal ml-1 text-sm">#{{ $transfer->nomor_transfer }}</span>
                        </p>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">Tanggal: <span
                                class="text-gray-900 ml-1 text-[10px]">{{ \Carbon\Carbon::parse($transfer->tanggal)->translatedFormat('d F Y') }}</span>
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-xl font-black text-blue-600 uppercase leading-none tracking-tighter">
                        {{ Auth::user()->currentTeam->name }}
                    </h2>
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-[0.1em] mt-1 italic opacity-70">
                        Logistic distribution unit</p>
                    <div class="mt-2 flex flex-col items-end gap-0.5">
                        <div class="w-10 h-0.5 bg-gray-900"></div>
                    </div>
                </div>
            </div>

            <!-- Distribution Info Card -->
            <div class="grid grid-cols-2 gap-8 mb-6">
                <div class="relative pl-3">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-200 rounded-full"></div>
                    <h3 class="text-[8px] font-black text-gray-400 uppercase tracking-[0.1em] mb-1">Gudang Pengirim
                        (Origin)
                    </h3>
                    <p class="text-lg font-black text-gray-900 uppercase tracking-tight">
                        {{ $transfer->gudangAsal->nama }}
                    </p>
                    <p class="text-[9px] text-gray-500 mt-0.5 leading-tight font-medium">
                        <span class="inline-block uppercase text-[7px] font-black text-gray-300">Alamat:</span>
                        {{ $transfer->gudangAsal->lokasi ?? 'Lokasi belum diatur' }}
                    </p>
                </div>
                <div class="text-right relative pr-3">
                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-blue-600 rounded-full"></div>
                    <h3 class="text-[8px] font-black text-gray-400 uppercase tracking-[0.1em] mb-1">Gudang Penerima
                        (Destination)</h3>
                    <p class="text-lg font-black text-blue-600 uppercase tracking-tight">
                        {{ $transfer->gudangTujuan->nama }}
                    </p>
                    <p class="text-[9px] text-gray-500 mt-0.5 leading-tight font-medium">
                        <span class="inline-block uppercase text-[7px] font-black text-blue-300">Alamat:</span>
                        {{ $transfer->gudangTujuan->lokasi ?? 'Lokasi belum diatur' }}
                    </p>
                </div>
            </div>

            <!-- Items Table Container -->
            <div class="mb-6 border-t border-gray-100 pt-4">
                <table class="w-full text-left border-collapse overflow-hidden border-x border-t border-gray-100">
                    <thead>
                        <tr class="bg-gray-900 text-white">
                            <th class="px-3 py-2 text-[8px] font-black uppercase tracking-[0.1em] w-10 text-center">No.
                            </th>
                            <th class="px-3 py-2 text-[8px] font-black uppercase tracking-[0.1em]">Deskripsi Barang
                                (SKU)
                            </th>
                            <th class="px-3 py-2 text-center text-[8px] font-black uppercase tracking-[0.1em] w-20">
                                Kuantitas</th>
                            <th class="px-3 py-2 text-center text-[8px] font-black uppercase tracking-[0.1em] w-20">
                                Satuan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="border-b border-gray-900">
                        @foreach($transfer->details as $index => $d)
                            <tr class="border-b border-gray-100 bg-white">
                                <td class="px-3 py-1.5 text-[10px] font-black text-gray-300 text-center">{{ $index + 1 }}
                                </td>
                                <td class="px-3 py-1.5">
                                    <p class="font-bold text-gray-900 uppercase tracking-tight text-[11px] leading-tight">
                                        {{ $d->barang->nama }}
                                    </p>
                                    <span
                                        class="text-[8px] font-mono font-bold text-gray-400 uppercase tracking-tighter">SKU:
                                        {{ $d->barang->sku }}</span>
                                </td>
                                <td class="px-3 py-1.5 text-center">
                                    <span
                                        class="text-sm font-black text-gray-900 italic tracking-tighter">{{ $d->jumlah }}</span>
                                </td>
                                <td class="px-3 py-1.5 text-center">
                                    <span
                                        class="text-[8px] font-black text-gray-400 uppercase tracking-[0.05em]">{{ $d->barang->satuan->nama ?? 'Unit' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <td colspan="2"
                                class="px-3 py-2 text-right text-[8px] font-black uppercase tracking-[0.1em] text-gray-400 italic">
                                Total Muatan</td>
                            <td class="px-3 py-2 text-center bg-gray-900 text-white">
                                <span
                                    class="text-sm font-black italic tracking-tighter">{{ $transfer->total_qty }}</span>
                            </td>
                            <td
                                class="px-3 py-2 bg-gray-100 text-gray-400 text-[7px] font-black uppercase tracking-widest text-center">
                                Units</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($transfer->keterangan)
                <div class="mb-6 p-3 bg-gray-50 border-l-2 border-gray-900">
                    <h4 class="text-[8px] font-black text-gray-400 uppercase tracking-[0.1em] mb-0.5">Catatan (Notes):</h4>
                    <p class="text-[10px] text-gray-700 font-medium leading-snug uppercase tracking-tight italic">
                        "{{ $transfer->keterangan }}"</p>
                </div>
            @endif
        </div>

        <!-- Spacer to push signature block to the bottom -->
        <div class="flex-grow"></div>

        <!-- Bottom Section -->
        <div class="flex-none">
            <!-- Signature Block -->
            <div class="grid grid-cols-3 gap-6 mt-10 print-break-inside-avoid">
                <div class="flex flex-col items-center">
                    <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.1em] mb-12 italic">Pengirim,</p>
                    <div class="border-b border-gray-900 w-full mb-1"></div>
                    <p class="text-[10px] font-black uppercase tracking-tight text-gray-900">
                        {{ $transfer->user->name ?? 'Administrator' }}
                    </p>
                    <span
                        class="text-[7px] font-bold text-gray-400 uppercase tracking-tighter">{{ $transfer->gudangAsal->nama }}</span>
                </div>

                <div class="flex flex-col items-center">
                    <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.1em] mb-12 italic">Kurir,</p>
                    <div class="border-b border-gray-200 w-full mb-1 border-dashed"></div>
                    <p class="text-[7px] font-bold text-gray-200 uppercase tracking-[0.1em] italic mt-0.5">Ttd & Nama
                        Jelas
                    </p>
                </div>

                <div class="flex flex-col items-center">
                    <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.1em] mb-12 italic">Penerima,</p>
                    <div class="border-b border-blue-600 w-full mb-1 border-dashed"></div>
                    <p class="text-[7px] font-bold text-gray-200 uppercase tracking-[0.1em] italic mt-0.5">Stempel & Ttd
                    </p>
                    <span
                        class="text-[7px] font-bold text-blue-600 uppercase tracking-tighter">{{ $transfer->gudangTujuan->nama }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto print trigger for seamless workflow -->
    <script>
        // window.onload = () => {
        //     setTimeout(() => { window.print(); }, 500);
        // }
    </script>
</body>

</html>