<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasusCovid extends Model
{
    protected $table = 'kasus_covid';
    protected $casts = [
        'coordinate' => 'array',
    ];

    protected $guarded = [];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id');
    }
}
