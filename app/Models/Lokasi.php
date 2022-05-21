<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'table_lokasi';
    protected $primaryKey = 'id_lokasi';
    protected $guarded = [];

    public function member()
    {
        return $this->hasMany(Member::class, 'id_lokasi', 'id_lokasi');
    }
}
