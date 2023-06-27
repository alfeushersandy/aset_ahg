<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ban extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'detail_barang';
    protected $primaryKey = 'id_detail_barang';
    protected $guarded = [];

    public function member() {
        return $this->hasOne(Member::class, 'id', 'id_aset');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_barang', 'id_barang');
    }
    
}
