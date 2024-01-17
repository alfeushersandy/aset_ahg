<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Permintaan;
use App\Models\Member;
use App\Models\Permintaandetail;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use PDF;

class ServiceController extends Controller
{
    public function index()
    {
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                        ->where('permintaan.status', 'Submited')
                        ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                        ->get();
            $sparepart = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                        ->where('permintaan.status', 'On Progress')
                        ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                        ->get();
            $count = $permintaan->count();
        

        return view('service.index', compact('permintaan', 'sparepart', 'count'));
    }

    public function data() 
    {
        $service = DB::table('permintaan')
                ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                ->where('permintaan.status', 'On Progress')
                ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                ->get();

        return datatables()
            ->of($service)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($permintaan) {
                return date("d-m-Y", strtotime($permintaan->tanggal));
            })
            ->addColumn('select_all', function ($service) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $service->id .'">
                ';
            })
            ->addColumn('aksi', function ($service) {
                if(Auth::user()->level !== 4 && Auth::user()->level !== 5){
                    return '
                   
                            <div class="btn-group">
                                <a href="'. route('service.update', $service->id) .'" class="btn btn-success">selesai</a>
                            </div>
                    
                    ';
                }
            })
            ->rawColumns(['aksi', 'select_all', 'tanggal'])
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
            $permintaan = DB::table('permintaan')
                        ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                        ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                        ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                        ->where('permintaan.status', 'Selesai')
                        ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                        ->get();
        

        return view('selesai.index', compact('permintaan'));
    }

    public function histori(request $request) 
    {
        
        $member = Permintaan::leftjoin('member', 'member.kode_member', '=', 'permintaan.kode_customer')
                        ->groupBy('kode_customer')
                        ->get();
        return view('service_history.index', compact('member'));
    }

    public function Getdata($id, $tanggal_awal, $tanggal_akhir)
    {
        
        $data = array();
        $no = 1;
        $total = 0;
        $permintaan = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                        ->join('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                         ->where('permintaan.kode_customer', $id)
                         ->where('permintaan.tanggal', '>=', $tanggal_awal)
                         ->where('permintaan.tanggal', '<=', $tanggal_akhir)
                         ->select('tanggal', 'kode_permintaan', 'nama_barang','subtotal','jumlah')
                         ->get();
        


                         
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
        $detail = Permintaandetail::all()->sum('subtotal');
        return view('all_unit_service.index', compact('detail'));
    }

    public function allUnit()
    {
        $permintaan = Permintaan::leftjoin('permintaan_detail', 'permintaan_detail.id_permintaan', 'permintaan.id')
            ->leftjoin('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
            ->leftjoin('member', 'member.kode_member', '=', 'permintaan.kode_customer')
            ->select('tanggal','member.kode_kabin', 'kode_permintaan', 'nama_barang','subtotal','jumlah')
            ->get();

        $data = array();
        
            foreach ($permintaan as $items ) {

                $row = array();
                $row['tanggal'] = $items->tanggal;
                $row['kode_member'] = $items->kode_kabin;
                $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_permintaan.'</span';
                $row['nama_barang'] = $items->nama_barang;
                $row['jumlah'] = $items->jumlah;
                $row['biaya'] = 'Rp ' . format_uang($items->subtotal);
    
                $data[] = $row;
            }

        return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['jumlah', 'biaya', 'kode_permintaan'])
                ->make(true);
    }

    public function getAll($tanggal_awal, $tanggal_akhir){
        $permintaan = Permintaan::leftjoin('permintaan_detail', 'permintaan_detail.id_permintaan', 'permintaan.id')
                    ->leftjoin('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                    ->leftjoin('member', 'member.kode_member', '=', 'permintaan.kode_customer')
                    ->select('tanggal','member.kode_kabin', 'kode_permintaan', 'nama_barang','subtotal','jumlah')
                    ->where('tanggal', '>=', $tanggal_awal)
                    ->where('tanggal', '<=', $tanggal_akhir)
                    ->get();

        $data = array();

        foreach ($permintaan as $items ) {

            $row = array();
            $row['tanggal'] = $items->tanggal;
            $row['kode_member'] = $items->kode_kabin;
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_permintaan.'</span';
            $row['nama_barang'] = $items->nama_barang;
            $row['jumlah'] = $items->jumlah;
            $row['biaya'] = 'Rp ' . format_uang($items->subtotal);

            $data[] = $row;
        }

        return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['jumlah', 'biaya', 'kode_permintaan'])
                ->make(true);
    }

    public function getTotal($tanggal_awal, $tanggal_akhir){
        $permintaan_detail = Permintaan::leftjoin('permintaan_detail', 'permintaan_detail.id_permintaan', 'permintaan.id')
                                    ->where('tanggal', '>=' , $tanggal_awal)
                                    ->where('tanggal', '<=', $tanggal_akhir)
                                    ->sum('subtotal');

        return response()->json(format_uang($permintaan_detail));                                  
    }
    
    public function laporan($id, $tanggal_awal, $tanggal_akhir)
    {
        $setting = Setting::first();
        $member = Member::where('kode_member',$id)->first();
        $permintaan = Permintaan::leftjoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')   
                    ->leftjoin('permintaan_detail', 'permintaan_detail.id_permintaan', '=', 'permintaan.id') 
                    ->where('kode_customer', $id)
                    ->where('permintaan.tanggal', '>=', $tanggal_awal)
                    ->where('permintaan.tanggal', '<=', $tanggal_akhir)
                    ->get();
        $total = $permintaan->sum('subtotal');

        $pdf = PDF::loadView('service_history.laporan', compact('setting', 'permintaan', 'member', 'total', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function laporanRekap($tanggal_awal, $tanggal_akhir){
        $setting = Setting::first();
        $permintaan = Permintaan::leftjoin('member', 'member.kode_member', '=', 'permintaan.kode_customer')
                    ->groupBy('kode_customer')
                    ->selectRaw('kode_kabin, sum(total_item) as sum_item, sum(total_harga) as sum_harga')
                    ->orderBy('kode_kabin', 'asc')
                    ->where('tanggal', '>=', $tanggal_awal)
                    ->where('tanggal', '<=', $tanggal_akhir)
                    ->get();
        $sum = $permintaan->sum('sum_harga');
        $pdf = PDF::loadView('service_history.rekap', compact('setting', 'permintaan', 'sum', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function laporanAllUnit($tanggal_awal, $tanggal_akhir)
    {
        $setting = Setting::first();
        $permintaan = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                        ->leftjoin('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                        ->join('member', 'member.kode_member', 'permintaan.kode_customer')
                        ->where('tanggal', '>=', $tanggal_awal)
                        ->where('tanggal', '<=', $tanggal_akhir)
                        ->orderBy('kode_kabin', 'asc')
                        ->select('tanggal', 'kode_permintaan', 'nama_barang', 'kode_kabin','subtotal','jumlah', 'satuan')
                        ->get();
        $sum = $permintaan->sum('subtotal');

        $pdf = PDF::loadView('all_unit_service.laporan', compact('setting', 'permintaan', 'sum', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function detailByBarang()
    {
        $barang = Barang::all();
        return view('reportbybarang.index', compact('barang'));
    }
    

    public function getByBarang($id_barang, $tanggal_awal, $tanggal_akhir)
    {
        $data = array();
        $no = 1;
        $total = 0;
        $jumlah = 0;
        $detail = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                ->leftjoin('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                ->join('member', 'member.kode_member', 'permintaan.kode_customer')
                ->where('permintaan_detail.id_barang', $id_barang)
                ->where('tanggal', '>=', $tanggal_awal)
                ->where('tanggal', '<=', $tanggal_akhir)
                ->select('tanggal', 'kode_permintaan', 'nama_barang', 'kode_kabin','subtotal','jumlah', 'nopol')
                ->get();
        

        foreach ($detail as $items ) {

            $row = array();
            $row['tanggal'] = tanggal_indonesia($items->tanggal);
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_permintaan.'</span';
            $row['nama_barang'] = $items->nama_barang;
            $row['biaya'] = $items->subtotal;
            $row['kode_aset'] = $items->kode_kabin;
            $row['identitas'] = $items->nopol;
            $row['jumlah'] = $items->jumlah;

            $data[] = $row;
            $jumlah += $items->jumlah;
            $total += $items->subtotal;
        }
    
        $data[] = [
            'tanggal' => '',
            'kode_permintaan' => '',
            'kode_aset' => '',
            'identitas' => '',
            'nama_barang' => '',
            'biaya' => "<b> Rp ".format_uang($total). "</b>",
            'jumlah' => '<b>'. $jumlah . '</b>',
        ];
        return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['jumlah', 'biaya', 'kode_permintaan'])
                ->make(true);
        }

        public function cetakByBarang($id_barang, $tanggal_awal, $tanggal_akhir)
        {
            $setting = Setting::first();
            $detail = Permintaandetail::leftjoin('permintaan', 'permintaan.id', 'permintaan_detail.id_permintaan')
                    ->leftjoin('barang', 'barang.id_barang', 'permintaan_detail.id_barang')
                    ->join('member', 'member.kode_member', 'permintaan.kode_customer')
                    ->where('permintaan_detail.id_barang', $id_barang)
                    ->where('tanggal', '>=', $tanggal_awal)
                    ->where('tanggal', '<=', $tanggal_akhir)
                    ->select('tanggal', 'kode_permintaan', 'nama_barang', 'kode_kabin','subtotal','jumlah', 'nopol')
                    ->get();
            $sum = $detail->sum('subtotal');
            $sum_total = $detail->sum('jumlah');

            $pdf = PDF::loadView('reportbybarang.laporan', compact('setting', 'detail', 'sum', 'tanggal_awal', 'tanggal_akhir', 'sum_total'));
            $pdf->setPaper(0,0,609,440, 'potrait');
            return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');

        }
    

  
    
   
}
