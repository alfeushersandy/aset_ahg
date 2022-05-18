<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $guarded = [];

    public function permintaan() {
        return $this->hasMany(Permintaan::class, 'id_mekanik');
    }
}
