<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itdetail extends Model
{
    use HasFactory;
    protected $table = 'it_detail';
    protected $primaryKey = 'id_it_detail';
    protected $guarded = [];
}
