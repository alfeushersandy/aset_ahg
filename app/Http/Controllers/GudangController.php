<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Petugas;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index(){
        $permintaan = Permintaan::with('mekanik', 'member')->where('status', '!=', 'selesai')->get();
        $mekanik = Petugas::all()->pluck('nama_petugas', 'id_petugas');
        return view('barangkeluar.index', compact('permintaan', 'mekanik'));
    }
}
