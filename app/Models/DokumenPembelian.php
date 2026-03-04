<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class DokumenPembelian extends Model
{
    use LogsActivity;
    protected $table = 'dokumen_pembelian';
    protected $guarded = ['id'];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
