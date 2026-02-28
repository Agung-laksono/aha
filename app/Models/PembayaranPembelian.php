<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class PembayaranPembelian extends Model
{
    use LogsActivity;

    protected $table = 'pembayaran_pembelian';
    protected $guarded = ['id'];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class, 'akun_kas_id');
    }
}
