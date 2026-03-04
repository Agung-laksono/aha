<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class PenutupanKas extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'saldo_aplikasi' => 'decimal:2',
        'saldo_fisik' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logDescription($action)
    {
        $selisih = number_format((float) $this->selisih, 0, ',', '.');
        $namaAkun = $this->akunKas->nama ?? 'Akun';
        return "Penutupan Kas $namaAkun - Selisih: Rp $selisih";
    }
}
