<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerencanaanDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'pr_detail';
    protected $primaryKey = 'id_perencanaan_detail';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function perencanaan()
    {
        return $this->belongsTo(Perencanaan::class, 'id_perencanaan', 'id_perencanaan');
    }
}
