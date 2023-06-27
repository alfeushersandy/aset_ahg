<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penerimaan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'penerimaan';
    protected $primaryKey = 'id_penerimaan';
    protected $guarded = [];

    public function member() {
        return $this->hasOne(Member::class, 'id', 'id_aset');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function perencanaan()
    {
        return $this->hasOne(Perencanaan::class, 'id_perencanaan', 'id_perencanaan');
    }
}
