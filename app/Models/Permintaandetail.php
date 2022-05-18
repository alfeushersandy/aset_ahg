<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaandetail extends Model
{
    use HasFactory;
    protected $table = 'permintaan_detail';
    protected $primaryKey = 'id_permintaan_detail';
    protected $guarded = [];

    public function barang()
    {
        return $this->hasOne(Barang::class, 'id_barang', 'id_barang');
    }
}
