<?php

namespace App\Http\Controllers;

use App\Models\Barangkeluar;
use App\Models\Permintaan;
use App\Models\Petugas;
use Illuminate\Http\Request;

class GudangController extends Controller
{
    public function index(){
        $mekanik = Petugas::all()->pluck('nama_petugas', 'id_petugas');
        $barang_keluar = Barangkeluar::all();
        return view('barangkeluar.index', compact('mekanik', 'barang_keluar'));
    }

    public function getKodeService(){
        $permintaan = Permintaan::where('status', '!=', 'selesai')->get();
        return response()->json($permintaan);
    }

    public function create(Request $request){
        $barang_keluar = new Barangkeluar;
        $barang_keluar->tanggal = $request->tanggal;
        $barang_keluar->id_pemohon = $request->pemohon;
        $barang_keluar->keperluan = $request->keperluan;
        $barang_keluar->kode_keperluan = $request->kode_keperluan;
        $barang_keluar->keterangan = $request->keterangan;
        $barang_keluar->status = 1; 
        $barang_keluar->save();

        session(['id_barang_keluar' => $barang_keluar->id_barang_keluar]);

        return redirect()->route('permintaan_detail.index');
    }
}
