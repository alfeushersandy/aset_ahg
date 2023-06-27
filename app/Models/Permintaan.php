<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;
    protected $table = 'permintaan';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function member() {
        return $this->hasOne(Member::class, 'kode_member', 'kode_customer');
    }

    public function mekanik() {
        return $this->hasOne(Petugas::class, 'id_petugas', 'id_mekanik');
    }

    public function service() {
        return $this->hasOne(Service::class, 'id_permintaan', 'id');
    }

    public function lokasi() {
        return $this->hasOne(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }
}
