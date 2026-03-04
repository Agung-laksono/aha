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
use App\Models\CatatanPreset;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\AkunKas;
use App\Models\MutasiKas;
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

    // Properti Pelunasan Hutang
    public $showPaymentModal = false;
    public $pembelianIdBayar;
    public $jumlahBayarPelunasan;
    public $selectedAkunKasIdPelunasan;
    public $buktiPembayaranPelunasan;

    // Properti Barang
    public $namaBarang, $skuBarang, $deskripsiBarang, $satuan_id, $sub_kategori_id;
    public $harga_beli, $harga_jual;
    public $gambars = [];
    public $uploadGambars = [];
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
    public $showConfirmPurchaseModal = false;
    public $purchaseCart = []; // [{barang_id, nama, sku, qty, harga, gudang_id, status}]
    public $selectedVendor = null; // {id, nama}
    public $nomorNota = '';
    public $tanggalPembelian = '';
    public $searchVendor = '';
    public $showVendorPicker = false;
    public $searchBarangPurchase = '';

    // Advanced Purchase Properties
    public $ongkir = 0;
    public $biayaLain = 0;
    public $metodePembayaran = 'Cash';
    public $jatuhTempo = '';
    public $akunKasId = '';
    public $jumlahDP = '';
    public $invoiceFile = null;
    public $compressedInvoice = null; // For the base64 compressed image

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

    // Granular Receiving Properties
    public $qtyReceived = []; // [detail_id => qty]
    public $gudangReceived = []; // [detail_id => gudang_id]
    public $gambarVendor = null;

    // Catatan Presets
    public function getHistoryNotes($barangId)
    {
        return PembelianDetail::where('barang_id', $barangId)
            ->whereNotNull('catatan')
            ->where('catatan', '!=', '')
            ->where('catatan', '!=', '<p><br></p>')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->pluck('catatan')
            ->unique()
            ->values()
            ->toArray();
    }

    public function savePreset($teks, $tipe)
    {
        if (empty($teks) || $teks === '<p><br></p>')
            return;

        CatatanPreset::updateOrCreate(
            ['user_id' => Auth::id(), 'teks' => $teks],
            ['tipe' => $tipe]
        );

        $this->dispatch('preset-saved', ['tipe' => $tipe, 'presets' => $this->getPresets($tipe)]);
    }

    public function getPresets($tipe)
    {
        return CatatanPreset::where('user_id', Auth::id())
            ->where('tipe', $tipe)
            ->orderBy('created_at', 'desc')
            ->pluck('teks')
            ->toArray();
    }

    #[Computed]
    public function pendingReturnsCount()
    {
        if (auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin') || auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'editor')) {
            return \App\Models\PembelianDetail::where('qty_retur_request', '>', 0)->count();
        }
        return 0;
    }

    // Region Lists
    public $provinces = [];
    public $regencies = [];
    public $districts = [];
    public $villages = [];

    // Properti Transfer Stok
    public $searchTransfer = '';

    // Gudang Form
    public $namaGudang = '';
    public $lokasiGudang = '';

    // Transaction History
    public $showHistoryModal = false;
    public $selectedPembelianId = null;
    public $searchHistory = '';
    public $qtyRetur = []; // [detail_id => qty]

    // Stock Movement Log
    public $showStockLogModal = false;
    public $searchStockLog = '';
    public $filterGudangLog = '';
    public $stockLogPerPage = 20;

    // Stock Transfer
    public $showTransferModal = false;
    public $gudangAsalId = null;
    public $gudangTujuanId = null;
    public $tanggalTransfer = '';
    public $keteranganTransfer = '';
    public $transferItems = []; // [{barang_id, nama, stok_asal, qty}]
    public $searchTransferItem = '';
    public $showSuggestions = false;

    public function updatedGudangAsalId()
    {
        if (count($this->transferItems) > 0) {
            $this->reset(['transferItems', 'searchTransferItem', 'showSuggestions']);
            session()->flash('error_transfer', 'Keranjang transfer dikosongkan karena Gudang Asal diubah.');
        }
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'transferItems')) {
            $this->validateOnly($propertyName, [
                'transferItems.*.qty' => 'required|integer|min:1'
            ]);

            // Real-time stock check for the specific item
            $parts = explode('.', $propertyName);
            if (count($parts) === 3 && $parts[2] === 'qty') {
                $index = $parts[1];
                $item = $this->transferItems[$index];

                $stok = \App\Models\Stok::where('barang_id', $item['barang_id'])
                    ->where('gudang_id', $this->gudangAsalId)
                    ->first();

                if (!$stok || $stok->jumlah < $item['qty']) {
                    $this->addError("transferItems.{$index}.qty", "Stok tidak mencukupi (Tersedia: " . ($stok ? $stok->jumlah : 0) . ")");
                }
            }
        }

        if (in_array($propertyName, ['gudangAsalId', 'gudangTujuanId', 'tanggalTransfer'])) {
            $this->validateOnly($propertyName, [
                'gudangAsalId' => 'required',
                'gudangTujuanId' => 'required|different:gudangAsalId',
                'tanggalTransfer' => 'required|date',
            ]);
        }
    }

    public function updatedMetodePembayaran($value)
    {
        if ($value === 'Cash') {
            $this->jumlahDP = '';
            $this->jatuhTempo = '';
        } else {
            // Kredit - keep empty as per user request for default null/empty
            $this->jatuhTempo = '';
        }
    }

    public function updatedJumlahDP($value)
    {
        // Remove dots for numeric check
        $numericVal = (float) str_replace('.', '', $value);
        // Force re-render of components dependent on DP
        if ($numericVal > 0 && $this->metodePembayaran === 'Cash') {
            $this->metodePembayaran = 'Kredit';
            // Keep jatuh tempo empty as per user request
            $this->jatuhTempo = '';
        }
    }

    #[Computed]
    public function pembelians()
    {
        $query = Pembelian::with(['vendor', 'details.barang', 'pembayarans', 'dokumens']);

        if ($this->searchHistory) {
            $query->where('nomor_nota', 'like', '%' . $this->searchHistory . '%')
                ->orWhereHas('vendor', function ($q) {
                    $q->where('nama', 'like', '%' . $this->searchHistory . '%');
                });
        }

        return $query->latest()->get();
    }

    #[Computed]
    public function userAkunKas()
    {
        $user = auth()->user();
        $query = \App\Models\AkunKas::with('user')
            ->where('team_id', $user->current_team_id);

        if (!$user->hasTeamPermission($user->currentTeam, 'manage_kas')) {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    #[Computed]
    public function teamUsers()
    {
        return auth()->user()->currentTeam->allUsers();
    }

    #[Computed]
    public function pendingTransfers()
    {
        return \App\Models\StockTransfer::with(['gudangAsal', 'gudangTujuan', 'details.barang'])
            ->when($this->searchTransfer, function ($query) {
                $query->where('nomor_transfer', 'like', '%' . $this->searchTransfer . '%')
                    ->orWhereHas('gudangAsal', fn($q) => $q->where('nama', 'like', '%' . $this->searchTransfer . '%'))
                    ->orWhereHas('gudangTujuan', fn($q) => $q->where('nama', 'like', '%' . $this->searchTransfer . '%'));
            })
            ->latest()
            ->get();
    }

    public function confirmReceive($transferId)
    {
        $transfer = \App\Models\StockTransfer::with('details')->findOrFail($transferId);

        if ($transfer->status !== 'pending') {
            session()->flash('error', 'Transfer ini sudah diproses.');
            return;
        }

        \DB::transaction(function () use ($transfer) {
            $transfer->update([
                'status' => 'received',
                'received_at' => now()
            ]);

            foreach ($transfer->details as $detail) {
                // Update Stok Tujuan (Increase or Create)
                $stokTujuan = \App\Models\Stok::firstOrCreate(
                    ['barang_id' => $detail->barang_id, 'gudang_id' => $transfer->gudang_tujuan_id],
                    ['jumlah' => 0]
                );
                $stokTujuan->increment('jumlah', $detail->jumlah);

                // Record Stock Movement - IN to Destination
                \App\Models\StockMovement::record(
                    $detail->barang_id,
                    $transfer->gudang_tujuan_id,
                    'Masuk',
                    $detail->jumlah,
                    'Transfer Stok (Masuk)',
                    $transfer,
                    "Transfer dari " . $transfer->gudangAsal->nama . " (#{$transfer->nomor_transfer})"
                );
            }
        });

        session()->flash('success', "Transfer #{$transfer->nomor_transfer} berhasil diterima. Stok tujuan telah diperbarui!");
    }

    public function downloadSuratJalan($transferId)
    {
        $this->dispatch('open-new-tab', url: route('print-transfer', $transferId));
    }


    #[Computed]
    public function selectedPembelian()
    {
        if (!$this->selectedPembelianId)
            return null;
        return Pembelian::with(['vendor', 'details.barang', 'details.gudang', 'pembayarans', 'dokumens'])->find($this->selectedPembelianId);
    }

    #[Computed]
    public function isPurchaseReady()
    {
        // 1. Vendor & Meta
        if (!$this->selectedVendor || !$this->nomorNota || !$this->tanggalPembelian)
            return false;

        // 2. Cart
        if (count($this->purchaseCart) === 0)
            return false;

        // 3. Payment Method
        if (!$this->metodePembayaran)
            return false;

        // 4. Specific Payment Logic
        if ($this->metodePembayaran === 'Cash') {
            if (!$this->akunKasId)
                return false;
        } else {
            // Kredit
            if (!$this->jatuhTempo)
                return false;
            // Jika ada DP, wajib pilih Akun Kas
            if ($this->jumlahDP > 0 && !$this->akunKasId)
                return false;
        }

        // 5. Proof of Purchase
        if (!$this->compressedInvoice)
            return false;

        return true;
    }

    public function selectPembelian($id)
    {
        $this->selectedPembelianId = $id;

        // Initialize receiving inputs for PO details
        $pembelian = Pembelian::with('details')->find($id);
        if ($pembelian) {
            foreach ($pembelian->details as $d) {
                if ($d->status_item !== 'Received') {
                    $this->qtyReceived[$d->id] = $d->qty_pesan - $d->qty_terima;
                    $this->gudangReceived[$d->id] = $d->gudang_id ?? (Gudang::first()->id ?? '');
                }
            }
        }
    }


    public function showHistory()
    {
        $this->showPurchaseModal = false;
        $this->showHistoryModal = true;

        $firstPembelian = $this->pembelians->first();
        if ($firstPembelian) {
            $this->selectedPembelianId = $firstPembelian->id;
        }
    }
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
        $toValidate = [];
        foreach ($this->uploadGambars as $index => $gambar) {
            if (!is_string($gambar)) {
                $toValidate['uploadGambars.' . $index] = 'image|max:2048';
            }
        }

        if (!empty($toValidate)) {
            $this->validate($toValidate);
        }

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

    public function updateCroppedImage($index, $base64Data, $target = 'gambars')
    {
        if ($target === 'gambars') {
            if (isset($this->gambars[$index])) {
                $this->gambars[$index] = $base64Data;
            }
        } elseif ($target === 'gambarVendor') {
            $this->gambarVendor = $base64Data;
        } elseif ($target === 'gambarGudang') {
            $this->gambarGudang = $base64Data;
        } elseif ($target === 'buktiPembayaranPelunasan') {
            $this->buktiPembayaranPelunasan = $base64Data;
        }
    }

    protected function saveImage($fileOrBase64, $folder)
    {
        if (!$fileOrBase64)
            return null;

        if (is_string($fileOrBase64) && str_starts_with($fileOrBase64, 'data:image')) {
            $image_parts = explode(";base64,", $fileOrBase64);
            $extension = 'png'; // Default
            if (isset($image_parts[0])) {
                if (strpos($image_parts[0], 'jpeg') !== false)
                    $extension = 'jpg';
                elseif (strpos($image_parts[0], 'webp') !== false)
                    $extension = 'webp';
            }
            $image_base64 = base64_decode($image_parts[1]);
            $filename = $folder . '/' . uniqid() . '.' . $extension;
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image_base64);
            return $filename;
        }

        if ($fileOrBase64 instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            return $fileOrBase64->store($folder, 'public');
        }

        return null;
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
        $query = Barang::with(['gambarBarangs', 'hargaJualTerakhir', 'kategori', 'subKategori', 'stoks.gudang']);

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
            case 'stock_high':
                $query->withSum('stoks', 'jumlah')->orderByRaw('COALESCE(stoks_sum_jumlah, 0) desc');
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

    protected function fetchWilayah(string $type, $parentId = null): array
    {
        $filename = ($type === 'provinces') ? 'provinces.json' : "{$type}_{$parentId}.json";
        $path = storage_path('app/wilayah/' . $filename);

        if (file_exists($path)) {
            $content = file_get_contents($path);
            return json_decode($content, true) ?: [];
        }

        return [];
    }

    public function render()
    {
        $user = auth()->user();
        $gudangsFilter = Gudang::all();

        if ($user && !$user->hasTeamRole($user->currentTeam, 'admin')) {
            $allowedIds = $user->accessibleGudangIds();
            $gudangsFilter = Gudang::whereIn('id', $allowedIds)->get();
        }

        return view('livewire.inventory', [
            'gudangs' => $gudangsFilter,
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
            'vendors' => Vendor::when($this->searchVendor, function ($q) {
                $q->where('nama', 'like', '%' . $this->searchVendor . '%')
                    ->orWhere('kontak', 'like', '%' . $this->searchVendor . '%')
                    ->orWhere('tag', 'like', '%' . $this->searchVendor . '%');
            })->orderBy('nama')->limit(20)->get(),
            'searchBarangResults' => $this->searchBarangPurchase
                ? Barang::where('nama', 'like', '%' . $this->searchBarangPurchase . '%')
                    ->orWhere('sku', 'like', '%' . $this->searchBarangPurchase . '%')
                    ->take(5)->get()
                : collect(),
        ]);
    }


    public function storeKategori()
    {
        // RBAC Check
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Hanya Admin atau Purchasing yang dapat mengelola kategori.');
            return;
        }

        $this->validate([
            'namaKategori' => 'required|unique:kategoris,nama',
            'kodeKategori' => 'required|unique:kategoris,kode',
            'deskripsiKategori' => 'required',
        ]);

        Kategori::create([
            'nama' => $this->namaKategori,
            'kode' => $this->kodeKategori,
            'deskripsi' => $this->deskripsiKategori,
        ]);

        session()->flash('success', 'Kategori "' . $this->namaKategori . '" berhasil disimpan!');

        $this->reset('namaKategori', 'kodeKategori', 'deskripsiKategori');
        $this->dispatch('close-modal', modalId: 'modal-kategori');
    }
    public function storeSubKategori()
    {
        // RBAC Check
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Hanya Admin atau Purchasing yang dapat mengelola sub-kategori.');
            return;
        }

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
        $this->dispatch('close-modal', modalId: 'modal-subKategori');
    }

    public function storeSatuan()
    {
        // RBAC Check
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Hanya Admin atau Purchasing yang dapat mengelola satuan.');
            return;
        }

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
        $this->dispatch('close-modal', modalId: 'modal-satuan');
    }


    public function storeBarang()
    {
        // RBAC Check: Only Admin and Purchasing (Editor) can create/edit Barang
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak untuk menambah atau mengubah data barang.');
            return;
        }

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
                    $path = $this->saveImage($gambar, 'Barang');

                    if ($path) {
                        GambarBarang::create([
                            'barang_id' => $barang->id,
                            'path' => $path,
                            'is_utama' => ($index === 0)
                        ]);
                    }
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

        $this->dispatch('close-modal', modalId: 'modal-barang');
    }

    // --- PURCHASE METHODS ---

    public function openPurchaseModal()
    {
        // RBAC Check
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Pembelian hanya dapat dilakukan oleh Admin atau Purchasing.');
            return;
        }

        // Mutually exclusive with history
        $this->showHistoryModal = false;

        // Don't reset purchaseCart here to allow picking items from the list
        if (!$this->tanggalPembelian)
            $this->tanggalPembelian = date('Y-m-d');

        $this->generateNomorNota();
        $this->showPurchaseModal = true;
    }

    private function generateNomorNota()
    {
        $prefix = "NP"; // Nota Pembelian
        $date = date('Ymd');
        $vendorCode = "XXX";

        if ($this->selectedVendor) {
            $vendorCode = $this->getVendorCode($this->selectedVendor['nama']);
        }

        $basePrefix = "{$prefix}/{$vendorCode}/{$date}/";

        $lastPurchase = Pembelian::where('nomor_nota', 'like', $basePrefix . '%')
            ->orderBy('nomor_nota', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastPurchase) {
            $parts = explode('/', $lastPurchase->nomor_nota);
            $lastNum = (int) end($parts);
            $nextNumber = $lastNum + 1;
        }

        $this->nomorNota = $basePrefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    private function getVendorCode($name)
    {
        $words = explode(' ', preg_replace('/[^A-Za-z0-9 ]/', '', $name));
        $words = array_filter($words);

        if (count($words) >= 3) {
            $code = $words[0][0] . $words[1][0] . $words[2][0];
        } elseif (count($words) == 2) {
            $code = $words[0][0] . substr($words[1], 0, 2);
        } else {
            $code = substr($words[0], 0, 3);
        }

        return strtoupper(str_pad($code, 3, 'X', STR_PAD_RIGHT));
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
        $vendor = Vendor::find($id);
        $this->selectedVendor = [
            'id' => $id,
            'nama' => $vendor?->nama ?? $nama,
            'kontak' => $vendor?->kontak,
            'alamat' => $vendor?->alamat,
            'tag' => $vendor?->tag,
            'gambar' => $vendor?->gambar,
        ];
        $this->showVendorPicker = false;
        $this->searchVendor = '';

        // Update Nota with vendor initials
        $this->generateNomorNota();
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

        $img = $barang->gambarBarangs->where('gambar_utama', true)->first() ?? $barang->gambarBarangs->first();
        $imgPath = $img ? (str_starts_with($img->path, 'http') ? $img->path : asset('storage/' . $img->path)) : 'https://ui-avatars.com/api/?name=' . urlencode($barang->nama) . '&color=random';

        $this->purchaseCart[] = [
            'barang_id' => $barang->id,
            'nama' => $barang->nama,
            'sku' => $barang->sku,
            'qty' => 1,
            'gambar' => $imgPath,
            'harga' => $barang->hargaBeliTerakhir->harga ?? 0,
            'gudang_id' => '',
            'status' => 'Received', // Default to received for simplicity, user can change
            'catatan' => '',
            'catatan_internal' => '',
        ];

        $this->searchBarangPurchase = '';
        $this->dispatch('item-added-to-cart', ['name' => $barang->nama]);
    }

    public function removeFromPurchaseCart($index)
    {
        unset($this->purchaseCart[$index]);
        $this->purchaseCart = array_values($this->purchaseCart);
    }

    public function confirmPurchase()
    {
        // RBAC Check
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak untuk merekam transaksi pembelian.');
            return;
        }

        $this->validate([
            'selectedVendor' => 'required',
            'nomorNota' => 'required|unique:pembelians,nomor_nota',
            'tanggalPembelian' => 'required|date',
            'purchaseCart' => 'required|array|min:1',
            'purchaseCart.*.gudang_id' => 'required_if:purchaseCart.*.status,Received',
            'metodePembayaran' => 'required',
            'akunKasId' => [
                'required_if:metodePembayaran,Cash',
                function ($attribute, $value, $fail) {
                    if ($this->metodePembayaran === 'Kredit' && $this->jumlahDP > 0 && empty($value)) {
                        $fail('Sedang ada DP, harap pilih Akun Kas asal dana.');
                    }
                }
            ],
            'jatuhTempo' => 'required_if:metodePembayaran,Kredit',
            'compressedInvoice' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_string($value)) {
                        $validator = \Illuminate\Support\Facades\Validator::make(
                            [$attribute => $value],
                            [$attribute => 'image|max:2048']
                        );
                        if ($validator->fails()) {
                            $fail($validator->errors()->first($attribute));
                        }
                    }
                }
            ],
        ], [
            'selectedVendor.required' => 'Pilih vendor terlebih dahulu.',
            'nomorNota.required' => 'Nomor nota wajib diisi.',
            'nomorNota.unique' => 'Nomor nota sudah terdaftar di sistem.',
            'tanggalPembelian.required' => 'Tanggal transaksi wajib diisi.',
            'purchaseCart.min' => 'Keranjang masih kosong, tambahkan minimal 1 barang.',
            'purchaseCart.*.gudang_id.required_if' => 'Pilih Tujuan Gudang untuk barang Diterima.',
            'metodePembayaran.required' => 'Pilih metode pembayaran.',
            'akunKasId.required_if' => 'Pilih Akun Kas untuk pembayaran tunai.',
            'jatuhTempo.required_if' => 'Tanggal jatuh tempo wajib diisi untuk transaksi kredit.',
            'compressedInvoice.required' => 'Bukti nota/invoice wajib diunggah.',
        ]);

        $this->showConfirmPurchaseModal = true;
    }

    public function savePurchase()
    {
        // RBAC Check: Only Admin and Purchasing (Editor) can save Purchases
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak untuk merekam transaksi pembelian.');
            return;
        }

        $this->validate([
            'selectedVendor' => 'required',
            'nomorNota' => 'required|unique:pembelians,nomor_nota',
            'tanggalPembelian' => 'required|date',
            'purchaseCart' => 'required|array|min:1',
            'purchaseCart.*.gudang_id' => 'required_if:purchaseCart.*.status,Received',
            'metodePembayaran' => 'required',
            'akunKasId' => [
                'required_if:metodePembayaran,Cash',
                function ($attribute, $value, $fail) {
                    if ($this->metodePembayaran === 'Kredit' && $this->jumlahDP > 0 && empty($value)) {
                        $fail('Sedang ada DP, harap pilih Akun Kas asal dana.');
                    }
                }
            ],
            'jatuhTempo' => 'required_if:metodePembayaran,Kredit',
            'compressedInvoice' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_string($value)) {
                        $validator = \Illuminate\Support\Facades\Validator::make(
                            [$attribute => $value],
                            [$attribute => 'image|max:2048']
                        );
                        if ($validator->fails()) {
                            $fail($validator->errors()->first($attribute));
                        }
                    }
                }
            ],
        ], [
            'selectedVendor.required' => 'Pilih vendor terlebih dahulu.',
            'nomorNota.required' => 'Nomor nota wajib diisi.',
            'nomorNota.unique' => 'Nomor nota sudah terdaftar di sistem.',
            'tanggalPembelian.required' => 'Tanggal transaksi wajib diisi.',
            'purchaseCart.min' => 'Keranjang masih kosong, tambahkan minimal 1 barang.',
            'purchaseCart.*.gudang_id.required_if' => 'Pilih Tujuan Gudang untuk barang Diterima.',
            'metodePembayaran.required' => 'Pilih metode pembayaran.',
            'akunKasId.required_if' => 'Pilih Akun Kas untuk pembayaran tunai.',
            'jatuhTempo.required_if' => 'Tanggal jatuh tempo wajib diisi untuk transaksi kredit.',
            'compressedInvoice.required' => 'Bukti nota/invoice wajib diunggah.',
        ]);

        $subtotal = collect($this->purchaseCart)->sum(fn($i) => $i['qty'] * $i['harga']);
        $totalTagihan = $subtotal + (float) ($this->ongkir ?: 0) + (float) ($this->biayaLain ?: 0);
        $numericDP = (float) (str_replace('.', '', $this->jumlahDP) ?: 0);

        // Balance Validation
        if ($this->metodePembayaran === 'Cash' || ($this->metodePembayaran === 'Kredit' && $numericDP > 0)) {
            $akun = \App\Models\AkunKas::find($this->akunKasId);
            $requiredAmount = ($this->metodePembayaran === 'Cash') ? $totalTagihan : $numericDP;

            if ($akun && $akun->saldo_saat_ini < $requiredAmount) {
                $this->addError('akunKasId', 'Saldo kas tidak mencukupi (Tersedia: Rp' . number_format($akun->saldo_saat_ini, 0, ',', '.') . ').');
                return;
            }
        }

        DB::transaction(function () use ($subtotal, $totalTagihan, $numericDP) {
            $totalQty = collect($this->purchaseCart)->sum('qty');

            // Distribution of Landed Cost (Ongkir & Biaya Lain)
            $additionalCostPerItem = $totalQty > 0 ? (($this->ongkir ?: 0) + ($this->biayaLain ?: 0)) / $totalQty : 0;

            // Determine status
            $hasPO = collect($this->purchaseCart)->contains('status', 'PO');
            $hasPending = collect($this->purchaseCart)->contains('status', 'Pending');
            $overallStatus = ($hasPO || $hasPending) ? 'PO' : 'Received';

            // Determine payment status
            $statusPembayaran = ($this->metodePembayaran === 'Cash') ? 'Paid' : (($numericDP > 0) ? 'Partial' : 'Unpaid');

            $pembelian = Pembelian::create([
                'nomor_nota' => $this->nomorNota,
                'vendor_id' => $this->selectedVendor['id'],
                'total_harga' => $subtotal,
                'biaya_ongkir' => $this->ongkir ?: 0,
                'biaya_lain' => $this->biayaLain ?: 0,
                'metode_pembayaran' => $this->metodePembayaran,
                'status_pembayaran' => $statusPembayaran,
                'status' => $overallStatus,
                'tanggal' => $this->tanggalPembelian,
                'jatuh_tempo' => ($this->metodePembayaran === 'Kredit' && $this->jatuhTempo) ? $this->jatuhTempo : null,
                'user_id' => auth()->id(),
            ]);

            foreach ($this->purchaseCart as $item) {
                $qtyTerima = ($item['status'] === 'Received') ? $item['qty'] : 0;

                // Landed Cost: Base Price + Pro-rated additional costs
                $landedCost = $item['harga'] + $additionalCostPerItem;

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'barang_id' => $item['barang_id'],
                    'qty_pesan' => $item['qty'],
                    'qty_terima' => $qtyTerima,
                    'harga_beli' => $item['harga'], // Price from vendor
                    'gudang_id' => ($item['status'] === 'Received') ? $item['gudang_id'] : null,
                    'status_item' => $item['status'],
                    'catatan' => $item['catatan'],
                    'catatan_internal' => $item['catatan_internal'],
                ]);

                // Update Stok if received
                if ($qtyTerima > 0 && $item['gudang_id']) {
                    $stok = Stok::firstOrNew([
                        'barang_id' => $item['barang_id'],
                        'gudang_id' => $item['gudang_id']
                    ]);
                    $stok->jumlah = ($stok->exists ? $stok->jumlah : 0) + $qtyTerima;
                    $stok->save();

                    // Update Harga Beli Terakhir (using Landed Cost for accurate valuation)
                    HargaBeli::create([
                        'barang_id' => $item['barang_id'],
                        'harga' => $landedCost,
                        'user_id' => Auth::id() ?? 1,
                    ]);

                    // Log Stock Movement
                    \App\Models\StockMovement::record(
                        $item['barang_id'],
                        $item['gudang_id'],
                        'Masuk',
                        $qtyTerima,
                        'Pembelian',
                        $pembelian,
                        "Penerimaan barang langsung dari pembelian (Nota: {$pembelian->nomor_nota})"
                    );
                }
            }

            // Record Payment if Cash or Credit with DP
            $isCash = $this->metodePembayaran === 'Cash';
            $isCreditWithDP = $this->metodePembayaran === 'Kredit' && $numericDP > 0;

            if (($isCash || $isCreditWithDP) && $this->akunKasId) {
                $bayar = $isCash ? $totalTagihan : $numericDP;

                \App\Models\PembayaranPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'akun_kas_id' => $this->akunKasId,
                    'jumlah_bayar' => $bayar,
                    'tanggal_bayar' => $this->tanggalPembelian,
                    'catatan' => $isCash ? 'Pembayaran lunas saat transaksi.' : 'Pembayaran Uang Muka (DP).',
                    'user_id' => auth()->id(),
                ]);

                // Update Kas Balance
                $akun = \App\Models\AkunKas::find($this->akunKasId);
                if ($akun) {
                    $akun->decrement('saldo_saat_ini', $bayar);

                    // Record Mutasi Kas History
                    \App\Models\MutasiKas::create([
                        'akun_kas_id' => $this->akunKasId,
                        'user_id' => auth()->id(),
                        'tipe' => 'Keluar',
                        'kategori' => 'Pembelian',
                        'jumlah' => $bayar,
                        'tanggal' => $this->tanggalPembelian,
                        'keterangan' => ($isCash ? 'Pembelian Barang (Nota: ' : 'DP Pembelian Barang (Nota: ') . $pembelian->nomor_nota . ')',
                    ]);
                }
            }

            // Save Compressed Invoice (Base64 or normal upload via saveImage)
            if ($this->compressedInvoice) {
                $path = $this->saveImage($this->compressedInvoice, 'pembelian/dokumen');
                if ($path) {
                    \App\Models\DokumenPembelian::create([
                        'pembelian_id' => $pembelian->id,
                        'file_path' => $path,
                        'nama_file' => 'Nota Fisik ' . $pembelian->nomor_nota,
                    ]);
                }
            }
        });

        session()->flash('success', 'Transaksi Pro ' . $this->nomorNota . ' berhasil disimpan!');
        $this->dispatch('purchase-saved'); // Emit event for frontend to close modals gracefully
        $this->showPurchaseModal = false;
        $this->showConfirmPurchaseModal = false;
        $this->reset(['purchaseCart', 'selectedVendor', 'nomorNota', 'ongkir', 'biayaLain', 'metodePembayaran', 'jatuhTempo', 'akunKasId', 'jumlahDP', 'compressedInvoice']);
    }

    public function cancelPembelian($id)
    {
        // Team Permission Check
        if (!auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'cancel')) {
            $this->dispatch('error-retur', ['message' => 'Anda tidak memiliki akses untuk membatalkan transaksi.']);
            return;
        }

        DB::transaction(function () use ($id) {
            $pembelian = Pembelian::with(['details', 'pembayarans'])->findOrFail($id);

            if ($pembelian->status === 'Cancelled')
                return;

            // 1. Reverse Stock if Received
            if ($pembelian->status === 'Received') {
                foreach ($pembelian->details as $detail) {
                    if ($detail->qty_terima > 0 && $detail->gudang_id) {
                        $stok = Stok::where('barang_id', $detail->barang_id)
                            ->where('gudang_id', $detail->gudang_id)
                            ->first();

                        if ($stok) {
                            $stok->jumlah -= $detail->qty_terima;
                            $stok->save();

                            // Log Stock Movement (Reversal)
                            \App\Models\StockMovement::record(
                                $detail->barang_id,
                                $detail->gudang_id,
                                'Keluar',
                                $detail->qty_terima,
                                'Pembatalan',
                                $pembelian,
                                "Pembatalan transaksi pembelian (Nota: {$pembelian->nomor_nota})"
                            );
                        }
                    }
                }
            }

            // 2. Reverse Payments if any (Refund Money to Kas)
            foreach ($pembelian->pembayarans as $pembayaran) {
                $akun = \App\Models\AkunKas::find($pembayaran->akun_kas_id);
                if ($akun) {
                    $akun->increment('saldo_saat_ini', $pembayaran->jumlah_bayar);

                    // Record Mutasi Kas History (Refund)
                    \App\Models\MutasiKas::create([
                        'akun_kas_id' => $akun->id,
                        'user_id' => auth()->id(),
                        'tipe' => 'Masuk',
                        'kategori' => 'Pembatalan',
                        'jumlah' => $pembayaran->jumlah_bayar,
                        'tanggal' => now(),
                        'keterangan' => 'Refund Pembatalan Nota: ' . $pembelian->nomor_nota,
                    ]);
                }
                // Option: Delete payment record or mark as void. Here we'll delete it for transparency in balance.
                $pembayaran->delete();
            }

            $pembelian->status = 'Cancelled';
            $pembelian->status_pembayaran = 'Void';
            $pembelian->save();

            // Manual Log
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'team_id' => auth()->user()->current_team_id,
                'action' => 'CANCEL_PRO_INVOICE',
                'description' => 'Membatalkan Nota PRO ' . $pembelian->nomor_nota . '. Stok dan Saldo Kas telah dikoreksi.',
                'subject_type' => Pembelian::class,
                'subject_id' => $pembelian->id
            ]);
        });

        session()->flash('success', 'Transaksi PRO berhasil dibatalkan. Stok dan Saldo Kas telah dikoreksi.');
        $this->selectedPembelianId = $id; // Refresh detail
    }

    public function markAsReceived($id)
    {
        // RBAC Check: Only Admin and Logistik can receive items
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'logistik')) {
            session()->flash('error', 'Akses ditolak. Penerimaan barang hanya dapat dilakukan oleh Admin atau Staf Logistik / Gudang.');
            return;
        }

        DB::transaction(function () use ($id) {
            $pembelian = Pembelian::with(['details'])->findOrFail($id);

            if ($pembelian->status !== 'PO') {
                return;
            }

            foreach ($pembelian->details as $detail) {
                if ($detail->status_item !== 'Received') {
                    $sisa = $detail->qty_pesan - $detail->qty_terima;
                    if ($sisa > 0) {
                        // Use default warehouse if not specified
                        $gudangId = $detail->gudang_id ?? Gudang::first()->id;
                        $gudang = Gudang::find($gudangId);
                        $this->processReceiving($detail, $sisa, $gudangId);

                        // Add Individual Log for each item received mass-fully
                        \App\Models\ActivityLog::create([
                            'user_id' => auth()->id(),
                            'team_id' => auth()->user()->current_team_id,
                            'action' => 'RECEIVE_ITEM',
                            'description' => "Menerima $sisa unit {$detail->barang->nama} (Mass Receive) ke gudang " . ($gudang->nama ?? 'Unknown') . " (Nota: {$pembelian->nomor_nota})",
                            'subject_type' => PembelianDetail::class,
                            'subject_id' => $detail->id,
                            'properties' => [
                                'qty' => $sisa,
                                'gudang_id' => $gudangId,
                                'gudang_nama' => $gudang->nama ?? 'Unknown'
                            ]
                        ]);

                        // Log Stock Movement
                        \App\Models\StockMovement::record(
                            $detail->barang_id,
                            $gudangId,
                            'Masuk',
                            $sisa,
                            'Penerimaan',
                            $detail,
                            "Penerimaan barang massal untuk Nota: {$pembelian->nomor_nota}"
                        );
                    }
                }
            }

            $pembelian->status = 'Received';
            $pembelian->save();

            // Manual Log
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'team_id' => auth()->user()->current_team_id,
                'action' => 'RECEIVE_PO_ALL',
                'description' => 'Memproses penerimaan massal untuk Nota PO ' . $pembelian->nomor_nota . '.',
                'subject_type' => Pembelian::class,
                'subject_id' => $pembelian->id
            ]);
        });

        session()->flash('success', 'Barang PO berhasil diterima sepenuhnya!');
        $this->selectedPembelianId = $id;
    }

    public function receiveItem($detailId)
    {
        // RBAC Check: Only Admin and Logistik can receive items
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'logistik')) {
            session()->flash('error', 'Akses ditolak. Penerimaan barang hanya dapat dilakukan oleh Admin atau Staf Logistik.');
            return;
        }

        $qty = (int) ($this->qtyReceived[$detailId] ?? 0);
        $gudangId = $this->gudangReceived[$detailId] ?? null;

        if ($qty <= 0) {
            $this->dispatch('error-retur', ['message' => 'Jumlah yang diterima harus lebih dari 0.']);
            return;
        }

        if (!$gudangId) {
            $this->dispatch('error-retur', ['message' => 'Pilih gudang tujuan terlebih dahulu.']);
            return;
        }

        // Per-Gudang Access Check: logistik users can only receive to their assigned warehouses
        if ($user->hasTeamRole($user->currentTeam, 'logistik')) {
            $allowedGudangIds = $user->accessibleGudangIds();
            if (!in_array((int) $gudangId, $allowedGudangIds)) {
                $this->dispatch('error-retur', ['message' => 'Akses ditolak. Anda tidak memiliki akses ke gudang yang dipilih.']);
                return;
            }
        }

        DB::transaction(function () use ($detailId, $qty, $gudangId) {
            $detail = PembelianDetail::with(['pembelian', 'barang'])->findOrFail($detailId);
            $sisa = $detail->qty_pesan - $detail->qty_terima;

            if ($qty > $sisa) {
                throw new \Exception("Jumlah diterima ($qty) melebihi sisa pesanan ($sisa).");
            }

            $this->processReceiving($detail, $qty, $gudangId);

            // Update Overall Purchase Status
            $pembelian = $detail->pembelian;
            $allReceived = $pembelian->details()->where('status_item', '!=', 'Received')->count() === 0;

            if ($allReceived) {
                $pembelian->update(['status' => 'Received']);
            } else {
                $pembelian->update(['status' => 'PO']); // Remain in PO if partial
            }

            // Log
            $gudang = Gudang::find($gudangId);
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'team_id' => auth()->user()->current_team_id,
                'action' => 'RECEIVE_ITEM',
                'description' => "Menerima $qty unit {$detail->barang->nama} ke gudang " . ($gudang->nama ?? 'Unknown') . " (Nota: {$pembelian->nomor_nota})",
                'subject_type' => PembelianDetail::class,
                'subject_id' => $detail->id,
                'properties' => [
                    'qty' => $qty,
                    'gudang_id' => $gudangId,
                    'gudang_nama' => $gudang->nama ?? 'Unknown'
                ]
            ]);

            // Log Stock Movement
            \App\Models\StockMovement::record(
                $detail->barang_id,
                $gudangId,
                'Masuk',
                $qty,
                'Penerimaan',
                $detail,
                "Penerimaan barang individu untuk Nota: {$pembelian->nomor_nota}"
            );
        });

        $this->dispatch('success-retur', ['message' => 'Barang berhasil diterima dan stok telah diperbarui.']);
        $this->qtyReceived[$detailId] = 0;
    }

    protected function processReceiving($detail, $qty, $gudangId)
    {
        // 1. Update Detail
        $newQtyTerima = $detail->qty_terima + $qty;
        $newStatus = ($newQtyTerima >= $detail->qty_pesan) ? 'Received' : 'Partial';

        $detail->update([
            'qty_terima' => $newQtyTerima,
            'status_item' => $newStatus,
            'gudang_id' => $gudangId // Update last allocated warehouse
        ]);

        // 2. Add to Stock
        $stok = Stok::firstOrNew([
            'barang_id' => $detail->barang_id,
            'gudang_id' => $gudangId
        ]);
        $stok->jumlah = ($stok->exists ? $stok->jumlah : 0) + $qty;
        $stok->save();

        // 3. Update Harga Beli Terakhir
        HargaBeli::create([
            'barang_id' => $detail->barang_id,
            'harga' => $detail->harga_beli,
            'user_id' => Auth::id() ?? 1,
        ]);
    }

    public function askRetur($detailId)
    {
        // Check if user has logistik/admin roles to request
        if (!auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'logistik') && !auth()->user()->hasTeamRole(auth()->user()->currentTeam, 'admin')) {
            $this->dispatch('error-retur', ['message' => 'Anda tidak memiliki akses untuk mengajukan retur.']);
            return;
        }

        $qtyToReturn = (int) ($this->qtyRetur[$detailId] ?? 0);

        if ($qtyToReturn <= 0)
            return;

        $detail = PembelianDetail::findOrFail($detailId);

        // Cannot request more than what was received - already requested
        if ($qtyToReturn > ($detail->qty_terima - $detail->qty_retur_request)) {
            $this->dispatch('error-retur', ['message' => 'Jumlah pengajuan melebihi sisa barang yang bisa diretur.']);
            return;
        }

        $detail->qty_retur_request += $qtyToReturn;
        $detail->save();

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'team_id' => auth()->user()->current_team_id,
            'action' => 'ITEM_RETURN_REQUEST',
            'description' => 'Staf Gudang mengajukan retur sebanyak ' . $qtyToReturn . ' unit untuk ' . $detail->barang->nama . '.',
            'subject_type' => PembelianDetail::class,
            'subject_id' => $detail->id
        ]);

        $this->qtyRetur[$detailId] = 0;
        $this->dispatch('success-retur', ['message' => 'Pengajuan retur berhasil dikirim ke Admin.']);
    }

    public function submitRetur($detailId)
    {
        // Team Permission Check - ONLY ADMIN
        if (!auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'return')) {
            $this->dispatch('error-retur', ['message' => 'Hanya Admin yang dapat mengonfirmasi retur.']);
            return;
        }

        $qtyToReturnFromInput = (int) ($this->qtyRetur[$detailId] ?? 0);
        $detail = PembelianDetail::with('barang', 'pembelian.pembayarans')->findOrFail($detailId);

        // If Admin is approving a pending request, use the requested amount if input is 0. 
        // Otherwise, use input (direct admin return or override).
        $qtyToReturn = $qtyToReturnFromInput > 0 ? $qtyToReturnFromInput : $detail->qty_retur_request;

        if ($qtyToReturn <= 0) {
            $this->dispatch('error-retur', ['message' => 'Tidak ada pengajuan retur untuk diproses.']);
            return;
        }

        DB::transaction(function () use ($detail, $qtyToReturn, $detailId) {
            $pembelian = $detail->pembelian;

            if ($pembelian->status === 'Cancelled')
                return;

            if ($qtyToReturn > $detail->qty_terima) {
                $this->dispatch('error-retur', ['message' => 'Jumlah retur melebihi barang yang diterima.']);
                return;
            }

            // 1. Financial Reversal (Refund)
            if ($pembelian->status_pembayaran !== 'Unpaid') {
                $refundValue = $qtyToReturn * $detail->harga_beli;
                $pembayaranTerkait = $pembelian->pembayarans->last();
                $akunId = $pembayaranTerkait ? $pembayaranTerkait->akun_kas_id : $pembelian->akun_kas_id;

                if ($akunId) {
                    $akun = \App\Models\AkunKas::find($akunId);
                    if ($akun) {
                        $akun->increment('saldo_saat_ini', $refundValue);

                        \App\Models\MutasiKas::create([
                            'akun_kas_id' => $akun->id,
                            'user_id' => auth()->id(),
                            'tipe' => 'Masuk',
                            'kategori' => 'Retur',
                            'jumlah' => $refundValue,
                            'tanggal' => now(),
                            'keterangan' => 'Refund Retur: ' . $detail->barang->nama . ' (Nota: ' . $pembelian->nomor_nota . ')',
                        ]);
                    }
                }
            }

            // 2. Physical Reversal (Stock)
            if ($detail->gudang_id) {
                $stok = Stok::where('barang_id', $detail->barang_id)
                    ->where('gudang_id', $detail->gudang_id)
                    ->first();

                if ($stok) {
                    $stok->jumlah -= $qtyToReturn;
                    $stok->save();
                }
            }

            // 3. Update Detail & Header
            $detail->qty_terima -= $qtyToReturn;
            $detail->qty_pesan -= $qtyToReturn;

            // clear the request since it has been fulfilled
            $detail->qty_retur_request = 0;
            if ($detail->qty_retur_request < 0)
                $detail->qty_retur_request = 0;

            $detail->save();

            // Hitung ulang total harga nota
            $newTotal = PembelianDetail::where('pembelian_id', $pembelian->id)
                ->sum(DB::raw('qty_pesan * harga_beli'));

            $pembelian->total_harga = $newTotal;
            $pembelian->save();

            // 4. Logging
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'team_id' => auth()->user()->current_team_id,
                'action' => 'ITEM_RETURN_PRO',
                'description' => 'Retur PRO: ' . $qtyToReturn . ' unit ' . $detail->barang->nama . ' disetujui Admin. (Refund: Rp' . number_format($qtyToReturn * $detail->harga_beli, 0, ',', '.') . ').',
                'subject_type' => PembelianDetail::class,
                'subject_id' => $detail->id
            ]);

            // Log Stock Movement
            \App\Models\StockMovement::record(
                $detail->barang_id,
                $detail->gudang_id,
                'Keluar',
                $qtyToReturn,
                'Retur',
                $detail,
                "Retur barang untuk Nota: {$pembelian->nomor_nota}"
            );

            $this->qtyRetur[$detailId] = 0;
        });

        $this->dispatch('success-retur', ['message' => 'Barang berhasil diretur. Stok dan Saldo Kas telah diperbarui.']);
    }

    public function mount()
    {
        $this->provinces = $this->fetchWilayah('provinces');
    }

    public function updatedProvinceId($id)
    {
        $this->reset(['regency_id', 'district_id', 'village_id', 'regencies', 'districts', 'villages']);
        if ($id) {
            $this->regencies = $this->fetchWilayah('regencies', $id);
        }
    }

    public function updatedRegencyId($id)
    {
        $this->reset(['district_id', 'village_id', 'districts', 'villages']);
        if ($id) {
            $this->districts = $this->fetchWilayah('districts', $id);
        }
    }

    public function updatedDistrictId($id)
    {
        $this->reset(['village_id', 'villages']);
        if ($id) {
            $this->villages = $this->fetchWilayah('villages', $id);
        }
    }

    public function updatedSubKategoriId($value)
    {
        if (!$value) {
            $this->skuBarang = '';
            return;
        }

        $sub = SubKategori::with('kategori')->find($value);
        if (!$sub)
            return;

        $prefix = ($sub->kategori->kode ?? '') . '-' . ($sub->kode ?? '');

        // Find the last number for this prefix
        $lastBarang = Barang::where('sku', 'like', $prefix . '-%')
            ->orderBy('sku', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastBarang) {
            $lastSku = $lastBarang->sku;
            $parts = explode('-', $lastSku);
            $lastNumber = (int) end($parts);
            $nextNumber = $lastNumber + 1;
        }

        $this->skuBarang = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function storeVendor()
    {
        // RBAC Check: Only Admin and Purchasing (Editor) can manage Vendors
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'editor')) {
            session()->flash('error', 'Akses ditolak. Anda tidak memiliki hak untuk menambah atau mengubah data vendor.');
            return;
        }

        $rules = [
            'namaVendor' => 'required|min:3',
            'kontakVendor' => 'nullable',
            'province_id' => 'nullable',
            'regency_id' => 'nullable',
            'district_id' => 'nullable',
            'village_id' => 'nullable',
            'alamatVendor' => 'nullable|min:3',
            'gambarVendor' => 'nullable',
        ];

        if ($this->gambarVendor && !is_string($this->gambarVendor)) {
            $rules['gambarVendor'] = 'image|max:2048';
        }

        $this->validate($rules);

        $gambarPath = $this->saveImage($this->gambarVendor, 'vendors');

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

        // Save this BEFORE reset() destroys it
        $wasInPurchaseModal = $this->showPurchaseModal;

        $this->reset(['namaVendor', 'kontakVendor', 'province_id', 'regency_id', 'district_id', 'village_id', 'alamatVendor', 'tagVendor', 'gambarVendor', 'showModalVendor', 'regencies', 'districts', 'villages']);

        // If opened from purchase modal, auto select newly created vendor
        if ($wasInPurchaseModal) {
            $this->showPurchaseModal = true;
            $this->selectVendor($vendor->id, $vendor->nama);
        }

        session()->flash('success', 'Vendor ' . $vendor->nama . ' berhasil ditambahkan!');
        $this->dispatch('close-modal', modalId: 'modal-vendor');
    }

    public function storeGudang()
    {
        // RBAC Check: Only Admin can create/edit Gudangs
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin')) {
            session()->flash('error', 'Akses ditolak. Hanya Administrator yang dapat mengelola daftar Gudang.');
            return;
        }

        $rules = [
            'namaGudang' => 'required|min:2|unique:gudangs,nama',
            'gambarGudang' => 'nullable',
        ];

        if ($this->gambarGudang && !is_string($this->gambarGudang)) {
            $rules['gambarGudang'] = 'image|max:2048';
        }

        $this->validate($rules);

        $gambarPath = $this->saveImage($this->gambarGudang, 'gudangs');

        Gudang::create([
            'nama' => $this->namaGudang,
            'lokasi' => $this->lokasiGudang,
            'deskripsi' => $this->deskripsiGudang,
            'gambar' => $gambarPath,
        ]);

        $this->reset(['namaGudang', 'lokasiGudang', 'deskripsiGudang', 'gambarGudang', 'showModalGudang']);
        $this->dispatch('close-modal', modalId: 'modal-gudang');
        session()->flash('success', 'Gudang baru berhasil ditambahkan!');
    }



    #[Computed]
    public function stockMovements()
    {
        $query = \App\Models\StockMovement::with(['barang', 'gudang', 'user']);

        if ($this->searchStockLog) {
            $query->whereHas('barang', function ($q) {
                $q->where('nama', 'like', '%' . $this->searchStockLog . '%')
                    ->orWhere('sku', 'like', '%' . $this->searchStockLog . '%');
            });
        }

        if ($this->filterGudangLog) {
            $query->where('gudang_id', $this->filterGudangLog);
        }

        return $query->latest()->paginate($this->stockLogPerPage);
    }

    public function loadMoreStockLog()
    {
        $this->stockLogPerPage += 20;
    }

    public function openStockLogModal()
    {
        $this->showStockLogModal = true;
    }

    // --- STOCK TRANSFER LOGIC ---

    public function openTransferModal()
    {
        $this->reset(['gudangAsalId', 'gudangTujuanId', 'keteranganTransfer', 'transferItems', 'searchTransferItem', 'showSuggestions']);
        $this->tanggalTransfer = date('Y-m-d');
        $this->showTransferModal = true;
    }

    #[Computed]
    public function searchBarangs()
    {
        if (strlen($this->searchTransferItem) < 2) {
            $this->showSuggestions = false;
            return collect();
        }

        $this->showSuggestions = true;
        return \App\Models\Barang::where('nama', 'like', '%' . $this->searchTransferItem . '%')
            ->orWhere('sku', 'like', '%' . $this->searchTransferItem . '%')
            ->limit(10)
            ->get();
    }

    public function addToTransferCart($barangId)
    {
        if (!$this->gudangAsalId) {
            session()->flash('error_transfer', 'Pilih Gudang Asal terlebih dahulu.');
            return;
        }

        // Check if already in cart
        if (collect($this->transferItems)->contains('barang_id', $barangId)) {
            return;
        }

        $barang = \App\Models\Barang::find($barangId);
        $stokAsal = \App\Models\Stok::where('barang_id', $barangId)
            ->where('gudang_id', $this->gudangAsalId)
            ->first();

        $jumlahStok = $stokAsal ? $stokAsal->jumlah : 0;

        if ($jumlahStok <= 0) {
            session()->flash('error_transfer', 'Stok barang ini kosong di gudang asal.');
            return;
        }

        $this->transferItems[] = [
            'barang_id' => $barangId,
            'nama' => $barang->nama,
            'sku' => $barang->sku,
            'stok_asal' => $jumlahStok,
            'qty' => 1
        ];

        $this->searchTransferItem = '';
        $this->showSuggestions = false;
    }

    public function removeFromTransferCart($index)
    {
        unset($this->transferItems[$index]);
        $this->transferItems = array_values($this->transferItems);
    }

    public function saveTransfer()
    {
        $this->validate([
            'gudangAsalId' => 'required',
            'gudangTujuanId' => 'required|different:gudangAsalId',
            'tanggalTransfer' => 'required|date',
            'transferItems' => 'required|array|min:1',
            'transferItems.*.qty' => 'required|integer|min:1'
        ], [
            'gudangTujuanId.different' => 'Gudang tujuan tidak boleh sama dengan gudang asal.',
            'transferItems.required' => 'Pilih minimal satu barang untuk ditransfer.'
        ]);

        // Final stock check
        foreach ($this->transferItems as $item) {
            $stok = \App\Models\Stok::where('barang_id', $item['barang_id'])
                ->where('gudang_id', $this->gudangAsalId)
                ->first();

            if (!$stok || $stok->jumlah < $item['qty']) {
                session()->flash('error_transfer', "Stok {$item['nama']} tidak mencukupi di gudang asal.");
                return;
            }
        }

        \DB::transaction(function () {
            $nomor = 'TRF-' . date('Ymd') . '-' . strtoupper(str()->random(5));

            $transfer = \App\Models\StockTransfer::create([
                'nomor_transfer' => $nomor,
                'gudang_asal_id' => $this->gudangAsalId,
                'gudang_tujuan_id' => $this->gudangTujuanId,
                'user_id' => auth()->id(),
                'team_id' => auth()->user()->current_team_id,
                'tanggal' => $this->tanggalTransfer,
                'status' => 'pending',
                'keterangan' => $this->keteranganTransfer,
                'total_qty' => collect($this->transferItems)->sum('qty')
            ]);

            foreach ($this->transferItems as $item) {
                // Save detail
                $transfer->details()->create([
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['qty']
                ]);

                // Update Stok Asal (Decrease)
                $stokAsal = \App\Models\Stok::where('barang_id', $item['barang_id'])
                    ->where('gudang_id', $this->gudangAsalId)
                    ->first();
                $stokAsal->decrement('jumlah', $item['qty']);

                // Record Stock Movement - Only OUT from Source
                \App\Models\StockMovement::record(
                    $item['barang_id'],
                    $this->gudangAsalId,
                    'Keluar',
                    $item['qty'],
                    'Transfer Stok (Keluar)',
                    $transfer,
                    "Transfer ke " . $transfer->gudangTujuan->nama . " (#{$nomor}) - Status: Pending"
                );
            }
        });

        $this->showTransferModal = false;
        $this->reset(['transferItems', 'gudangAsalId', 'gudangTujuanId', 'keteranganTransfer']);
        session()->flash('success', 'Transfer stok berhasil diproses!');
    }

    public function openPelunasanModal($id)
    {
        $this->pembelianIdBayar = $id;
        $pembelian = Pembelian::find($id);
        if ($pembelian) {
            $this->jumlahBayarPelunasan = $pembelian->sisa_tagihan;
            $this->selectedAkunKasIdPelunasan = AkunKas::first()->id ?? '';
            $this->showPaymentModal = true;
        }
    }

    public function processPelunasan()
    {
        $this->validate([
            'jumlahBayarPelunasan' => 'required|numeric|min:1',
            'selectedAkunKasIdPelunasan' => 'required|exists:akun_kas,id',
            'buktiPembayaranPelunasan' => 'required',
        ]);

        $pembelian = Pembelian::findOrFail($this->pembelianIdBayar);
        $akunKas = AkunKas::findOrFail($this->selectedAkunKasIdPelunasan);

        if ($this->jumlahBayarPelunasan > $pembelian->sisa_tagihan) {
            $this->addError('jumlahBayarPelunasan', 'Jumlah bayar tidak boleh melebihi sisa tagihan.');
            return;
        }

        if ($this->jumlahBayarPelunasan > $akunKas->saldo_saat_ini) {
            $this->addError('jumlahBayarPelunasan', 'Saldo kas tidak mencukupi.');
            return;
        }

        \DB::transaction(function () use ($pembelian, $akunKas) {
            $path = $this->saveImage($this->buktiPembayaranPelunasan, 'bukti_pembayaran');

            // 1. Catat Pembayaran
            \App\Models\PembayaranPembelian::create([
                'pembelian_id' => $pembelian->id,
                'akun_kas_id' => $akunKas->id,
                'jumlah_bayar' => $this->jumlahBayarPelunasan,
                'tanggal_bayar' => now(),
                'metode_bayar' => 'Cash',
                'keterangan' => 'Pelunasan Hutang (via Inventory) Nota #' . $pembelian->nomor_nota,
                'bukti_pembayaran' => $path,
                'user_id' => auth()->id(),
            ]);

            // 2. Potong Saldo Akun Kas
            $akunKas->decrement('saldo_saat_ini', $this->jumlahBayarPelunasan);

            // 3. Catat Mutasi Kas
            \App\Models\MutasiKas::create([
                'akun_kas_id' => $akunKas->id,
                'user_id' => auth()->id(),
                'tanggal' => now(),
                'tipe' => 'Keluar',
                'kategori' => 'Pelunasan Hutang',
                'jumlah' => $this->jumlahBayarPelunasan,
                'keterangan' => 'Pelunasan Hutang ke ' . ($pembelian->vendor->nama ?? 'Vendor') . ' (Nota #' . $pembelian->nomor_nota . ')',
            ]);

            // 4. Update status pembayaran jika sudah lunas
            if (($pembelian->terbayar + $this->jumlahBayarPelunasan) >= ($pembelian->total_harga + $pembelian->biaya_ongkir + $pembelian->biaya_lain)) {
                $pembelian->update(['status_pembayaran' => 'Lunas']);
            } else {
                $pembelian->update(['status_pembayaran' => 'Dibayar Sebagian']);
            }
        });

        $this->showPaymentModal = false;
        $this->reset(['jumlahBayarPelunasan', 'pembelianIdBayar']);

        $this->dispatch('swal:success', [
            'title' => 'Pembayaran Berhasil',
            'text' => 'Pelunasan hutang telah dicatat.',
        ]);
    }
}
