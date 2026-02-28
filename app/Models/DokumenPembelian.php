<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenPembelian extends Model
{
    protected $table = 'dokumen_pembelian';
    protected $guarded = ['id'];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
