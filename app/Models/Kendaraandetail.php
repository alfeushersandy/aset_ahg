<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraandetail extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'kendaraan_detail';
    protected $primaryKey = 'id_kendaraan_detail';
    protected $guarded = [];
}
