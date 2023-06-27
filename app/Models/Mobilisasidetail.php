<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobilisasidetail extends Model
{
    use HasFactory;
    protected $table = 'mobilisasi_detail';
    protected $primaryKey = 'id_mobilisasi_detail';
    protected $guarded = [];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'id_aset');
    }

    public function lokasi1()
    {
        return $this->hasOne(Lokasi::class, 'id_lokasi', 'lokasi_awal');
    }

    public function lokasi2()
    {
        return $this->hasOne(Lokasi::class, 'id_lokasi', 'lokasi_tujuan');
    }

    public function mobilisasi()
    {
        return $this->hasMany(Mobilisasi::class, 'id_mobilisasi', 'id_mobilisasi');
    }

}
