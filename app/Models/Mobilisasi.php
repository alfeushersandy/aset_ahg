<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobilisasi extends Model
{
    use HasFactory;
    protected $table = 'mobilisasi';
    protected $primaryKey = 'id_mobilisasi';
    protected $guarded = [];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi_pemohon', 'id_lokasi');
    }
}
