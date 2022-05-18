<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'member';
    protected $primaryKey = 'id';
    // protected $primaryKey = 'id';
    protected $guarded = [];

    public function permintaan() {
        return $this->hasMany(Permintaan::class, 'kode_customer', 'kode_member');
    }
}
