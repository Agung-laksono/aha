<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Stok;
use App\Models\StockTransfer;
use App\Models\StockTransferDetail;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class MutasiStok extends Component
{
    use WithFileUploads, WithPagination;

    // Filter & Search
    #[Url(history: true)]
    public $searchTransfer = '';

    // Tab Status
    public $currentTab = 'all'; // all, pending, received

    // Form Properties (Transfer Baru)
    public $showTransferModal = false;
    public $gudangAsalId = null;
    public $gudangTujuanId = null;
    public $tanggalTransfer = '';
    public $keteranganTransfer = '';
    public $transferItems = []; // [{barang_id, nama, sku, stok_asal, qty}]
    public $searchTransferItem = '';
    public $showSuggestions = false;

    // Receive Properties
    public $showReceiveModal = false;
    public $showDetailModal = false;
    public $selectedTransferId = null;
    public $selectedTransfer = null;
    public $fotoBukti = null;
    public $keteranganPenerima = '';

    protected $listeners = ['refreshMutasi' => '$refresh'];

    public function mount()
    {
        $this->tanggalTransfer = now()->format('Y-m-d');

        // Auto-select first warehouse if admin or only one available
        $accessibleGudangs = $this->getAccessibleGudangs();
        if ($accessibleGudangs->count() === 1) {
            $this->gudangAsalId = $accessibleGudangs->first()->id;
        }
    }

    public function updatedGudangAsalId()
    {
        if (count($this->transferItems) > 0) {
            $this->reset(['transferItems', 'searchTransferItem', 'showSuggestions']);
            session()->flash('error', 'Keranjang dikosongkan karena Gudang Asal berubah.');
        }
    }

    public function updatedSearchTransferItem()
    {
        $this->showSuggestions = !empty($this->searchTransferItem);
    }

    public function addToTransferCart($barangId)
    {
        if (!$this->gudangAsalId) {
            session()->flash('error', 'Pilih Gudang Asal terlebih dahulu.');
            return;
        }

        $barang = Barang::findOrFail($barangId);

        // Check if already in cart
        foreach ($this->transferItems as $item) {
            if ($item['barang_id'] == $barangId) {
                session()->flash('error', 'Barang sudah ada di daftar.');
                return;
            }
        }

        $stok = Stok::where('barang_id', $barangId)
            ->where('gudang_id', $this->gudangAsalId)
            ->first();

        if (!$stok || $stok->jumlah <= 0) {
            session()->flash('error', 'Stok barang di gudang asal habis.');
            return;
        }

        $this->transferItems[] = [
            'barang_id' => $barang->id,
            'nama' => $barang->nama,
            'sku' => $barang->sku,
            'stok_asal' => $stok->jumlah,
            'qty' => 1
        ];

        $this->reset(['searchTransferItem', 'showSuggestions']);
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
        ]);

        // Stock check
        foreach ($this->transferItems as $index => $item) {
            $stok = Stok::where('barang_id', $item['barang_id'])
                ->where('gudang_id', $this->gudangAsalId)
                ->first();

            if (!$stok || $stok->jumlah < $item['qty']) {
                $this->addError("transferItems.{$index}.qty", "Stok kritis! (Tersedia: " . ($stok ? $stok->jumlah : 0) . ")");
                return;
            }
        }

        DB::transaction(function () {
            $nomorTransfer = 'MUT-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

            $totalQty = collect($this->transferItems)->sum('qty');

            $transfer = StockTransfer::create([
                'nomor_transfer' => $nomorTransfer,
                'gudang_asal_id' => $this->gudangAsalId,
                'gudang_tujuan_id' => $this->gudangTujuanId,
                'user_id' => Auth::id(),
                'team_id' => Auth::user()->current_team_id,
                'tanggal' => $this->tanggalTransfer,
                'status' => 'pending',
                'keterangan' => $this->keteranganTransfer,
                'total_qty' => $totalQty
            ]);

            foreach ($this->transferItems as $item) {
                StockTransferDetail::create([
                    'stock_transfer_id' => $transfer->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['qty']
                ]);

                // Update Stok Asal (Decrease)
                $stokAsal = Stok::where('barang_id', $item['barang_id'])
                    ->where('gudang_id', $this->gudangAsalId)
                    ->first();
                $stokAsal->decrement('jumlah', $item['qty']);

                // Record Movement OUT
                StockMovement::record(
                    $item['barang_id'],
                    $this->gudangAsalId,
                    'Keluar',
                    $item['qty'],
                    'Mutasi Stok (Keluar)',
                    $transfer,
                    "Dikirim ke " . $transfer->gudangTujuan->nama . " (#{$nomorTransfer})"
                );
            }
        });

        session()->flash('success', 'Mutasi stok berhasil diproses!');
        $this->reset(['showTransferModal', 'transferItems', 'keteranganTransfer']);
    }

    public function showDetail($id)
    {
        $this->selectedTransfer = StockTransfer::with(['gudangAsal', 'gudangTujuan', 'details.barang', 'user'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function selectForReceive($id)
    {
        $this->selectedTransferId = $id;
        $this->showReceiveModal = true;
        $this->reset(['fotoBukti', 'keteranganPenerima']);
    }

    public function confirmReceive()
    {
        if (!$this->selectedTransferId)
            return;

        $transfer = StockTransfer::with(['details', 'gudangAsal'])->findOrFail($this->selectedTransferId);

        if ($transfer->status !== 'pending') {
            session()->flash('error', 'Status mutasi sudah berubah.');
            return;
        }

        DB::transaction(function () use ($transfer) {
            $path = null;
            if ($this->fotoBukti) {
                $path = $this->saveImage($this->fotoBukti, 'foto_mutasi');
            }

            $newKeterangan = $transfer->keterangan;
            if ($this->keteranganPenerima) {
                $newKeterangan .= ($newKeterangan ? "\n" : "") . "[Penerima]: " . $this->keteranganPenerima;
            }

            $transfer->update([
                'status' => 'received',
                'received_at' => now(),
                'foto_bukti' => $path,
                'keterangan' => $newKeterangan
            ]);

            foreach ($transfer->details as $detail) {
                $stokTujuan = Stok::firstOrCreate(
                    ['barang_id' => $detail->barang_id, 'gudang_id' => $transfer->gudang_tujuan_id],
                    ['jumlah' => 0]
                );
                $stokTujuan->increment('jumlah', $detail->jumlah);

                StockMovement::record(
                    $detail->barang_id,
                    $transfer->gudang_tujuan_id,
                    'Masuk',
                    $detail->jumlah,
                    'Mutasi Stok (Masuk)',
                    $transfer,
                    "Diterima dari " . $transfer->gudangAsal->nama . " (#{$transfer->nomor_transfer})"
                );
            }
        });

        session()->flash('success', "Stok mutasi #{$transfer->nomor_transfer} berhasil diterima!");
        $this->reset(['showReceiveModal', 'selectedTransferId', 'fotoBukti', 'keteranganPenerima']);
    }

    public function updateCroppedImage($index, $base64Data, $property)
    {
        if ($property === 'fotoBukti') {
            $this->fotoBukti = $base64Data;
        }
    }

    protected function saveImage($base64, $folder)
    {
        if (str_starts_with($base64, 'data:image')) {
            $image_parts = explode(";base64,", $base64);
            $image_base64 = base64_decode($image_parts[1]);
            $filename = $folder . '/' . uniqid() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $image_base64);
            return $filename;
        }
        return null;
    }

    public function downloadSuratJalan($transferId)
    {
        $url = \Illuminate\Support\Facades\URL::signedRoute('print-transfer', ['id' => $transferId]);
        $this->dispatch('open-new-tab', url: $url);
    }

    protected function getAccessibleGudangs()
    {
        $user = auth()->user();
        if ($user->hasTeamRole($user->currentTeam, 'admin')) {
            return Gudang::all();
        }
        return Gudang::whereIn('id', $user->accessibleGudangIds())->get();
    }

    #[Computed]
    public function transfers()
    {
        $user = auth()->user();
        $isAdmin = $user->hasTeamRole($user->currentTeam, 'admin');
        $accessibleIds = $user->accessibleGudangIds();

        return StockTransfer::with(['gudangAsal', 'gudangTujuan', 'details.barang', 'user'])
            ->when(!$isAdmin, function ($query) use ($accessibleIds) {
                $query->where(function ($q) use ($accessibleIds) {
                    $q->whereIn('gudang_asal_id', $accessibleIds)
                        ->orWhereIn('gudang_tujuan_id', $accessibleIds);
                });
            })
            ->when($this->currentTab !== 'all', fn($q) => $q->where('status', $this->currentTab))
            ->when($this->searchTransfer, function ($query) {
                $query->where('nomor_transfer', 'like', '%' . $this->searchTransfer . '%')
                    ->orWhereHas('gudangAsal', fn($q) => $q->where('nama', 'like', '%' . $this->searchTransfer . '%'))
                    ->orWhereHas('gudangTujuan', fn($q) => $q->where('nama', 'like', '%' . $this->searchTransfer . '%'));
            })
            ->latest()
            ->paginate(15);
    }

    #[Computed]
    public function searchBarangResults()
    {
        return $this->searchTransferItem
            ? Barang::where('nama', 'like', '%' . $this->searchTransferItem . '%')
                ->orWhere('sku', 'like', '%' . $this->searchTransferItem . '%')
                ->take(5)->get()
            : collect();
    }

    public function render()
    {
        return view('livewire.mutasi-stok', [
            'gudangs' => Gudang::all(),
            'accessibleGudangs' => $this->getAccessibleGudangs(),
        ])->layout('layouts.app');
    }
}
