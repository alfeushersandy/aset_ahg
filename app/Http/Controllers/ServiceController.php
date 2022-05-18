<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Permintaan;
use App\Models\Member;
use App\Models\Permintaandetail;
use App\Models\Setting;

use PDF;

class ServiceController extends Controller
{
    public function index()
    {
        $user = auth()->user()->level;
        if($user === 3){
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->where('status', 'Check by Mechanic')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->where('nama', auth()->user()->name)
                        ->get();
            $sparepart = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->where('status', 'On Progress')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->where('nama', auth()->user()->name)
                        ->get();
        }else{
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->where('status', 'Check by Mechanic')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->get();
            $sparepart = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->where('status', 'On Progress')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->get();
        }
        

        return view('service.index', compact('permintaan', 'sparepart'));
    }

    public function data() 
    {
        $service = DB::table('permintaan')
                ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                ->where('status', 'On Progress')
                ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                ->get();

        return datatables()
            ->of($service)
            ->addIndexColumn()
            ->addColumn('select_all', function ($service) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $service->id .'">
                ';
            })
            ->addColumn('aksi', function ($service) {
                return '
               
                        <div class="btn-group">
                            <a href="'. route('service.update', $service->id) .'" class="btn btn-success">selesai</a>
                        </div>
                
                ';
            })
            ->rawColumns(['aksi', 'select_all'])
            ->make(true);
    }

    public function create($id)
    {
        $pemeriksaan = Permintaan::FindOrFail($id);

        $pemeriksaan->status = 'On Progress';
        $pemeriksaan->update();

        return redirect()->route('service.index');
    }

    public function update($id)
    {
        $pemeriksaan = Permintaan::findorfail($id);
        $pemeriksaan->status = 'Selesai';
        $pemeriksaan->update();

        return redirect()->route('service.index');
    }

    public function selesai() {
        $user = auth()->user()->level;
        if($user === 3){
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->where('status', 'Selesai')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->where('nama', auth()->user()->name)
                        ->get();           
        }else{
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->where('status', 'Selesai')
                        ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                        ->get();
        }
        

        return view('selesai.index', compact('permintaan'));
    }

    public function histori(request $request) 
    {
        
        $member = Member::OrderBy('kode_member', 'ASC')->get();
        $kode_kendaraan = $request->kode_kendaraan;
        $permintaan = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
        ->leftjoin('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
         ->where('permintaan.kode_customer', $kode_kendaraan)
         ->select('tanggal', 'kode_permintaan', 'nama_barang','biaya','jumlah')
         ->get();
        return view('service_history.index', compact('member', 'kode_kendaraan', 'permintaan'));
    }

    public function Getdata($id)
    {
        
        $data = array();
        $no = 1;
        $total = 0;
        $permintaan = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                        ->join('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                         ->where('permintaan.kode_customer', $id)
                         ->select('tanggal', 'kode_permintaan', 'nama_barang','subtotal','jumlah')
                         ->get();
        $sum = $permintaan->sum('subtotal');


                         
        foreach ($permintaan as $items ) {

            $row = array();
            $row['tanggal'] = $items->tanggal;
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_permintaan.'</span';
            $row['nama_barang'] = $items->nama_barang;
            $row['jumlah'] = $items->jumlah;
            $row['biaya'] = $items->subtotal;

            $data[] = $row;
            $total += $items->subtotal;
        }
    
    $data[] = [
        'tanggal' => '',
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

    public function allArmada()
    {
        return view('all_unit_service.index');
    }

    public function allUnit()
    {
        $member = Member::with('permintaan')->get();
         
        foreach ($member as $items ) {

            $row = array();
            $row['kode_member'] = $items->kode_kabin;
            $row['jumlah'] = $items->permintaan->sum('total_item');
            $row['biaya'] = $items->permintaan->sum('total_harga');

            $data[] = $row;
        }
    

    return datatables()
            ->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    
    public function laporan($id)
    {
        $setting = Setting::first();
        $member = Member::find($id);
        $permintaan = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                        ->join('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                         ->where('permintaan.kode_customer', $id)
                         ->orderBy('kode_permintaan', 'desc')
                         ->select('tanggal', 'kode_permintaan', 'nama_barang','subtotal','jumlah')
                         ->get();
        $sum = $permintaan->sum('subtotal');

        $pdf = PDF::loadView('service_history.laporan', compact('setting', 'permintaan', 'member', 'sum'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
    

    
    

  
    
   
}
