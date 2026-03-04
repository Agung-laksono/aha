<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    use LogsActivity;
    protected $guarded = ['id'];

    /**
     * Staf logistik yang ditugaskan ke gudang ini (Many-to-Many).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'gudang_user')->withTimestamps();
    }
}

