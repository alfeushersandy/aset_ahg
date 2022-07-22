<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Lokasi;
use App\Models\Mobilisasi;
use App\Models\Mobilisasidetail;
use App\Models\Setting;
use PDF;

use Illuminate\Support\Facades\DB;

class MobilisasiController extends Controller
{
    public function index(){
        $lokasi = Lokasi::all()->pluck('nama_lokasi', 'id_lokasi');
        $kendaraan = Member::all()->pluck('kode_kabin', 'kode_member');
        return view('mobilisasi.index', compact('kendaraan', 'lokasi'));
    }

    public function data()
    {
        $mobilisasi = DB::table('mobilisasi')
                ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'mobilisasi.id_lokasi_pemohon')
                ->leftJoin('status', 'status.id_status', '=', 'mobilisasi.id_status')
                ->orderBy('kode_mobilisasi', 'desc')
                ->get();

                return datatables()
                ->of($mobilisasi)
                ->addIndexColumn()
                ->addColumn('aksi', function ($mobilisasi) {
                    return '
                    <div class="btn-group">
                        <button type="button" onclick="editForm(`'. route('mobilisasi.update', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                        <button type="button" onclick="deleteData(`'. route('mobilisasi.destroy', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        

    }


    public function create(Request $request)
    {
        $mobilisasi = Mobilisasi::latest()->first() ?? new Mobilisasi();
        $kode_mobilisasi1 = substr($mobilisasi->kode_mobilisasi,2);
        $kode_mobilisasi = (int) $kode_mobilisasi1 +1;

        $mobilisasi = new mobilisasi();
        $mobilisasi->tanggal = $request->tanggal;
        $mobilisasi->kode_mobilisasi = 'MB'.tambah_nol_didepan($kode_mobilisasi, 5);
        $mobilisasi->id_lokasi_pemohon = $request->id_lokasi_pemohon;
        $mobilisasi->pemohon = $request->pemohon;
        $mobilisasi->total_item = 0;
        $mobilisasi->keterangan = $request->keterangan;
        $mobilisasi->id_status = 1;
        $mobilisasi->save();

        session(['id_mobilisasi' => $mobilisasi->id_mobilisasi]);

        return redirect()->route('mobilisasidetail.index');
    }

    public function store(Request $request)
    {
        
        
        $mobilisasi = Mobilisasi::findorfail($request->id_mobilisasi);
        $detail = Mobilisasidetail::where('id_mobilisasi', $mobilisasi->id_mobilisasi)->get();

        $mobilisasi->total_item = $detail->count();
        $mobilisasi->update();

        
        foreach ($detail as $item) {

            $member = Member::find($item->id_aset);
            $member->id_lokasi = $item->lokasi_tujuan;
            $member->user = $item->user;
            $member->update();
            
        }

        return redirect()->route('mobilisasi.selesai');
    }

    public function show($id)
    {
        $permintaan = Mobilisasi::find($id);

        return response()->json($permintaan);
    }

    public function update(Request $request, $id)
    {
        $permintaan = Mobilisasi::find($id);
        $permintaan->update($request->all());

        return redirect()->route('mobilisasi.index');
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('mobilisasi.selesai', compact('setting'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $mobilisasi = Mobilisasi::find(session('id_mobilisasi'));
        if (! $mobilisasi) {
            abort(404);
        }
        $detail = Mobilisasidetail::with('member','lokasi1','lokasi2')
            ->where('id_mobilisasi', session('id_mobilisasi'))
            ->get();

        $pdf = PDF::loadView('mobilisasi.nota_besar', compact('setting', 'mobilisasi', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Surat_Jalan-'. date('Y-m-d-his') .'.pdf');
    }
    
    public function destroy($id)
    {
        $permintaan = Mobilisasi::find($id);
        $permintaan->delete();

        return response(null, 204);
    }

   
}
