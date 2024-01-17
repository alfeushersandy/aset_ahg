<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_pakai extends Model
{
    use HasFactory;
    protected $table = 'detail_pakai';
    protected $primaryKey = 'id_detail_pakai';

    public function permintaan()
    {
        return $this->hasOne(Permintaan::class, 'id', 'id_permintaan');
    }

    public function ban()
    {
        return $this->hasOne(Ban::class, 'id_detail_barang', 'id_detail_barang');
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'id_aset');
    }
}
