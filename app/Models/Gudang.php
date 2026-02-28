<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $guarded = ['id'];

    /**
     * Staf logistik yang ditugaskan ke gudang ini (Many-to-Many).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'gudang_user')->withTimestamps();
    }
}

