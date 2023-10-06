<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan_cart extends Model
{
    use HasFactory;
    protected $table = 'penerimaan_cart';
    protected $primaryKey = 'id_cart';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(barang::class, 'id_barang', 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
