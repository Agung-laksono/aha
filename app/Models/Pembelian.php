<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use LogsActivity;
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(PembayaranPembelian::class);
    }

    public function dokumens()
    {
        return $this->hasMany(DokumenPembelian::class);
    }

    public function getTerbayarAttribute()
    {
        return $this->pembayarans()->sum('jumlah_bayar');
    }

    public function getSisaTagihanAttribute()
    {
        return $this->total_harga + $this->biaya_ongkir + $this->biaya_lain - $this->terbayar;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
