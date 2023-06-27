<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perencanaan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tb_ren';
    protected $primaryKey = 'id_perencanaan';
    protected $guarded = [];

    public function member() {
        return $this->hasOne(Member::class, 'id', 'id_aset');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }
}
