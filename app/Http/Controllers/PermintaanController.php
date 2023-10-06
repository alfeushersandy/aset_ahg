<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\Petugas;
use App\Models\Permintaan;
use App\Models\Permintaandetail;
use App\Models\Barang;
use App\Models\Setting;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all()->pluck('nama_lokasi', 'id_lokasi');
        $kendaraan = Member::where('status', 'On Duty')->orWhere('status', 'Rusak')->orWhere('status', 'On Service')->orWhere('status', 'OFF')->orWhere('status', 'Tersedia')->orWhere('status', NULL)->get();
        $mekanik = Petugas::all()->pluck('nama_petugas', 'id_petugas');
        return view('permintaan.index', compact('kendaraan', 'mekanik', 'lokasi'));
    }

    public function data()
    {
        $permintaan = DB::table('permintaan')
                ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                ->orderBy('kode_permintaan', 'desc')
                ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                ->get();

                return datatables()
                ->of($permintaan)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($permintaan) {
                    return date("d-m-Y", strtotime($permintaan->tanggal));
                })
                ->addColumn('aksi', function ($permintaan) {
                    if(Auth::user()->level == 3){
                        if($permintaan->status == "Selesai" || $permintaan->status == "On Progress"){
                            if($permintaan->total_item > 0){
                                return '
                                <div class="btn-group">
                                    <button type="button" onclick="notaBesar(`'. route('permintaan.form_service', $permintaan->id) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-file-pdf-o"></i></button>
                                    <button type="button" onclick="showDetail(`'. route('permintaan.detail', $permintaan->id) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                                    <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>
                                '; 
                            }else{
                                return '
                                <div class="btn-group">
                                    <button type="button" onclick="notaBesar(`'. route('permintaan.form_service', $permintaan->id) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-file-pdf-o"></i></button>
                                    <button type="button" onclick="showDetail(`'. route('permintaan.detail', $permintaan->id) .'`)" class="btn btn-xs btn-success btn-flat" disabled><i class="fa fa-eye"></i></button>
                                    <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>
                                '; 
                            }
                        }else{
                            if($permintaan->total_item > 0){
                                return '
                                <div class="btn-group">
                                    <button type="button" onclick="notaBesar(`'. route('permintaan.form_service', $permintaan->id) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-file-pdf-o"></i></button>
                                    <button type="button" onclick="showDetail(`'. route('permintaan.detail', $permintaan->id) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                                    <button type="button" onclick="editForm(`'. route('permintaan.update', $permintaan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                                    <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>
                                '; 
                            }else{
                                return '
                                <div class="btn-group">
                                    <button type="button" onclick="notaBesar(`'. route('permintaan.form_service', $permintaan->id) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-file-pdf-o"></i></button>
                                    <button type="button" onclick="showDetail(`'. route('permintaan.detail', $permintaan->id) .'`)" class="btn btn-xs btn-success btn-flat" disabled><i class="fa fa-eye"></i></button>
                                    <button type="button" onclick="editForm(`'. route('permintaan.update', $permintaan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                                    <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>
                                '; 
                            }
                        }
                    }else{
                        if($permintaan->total_item > 0){
                            return '
                            <div class="btn-group">
                                <button type="button" onclick="notaBesar(`'. route('permintaan.form_service', $permintaan->id) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-file-pdf-o"></i></i></button>
                                <button type="button" onclick="showDetail(`'. route('permintaan.detail', $permintaan->id) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                                <button type="button" onclick="editForm(`'. route('permintaan.update', $permintaan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                                <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>
                            '; 
                        }else{
                            return '
                            <div class="btn-group">
                                <button type="button" onclick="notaBesar(`'. route('permintaan.form_service', $permintaan->id) .'`)" class="btn btn-xs btn-warning btn-flat"><i class="fa fa-file-pdf-o"></i></i></button>
                                <button type="button" onclick="showDetail(`'. route('permintaan.detail', $permintaan->id) .'`)" class="btn btn-xs btn-success btn-flat" disabled><i class="fa fa-eye"></i></button>
                                <button type="button" onclick="editForm(`'. route('permintaan.update', $permintaan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                                <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>
                            '; 
                        }
                    }
                })
                ->rawColumns(['aksi','select_all', 'tanggal'])
                ->make(true);
        

    }

    public function create(Request $request)
    {
        $permintaan = Permintaan::latest()->first() ?? new Permintaan();
        $kode_permintaan1 = substr($permintaan->kode_permintaan,2);
        $kode_permintaan = (int) $kode_permintaan1 +1;

        $permintaan = new Permintaan();
        $permintaan->tanggal = $request->tanggal;
        $permintaan->kode_permintaan = 'PM'.tambah_nol_didepan($kode_permintaan, 5);
        $permintaan->kode_customer = $request->kode_customer;
        $permintaan->km = $request->km;
        $permintaan->id_lokasi = $request->id_lokasi;
        $permintaan->user = $request->user;
        $permintaan->Keluhan = $request->Keluhan;
        $permintaan->total_item = 0;
        $permintaan->total_harga = 0;
        $permintaan->id_mekanik = $request->id_mekanik;
        $permintaan->status = 'Submited';
        $permintaan->save();

        session(['id_permintaan' => $permintaan->id]);

        return redirect()->route('permintaan.selesai_form');
    }

    public function sparepart($id)
    {
        session(['id_permintaan' => $id]);
        return redirect()->route('permintaandetail.index', $id);
    }

    public function store(Request $request)
    {
        
        $permintaan = Permintaan::findorfail($request->id_permintaan);
        $permintaan->total_item = $request->total_item;
        $permintaan->total_harga = $request->total;
        $permintaan->update();

        $detail = Permintaandetail::where('id_permintaan', $permintaan->id)->get();
        foreach ($detail as $item) {

            $produk = Barang::find($item->id_barang);
            $produk->stok -= $item->jumlah;
            $produk->update();
            
        }

        $request->session()->forget('id_permintaan');

        return redirect()->route('permintaan.selesai', $permintaan->id);
    }
    

    public function show($id)
    {
        $permintaan = Permintaan::find($id);

        return response()->json($permintaan);
    }

    public function update(Request $request, $id)
    {
        $permintaan = Permintaan::find($id);
        $permintaan->update($request->all());

        return redirect()->route('permintaan.index');
    }

    public function destroy($id)
    {
        $permintaan_detail = Permintaandetail::where('id_permintaan', $id)->get();
        foreach ($permintaan_detail as $detail) {
            $barang = Barang::where('id_barang', $detail->id_barang)->get();
            foreach ($barang as $barang) {
                $barang->stok += $detail->jumlah;
                $barang->update();
            }
            $detail->delete();
        }

        $permintaan = Permintaan::find($id);
        $permintaan->delete();


        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $id) {
            $permintaan = Permintaan::find($id);
            $permintaan->delete();
        }

        return response(null, 204);
    }

    public function selesai($id)
    {
        $setting = Setting::first();
        $id_permintaan = $id;
        return view('permintaan.selesai', compact('setting', 'id_permintaan'));
    }

    public function selesai_form()
    {
        $id_permintaan = session('id_permintaan');
        $setting = Setting::first();
        return view('permintaan.selesai_form', compact('setting', 'id_permintaan'));
    }

    public function notaBesar($id)
    {
        $setting = Setting::first();
        $permintaan = Permintaan::find($id);
        if (! $permintaan) {
            abort(404);
        }
        $detail = PermintaanDetail::with('barang')
            ->where('id_permintaan', $id)
            ->get();

        $pdf = PDF::loadView('permintaan.nota_besar', compact('setting', 'permintaan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function formService()
    {
        $setting = Setting::first();
        $permintaan = Permintaan::with('mekanik', 'lokasi')->find(session('id_permintaan'));
        if (! $permintaan) {
            abort(404);
        }
        $trimed = explode("\r\n", $permintaan->Keluhan);
        $pdf = PDF::loadView('permintaan.form_service', compact('setting', 'permintaan', 'trimed'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Form_service-'. date('Y-m-d-his') .'.pdf');
    }

    public function formCetakService($id_permintaan)
    {
        $setting = Setting::first();
        $permintaan = Permintaan::with('mekanik', 'lokasi')->find($id_permintaan);
        if (! $permintaan) {
            abort(404);
        }
        $trimed = explode("\r\n", $permintaan->Keluhan);
        $pdf = PDF::loadView('permintaan.form_service', compact('setting', 'permintaan', 'trimed'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Form_service-'. date('Y-m-d-his') .'.pdf');
    }

    public function detail($id_permintaan)
    {
        $data = array();
        $no = 1;
        $total = 0;

        $permintaan = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                        ->join('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                         ->where('permintaan_detail.id_permintaan', $id_permintaan)
                         ->select('tanggal', 'kode_permintaan', 'nama_barang','subtotal','jumlah')
                         ->get();
        


                         
        foreach ($permintaan as $items ) {

            $row = array();
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_permintaan.'</span';
            $row['nama_barang'] = $items->nama_barang;
            $row['jumlah'] = $items->jumlah;
            $row['biaya'] = "Rp. " . format_uang($items->subtotal);

            $data[] = $row;
            $total += $items->subtotal;
        }
    
    $data[] = [
        'kode_permintaan' => '',
        'nama_barang' => '',
        'biaya' => "<b> Rp ".format_uang($total). "</b>",
        'jumlah' => '<b>Total Biaya</b>',
    ];
    return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['jumlah', 'biaya', 'kode_permintaan'])
            ->make(true);
    }
}
