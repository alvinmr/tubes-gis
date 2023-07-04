<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    protected $casts = [
        'images' => 'array',
        'coordinate' => 'array',
    ];

    protected $guarded = [];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_faskes', 'id_faskes', 'id_fasilitas');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'id_tipe');
    }
}
