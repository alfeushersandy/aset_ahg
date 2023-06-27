<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\Petugas;
use App\Models\Lokasi;
use App\Models\Perencanaan;
use App\Models\PerencanaanDetail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PerencanaanController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all()->pluck('nama_lokasi', 'id_lokasi');
        $kendaraan =  Member::where('status', 'On Duty')->orWhere('status', 'Rusak')->orWhere('status', 'On Service')->orWhere('status', 'OFF')->orWhere('status', 'Tersedia')->orWhere('status', NULL)->get();
        $perencanaan = Perencanaan::with('lokasi', 'member')->get();
        return view('perencanaan.index', compact('kendaraan', 'lokasi', 'perencanaan'));
    }

    public function create(Request $request)
    {
        $permintaan = Perencanaan::latest()->first() ?? new Perencanaan();
        $kode_permintaan1 = substr($permintaan->kode_rencana,2);
        $kode_permintaan = (int) $kode_permintaan1 +1;

        $permintaan = new Perencanaan();
        $permintaan->tanggal_rencana = $request->tanggal;
        $permintaan->kode_rencana = 'PR'.tambah_nol_didepan($kode_permintaan, 5);
        $permintaan->id_aset = $request->id_aset;
        $permintaan->id_lokasi = $request->id_lokasi;
        $permintaan->km = $request->km;
        $permintaan->total_item = 0;
        $permintaan->total_harga_master = 0;
        $permintaan->total_harga_perkiraan = 0;
        $permintaan->total_harga_realisasi = 0;
        $permintaan->input_by = Auth::user()->id;
        $permintaan->save();

        session(['id_rencana' => $permintaan->id_perencanaan]);

        return redirect()->route('perencanaan_detail.index');
    }

    public function store(Request $request)
    {
        
        $perencanaan = Perencanaan::findorfail($request->id_perencanaan);
        $perencanaan->total_item = $request->total_item;
        $perencanaan->total_harga_perkiraan = $request->total;
        $perencanaan->update();

        return redirect()->route('perencanaan.selesai');
    }

    public function selesai()
    {
        $setting = Setting::first();
        $id_perencanaan = session('id_rencana');
        return view('perencanaan.selesai', compact('setting', 'id_perencanaan'));
    }

    public function notaBesar()
    {
        $id_perencanaan = session('id_rencana');
        $setting = Setting::first();
        $perencanaan = Perencanaan::find($id_perencanaan);
        if (! $perencanaan) {
            abort(404);
        }
        $detail = PerencanaanDetail::with('barang')
            ->where('id_perencanaan', $id_perencanaan)
            ->get();

        $pdf = PDF::loadView('perencanaan.nota_besar', compact('setting', 'perencanaan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Rencana-'. date('Y-m-d-his') .'.pdf');
    }

    public function laporan(request $request) 
    {
        
        $rencana = Perencanaan::leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
                        ->groupBy('id_aset')
                        ->get();
        return view('laporan_perencanaan.index', compact('rencana'));
    }

    public function Getdata($id, $tanggal_awal, $tanggal_akhir)
    {
        
        $data = array();
        $no = 1;
        $total = 0;
        $perencanaan = PerencanaanDetail::leftjoin('tb_ren', 'tb_ren.id_perencanaan', 'pr_detail.id_perencanaan')
                        ->join('barang', 'barang.id_barang', 'pr_detail.id_barang')
                         ->where('tb_ren.id_aset', $id)
                         ->where('tb_ren.tanggal_rencana', '>=', $tanggal_awal)
                         ->where('tb_ren.tanggal_rencana', '<=', $tanggal_akhir)
                         ->select('tanggal_rencana', 'kode_rencana', 'nama_barang','pr_detail.jumlah as jumlah','biaya_perkiraan', 'km', 'subtotal_perkiraan', 'pr_detail.status as status')
                         ->get();
        


                         
        foreach ($perencanaan as $items ) {

            $row = array();
            $row['tanggal'] = $items->tanggal_rencana;
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_rencana.'</span';
            $row['nama_barang'] = $items->nama_barang;
            $row['jumlah'] = $items->jumlah;
            $row['biaya_perkiraan'] = $items->biaya_perkiraan;
            $row['subtotal_perkiraan'] = $items->subtotal_perkiraan;
            $row['status'] = $items->status;

            $data[] = $row;
            $total += $items->subtotal_perkiraan;
        }
    
    $data[] = [
        'tanggal' => '',
        'kode_permintaan' => '',
        'nama_barang' => '',
        'jumlah' => "",
        'biaya_perkiraan' => '<b>Total Biaya</b>',
        'subtotal_perkiraan' =>"<b> Rp ".format_uang($total). "</b>",
        'status' => '',
    ];
    return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['subtotal_perkiraan', 'biaya_perkiraan', 'kode_permintaan'])
            ->make(true);
    }

    public function cetak_laporan($id, $tanggal_awal, $tanggal_akhir)
    {
        $setting = Setting::first();
        $member = Member::find($id);
        $perencanaan = Perencanaan::where('tb_ren.id_aset', $id)
                         ->where('tb_ren.tanggal_rencana', '>=', $tanggal_awal)
                         ->where('tb_ren.tanggal_rencana', '<=', $tanggal_akhir)
                         ->get();
        $total = $perencanaan->sum('total_harga_perkiraan');

        $pdf = PDF::loadView('laporan_perencanaan.laporan', compact('setting', 'perencanaan', 'member', 'total', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Perencanaan-'. date('Y-m-d-his') .'.pdf');
    }

    public function allRencana()
    {
        $detail = PerencanaanDetail::all()->sum('subtotal_perkiraan');
        return view('laporan_perencanaan_all.index', compact('detail'));
    }

    public function allUnit()
    {
        $perencanaan = Perencanaan::leftjoin('pr_detail', 'pr_detail.id_perencanaan', 'tb_ren.id_perencanaan')
            ->leftjoin('barang', 'barang.id_barang', 'pr_detail.id_barang')
            ->leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
            ->get();

        $data = array();
        
            foreach ($perencanaan as $items ) {

                $row = array();
                $row['tanggal'] = $items->tanggal_rencana;
                $row['kode_member'] = $items->kode_kabin;
                $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_rencana.'</span';
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
        $detail = Perencanaan::leftjoin('pr_detail', 'pr_detail.id_perencanaan', 'tb_ren.id_perencanaan')
                    ->leftjoin('barang', 'barang.id_barang', 'pr_detail.id_barang')
                    ->leftjoin('member', 'member.id', '=', 'tb_ren.id_aset')
                    ->where('tanggal_rencana', '>=', $tanggal_awal)
                    ->where('tanggal_rencana', '<=', $tanggal_akhir)
                    ->get();

        $data = array();

        foreach ($detail as $items ) {

            $row = array();
            $row['tanggal'] = date('d-m-Y', strtotime($items->tanggal_rencana));
            $row['kode_member'] = $items->kode_kabin;
            $row['kode_permintaan'] = '<span class="label label-danger">'.$items->kode_rencana.'</span';
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
        $rencana_detail = Perencanaan::leftjoin('pr_detail', 'pr_detail.id_perencanaan', 'tb_ren.id_perencanaan')
                                    ->where('tanggal_rencana', '>=' , $tanggal_awal)
                                    ->where('tanggal_rencana', '<=', $tanggal_akhir)
                                    ->sum('subtotal_perkiraan');

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
