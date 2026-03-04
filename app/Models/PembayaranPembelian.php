<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class PembayaranPembelian extends Model
{
    use LogsActivity;

    protected $table = 'pembayaran_pembelians';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class, 'akun_kas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logDescription($action)
    {
        $jumlah = number_format((float) $this->jumlah_bayar, 0, ',', '.');
        $nota = $this->pembelian->nomor_nota ?? '#';
        return "Pembayaran Hutang: Rp $jumlah (Nota #$nota)";
    }
}
