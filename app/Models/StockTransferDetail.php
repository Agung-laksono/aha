<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class StockTransferDetail extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $guarded = [];

    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
