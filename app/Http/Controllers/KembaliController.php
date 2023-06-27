<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Mobilisasi;
use App\Models\Mobilisasidetail;
use Illuminate\Http\Request;

class KembaliController extends Controller
{
    public function index()
    {
        return view('kembali.index');
    }

    public function data(){
        $detail = Mobilisasidetail::with('member', 'lokasi1', 'lokasi2', 'mobilisasi')->where('status', 1)->get();
        
        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_mobilisasi', function($detail){
                return $detail->mobilisasi[0]->kode_mobilisasi;
            })
            ->addColumn('kode_aset', function($detail){
                return $detail->member->kode_kabin;
            })
            ->addColumn('identitas', function($detail){
                return $detail->member->nopol;
            })
            ->addColumn('lokasi_tujuan', function($detail){
                return $detail->lokasi2->nama_lokasi;
            })
            ->addColumn('tanggal_awal', function($detail){
                return tanggal_indonesia($detail->tanggal_awal, false);
            })
            ->addColumn('aksi', function($detail){
                return '
                <div class="btn-group">
                    <a href="' . route('kembali.kembali', $detail->id_mobilisasi_detail) . '" class="btn btn-primary">Selesai</a>
                    <button type="button" onclick="deleteData(`'. route('kembali.destroy', $detail->id_mobilisasi_detail) .'`)" class="btn btn-xs btn-danger">Hapus</button>
                </div>
                ';
            })
            ->rawColumns(['lokasi_awal', 'lokasi_tujuan', 'kode_aset', 'identitas', 'aksi'])
            ->make(true);
    }

    public function kembali(Mobilisasidetail $detail)
    {
        $detail->status = false;
        $detail->tanggal_kembali = date('Y-m-d');
        $detail->update();

        $member = Member::find($detail->id_aset);
        $member->id_lokasi = $member->id_home_base;
        $member->user = "";
        $member->update();

        $detail_by_id = Mobilisasidetail::where('id_mobilisasi', $detail->id_mobilisasi)->count();
        $mobilisasi_by_status = Mobilisasidetail::where('id_mobilisasi', $detail->id_mobilisasi)->where('status', 0)->count();
        $mobilisasi = Mobilisasi::find($detail->id_mobilisasi);
        
        if($mobilisasi_by_status !== $detail_by_id){
            return redirect()->route('kembali.index');
        }else{
            $mobilisasi->tanggal_kembali = now();
            $mobilisasi->status = 0;
            $mobilisasi->update();
            return redirect()->route('mobilisasi.index');
        }
    }

    public function destroy($id)
    {
        $detail = Mobilisasidetail::find($id);
        $detail->delete();

        return redirect()->route('kembali.index');
    }
}

