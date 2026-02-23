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

    public function render()
    {
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
}
