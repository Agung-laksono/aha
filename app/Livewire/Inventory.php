<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Satuan;
use App\Models\Stok;
use App\Models\HargaBeli;
use App\Models\HargaJual;
use App\Models\GambarBarang;
use App\Models\Vendor;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Inventory extends Component
{
    use WithFileUploads;

    public $namaKategori, $kodeKategori, $deskripsiKategori;
    public $namaSubKategori, $kodeSubKategori, $deskripsiSubKategori, $kategori_id;
    public $kategori_id_barang; // Untuk filter di modal barang

    // modal satuan
    public $namaSatuan, $kodeSatuan, $deskripsiSatuan;

    // Properti Barang
    public $namaBarang, $skuBarang, $deskripsiBarang, $satuan_id, $sub_kategori_id;
    public $harga_beli, $harga_jual;
    public $gambars = [];
    public $perPage = 24;

    // Filter & Sort Properties with URL persistence
    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $filterKategori = '';

    #[Url(history: true)]
    public $filterSubKategori = '';

    #[Url(history: true)]
    public $sortBy = 'newest';

    #[Url(history: true)]
    public $viewMode = 'grid'; // grid or list

    #[Url(history: true)]
    public $gridCols = 4; // default cols for lg/xl

    // Purchase Properties
    public $showPurchaseModal = false;
    public $purchaseCart = []; // [{barang_id, nama, sku, qty, harga, gudang_id, status}]
    public $selectedVendor = null; // {id, nama}
    public $nomorNota = '';
    public $tanggalPembelian = '';
    public $searchVendor = '';
    public $showVendorPicker = false;
    public $searchBarangPurchase = '';

    // Master Data Form Properties
    public $showModalVendor = false;
    public $showModalGudang = false;

    // Vendor Form
    public $namaVendor = '';
    public $kontakVendor = '';
    public $province_id = '';
    public $regency_id = '';
    public $district_id = '';
    public $village_id = '';
    public $alamatVendor = '';
    public $tagVendor = '';
    public $gambarVendor = null;

    // Region Lists
    public $provinces = [];
    public $regencies = [];
    public $districts = [];
    public $villages = [];

    // Gudang Form
    public $namaGudang = '';
    public $lokasiGudang = '';
    public $deskripsiGudang = '';
    public $gambarGudang = null;

    public function updatedFilterKategori()
    {
        $this->filterSubKategori = '';
        $this->resetPage();
    }

    public function resetPage()
    {
        $this->perPage = 24;
    }

    public function loadMore()
    {
        $this->perPage += 24;
    }

    public function updatedUploadGambars()
    {
        $this->validate([
            'uploadGambars.*' => 'image|max:2048',
        ]);

        foreach ($this->uploadGambars as $gambar) {
            $this->gambars[] = $gambar;
        }

        $this->reset('uploadGambars');
    }

    public function removeGambar($index)
    {
        if (isset($this->gambars[$index])) {
            unset($this->gambars[$index]);
            $this->gambars = array_values($this->gambars);
        }
    }

    public function updateCroppedImage($index, $base64Data)
    {
        // Data base64 dari Cropper.js
        // Kita simpan saja metadata atau data mentahnya untuk diproses saat store
        if (isset($this->gambars[$index])) {
            $this->gambars[$index] = $base64Data;
        }
    }

    public function updatedNamaBarang()
    {
        $this->validateOnly('namaBarang', [
            'namaBarang' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Barang::whereRaw('LOWER(nama) = ?', [strtolower($value)])->exists()) {
                        $fail('Nama barang ini sudah terdaftar.');
                    }
                },
            ],
        ]);
    }

    public function updatedSkuBarang()
    {
        $this->validateOnly('skuBarang', [
            'skuBarang' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Barang::whereRaw('LOWER(sku) = ?', [strtolower($value)])->exists()) {
                        $fail('SKU ini sudah digunakan oleh barang lain.');
                    }
                },
            ],
        ]);
    }

    #[Computed]
    public function barangs()
    {
        $query = Barang::with(['gambarBarangs', 'hargaJualTerakhir', 'kategori', 'subKategori']);

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->filterKategori) {
            $query->where('kategori_id', $this->filterKategori);
        }

        // Subcategory filter
        if ($this->filterSubKategori) {
            $query->where('sub_kategori_id', $this->filterSubKategori);
        }

        // Sorting
        switch ($this->sortBy) {
            case 'az':
                $query->orderBy('nama', 'asc');
                break;
            case 'za':
                $query->orderBy('nama', 'desc');
                break;
            case 'price_high':
                $query->orderBy(
                    HargaJual::select('harga')
                        ->whereColumn('barang_id', 'barangs.id')
                        ->latest()
                        ->take(1),
                    'desc'
                );
                break;
            case 'price_low':
                $query->orderBy(
                    HargaJual::select('harga')
                        ->whereColumn('barang_id', 'barangs.id')
                        ->latest()
                        ->take(1),
                    'asc'
                );
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query->take($this->perPage)->get();
    }

    #[Computed]
    public function totalBarang()
    {
        $query = Barang::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterKategori) {
            $query->where('kategori_id', $this->filterKategori);
        }

        if ($this->filterSubKategori) {
            $query->where('sub_kategori_id', $this->filterSubKategori);
        }

        return $query->count();
    }

    protected function fetchWilayah(string $url): array
    {
        $key = 'wilayah_' . md5($url);
        return Cache::remember($key, now()->addHours(6), function () use ($url) {
            $response = Http::withoutVerifying()->get($url);
            return $response->json()['data'] ?? [];
        });
    }

    public function render()
    {
        $this->provinces = $this->fetchWilayah('https://wilayah.id/api/provinces.json');

        return view('livewire.inventory', [
            'gudangs' => Gudang::all(),
            'kategoris' => Kategori::all(),
            'subKategoris' => SubKategori::all(),
            'subKategorisFiltered' => $this->kategori_id_barang
                ? SubKategori::where('kategori_id', $this->kategori_id_barang)->get()
                : collect(),
            'subKategorisFilter' => $this->filterKategori
                ? SubKategori::where('kategori_id', $this->filterKategori)->get()
                : collect(),
            'satuans' => Satuan::all(),
            'stoks' => Stok::all(),
            'searchBarangResults' => $this->searchBarangPurchase
                ? Barang::where('nama', 'like', '%' . $this->searchBarangPurchase . '%')
                    ->orWhere('sku', 'like', '%' . $this->searchBarangPurchase . '%')
                    ->take(5)->get()
                : collect(),
        ]);
    }


    public function storeKategori()
    {
        $this->validate([
            'namaKategori' => 'required|unique:kategoris,nama',
            'kodeKategori' => 'required|unique:kategoris,kode',
        ]);

        Kategori::create([
            'nama' => $this->namaKategori,
            'kode' => $this->kodeKategori,
            'deskripsi' => $this->deskripsiKategori,
        ]);

        session()->flash('success', 'Kategori "' . $this->namaKategori . '" berhasil disimpan!');

        $this->reset('namaKategori', 'kodeKategori', 'deskripsiKategori');
    }
    public function storeSubKategori()
    {
        $this->validate([
            'kategori_id' => 'required',
            'namaSubKategori' => 'required|unique:sub_kategoris,nama',
            'kodeSubKategori' => 'required|unique:sub_kategoris,kode',
            'deskripsiSubKategori' => 'required',

        ]);

        SubKategori::create([
            'nama' => $this->namaSubKategori,
            'kategori_id' => $this->kategori_id,
            'kode' => $this->kodeSubKategori,
            'deskripsi' => $this->deskripsiSubKategori,
        ]);

        session()->flash('success', 'Sub Kategori "' . $this->namaSubKategori . '" berhasil disimpan!');

        $this->reset('namaSubKategori', 'kodeSubKategori', 'deskripsiSubKategori', 'kategori_id');
    }

    public function storeSatuan()
    {
        $this->validate([
            'namaSatuan' => 'required|unique:satuans,nama',
            'kodeSatuan' => 'required|unique:satuans,kode',
        ]);

        Satuan::create([
            'nama' => $this->namaSatuan,
            'kode' => $this->kodeSatuan,
            'deskripsi' => $this->deskripsiSatuan ?? '-',
        ]);

        session()->flash('success', 'Satuan "' . $this->namaSatuan . '" berhasil disimpan!');

        $this->reset('namaSatuan', 'kodeSatuan', 'deskripsiSatuan');
    }


    public function storeBarang()
    {
        $this->validate([
            'namaBarang' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Barang::whereRaw('LOWER(nama) = ?', [strtolower($value)])->exists()) {
                        $fail('Nama barang ini sudah terdaftar.');
                    }
                },
            ],
            'skuBarang' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Barang::whereRaw('LOWER(sku) = ?', [strtolower($value)])->exists()) {
                        $fail('SKU ini sudah digunakan oleh barang lain.');
                    }
                },
            ],
            'sub_kategori_id' => 'required',
            'satuan_id' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'gambars' => 'required|array|min:1',
        ], [
            'gambars.required' => 'Minimal satu gambar harus diunggah.',
            'gambars.min' => 'Minimal satu gambar harus diunggah.',
        ]);

        DB::transaction(function () {
            $barang = Barang::create([
                'nama' => $this->namaBarang,
                'sku' => $this->skuBarang,
                'deskripsi' => $this->deskripsiBarang ?? '-',
                'sub_kategori_id' => $this->sub_kategori_id,
                'satuan_id' => $this->satuan_id,
                'kategori_id' => SubKategori::find($this->sub_kategori_id)->kategori_id,
            ]);

            if ($this->gambars) {
                foreach ($this->gambars as $index => $gambar) {
                    if (is_string($gambar) && str_starts_with($gambar, 'data:image')) {
                        // Jika gambar adalah base64 (hasil crop)
                        $image_parts = explode(";base64,", $gambar);
                        $image_base64 = base64_decode($image_parts[1]);
                        $filename = 'Barang/' . uniqid() . '.png';
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image_base64);
                        $path = $filename;
                    } else {
                        // Jika gambar adalah TemporaryUploadedFile asli
                        $path = $gambar->store('Barang', 'public');
                    }

                    GambarBarang::create([
                        'barang_id' => $barang->id,
                        'path' => $path,
                        'gambar_utama' => $index === 0,
                    ]);
                }
            }

            HargaBeli::create([
                'barang_id' => $barang->id,
                'harga' => $this->harga_beli,
                'user_id' => Auth::id() ?? 1,
            ]);

            HargaJual::create([
                'barang_id' => $barang->id,
                'harga' => $this->harga_jual,
                'user_id' => Auth::id() ?? 1,
            ]);
        });

        session()->flash('success', 'Barang "' . $this->namaBarang . '" berhasil disimpan!');

        $this->reset([
            'namaBarang',
            'skuBarang',
            'deskripsiBarang',
            'satuan_id',
            'sub_kategori_id',
            'harga_beli',
            'harga_jual',
            'gambars',
            'kategori_id_barang'
        ]);

        $this->dispatch('close-modal');
    }

    // --- PURCHASE METHODS ---

    public function openPurchaseModal()
    {
        // Don't reset purchaseCart here to allow picking items from the list
        if (!$this->tanggalPembelian)
            $this->tanggalPembelian = date('Y-m-d');
        if (!$this->nomorNota)
            $this->nomorNota = 'PO-' . date('YmdHis');
        $this->showPurchaseModal = true;
    }

    public function clearPurchaseCart()
    {
        $this->reset(['purchaseCart', 'selectedVendor', 'nomorNota']);
        $this->nomorNota = 'PO-' . date('YmdHis');
    }

    #[Computed]
    public function vendors()
    {
        return Vendor::when($this->searchVendor, function ($q) {
            $q->where('nama', 'like', '%' . $this->searchVendor . '%');
        })->get();
    }

    public function selectVendor($id, $nama)
    {
        $this->selectedVendor = ['id' => $id, 'nama' => $nama];
        $this->showVendorPicker = false;
    }

    public function addToPurchaseCart($barangId)
    {
        $barang = Barang::find($barangId);
        if (!$barang)
            return;

        // Check if already in cart
        foreach ($this->purchaseCart as $item) {
            if ($item['barang_id'] == $barangId)
                return;
        }

        $this->purchaseCart[] = [
            'barang_id' => $barang->id,
            'nama' => $barang->nama,
            'sku' => $barang->sku,
            'qty' => 1,
            'harga' => $barang->hargaBeliTerakhir->harga ?? 0,
            'gudang_id' => Gudang::first()->id ?? null,
            'status' => 'Received', // Default to received for simplicity, user can change
        ];

        $this->searchBarangPurchase = '';
        $this->dispatch('item-added-to-cart', ['name' => $barang->nama]);
    }

    public function removeFromPurchaseCart($index)
    {
        unset($this->purchaseCart[$index]);
        $this->purchaseCart = array_values($this->purchaseCart);
    }

    public function savePurchase()
    {
        $this->validate([
            'selectedVendor' => 'required',
            'nomorNota' => 'required|unique:pembelians,nomor_nota',
            'tanggalPembelian' => 'required|date',
            'purchaseCart' => 'required|array|min:1',
        ]);

        DB::transaction(function () {
            $totalHarga = collect($this->purchaseCart)->sum(fn($i) => $i['qty'] * $i['harga']);

            // Determine overall status based on items
            $hasPO = collect($this->purchaseCart)->contains('status', 'PO');
            $hasPending = collect($this->purchaseCart)->contains('status', 'Pending');
            $overallStatus = ($hasPO || $hasPending) ? 'PO' : 'Received';

            $pembelian = Pembelian::create([
                'nomor_nota' => $this->nomorNota,
                'vendor_id' => $this->selectedVendor['id'],
                'total_harga' => $totalHarga,
                'status' => $overallStatus,
                'tanggal' => $this->tanggalPembelian,
            ]);

            foreach ($this->purchaseCart as $item) {
                $qtyTerima = ($item['status'] === 'Received') ? $item['qty'] : 0;

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'barang_id' => $item['barang_id'],
                    'qty_pesan' => $item['qty'],
                    'qty_terima' => $qtyTerima,
                    'harga_beli' => $item['harga'],
                    'gudang_id' => $item['gudang_id'],
                    'status_item' => $item['status'],
                ]);

                // Update Stok if received
                if ($qtyTerima > 0 && $item['gudang_id']) {
                    Stok::updateOrCreate(
                        ['barang_id' => $item['barang_id'], 'gudang_id' => $item['gudang_id']],
                        ['jumlah' => DB::raw("jumlah + $qtyTerima")]
                    );

                    // Update Harga Beli Terakhir
                    HargaBeli::create([
                        'barang_id' => $item['barang_id'],
                        'harga' => $item['harga'],
                        'user_id' => Auth::id() ?? 1,
                    ]);
                }
            }
        });

        session()->flash('success', 'Transaksi Pembelian ' . $this->nomorNota . ' berhasil disimpan!');
        $this->showPurchaseModal = false;
        $this->reset(['purchaseCart', 'selectedVendor', 'nomorNota']);
    }

    public function updatedProvinceId($id)
    {
        if ($id) {
            $this->regencies = $this->fetchWilayah("https://wilayah.id/api/regencies/{$id}.json");
        } else {
            $this->regencies = [];
        }
        $this->reset(['regency_id', 'district_id', 'village_id', 'districts', 'villages']);
    }

    public function updatedRegencyId($id)
    {
        if ($id) {
            $this->districts = $this->fetchWilayah("https://wilayah.id/api/districts/{$id}.json");
        } else {
            $this->districts = [];
        }
        $this->reset(['district_id', 'village_id', 'villages']);
    }

    public function updatedDistrictId($id)
    {
        if ($id) {
            $this->villages = $this->fetchWilayah("https://wilayah.id/api/villages/{$id}.json");
        } else {
            $this->villages = [];
        }
        $this->reset(['village_id']);
    }

    public function storeVendor()
    {
        $this->validate([
            'namaVendor' => 'required|min:3',
            'kontakVendor' => 'nullable|min:8',
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
            'alamatVendor' => 'required|min:5',
            'gambarVendor' => 'nullable|image|max:2048',
        ]);

        $gambarPath = null;
        if ($this->gambarVendor) {
            $gambarPath = $this->gambarVendor->store('vendors', 'public');
        }

        $vendor = Vendor::create([
            'nama' => $this->namaVendor,
            'kontak' => $this->kontakVendor,
            'province_id' => $this->province_id,
            'regency_id' => $this->regency_id,
            'district_id' => $this->district_id,
            'village_id' => $this->village_id,
            'alamat' => $this->alamatVendor,
            'tag' => $this->tagVendor,
            'gambar' => $gambarPath,
        ]);

        $this->reset(['namaVendor', 'kontakVendor', 'province_id', 'regency_id', 'district_id', 'village_id', 'alamatVendor', 'tagVendor', 'gambarVendor', 'showModalVendor', 'regencies', 'districts', 'villages']);

        // If opened from purchase modal, auto select it
        if ($this->showPurchaseModal) {
            $this->selectVendor($vendor->id, $vendor->nama);
        }

        $this->dispatch('close-modal-vendor');
        session()->flash('success', 'Vendor baru berhasil ditambahkan!');
    }

    public function storeGudang()
    {
        $this->validate([
            'namaGudang' => 'required|min:2|unique:gudangs,nama',
            'gambarGudang' => 'nullable|image|max:2048',
        ]);

        $gambarPath = null;
        if ($this->gambarGudang) {
            $gambarPath = $this->gambarGudang->store('gudangs', 'public');
        }

        Gudang::create([
            'nama' => $this->namaGudang,
            'lokasi' => $this->lokasiGudang,
            'deskripsi' => $this->deskripsiGudang,
            'gambar' => $gambarPath,
        ]);

        $this->reset(['namaGudang', 'lokasiGudang', 'deskripsiGudang', 'gambarGudang', 'showModalGudang']);
        $this->dispatch('close-modal-gudang');
        session()->flash('success', 'Gudang baru berhasil ditambahkan!');
    }
}
