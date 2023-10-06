<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\PenerimaanDetail;
use App\Models\Perencanaan;
use App\Models\PerencanaanDetail;
use App\Models\Setting;
use App\Models\Member;
use App\Models\Penerimaan_cart;
use Illuminate\Cache\NullStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class BarangdatangController extends Controller
{
    public function index()
    {
        $rencana = $perencanaan = Perencanaan::with('lokasi', 'member')->where('status', null)->get();
        $rencana_edit = Perencanaan::all();
        $detail = PerencanaanDetail::with('barang', 'perencanaan')->get();
        $penerimaan = DB::table('penerimaan')
                ->leftJoin('penerimaan_detail','penerimaan_detail.id_penerimaan','=','penerimaan.id_penerimaan')
                ->leftJoin('pr_detail','pr_detail.id_perencanaan_detail', 'penerimaan_detail.id_perencanaan_detail')
                ->leftJoin('tb_ren','tb_ren.id_perencanaan', 'penerimaan_detail.id_perencanaan')
                ->leftJoin('barang','barang.id_barang','=','penerimaan_detail.id_barang')
                ->leftJoin('member', 'member.id', 'tb_ren.id_aset')
                ->where('penerimaan_detail.deleted_at', NULL)
                ->orderBy('id_penerimaan_detail', 'DESC')
                ->get();
        return view('penerimaan.index', compact('detail', 'rencana', 'penerimaan', 'rencana_edit'));
    }

    public function data()
    {
        $penerimaan = DB::table('penerimaan')
                ->leftJoin('penerimaan_detail','penerimaan_detail.id_penerimaan','=','penerimaan.id_penerimaan')
                ->leftJoin('tb_ren', 'tb_ren.id_perencanaan', '=', 'penerimaan.id_perencanaan')
                ->leftJoin('barang','barang.id_barang','=','penerimaan_detail.id_barang')
                ->select('nomor_terima', 'tb_ren.kode_rencana', 'tanggal_terima', 'kode_barang', 'nama_barang', 'jumlah_terima', 'sisa_vol_terima', 'harga_terima', 'subtotal_terima', 'id_aset'  )
                ->get();

                return datatables()
                ->of($penerimaan)
                ->addIndexColumn()
                ->make(true);
        
    }

    public function create(Request $request)
    {
        $penerimaan = Penerimaan::latest()->first() ?? new Penerimaan();
        $nomor_terima1 = substr($penerimaan->nomor_terima,2);
        $nomor_terima = (int) $nomor_terima1 +1;

        if(session('id_penerimaan')){
            return redirect(route('penerimaan_detail.index'))->with('error', 'masih ada transaksi aktif Harap selesaikan dahulu'); 
        }else{
            $terima = new Penerimaan();
            $terima->tanggal_terima = $request->tanggal;
            $terima->nomor_terima = 'T-'.tambah_nol_didepan($nomor_terima, 5);
            $terima->id_perencanaan = $request->id_perencanaan;
            $terima->penerima = $request->penerima;
            $terima->user_input = Auth::user()->id;
            $terima->save();

            session(['penerimaan' => $request->all()]);
    
            session([
                    'id_perencanaan' => $request->id_perencanaan,
                    'id_penerimaan' => $terima->id_penerimaan
                    ]);
    
            return redirect()->route('penerimaan_detail.index');
        }
    }

    public function simpan(Request $request)
    {
        
        $detail =  Penerimaan_cart::where('id_user', Auth::user()->id)->get();
        //update stok barang
        foreach ($detail as $item) {

            $produk = Barang::find($item->id_barang);
            $produk->stok += $item->jumlah_terima;
            $produk->update();
            
        }
        //masukkan ke table penerimaandetail
        foreach ($detail as $penerimaan) {
            $detail = new PenerimaanDetail();
            $detail->id_penerimaan = session('id_penerimaan');
            $detail->id_perencanaan = $penerimaan->id_perencanaan;
            $detail->id_perencanaan_detail = $penerimaan->id_perencanaan_detail;
            $detail->id_barang = $penerimaan->id_barang;
            $detail->jumlah_terima = $penerimaan->jumlah_terima;
            $detail->sisa_vol_terima = $penerimaan->sisa_vol_terima;
            $detail->harga_terima = $penerimaan->harga_terima;
            $detail->subtotal_terima = $penerimaan->subtotal_terima;
            $detail->status_penerimaan = $penerimaan->status_penerimaan;
            $detail->user_input = Auth::user()->id;
            $detail->save();
        }

        //hapus data di penerimaan cart
        Penerimaan_cart::where('id_user', Auth::user()->id)->delete();
        
        return redirect()->route('penerimaan.index');

    }

    public function edit($id)
    {
        session(['id_penerimaan' => $id]);
        return redirect()->route('penerimaan.show');
    }

    public function show()
    {
        $id_penerimaan = session('id_penerimaan');
        $penerimaan = Penerimaan::with('perencanaan')->find($id_penerimaan);
        $perencanaan = PerencanaanDetail::with('barang')->where('id_perencanaan', $penerimaan->id_perencanaan)->where('sisa_terima', '!=', 0)->get();
        return view('penerimaan_edit.index', compact('penerimaan', 'perencanaan'));
    }

    public function update(Request $request, $id)
    {
        $penerimaan = Penerimaan::find($id);

        $penerimaan->tanggal_terima = $request->tanggal_terima;
        $penerimaan->penerima = $request->penerima;
        $penerimaan->user_update = Auth::user()->id;
        $penerimaan->update();
    }

    public function data_Update($id)
    {
        $detail = PenerimaanDetail::with('barang')
            ->where('id_penerimaan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">'. $item->barang['kode_barang'] .'</span';
            $row['nama_barang'] = $item->barang['nama_barang'];
            $row['harga']  = 'Rp. '. format_uang($item->harga_terima);
            $row['jumlah']      = $item->jumlah_terima;
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal_terima);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('penerimaan_detail.data_destroy', $item->id_penerimaan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->biaya * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_barang' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_barang' => '',
            'harga'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];
        
        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_barang', 'jumlah', 'harga'])
            ->make(true);
    }

    public function update_form(Request $request)
    {
        $penerimaan = Penerimaan::where('id_penerimaan', session('id_penerimaan'))->first();

        $penerimaan->tanggal_terima = $request->tanggal_terima;
        $penerimaan->penerima = $request->penerima;
        $penerimaan->update();

        return redirect()->route('penerimaan.index');
    }

    public function laporan(request $request) 
    {
        
        $rencana = Penerimaan::join('tb_ren', 'tb_ren.id_perencanaan', 'penerimaan.id_penerimaan')
                        ->leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
                        ->groupBy('id_aset')
                        ->get();
        return view('laporan_penerimaan.index', compact('rencana'));
    }

    public function Getdata($id, $tanggal_awal, $tanggal_akhir)
    {
        
        $data = array();
        $no = 1;
        $total = 0;
        $perencanaan = PenerimaanDetail::leftjoin('penerimaan', 'penerimaan.id_penerimaan', 'penerimaan_detail.id_penerimaan')
                        ->leftjoin('tb_ren', 'tb_ren.id_perencanaan', 'penerimaan.id_perencanaan')
                        ->join('barang', 'barang.id_barang', 'penerimaan_detail.id_barang')
                         ->where('tb_ren.id_aset', $id)
                         ->where('penerimaan.tanggal_terima', '>=', $tanggal_awal)
                         ->where('penerimaan.tanggal_terima', '<=', $tanggal_akhir)
                         ->select('tanggal_terima', 'penerimaan.penerima as penerima','kode_rencana', 'nomor_terima', 'nama_barang', 'jumlah_terima','harga_terima', 'subtotal_terima', 'status_penerimaan', 'sisa_vol_terima')
                         ->get();
        


                         
        foreach ($perencanaan as $items ) {

            $row = array();
            $row['tanggal'] = date('d-m-Y', strtotime($items->tanggal_terima));
            $row['kode_perencanaan'] = '<span class="label label-info">' . $items->kode_rencana.'</span>';
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->nomor_terima.'</span>';
            $row['nama_barang'] = $items->nama_barang;
            $row['jumlah'] = $items->jumlah_terima;
            $row['sisa'] = $items->sisa_vol_terima;
            $row['biaya_perkiraan'] = "Rp. " . format_uang($items->harga_terima);
            $row['subtotal_perkiraan'] = "Rp. " . format_uang($items->subtotal_terima);
            $row['status'] = $items->status_penerimaan;
            $row['penerima'] = $items->penerima;

            $data[] = $row;
            $total += $items->subtotal_terima;
        }
    
    $data[] = [
        'tanggal' => '',
        'kode_perencanaan' => '',
        'kode_permintaan' => '',
        'nama_barang' => '',
        'jumlah' => "",
        'sisa' => "",
        'biaya_perkiraan' => '<b>Total Biaya</b>',
        'subtotal_perkiraan' =>"<b> Rp ".format_uang($total). "</b>",
        'status' => '',
        'penerima' => '',
    ];
    return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['subtotal_perkiraan', 'biaya_perkiraan', 'kode_permintaan', 'kode_perencanaan'])
            ->make(true);
    }

    public function cetak_laporan($id, $tanggal_awal, $tanggal_akhir)
    {
        $setting = Setting::first();
        $member = Member::find($id);
        $perencanaan = Penerimaan::leftjoin('tb_ren', 'tb_ren.id_perencanaan', 'penerimaan.id_perencanaan')
                         ->leftjoin('penerimaan_detail', 'penerimaan_detail.id_penerimaan', 'penerimaan.id_penerimaan')
                         ->where('penerimaan_detail.deleted_at', Null)
                         ->where('tb_ren.id_aset', $id)
                         ->where('penerimaan.tanggal_terima', '>=', $tanggal_awal)
                         ->where('penerimaan.tanggal_terima', '<=', $tanggal_akhir)
                         ->get();
        $total = $perencanaan->sum('subtotal_terima');

        $pdf = PDF::loadView('laporan_penerimaan.laporan', compact('setting', 'perencanaan', 'member', 'total', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Perencanaan-'. date('Y-m-d-his') .'.pdf');
    }

    public function allRencana()
    {
        $detail = PenerimaanDetail::where('deleted_at', NULL)->sum('subtotal_terima');
        return view('laporan_penerimaan_all.index', compact('detail'));
    }

    public function allUnit()
    {
        $perencanaan = Penerimaan::leftjoin('penerimaan_detail', 'penerimaan_detail.id_penerimaan', 'penerimaan.id_penerimaan')
            ->leftjoin('barang', 'barang.id_barang', 'penerimaan_detail.id_barang')
            ->leftjoin('tb_ren', 'tb_ren.id_perencanaan', 'penerimaan_detail.id_perencanaan')
            ->leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
            ->where('penerimaan_detail.deleted_at', NULL)
            ->get();

        $data = array();
        
            foreach ($perencanaan as $items ) {

                $row = array();
                $row['tanggal'] = $items->tanggal_terima;
                $row['kode_member'] = $items->kode_kabin;
                $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_rencana.'</span';
                $row['kode_terima'] = '<span class="label label-danger">'.$items->nomor_terima.'</span';
                $row['nama_barang'] = $items->nama_barang;
                $row['jumlah'] = $items->jumlah_terima;
                $row['biaya'] = 'Rp ' . format_uang($items->subtotal_terima);
    
                $data[] = $row;
            }

        return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['jumlah', 'biaya', 'kode_permintaan', 'kode_terima'])
                ->make(true);
    }

    public function getAll($tanggal_awal, $tanggal_akhir){
        $detail = Penerimaan::leftjoin('penerimaan_detail', 'penerimaan_detail.id_penerimaan', 'penerimaan.id_penerimaan')
                    ->leftjoin('barang', 'barang.id_barang', 'penerimaan_detail.id_barang')
                    ->leftjoin('tb_ren', 'tb_ren.id_perencanaan', 'penerimaan.id_perencanaan')
                    ->leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
                    ->where('penerimaan_detail.deleted_at', NULL)
                    ->where('tanggal_terima', '>=', $tanggal_awal)
                    ->where('tanggal_terima', '<=', $tanggal_akhir)
                    ->get();

        $data = array();

        foreach ($detail as $items ) {

            $row = array();
            $row['tanggal'] = date('d-m-Y', strtotime($items->tanggal_rencana));
            $row['kode_member'] = $items->kode_kabin;
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_rencana.'</span';
            $row['kode_terima'] = '<span class="label label-info">'.$items->nomor_terima.'</span';
            $row['nama_barang'] = $items->nama_barang;
            $row['jumlah'] = $items->jumlah_terima;
            $row['biaya'] = 'Rp ' . format_uang($items->subtotal_terima);

            $data[] = $row;
        }

        return datatables()
                ->of($data)
                ->addIndexColumn()
                ->rawColumns(['jumlah', 'biaya', 'kode_permintaan', 'kode_terima'])
                ->make(true);
    }

    public function getTotal($tanggal_awal, $tanggal_akhir){
        $rencana_detail = Penerimaan::leftjoin('penerimaan_detail', 'penerimaan_detail.id_penerimaan', 'penerimaan.id_penerimaan')
                                    ->where('penerimaan_detail.deleted_at', NULL)
                                    ->where('tanggal_terima', '>=' , $tanggal_awal)
                                    ->where('tanggal_terima', '<=', $tanggal_akhir)
                                    ->sum('subtotal_terima');

        return response()->json(format_uang($rencana_detail));                                  
    }

    public function laporanRekap($tanggal_awal, $tanggal_akhir){
        $setting = Setting::first();
        $permintaan = Perencanaan::leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
                    ->groupBy('id_aset')
                    ->selectRaw('kode_kabin, sum(total_item) as sum_item, sum(total_harga_perkiraan) as sum_harga')
                    ->orderBy('kode_kabin', 'asc')
                    ->where('tanggal_rencana', '>=', $tanggal_awal)
                    ->where('tanggal_rencana', '<=', $tanggal_akhir)
                    ->get();
        $sum = $permintaan->sum('sum_harga');
        $pdf = PDF::loadView('laporan_perencanaan_all.rekap', compact('setting', 'permintaan', 'sum', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }

    public function laporanAllUnit($tanggal_awal, $tanggal_akhir)
    {
        $setting = Setting::first();
        $permintaan = PerencanaanDetail::leftjoin('tb_ren', 'tb_ren.id_perencanaan','pr_detail.id_perencanaan')
                        ->leftjoin('barang', 'barang.id_barang', 'pr_detail.id_barang')
                        ->join('member', 'member.id', 'tb_ren.id_aset')
                        ->where('tanggal_rencana', '>=', $tanggal_awal)
                        ->where('tanggal_rencana', '<=', $tanggal_akhir)
                        ->orderBy('kode_kabin', 'asc')
                        ->select('tanggal_rencana', 'kode_rencana', 'nama_barang', 'kode_kabin','subtotal_perkiraan','jumlah')
                        ->get();
        $sum = $permintaan->sum('subtotal_perkiraan');

        $pdf = PDF::loadView('laporan_perencanaan_all.laporan', compact('setting', 'permintaan', 'sum', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }


    


}
