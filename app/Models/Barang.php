<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use LogsActivity;
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class);
    }

    public function gambarBarangs()
    {
        return $this->hasMany(GambarBarang::class);
    }

    public function hargaBelis()
    {
        return $this->hasMany(HargaBeli::class);
    }

    public function hargaJuals()
    {
        return $this->hasMany(HargaJual::class);
    }

    public function hargaBeliTerakhir()
    {
        return $this->hasOne(HargaBeli::class)->latestOfMany();
    }

    public function hargaJualTerakhir()
    {
        return $this->hasOne(HargaJual::class)->latestOfMany();
    }

    public function stoks()
    {
        return $this->hasMany(Stok::class);
    }

    public function getTotalStokAttribute()
    {
        return $this->stoks->sum('jumlah');
    }
}
