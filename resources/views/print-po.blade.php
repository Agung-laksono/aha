<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print PO - {{ $pembelian->nomor_nota }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background: white;
        }
    </style>
</head>

<body class="p-8">
    <div class="no-print mb-8 flex justify-center">
        <button onclick="window.print()"
            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg hover:bg-indigo-700 transition">
            Cetak Sekarang (A4)
        </button>
    </div>

    <div class="max-w-4xl mx-auto border p-8 bg-white">
        <!-- Header -->
        <div class="flex justify-between items-start border-b-2 border-gray-900 pb-6 mb-8">
            <div>
                <h1 class="text-4xl font-black uppercase tracking-tighter text-gray-900">Purchase Order</h1>
                <p class="text-sm font-bold text-gray-500 mt-1">Nomor Nota: <span
                        class="text-gray-900 font-mono">{{ $pembelian->nomor_nota }}</span></p>
                <p class="text-sm font-bold text-gray-500">Tanggal: <span
                        class="text-gray-900">{{ \Carbon\Carbon::parse($pembelian->tanggal)->translatedFormat('d F Y') }}</span>
                </p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-black text-indigo-600 uppercase">{{ Auth::user()->currentTeam->name }}</h2>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <!-- Vendor & Info -->
        <div class="grid grid-cols-2 gap-12 mb-12">
            <div>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Vendor :</h3>
                <p class="text-lg font-black text-gray-900">{{ $pembelian->vendor->nama ?? 'Umum' }}</p>
                <p class="text-sm text-gray-600">{{ $pembelian->vendor->kontak ?? '-' }}</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $pembelian->vendor->alamat ?? '-' }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status Pembayaran :</h3>
                <p class="text-lg font-black text-gray-900 uppercase">{{ $pembelian->status_pembayaran }}</p>
                <p class="text-sm text-gray-600">Metode: <span
                        class="font-bold">{{ $pembelian->metode_pembayaran }}</span></p>
                @if($pembelian->jatuh_tempo)
                    <p class="text-sm text-rose-600 font-bold mt-1 uppercase">Jatuh Tempo:
                        {{ \Carbon\Carbon::parse($pembelian->jatuh_tempo)->translatedFormat('d F Y') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Details Table -->
        <table class="w-full text-left border-collapse mb-12">
            <thead>
                <tr class="bg-gray-100 border-y-2 border-gray-900">
                    <th class="px-4 py-3 text-xs font-black uppercase tracking-widest">Item & SKU</th>
                    <th class="px-4 py-3 text-center text-xs font-black uppercase tracking-widest">Qty</th>
                    <th class="px-4 py-3 text-right text-xs font-black uppercase tracking-widest">Harga</th>
                    <th class="px-4 py-3 text-right text-xs font-black uppercase tracking-widest">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y border-b border-gray-200">
                @foreach($pembelian->details as $d)
                    <tr>
                        <td class="px-4 py-4">
                            <p class="font-bold text-gray-900">{{ $d->barang->gambar }}</p>
                            <p class="font-bold text-gray-900">{{ $d->barang->nama }}</p>
                            <p class="text-[10px] font-mono text-gray-400 uppercase">SKU: {{ $d->barang->sku }}</p>
                            @if($d->catatan)
                                <div class="text-[11px] text-indigo-600 font-medium italic mt-1 leading-tight prose prose-sm">
                                    {!! $d->catatan !!}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-gray-900">{{ $d->qty_pesan }}</td>
                        <td class="px-4 py-4 text-right text-gray-600">Rp{{ number_format($d->harga_beli, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-right font-black text-gray-900 italic">
                            Rp{{ number_format($d->qty_pesan * $d->harga_beli, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"
                        class="px-4 py-3 text-right text-xs font-black uppercase tracking-widest text-gray-400">Subtotal
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-gray-900">
                        Rp{{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                </tr>
                @if($pembelian->biaya_ongkir > 0)
                    <tr>
                        <td colspan="3"
                            class="px-4 py-1 text-right text-xs font-black uppercase tracking-widest text-gray-400">Ongkir
                        </td>
                        <td class="px-4 py-1 text-right font-bold text-gray-900">+
                            Rp{{ number_format($pembelian->biaya_ongkir, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if($pembelian->biaya_lain > 0)
                    <tr>
                        <td colspan="3"
                            class="px-4 py-1 text-right text-xs font-black uppercase tracking-widest text-gray-400">
                            Lain-lain</td>
                        <td class="px-4 py-1 text-right font-bold text-gray-900">+
                            Rp{{ number_format($pembelian->biaya_lain, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="bg-gray-50">
                    <td colspan="3"
                        class="px-4 py-4 text-right text-sm font-black uppercase tracking-widest text-gray-900">Total
                        Akhir</td>
                    <td class="px-4 py-4 text-right text-xl font-black text-indigo-600">
                        Rp{{ number_format($pembelian->total_harga + $pembelian->biaya_ongkir + $pembelian->biaya_lain, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures -->
        <div class="grid grid-cols-3 gap-8 mt-20 text-center">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-16">Dipesan Oleh,</p>
                <div class="border-b border-gray-900 w-3/4 mx-auto mb-1"></div>
                <p class="text-xs font-bold uppercase">{{ Auth::user()->name }}</p>
            </div>
            <div></div>
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-16">Pihak Vendor,</p>
                <div class="border-b border-gray-900 w-3/4 mx-auto mb-1"></div>
                <p class="text-xs font-bold uppercase">{{ $pembelian->vendor->nama ?? 'Vendor' }}</p>
            </div>
        </div>

        <div class="mt-20 pt-8 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-[0.2em]">Nota Digital ini diterbitkan oleh
                Sistem Inventory Pro v2.0</p>
        </div>
    </div>
</body>

</html>