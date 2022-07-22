<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';
    protected $primaryKey = 'id_departemen';
    protected $guarded = [];

    public function departemen() {
        return $this->hasMany(User::class, 'id_departemen', 'id_departemen');
    }
}
