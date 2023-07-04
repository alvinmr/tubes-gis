<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $guarded = [];

    public function faskes()
    {
        return $this->belongsToMany(Faskes::class, 'fasilitas_faskes', 'id_fasilitas', 'id_faskes');
    }
    public function type() {
        return $this->belongsTo(Type::class, 'id_tipe');
    }
}
