<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaanDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'penerimaan_detail';
    protected $primaryKey = 'id_penerimaan_detail';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}
