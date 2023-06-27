<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Lokasi;
use App\Models\Mobilisasi;
use App\Models\Mobilisasidetail;
use App\Models\Permintaandetail;
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
                ->orderBy('kode_mobilisasi', 'desc')
                ->get();

                return datatables()
                ->of($mobilisasi)
                ->addIndexColumn()
                ->addColumn('tanggal_kembali', function($mobilisasi){
                    if($mobilisasi->tanggal_kembali){
                        return tanggal_indonesia($mobilisasi->tanggal_kembali, false);
                    }else{
                        return $mobilisasi->tanggal_kembali;
                    }
                })
                ->addColumn('aksi', function ($mobilisasi) {
                    if($mobilisasi->status_kirim == "submited"){
                        return '
                        <div class="btn-group">
                            <button type="button" onclick="kirim()" class="btn btn-xs btn-success btn-flat">Dikirim</button>
                            <button type="button" onclick="showDetail(`'. route('mobilisasi.detail', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                            <button type="button" onclick="editForm(`'. route('mobilisasi.update', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                            <button type="button" onclick="deleteData(`'. route('mobilisasi.destroy', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                        </div>
                        ';
                    }else if($mobilisasi->status_kirim == "dikirim"){
                        return '
                        <div class="btn-group">
                        <button type="button" onclick="showDetail(`'. route('mobilisasi.detail', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-success btn-flat">Diterima</button>
                            <button type="button" onclick="showDetail(`'. route('mobilisasi.detail', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                            <button type="button" onclick="editForm(`'. route('mobilisasi.update', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                            <button type="button" onclick="deleteData(`'. route('mobilisasi.destroy', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                        </div>
                        ';
                    }else{
                        return '
                        <div class="btn-group">
                            <button type="button" onclick="showDetail(`'. route('mobilisasi.detail', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                            <button type="button" onclick="editForm(`'. route('mobilisasi.update', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                            <button type="button" onclick="deleteData(`'. route('mobilisasi.destroy', $mobilisasi->id_mobilisasi) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                        </div>
                        ';
                    }
                })
                ->addColumn('status', function($mobilisasi){
                    if($mobilisasi->status == 1){
                        return '<span>On Progress</span>';
                    }else{
                        return '<span>Selesai</span>';
                    }
                })
                ->rawColumns(['aksi', 'status'])
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
        $mobilisasi->status = 1;
        $mobilisasi->status_kirim = "submited";
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

    public function report(){
        $lokasi = Lokasi::all();
        return view('laporan_mobilisasi.index', compact('lokasi'));
    }

    public function getAll(){
        $data = array();
        $no = 1;
        $total = 0;
        $permintaan = Mobilisasidetail::leftjoin('mobilisasi', 'mobilisasi.id_mobilisasi', 'mobilisasi_detail.id_mobilisasi')
                        ->join('member', 'member.id', 'mobilisasi_detail.id_aset')
                        ->leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'mobilisasi_detail.lokasi_tujuan')
                        ->select('kode_mobilisasi', 'kode_kabin','nopol','nama_lokasi', 'mobilisasi_detail.user as user', 'mobilisasi_detail.tanggal_awal as tanggal_awal', 'mobilisasi_detail.tanggal_kembali as tanggal_kembali')
                        ->get();
        


                         
        foreach ($permintaan as $items ) {

            $row = array();
            $row['kode_mobilisasi'] = '<span class="label label-danger">'.$items->kode_mobilisasi.'</span';
            $row['kode_kabin'] = $items->kode_kabin;
            $row['nopol'] = $items->nopol;
            $row['lokasi'] = $items->nama_lokasi;
            $row['user'] = $items->user;
            $row['tanggal_awal'] = tanggal_indonesia($items->tanggal_awal, false);
            $row['tanggal_kembali'] =  $items->tanggal_kembali ? tanggal_indonesia($items->tanggal_kembali, false) : '';

            $data[] = $row;
        }

    return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['kode_mobilisasi', 'kode_kabin', 'nopol', 'lokasi', 'user', 'tanggal_awal', 'tanggal_akhir'])
            ->make(true);
    }

    public function reportData($id_lokasi, $tanggal_awal, $tanggal_akhir)
    {

        if($id_lokasi == 'Semua Data'){
            $data = array();
            $permintaan = Mobilisasidetail::leftjoin('mobilisasi', 'mobilisasi.id_mobilisasi', 'mobilisasi_detail.id_mobilisasi')
                        ->join('member', 'member.id', 'mobilisasi_detail.id_aset')
                        ->leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'mobilisasi_detail.lokasi_tujuan')
                        ->where('mobilisasi.tanggal', '>=', $tanggal_awal)
                        ->where('mobilisasi.tanggal', '<=', $tanggal_akhir)
                        ->select('kode_mobilisasi', 'kode_kabin','nopol','nama_lokasi', 'mobilisasi_detail.user as user', 'mobilisasi_detail.tanggal_awal as tanggal_awal', 'mobilisasi_detail.tanggal_kembali as tanggal_kembali')
                        ->get();
        }else{

            $data = array();
            $permintaan = Mobilisasidetail::leftjoin('mobilisasi', 'mobilisasi.id_mobilisasi', 'mobilisasi_detail.id_mobilisasi')
                            ->join('member', 'member.id', 'mobilisasi_detail.id_aset')
                            ->leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'mobilisasi_detail.lokasi_tujuan')
                            ->where('mobilisasi.id_lokasi_pemohon', $id_lokasi)
                            ->where('mobilisasi.tanggal', '>=', $tanggal_awal)
                            ->where('mobilisasi.tanggal', '<=', $tanggal_akhir)
                            ->select('kode_mobilisasi', 'kode_kabin','nopol','nama_lokasi', 'mobilisasi_detail.user as user', 'mobilisasi_detail.tanggal_awal as tanggal_awal', 'mobilisasi_detail.tanggal_kembali as tanggal_kembali')
                            ->get();
            
        }
        


                         
        foreach ($permintaan as $items ) {

            $row = array();
            $row['kode_mobilisasi'] = '<span class="label label-danger">'.$items->kode_mobilisasi.'</span';
            $row['kode_kabin'] = $items->kode_kabin;
            $row['nopol'] = $items->nopol;
            $row['lokasi'] = $items->nama_lokasi;
            $row['user'] = $items->user;
            $row['tanggal_awal'] = $items->tanggal_awal;
            $row['tanggal_kembali'] = $items->tanggal_kembali ? tanggal_indonesia($items->tanggal_kembali, false) : '';

            $data[] = $row;
        }

    return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['kode_mobilisasi', 'kode_kabin', 'nopol', 'lokasi', 'user', 'tanggal_awal', 'tanggal_kembali'])
            ->make(true);
    }

    public function laporan($id, $tanggal_awal, $tanggal_akhir)
    {
        $setting = Setting::first();
        if($id == 'Semua Data'){
            $lokasi = $id;
            $permintaan = Mobilisasidetail::leftjoin('mobilisasi', 'mobilisasi.id_mobilisasi', 'mobilisasi_detail.id_mobilisasi')
            ->join('member', 'member.id', 'mobilisasi_detail.id_aset')
            ->leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'mobilisasi_detail.lokasi_tujuan')
            ->where('mobilisasi.tanggal', '>=', $tanggal_awal)
            ->where('mobilisasi.tanggal', '<=', $tanggal_akhir)
            ->select('kode_mobilisasi', 'kode_kabin','nopol','nama_lokasi', 'mobilisasi_detail.user as user', 'mobilisasi_detail.tanggal_awal as tanggal_awal', 'mobilisasi_detail.tanggal_kembali as tanggal_kembali')
            ->get();

        }else{
            $lokasi = Lokasi::find($id);
            $permintaan = Mobilisasidetail::leftjoin('mobilisasi', 'mobilisasi.id_mobilisasi', 'mobilisasi_detail.id_mobilisasi')
                            ->join('member', 'member.id', 'mobilisasi_detail.id_aset')
                            ->leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'mobilisasi_detail.lokasi_tujuan')
                            ->where('mobilisasi.id_lokasi_pemohon', $id)
                            ->where('mobilisasi.tanggal', '>=', $tanggal_awal)
                            ->where('mobilisasi.tanggal', '<=', $tanggal_akhir)
                            ->select('kode_mobilisasi', 'kode_kabin','nopol','nama_lokasi', 'mobilisasi_detail.user as user', 'mobilisasi_detail.tanggal_awal as tanggal_awal', 'mobilisasi_detail.tanggal_kembali as tanggal_kembali')
                            ->get();
        }
        
        $pdf = PDF::loadView('laporan_mobilisasi.laporan', compact('setting', 'permintaan', 'lokasi', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Mobilisasi-'. date('Y-m-d-his') .'.pdf');
    }

    public function detail($id_mobilisasi)
    {
        $detail = Mobilisasidetail::with(['lokasi1', 'member'])->where('id_mobilisasi', $id_mobilisasi)->get();
        return datatables()
                ->of($detail)
                ->addIndexColumn()
                ->addColumn('kode_kabin', function($detail){
                    return $detail->member->kode_kabin;
                })
                ->addColumn('identitas', function($detail){
                    return $detail->member->nopol;
                })
                ->rawColumns(['kode_kabin','identitas'])
                ->make(true);
        
    }
   
}
