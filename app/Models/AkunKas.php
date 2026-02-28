<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class AkunKas extends Model
{
    use LogsActivity;

    protected $table = 'akun_kas';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mutasiKas()
    {
        return $this->hasMany(MutasiKas::class, 'akun_kas_id');
    }
}
