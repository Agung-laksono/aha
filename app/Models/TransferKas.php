<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TransferKas extends Model
{
    use LogsActivity;

    protected $table = 'transfer_kas';
    protected $guarded = ['id'];
    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute()
    {
        return $this->foto_bukti ? asset('storage/' . $this->foto_bukti) : null;
    }

    protected $casts = [
        'tanggal_transfer' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
        'jumlah' => 'decimal:2',
    ];

    public function pengirimAkun()
    {
        return $this->belongsTo(AkunKas::class, 'pengirim_akun_id');
    }

    public function penerimaAkun()
    {
        return $this->belongsTo(AkunKas::class, 'penerima_akun_id');
    }

    public function pengirimUser()
    {
        return $this->belongsTo(User::class, 'pengirim_user_id');
    }

    public function penerimaUser()
    {
        return $this->belongsTo(User::class, 'penerima_user_id');
    }

    public function logDescription($action)
    {
        $jumlah = number_format((float) $this->jumlah, 0, ',', '.');
        $dari = $this->pengirimAkun->nama ?? 'Akun';
        $ke = $this->penerimaAkun->nama ?? 'Akun';
        return "Transfer Rp $jumlah dari $dari ke $ke";
    }
}
