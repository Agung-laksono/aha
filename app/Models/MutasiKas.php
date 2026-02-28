<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiKas extends Model
{
    protected $table = 'mutasi_kas';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class, 'akun_kas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
