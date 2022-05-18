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
                        ->where('status', 'Submited')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->where('nama', auth()->user()->name)
                        ->get();
        }else{
            $permintaan = DB::table('permintaan')
            ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
            ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
            ->where('status', 'Submited')
            ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
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
                ->where('status', 'Check by Mechanic')
                ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                ->get();

        return datatables()
            ->of($pemeriksaan)
            ->addIndexColumn()
            ->addColumn('select_all', function ($pemeriksaan) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $pemeriksaan->id .'">
                ';
            })
            ->addColumn('aksi', function ($pemeriksaan) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('pemeriksaan.update', $pemeriksaan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('pemeriksaan.destroy', $pemeriksaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all'])
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
