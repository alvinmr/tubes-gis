<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{

    protected $table = 'desa';
    protected $guarded = [];

    public function kasusCovid()
    {
        return $this->hasMany(KasusCovid::class, 'desa_id', 'id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
}
