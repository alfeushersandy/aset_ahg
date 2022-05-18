<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kartu_stok extends Model
{
    use HasFactory;
    protected $table = 'kartu_stok';
    protected $primaryKey = 'id_kartu_stok';
    protected $guarded = [];
}
