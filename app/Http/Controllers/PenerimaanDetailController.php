<?php

namespace App\Http\Controllers;
use App\Models\Ban;
use App\Models\PerencanaanDetail;
use App\Models\PenerimaanDetail;
use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\Perencanaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanDetailController extends Controller
{
    public function index()
    {
        $id_perencanaan = session('id_perencanaan');
        $rencana = Perencanaan::where('id_perencanaan', $id_perencanaan)->first();
        $id_penerimaan = session('id_penerimaan');
        $penerimaan = Penerimaan::where('id_penerimaan', $id_penerimaan)->first();
        $perencanaan = PerencanaanDetail::with('barang')->where('id_perencanaan', $id_perencanaan)->where('sisa_terima', '!=', 0)->get();
        $total = PenerimaanDetail::where('id_penerimaan', $id_penerimaan)->sum('subtotal_terima');
        if (! $perencanaan) {
            abort(404);
        }

        return view('penerimaan_detail.index', compact('perencanaan', 'total', 'penerimaan', 'rencana'));
    }

    public function data($id)
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

    public function store(Request $request)
    {
        $perencanaan_detail = PerencanaanDetail::where('id_perencanaan_detail', $request->id_perencanaan_detail)->first();
        $sisa_terima = $perencanaan_detail->sisa_terima - $request->jumlah_terima;
        $produk = Barang::where('id_barang', $request->id_barang)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        if($request->id_kategori == 5){
            session(['barang_terima' => $request->all()]);
            return response()->json('Data berhasil disimpan', 200);
        }else{
            $detail = new PenerimaanDetail();
            $detail->id_penerimaan = $request->id_penerimaan;
            $detail->id_perencanaan = $request->id_perencanaan;
            $detail->id_perencanaan_detail = $request->id_perencanaan_detail;
            $detail->id_barang = $request->id_barang;
            $detail->jumlah_terima = $request->jumlah_terima;
            $detail->sisa_vol_terima = $sisa_terima;
            $detail->harga_terima = $request->harga_terima;
            $detail->subtotal_terima = $request->harga_terima * $request->jumlah_terima;
            if($perencanaan_detail->sisa_terima - $request->jumlah_terima == 0){
                $detail->status_penerimaan = 'Sudah Diterima'; 
            }else{
                $detail->status_penerimaan = 'Diterima sebagian'; 
            }
            $detail->user_input = Auth::user()->id;
            $detail->save();
            
            //update perencanaan detail di sisa terima 
            $perencanaan_detail->sisa_terima = $sisa_terima;
            $perencanaan_detail->biaya_realisasi = $detail->harga_terima;
            $perencanaan_detail->subtotal = $detail->subtotal_terima;
            if($sisa_terima == 0){
                $perencanaan_detail->status = "Terealisasi";
            }
            $perencanaan_detail->update();


            //update table rencana di biaya realisasi
            $jumlah_realisasi = PerencanaanDetail::where('id_perencanaan', $detail->id_rencana)->sum('subtotal');
            $penerimaan_detail = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->count();
            $penerimaan_by_status = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->where('status', 'Terealisasi')->count();
            $perencanaan = Perencanaan::find($detail->id_perencanaan);
            if($penerimaan_detail !== $penerimaan_by_status){
                $perencanaan->total_harga_realisasi = $jumlah_realisasi;
                $perencanaan->update();
            }else{
                $perencanaan->total_harga_realisasi = $jumlah_realisasi;
                $perencanaan->status = "Seluruh Rencana Telah Terealisasi";
                $perencanaan->update();
            }
            

            return redirect()->route('penerimaan_detail.index');
        }

        
    }

    public function show($id)
    {
        $permintaan = PenerimaanDetail::find($id);

        return response()->json($permintaan);
    }

    public function data_destroy($id)
    {
        $detail = PenerimaanDetail::find($id);
        $pr_detail = PerencanaanDetail::where('id_perencanaan_detail', $detail->id_perencanaan_detail)->first();
        $pr_detail->sisa_terima += $detail->jumlah_terima;
        $pr_detail->update();
        
        $detail->delete();

        $detail->user_delete = Auth::user()->id;
        $detail->update();

        $barang = Barang::where('id_barang', $detail->id_barang)->first();
        $barang->stok -= $detail->jumlah_terima; 
        $barang->update();  
        
        

        return response(null, 204);
    }

    public function update(Request $request)
    {
        $perencanaan_detail = PerencanaanDetail::where('id_perencanaan_detail', $request->id_perencanaan_detail)->first();
        $sisa_terima = $perencanaan_detail->sisa_terima - $request->jumlah_terima;
        $produk = Barang::where('id_barang', $request->id_barang)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }


        $detail = new PenerimaanDetail();
        $detail->id_penerimaan = $request->id_penerimaan;
        $detail->id_perencanaan = $request->id_perencanaan;
        $detail->id_perencanaan_detail = $request->id_perencanaan_detail;
        $detail->id_barang = $request->id_barang;
        $detail->jumlah_terima = $request->jumlah_terima;
        $detail->sisa_vol_terima = $sisa_terima;
        $detail->harga_terima = $request->harga_terima;
        $detail->subtotal_terima = $request->harga_terima * $request->jumlah_terima;
        if($perencanaan_detail->sisa_terima - $request->jumlah_terima == 0){
            $detail->status_penerimaan = 'Sudah Diterima'; 
        }else{
            $detail->status_penerimaan = 'Diterima sebagian'; 
        }
        $detail->user_input = Auth::user()->id;
        $detail->save();
        
        //update perencanaan detail di sisa terima 
        $perencanaan_detail->sisa_terima = $sisa_terima;
        $perencanaan_detail->biaya_realisasi = $detail->harga_terima;
        $perencanaan_detail->subtotal = $detail->subtotal_terima;
        if($sisa_terima == 0){
            $perencanaan_detail->status = "Terealisasi";
        }
        $perencanaan_detail->update();


        //update table rencana di biaya realisasi
        $jumlah_realisasi = PerencanaanDetail::where('id_perencanaan', $detail->id_rencana)->sum('subtotal');
        $penerimaan_detail = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->count();
        $penerimaan_by_status = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->where('status', 'Terealisasi')->count();
        $perencanaan = Perencanaan::find($detail->id_perencanaan);
        if($penerimaan_detail !== $penerimaan_by_status){
            $perencanaan->total_harga_realisasi = $jumlah_realisasi;
            $perencanaan->update();
        }else{
            $perencanaan->total_harga_realisasi = $jumlah_realisasi;
            $perencanaan->status = "Seluruh Rencana Telah Terealisasi";
            $perencanaan->update();
        }
        

        return redirect()->route('penerimaan.show');
    }

    public function banStore(Request $request)
    {
        $items = [$request->all('ban')];
        foreach ($items[0]['ban'] as $value) {
            $ban = Ban::orderBy('kode_ban', 'DESC')->latest()->first() ?? new Ban();
            $kode_ban1 = substr($ban->kode_ban,4);
            $kode_ban = (int) $kode_ban1 +1;

            $request['kode_ban'] = 'BAN-'. tambah_nol_didepan($kode_ban, 6);

            $ban = Ban::create([
                'id_barang' => session("barang_terima.id_barang"),
                'nomor_seri' => $value["'nomor_seri'"],
                'kode_ban' => $request['kode_ban'],
                'tgl_beli' => $value["'tanggal_beli'"]
            ]);
        }
        //input list barang yang diterima 
        $perencanaan_detail = PerencanaanDetail::where('id_perencanaan_detail', session("barang_terima.id_perencanaan_detail"))->first();
        $sisa_terima = $perencanaan_detail->sisa_terima - session('barang_terima.jumlah_terima');

        $detail = new PenerimaanDetail();
        $detail->id_penerimaan = session('barang_terima.id_penerimaan');
        $detail->id_perencanaan = session('barang_terima.id_perencanaan');
        $detail->id_perencanaan_detail = session('barang_terima.id_perencanaan_detail');
        $detail->id_barang = session('barang_terima.id_barang');
        $detail->jumlah_terima = session('barang_terima.jumlah_terima');
        $detail->sisa_vol_terima = $sisa_terima;
        $detail->harga_terima = session('barang_terima.harga_terima');
        $detail->subtotal_terima = session('barang_terima.harga_terima') * session('barang_terima.jumlah_terima');;
        if($perencanaan_detail->sisa_terima - session('barang_terima.jumlah_terima') == 0){
            $detail->status_penerimaan = 'Sudah Diterima'; 
        }else{
            $detail->status_penerimaan = 'Diterima sebagian'; 
        }
        $detail->user_input = Auth::user()->id;
        $detail->save();
        
        //update perencanaan detail di sisa terima 
        $perencanaan_detail->sisa_terima = $sisa_terima;
        $perencanaan_detail->biaya_realisasi = $detail->harga_terima;
        $perencanaan_detail->subtotal = $detail->subtotal_terima;
        if($sisa_terima == 0){
            $perencanaan_detail->status = "Terealisasi";
        }
        $perencanaan_detail->update();


        //update table rencana di biaya realisasi
        $jumlah_realisasi = PerencanaanDetail::where('id_perencanaan', $detail->id_rencana)->sum('subtotal');
        $penerimaan_detail = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->count();
        $penerimaan_by_status = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->where('status', 'Terealisasi')->count();
        $perencanaan = Perencanaan::find($detail->id_perencanaan);
        if($penerimaan_detail !== $penerimaan_by_status){
            $perencanaan->total_harga_realisasi = $jumlah_realisasi;
            $perencanaan->update();
        }else{
            $perencanaan->total_harga_realisasi = $jumlah_realisasi;
            $perencanaan->status = "Seluruh Rencana Telah Terealisasi";
            $perencanaan->update();
        }
        
        $request->session()->forget('barang_terima');
        return redirect()->route('penerimaan_detail.index');
    }

    
}
