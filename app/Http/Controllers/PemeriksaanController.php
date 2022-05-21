<?php

namespace App\Http\Controllers;
use App\Models\Permintaan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $user = auth()->user()->level;
        if($user === 3){
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                        ->where('status', 'Submited')
                        ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                        ->where('nama', auth()->user()->name)
                        ->get();
        }else{
            $permintaan = DB::table('permintaan')
            ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
            ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
            ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
            ->where('status', 'Submited')
            ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
            ->get();
        }
        $count = $permintaan->count();

        return view('pemeriksaan.index', compact('permintaan','count'));
    }

    public function data() 
    {
        $pemeriksaan = DB::table('permintaan')
                ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                ->where('status', 'Check by Mechanic')
                ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                ->get();

        return datatables()
            ->of($pemeriksaan)
            ->addIndexColumn()
            ->addColumn('select_all', function ($pemeriksaan) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $pemeriksaan->id .'">
                ';
            })
            ->rawColumns(['select_all'])
            ->make(true);
    }

    public function create($id)
    {
        $pemeriksaan = Permintaan::findorfail($id);
        $pemeriksaan->status = 'Check by Mechanic';
        $pemeriksaan->update();

        return redirect()->route('pemeriksaan.index');
    }
}
