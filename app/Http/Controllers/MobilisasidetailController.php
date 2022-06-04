<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use App\Models\Mobilisasi;
use App\Models\Mobilisasidetail;
use Illuminate\Support\Facades\DB;

class MobilisasidetailController extends Controller
{
    public function index()
    {
        $id_mobilisasi = session('id_mobilisasi');
        $mobilisasidetail = Mobilisasidetail::where('id_mobilisasi', $id_mobilisasi)->get('id_aset');
        $member = Member::with('kategori', 'lokasi')->whereNotIn('id', $mobilisasidetail)->get();
        $mobilisasi = Mobilisasi::find($id_mobilisasi);

        if (! $mobilisasi) {
            abort(404);
        }

        return view('mobilisasidetail.index', compact('id_mobilisasi', 'member', 'mobilisasi'));
    }

    public function data($id)
    {
        $mobilisasidetail = DB::table('mobilisasi_detail')
                ->leftJoin('mobilisasi','mobilisasi.id_mobilisasi','=','mobilisasi_detail.id_mobilisasi')
                ->leftJoin('member','member.id','=','mobilisasi_detail.id_aset')
                ->leftJoin('kategori', 'kategori.id_kategori', '=', 'member.id_kategori')
                ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'member.id_lokasi')
                ->orderBy('kode_mobilisasi', 'desc')
                ->select('mobilisasi_detail.*', 'kategori.nama_kategori', 'member.kode_kabin', 'member.nopol','nama_lokasi')
                ->where('mobilisasi_detail.id_mobilisasi', $id)
                ->get();
        
                return datatables()
                ->of($mobilisasidetail)
                ->addIndexColumn()
                ->addColumn('aksi', function ($mobilisasidetail) {
                    return '
                    <div class="btn-group">
                        <button type="submit" onclick="updatePengguna(`'. $mobilisasidetail->id_mobilisasi_detail. '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                        <button type="button" onclick="deleteData(`'. route('mobilisasidetail.destroy', $mobilisasidetail->id_mobilisasi_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
    }

    public function show($id){
        $member = Member::where('id', $id)-get();

    }

    public function store(Request $request)
    {
        $aset = Member::where('id', $request->id_aset)->first();
        if (! $aset) {
            return response()->json('Data gagal disimpan', 400);
        }

        $mobilisasi = Mobilisasi::find(session('id_mobilisasi'));

        $detail = new Mobilisasidetail();
        $detail->id_mobilisasi = $request->id_mobilisasi;
        $detail->id_aset = $aset->id;
        $detail->lokasi_awal = $aset->id_lokasi;
        $detail->lokasi_tujuan = $mobilisasi->id_lokasi_pemohon;
        $detail->user = $request->user;
        $detail->tanggal_awal = $request->tanggal_awal;
        $detail->tanggal_akhir = $request->tanggal_akhir;
        $detail->save();


        return redirect()->route('mobilisasidetail.index');
    }

    public function update(Request $request, $id)
    {
        $detail = Mobilisasidetail::find($id);
        $detail->user = $request->user;
        $detail->tanggal_awal = $request->tanggal_awal;
        $detail->tanggal_akhir = $request->tanggal_akhir;
        $detail->update();

        return response()->json('data berhasil diupdate', 200);
    }

    public function destroy($id)
    {
        $detail = Mobilisasidetail::find($id);
        $detail->delete();

        return response(null, 204);
    }
}
