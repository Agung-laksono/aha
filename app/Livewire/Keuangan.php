<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pembelian;
use App\Models\AkunKas;
use App\Models\PembayaranPembelian;
use App\Models\MutasiKas;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Keuangan extends Component
{
    use WithFileUploads;

    public $searchHutang = '';
    public $selectedAkunKasId;
    public $jumlahBayar;
    public $pembelianId;
    public $buktiPembayaran;
    public $showPaymentModal = false;

    // Mutasi Kas Properties (Migrated from Inventory)
    public $showModalKas = false;
    public $tipeKas = 'Masuk'; // Masuk or Keluar
    public $jumlahKas = 0;
    public $kategoriKas = '';
    public $tanggalKas = '';
    public $keteranganKas = '';

    // Akun Kas Form (Migrated from Inventory)
    public $namaAkunKas = '';
    public $kodeAkunKas = '';
    public $saldoAwal = 0;
    public $pjUserKasId = null;

    // Untuk modal konfirmasi
    public $selectedPembelian;

    // Tab Management
    public $activeTab = 'hutang'; // hutang, mutasi, transfer
    public $searchMutasi = '';
    public $filterMutasi = 'semua'; // semua, Masuk, Keluar

    // Audit Features
    public $auditMode = false;
    public $hanyaTanpaBukti = false;
    public $minNominalAudit = 0;
    public $fotoMutasi;

    // Daily Cash Closing
    public $showModalTutupKas = false;
    public $selectedAkunTutupId, $saldoFisik, $fotoBuktiFisik, $keteranganTutup;
    public $limitLogs = 20;

    // Form Transfer Kas
    public $showModalTransfer = false;
    public $transferPengirimId, $transferPenerimaId, $transferJumlah, $transferKeterangan, $transferFoto;

    // Reject Transfer
    public $showModalReject = false;
    public $transferRejectId;
    public $alasanPenolakan;

    // Filters Audit Log
    public $filterLogSearch, $filterLogUser, $filterLogStartDate, $filterLogEndDate;
    // Filters Transfer Kas
    public $filterTransferSearch, $filterTransferUser, $filterTransferStartDate, $filterTransferEndDate;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function mount()
    {
        $user = auth()->user();
        $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');

        if (!$isAdminOrFinance) {
            $this->activeTab = 'mutasi'; // Default tab untuk non-admin
        }

        $firstAkun = $this->akunKas->first();
        if ($firstAkun) {
            $this->selectedAkunKasId = $firstAkun->id;
        }

        $this->tanggalKas = now()->format('Y-m-d');
    }

    #[Computed]
    public function daftarHutang()
    {
        return Pembelian::with(['vendor', 'pembayarans'])
            ->where('metode_pembayaran', 'Kredit')
            ->where('status', '!=', 'Cancelled')
            ->get()
            ->filter(function ($p) {
                return $p->sisa_tagihan > 0;
            })
            ->filter(function ($p) {
                if (!$this->searchHutang)
                    return true;
                return str_contains(strtolower($p->nomor_nota), strtolower($this->searchHutang)) ||
                    str_contains(strtolower($p->vendor->nama ?? ''), strtolower($this->searchHutang));
            });
    }

    #[Computed]
    public function akunKas()
    {
        $user = auth()->user();
        if ($user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance')) {
            return AkunKas::with('user')->get();
        }
        return AkunKas::where('user_id', $user->id)->with('user')->get();
    }

    #[Computed]
    public function daftarMutasi()
    {
        $tipe = $this->filterMutasi === 'semua' ? null : $this->filterMutasi;
        return $this->queryMutasi($tipe)->paginate(10);
    }

    #[Computed]
    public function daftarLogs()
    {
        $query = \App\Models\ActivityLog::with(['user'])
            ->whereIn('subject_type', [
                \App\Models\MutasiKas::class,
                \App\Models\TransferKas::class,
                \App\Models\AkunKas::class,
                \App\Models\PenutupanKas::class,
                \App\Models\PembayaranPembelian::class
            ])
            ->where('team_id', auth()->user()->currentTeam->id);

        if ($this->filterLogSearch) {
            $query->where('description', 'like', '%' . $this->filterLogSearch . '%');
        }

        if ($this->filterLogUser) {
            $query->where('user_id', $this->filterLogUser);
        }

        if ($this->filterLogStartDate) {
            $query->whereDate('created_at', '>=', $this->filterLogStartDate);
        }

        if ($this->filterLogEndDate) {
            $query->whereDate('created_at', '<=', $this->filterLogEndDate);
        }

        return $query->latest()
            ->take($this->limitLogs)
            ->get();
    }

    public function resetLogFilters()
    {
        $this->reset(['filterLogSearch', 'filterLogUser', 'filterLogStartDate', 'filterLogEndDate']);
        $this->limitLogs = 20;
    }

    public function loadMoreLogs()
    {
        $this->limitLogs += 20;
    }

    #[Computed]
    public function totalHutang()
    {
        return $this->daftarHutang()->sum(fn($p) => $p->sisa_tagihan);
    }

    #[Computed]
    public function totalKas()
    {
        return AkunKas::sum('saldo_saat_ini');
    }

    public function openPaymentModal($id)
    {
        $this->pembelianId = $id;
        $this->selectedPembelian = Pembelian::find($id);
        $this->jumlahBayar = $this->selectedPembelian->sisa_tagihan;
        $this->showPaymentModal = true;
    }

    public function processPayment()
    {
        $this->validate([
            'jumlahBayar' => 'required|numeric|min:1',
            'selectedAkunKasId' => 'required|exists:akun_kas,id',
            'buktiPembayaran' => 'required',
        ]);

        $pembelian = Pembelian::findOrFail($this->pembelianId);
        $akunKas = AkunKas::findOrFail($this->selectedAkunKasId);

        if ($this->jumlahBayar > $pembelian->sisa_tagihan) {
            $this->addError('jumlahBayar', 'Jumlah bayar tidak boleh melebihi sisa tagihan.');
            return;
        }

        if ($this->jumlahBayar > $akunKas->saldo_saat_ini) {
            $this->addError('jumlahBayar', 'Saldo kas tidak mencukupi.');
            return;
        }

        DB::transaction(function () use ($pembelian, $akunKas) {
            $path = $this->saveImage($this->buktiPembayaran, 'bukti_pembayaran');

            // 1. Catat Pembayaran
            PembayaranPembelian::create([
                'pembelian_id' => $pembelian->id,
                'akun_kas_id' => $akunKas->id,
                'jumlah_bayar' => $this->jumlahBayar,
                'tanggal_bayar' => now(),
                'metode_bayar' => 'Cash', // Pelunasan biasanya cash dari kas terpilih
                'user_id' => auth()->id(),
                'keterangan' => 'Pelunasan Hutang Nota #' . $pembelian->nomor_nota,
                'bukti_pembayaran' => $path,
            ]);

            // 2. Potong Saldo Akun Kas
            $akunKas->decrement('saldo_saat_ini', $this->jumlahBayar);

            // 3. Catat Mutasi Kas
            MutasiKas::create([
                'akun_kas_id' => $akunKas->id,
                'user_id' => auth()->id(),
                'tanggal' => now(),
                'tipe' => 'Keluar',
                'kategori' => 'Pelunasan Hutang',
                'jumlah' => $this->jumlahBayar,
                'keterangan' => 'Pelunasan Hutang ke ' . ($pembelian->vendor->nama ?? 'Vendor') . ' (Nota #' . $pembelian->nomor_nota . ')',
            ]);

            // 4. Update status pembayaran jika sudah lunas
            if (($pembelian->terbayar + $this->jumlahBayar) >= ($pembelian->total_harga + $pembelian->biaya_ongkir + $pembelian->biaya_lain)) {
                $pembelian->update(['status_pembayaran' => 'Lunas']);
            } else {
                $pembelian->update(['status_pembayaran' => 'Dibayar Sebagian']);
            }
        });

        $this->showPaymentModal = false;
        $this->reset(['jumlahBayar', 'pembelianId', 'selectedPembelian']);

        $this->dispatch('swal:success', [
            'title' => 'Pembayaran Berhasil',
            'text' => 'Pelunasan hutang telah dicatat.',
        ]);
    }

    public function updateCroppedImage($index, $base64Data, $target = 'buktiPembayaran')
    {
        if ($target === 'buktiPembayaran') {
            $this->buktiPembayaran = $base64Data;
        } elseif ($target === 'fotoBuktiFisik') {
            $this->fotoBuktiFisik = $base64Data;
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

    #[Computed]
    public function mutasiKasList()
    {
        if (!$this->selectedAkunKasId)
            return collect();
        return MutasiKas::where('akun_kas_id', $this->selectedAkunKasId)
            ->with('user')
            ->latest()
            ->take(20)
            ->get();
    }
    #[Computed]
    public function daftarTransferKas()
    {
        $user = auth()->user();
        $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');

        $query = \App\Models\TransferKas::with(['pengirimAkun', 'penerimaAkun', 'pengirimUser', 'penerimaUser'])->latest();

        if (!$isAdminOrFinance) {
            $allowedAkunIds = $this->akunKas->pluck('id')->toArray();
            $query->where(function ($q) use ($allowedAkunIds) {
                $q->whereIn('pengirim_akun_id', $allowedAkunIds)
                    ->orWhereIn('penerima_akun_id', $allowedAkunIds);
            });
        }

        if ($this->filterTransferSearch) {
            $query->where('keterangan', 'like', '%' . $this->filterTransferSearch . '%');
        }

        if ($this->filterTransferUser) {
            $query->where(function ($q) {
                $q->where('pengirim_user_id', $this->filterTransferUser)
                    ->orWhere('penerima_user_id', $this->filterTransferUser);
            });
        }

        if ($this->filterTransferStartDate) {
            $query->whereDate('created_at', '>=', $this->filterTransferStartDate);
        }

        if ($this->filterTransferEndDate) {
            $query->whereDate('created_at', '<=', $this->filterTransferEndDate);
        }

        return $query->get();
    }

    public function resetTransferFilters()
    {
        $this->reset(['filterTransferSearch', 'filterTransferUser', 'filterTransferStartDate', 'filterTransferEndDate']);
    }

    #[Computed]
    public function allUsers()
    {
        return auth()->user()->currentTeam->allUsers();
    }

    #[Computed]
    public function floatingBalance()
    {
        return \App\Models\TransferKas::where('status', 'pending')->sum('jumlah');
    }

    #[Computed]
    public function pendingTransferCount()
    {
        $user = auth()->user();
        $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');

        $query = \App\Models\TransferKas::where('status', 'pending');

        if (!$isAdminOrFinance) {
            $allowedAkunIds = $this->akunKas->pluck('id')->toArray();
            $query->whereIn('penerima_akun_id', $allowedAkunIds);
        }

        return $query->count();
    }

    private function queryMutasi($tipe = null)
    {
        $user = auth()->user();
        $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');

        $query = MutasiKas::with(['user', 'akunKas'])->latest();

        // RBAC: Hanya lihat mutasi milik akun kas yang diizinkan
        if (!$isAdminOrFinance) {
            $allowedAkunIds = $this->akunKas->pluck('id')->toArray();
            $query->whereIn('akun_kas_id', $allowedAkunIds);
        }

        if ($tipe && $tipe !== 'semua') {
            $query->where('tipe', $tipe);
        }

        // Audit Filters
        if ($this->hanyaTanpaBukti) {
            $query->whereNull('foto_bukti');
        }

        if ($this->minNominalAudit > 0) {
            $query->where('jumlah', '>=', $this->minNominalAudit);
        }

        if ($this->searchMutasi) {
            $query->where(function ($q) {
                $q->where('kategori', 'like', '%' . $this->searchMutasi . '%')
                    ->orWhere('keterangan', 'like', '%' . $this->searchMutasi . '%')
                    ->orWhereHas('akunKas', function ($ak) {
                        $ak->where('nama', 'like', '%' . $this->searchMutasi . '%');
                    })
                    ->orWhereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->searchMutasi . '%');
                    });
            });
        }

        return $query;
    }

    public function openModalKas($akunId)
    {
        $this->selectedAkunKasId = $akunId;
        $this->tanggalKas = date('Y-m-d');
        $this->reset(['jumlahKas', 'kategoriKas', 'keteranganKas']);
        $this->tipeKas = 'Masuk';
        $this->showModalKas = true;
    }

    public function storeMutasiKas()
    {
        // RBAC Check: Only Admin and Finance can process Mutasi Kas
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'finance')) {
            $this->dispatch('swal:error', [
                'title' => 'Akses Ditolak',
                'text' => 'Hanya Admin dan Staff Keuangan yang dapat mengelola kas.',
            ]);
            return;
        }

        $this->validate([
            'selectedAkunKasId' => 'required|exists:akun_kas,id',
            'tipeKas' => 'required|in:Masuk,Keluar',
            'jumlahKas' => 'required|numeric|min:0',
            'kategoriKas' => 'required|string|min:3',
            'tanggalKas' => 'required|date',
            'keteranganKas' => 'nullable|string',
            'fotoMutasi' => 'nullable|image|max:2048',
        ]);

        // Security: Forbidden Backdate for non-admin
        $isAdmin = $user->hasTeamRole($user->currentTeam, 'admin');
        if (!$isAdmin && \Carbon\Carbon::parse($this->tanggalKas)->isPast() && !\Carbon\Carbon::parse($this->tanggalKas)->isToday()) {
            $this->dispatch('swal:error', [
                'title' => 'Akses Ditolak',
                'text' => 'Staff Finance tidak diperbolehkan menginput transaksi di tanggal lampau. Silakan hubungi Admin untuk koreksi data.',
            ]);
            return;
        }

        DB::transaction(function () {
            $akun = AkunKas::findOrFail($this->selectedAkunKasId);

            MutasiKas::create([
                'akun_kas_id' => $this->selectedAkunKasId,
                'user_id' => auth()->id(),
                'tipe' => $this->tipeKas,
                'kategori' => $this->kategoriKas,
                'jumlah' => $this->jumlahKas,
                'tanggal' => $this->tanggalKas,
                'keterangan' => $this->keteranganKas,
                'foto_bukti' => $this->fotoMutasi ? $this->saveImage($this->fotoMutasi, 'mutasi-bukti') : null,
            ]);

            if ($this->tipeKas === 'Masuk') {
                $akun->increment('saldo_saat_ini', $this->jumlahKas);
            } else {
                $akun->decrement('saldo_saat_ini', $this->jumlahKas);
            }
        });

        $this->dispatch('swal:success', [
            'title' => 'Berhasil',
            'text' => 'Mutasi Kas berhasil dicatat!',
        ]);
        $this->reset(['jumlahKas', 'kategoriKas', 'keteranganKas', 'fotoMutasi']);
    }

    public function toggleVerify($mutasiId)
    {
        // RBAC: Only Admin can verify
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin')) {
            $this->dispatch('swal:error', ['title' => 'Ditolak', 'text' => 'Hanya Admin yang bisa memverifikasi transaksi.']);
            return;
        }

        $mutasi = MutasiKas::findOrFail($mutasiId);
        $mutasi->update(['is_verified' => !$mutasi->is_verified]);

        $this->dispatch('swal:success', [
            'title' => 'Status Diperbarui',
            'text' => $mutasi->is_verified ? 'Transaksi ditandai terverifikasi.' : 'Verifikasi dibatalkan.',
        ]);
    }

    // --- FITUR TRANSFER KAS ---

    public function openModalTransfer($akunId)
    {
        $this->reset(['transferPengirimId', 'transferPenerimaId', 'transferJumlah', 'transferKeterangan']);
        $this->transferPengirimId = $akunId;
        $this->showModalTransfer = true;
    }

    public function ajukanTransfer()
    {
        $this->transferJumlah = (int) str_replace('.', '', $this->transferJumlah);

        $this->validate([
            'transferPengirimId' => 'required|exists:akun_kas,id',
            'transferPenerimaId' => 'required|exists:akun_kas,id|different:transferPengirimId',
            'transferJumlah' => 'required|numeric|min:1',
            'transferKeterangan' => 'nullable|string',
        ]);

        $pengirim = AkunKas::findOrFail($this->transferPengirimId);

        // RBAC: Hanya pemilik kas (atau admin) yang bisa mengirim dana dari kas ini
        $user = auth()->user();
        $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');
        if (!$isAdminOrFinance && $pengirim->user_id !== $user->id) {
            $this->dispatch('swal:error', ['title' => 'Ditolak', 'text' => 'Anda bukan pemegang dompet asal.']);
            return;
        }

        if ($pengirim->saldo_saat_ini < $this->transferJumlah) {
            $this->dispatch('swal:error', ['title' => 'Gagal', 'text' => 'Saldo dompet pengirim tidak mencukupi untuk transfer ini.']);
            return;
        }

        DB::transaction(function () use ($pengirim) {
            // 1. Potong saldo pengirim sebagai dana tertahan (floating)
            $pengirim->decrement('saldo_saat_ini', $this->transferJumlah);

            // 2. Buat Record Transfer Status Pending
            \App\Models\TransferKas::create([
                'pengirim_akun_id' => $this->transferPengirimId,
                'penerima_akun_id' => $this->transferPenerimaId,
                'jumlah' => $this->transferJumlah,
                'keterangan' => $this->transferKeterangan,
                'foto_bukti' => $this->saveImage($this->transferFoto, 'transfer-bukti'),
                'status' => 'pending',
                'pengirim_user_id' => auth()->id(),
            ]);
        });

        $this->showModalTransfer = false;
        $this->reset(['transferFoto']);
        $this->dispatch('swal:success', [
            'title' => 'Transfer Diajukan',
            'text' => 'Dana telah ditahan. Menunggu konfirmasi dari penerima atau Finance.',
        ]);
    }

    public function openModalReject($transferId)
    {
        $this->transferRejectId = $transferId;
        $this->alasanPenolakan = '';
        $this->showModalReject = true;
    }

    public function rejectTransfer()
    {
        $this->validate([
            'alasanPenolakan' => 'required|string|min:5',
        ]);

        $this->prosesTransfer($this->transferRejectId, 'reject');
        $this->showModalReject = false;
    }

    public function prosesTransfer($transferId, $aksi)
    {
        // Aksi = 'approve' atau 'reject'
        $transfer = \App\Models\TransferKas::with(['pengirimAkun', 'penerimaAkun'])->findOrFail($transferId);

        if ($transfer->status !== 'pending') {
            $this->dispatch('swal:error', ['title' => 'Kadaluarsa', 'text' => 'Siklus transfer ini sudah diproses sebelumnya.']);
            return;
        }

        // RBAC: Hanya Finance/Admin, ATAU pemilik kas penerima yang berhak Approve/Reject
        $user = auth()->user();
        $isAdminOrFinance = $user->hasTeamRole($user->currentTeam, 'admin') || $user->hasTeamRole($user->currentTeam, 'finance');

        if (!$isAdminOrFinance && $transfer->penerimaAkun->user_id !== $user->id) {
            $this->dispatch('swal:error', ['title' => 'Ditolak', 'text' => 'Anda tidak memiliki otorisasi (wewenang) untuk menerima kas ini.']);
            return;
        }

        DB::transaction(function () use ($transfer, $aksi, $user) {
            $transfer->update([
                'status' => $aksi === 'approve' ? 'completed' : 'rejected',
                'penerima_user_id' => $user->id,
                'tanggal_konfirmasi' => now(),
                'alasan_penolakan' => $aksi === 'reject' ? $this->alasanPenolakan : null,
            ]);

            if ($aksi === 'approve') {
                // Dana Sah masuk ke penerima
                $transfer->penerimaAkun->increment('saldo_saat_ini', (float) $transfer->jumlah);

                // Catat di Mutasi Kas sebagai Audit Log (Dual Entry)
                MutasiKas::create([
                    'akun_kas_id' => $transfer->pengirim_akun_id,
                    'user_id' => $transfer->pengirim_user_id, // Siapa yang kirim
                    'tipe' => 'Keluar',
                    'kategori' => 'Transfer Keluar',
                    'jumlah' => $transfer->jumlah,
                    'keterangan' => 'Transfer ke ' . ($transfer->penerimaAkun->nama) . ' (Disetujui oleh: ' . $user->name . ')',
                    'tanggal' => $transfer->tanggal_transfer->format('Y-m-d'),
                    'foto_bukti' => $transfer->foto_bukti,
                ]);

                MutasiKas::create([
                    'akun_kas_id' => $transfer->penerima_akun_id,
                    'user_id' => $user->id, // Finance/Penerima yang approve
                    'tipe' => 'Masuk',
                    'kategori' => 'Transfer Masuk',
                    'jumlah' => $transfer->jumlah,
                    'keterangan' => 'Menerima transfer dari ' . ($transfer->pengirimAkun->nama),
                    'tanggal' => now()->format('Y-m-d'),
                    'foto_bukti' => $transfer->foto_bukti,
                ]);

            } else {
                // Reject: Return fund to sender
                $transfer->pengirimAkun->increment('saldo_saat_ini', (float) $transfer->jumlah);
            }
        });

        $this->dispatch('swal:success', [
            'title' => 'Sukses',
            'text' => $aksi === 'approve' ? 'Transfer telah disetujui, dana efektif masuk.' : 'Transfer dibatalkan, dana dikembalikan ke pengirim.',
        ]);
    }

    public function storeAkunKas()
    {
        // RBAC Check
        $user = auth()->user();
        if (!$user->hasTeamRole($user->currentTeam, 'admin') && !$user->hasTeamRole($user->currentTeam, 'finance')) {
            $this->dispatch('swal:error', [
                'title' => 'Akses Ditolak',
                'text' => 'Hanya Admin dan Staff Keuangan yang dapat mengelola akun kas.',
            ]);
            return;
        }

        $this->validate([
            'namaAkunKas' => 'required|min:3',
            'kodeAkunKas' => 'required|unique:akun_kas,kode',
            'saldoAwal' => 'required|numeric|min:0',
        ]);

        AkunKas::create([
            'nama' => $this->namaAkunKas,
            'kode' => $this->kodeAkunKas,
            'saldo_awal' => $this->saldoAwal,
            'saldo_saat_ini' => $this->saldoAwal,
            'user_id' => $this->pjUserKasId ?: auth()->id(),
            'team_id' => auth()->user()->current_team_id,
        ]);

        $this->reset(['namaAkunKas', 'kodeAkunKas', 'saldoAwal', 'pjUserKasId']);
        $this->dispatch('close-modal', modalId: 'modal-akun-kas');
        $this->dispatch('swal:success', [
            'title' => 'Berhasil',
            'text' => 'Akun Kas baru berhasil ditambahkan!',
        ]);
    }

    // --- FITUR PENUTUPAN KAS HARIAN ---
    public function openModalTutupKas($akunId)
    {
        $this->selectedAkunTutupId = $akunId;
        $this->saldoFisik = 0;
        $this->keteranganTutup = '';
        $this->fotoBuktiFisik = null;
        $this->showModalTutupKas = true;
    }

    public function tutupKas()
    {
        $this->validate([
            'selectedAkunTutupId' => 'required|exists:akun_kas,id',
            'saldoFisik' => 'required|numeric|min:0',
            'fotoBuktiFisik' => 'nullable', // Boleh base64 atau image object
            'keteranganTutup' => 'nullable|string',
        ]);

        $akun = AkunKas::findOrFail($this->selectedAkunTutupId);
        $saldoAplikasi = (float) $akun->saldo_saat_ini;
        $saldoFisik = (float) $this->saldoFisik;
        $selisih = $saldoFisik - $saldoAplikasi;

        // Cek jika sudah ditutup hari ini
        $exists = \App\Models\PenutupanKas::where('akun_kas_id', $this->selectedAkunTutupId)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        if ($exists) {
            $this->dispatch('swal:error', [
                'title' => 'Gagal',
                'text' => 'Kas ini sudah ditutup untuk hari ini.',
            ]);
            return;
        }

        DB::transaction(function () use ($saldoAplikasi, $saldoFisik, $selisih) {
            \App\Models\PenutupanKas::create([
                'akun_kas_id' => $this->selectedAkunTutupId,
                'user_id' => auth()->id(),
                'tanggal' => now(),
                'saldo_aplikasi' => $saldoAplikasi,
                'saldo_fisik' => $saldoFisik,
                'selisih' => $selisih,
                'foto_bukti_fisik' => $this->fotoBuktiFisik ? $this->saveImage($this->fotoBuktiFisik, 'tutup-kas') : null,
                'keterangan' => $this->keteranganTutup,
                'status' => $selisih == 0 ? 'balanced' : 'mismatch',
            ]);
        });

        $this->showModalTutupKas = false;
        $this->dispatch('swal:success', [
            'title' => 'Berhasil',
            'text' => 'Penutupan Kas harian telah dicatat' . ($selisih != 0 ? ' (Ada Selisih!)' : ''),
        ]);
    }

    public function isClosedToday($akunId)
    {
        return \App\Models\PenutupanKas::where('akun_kas_id', $akunId)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();
    }

    #[Computed]
    public function teamUsers()
    {
        return auth()->user()->currentTeam->allUsers();
    }

    public function render()
    {
        return view('livewire.keuangan');
    }
}
