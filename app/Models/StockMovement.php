<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StockMovement extends Model
{
    use LogsActivity;
    protected $guarded = ['id'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    /**
     * Helper to record a stock movement.
     */
    public static function record($barangId, $gudangId, $type, $quantity, $actionType, $reference = null, $description = null)
    {
        return self::create([
            'barang_id' => $barangId,
            'gudang_id' => $gudangId,
            'user_id' => Auth::id() ?? 1,
            'team_id' => Auth::user()?->current_team_id,
            'type' => $type,
            'quantity' => abs($quantity),
            'action_type' => $actionType,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
            'description' => $description,
        ]);
    }
}
