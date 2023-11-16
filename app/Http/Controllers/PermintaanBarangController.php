<?php

namespace App\Http\Controllers;

use App\Models\PerencanaanDetail;
use App\Models\Member;
use App\Models\Lokasi;
use App\Models\Perencanaan;
use Illuminate\Http\Request;

class PermintaanBarangController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all()->pluck('nama_lokasi', 'id_lokasi');
        $kendaraan =  Member::where('status', 'On Duty')->orWhere('status', 'Rusak')->orWhere('status', 'On Service')->orWhere('status', 'OFF')->orWhere('status', 'Tersedia')->orWhere('status', NULL)->get();
        $perencanaan = Perencanaan::with('lokasi', 'member')->get();
        $permintaan_barang = PerencanaanDetail::with('barang', 'perencanaan')->get();
        return view('permintaan_barang.index', compact('permintaan_barang', 'perencanaan', 'lokasi', 'kendaraan' ));
    }
}
