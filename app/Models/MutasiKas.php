<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class MutasiKas extends Model
{
    use LogsActivity;
    protected $table = 'mutasi_kas';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'is_verified' => 'boolean',
    ];

    public function getFotoUrlAttribute()
    {
        return $this->foto_bukti ? \Storage::url($this->foto_bukti) : null;
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
        $jumlah = number_format((float) $this->jumlah, 0, ',', '.');
        return "Mutasi {$this->tipe} [{$this->kategori}]: Rp $jumlah";
    }
}
