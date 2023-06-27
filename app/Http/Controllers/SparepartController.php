<?php

namespace App\Http\Controllers;

use App\Models\Permintaandetail;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Servicedetail;
use App\Models\Produk;
use App\Models\Setting;
use PDF;

class SparepartController extends Controller
{
    public function index()
    {
        return view('laporan_sparepart.index');
    }

    public function data()
    {
        $sparepart = Permintaandetail::leftjoin('barang', 'barang.id_barang', '=', 'permintaan_detail.id_barang')
                    ->groupBy('permintaan_detail.id_barang')
                    ->selectRaw('nama_barang, kode_barang, sum(permintaan_detail.jumlah) as sum_item, sum(permintaan_detail.subtotal) as sum_harga')
                    ->orderBy('sum_item', 'DESC')
                    ->get();

        return datatables()
                ->of($sparepart)
                ->addIndexColumn()
                ->addColumn('sum_harga', function ($sparepart) {
                    return format_uang($sparepart->sum_harga);
                })
                ->make(true);
    }

    public function getAll($tanggal_awal, $tanggal_akhir){
        $sparepart = Permintaandetail::leftjoin('barang', 'barang.id_barang', '=', 'permintaan_detail.id_barang')
                    ->leftjoin('permintaan', 'permintaan.id', '=', 'permintaan_detail.id_permintaan')
                    ->groupBy('permintaan_detail.id_barang')
                    ->selectRaw('nama_barang, kode_barang, sum(permintaan_detail.jumlah) as sum_item, sum(permintaan_detail.subtotal) as sum_harga')
                    ->where('permintaan.tanggal', '>=', $tanggal_awal)
                    ->where('permintaan.tanggal', '<=', $tanggal_akhir)
                    ->orderBy('sum_item', 'DESC')
                    ->get();

    

        return datatables()
                ->of($sparepart)
                ->addIndexColumn()
                ->addColumn('sum_harga', function ($sparepart) {
                    return format_uang($sparepart->sum_harga);
                })
                ->make(true);
    }

    public function laporan($tanggal_awal, $tanggal_akhir){
        $setting = Setting::first();
        $permintaan = Permintaandetail::leftjoin('barang', 'barang.id_barang', '=', 'permintaan_detail.id_barang')
                    ->leftjoin('permintaan', 'permintaan.id', '=', 'permintaan_detail.id_permintaan')
                    ->groupBy('permintaan_detail.id_barang')
                    ->selectRaw('nama_barang, kode_barang, satuan, sum(permintaan_detail.jumlah) as sum_item, sum(permintaan_detail.subtotal) as sum_harga')
                    ->orderBy('sum_item', 'DESC')
                    ->where('permintaan.tanggal', '>=', $tanggal_awal)
                    ->where('permintaan.tanggal', '<=', $tanggal_akhir)
                    ->get();

        $pdf = PDF::loadView('laporan_sparepart.laporan', compact('setting', 'permintaan', 'tanggal_awal', 'tanggal_akhir'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Rekap_Sparepart-'. date('Y-m-d-his') .'.pdf');
    }

    public function create($id)
    {
        $service = new Service();
        $service->id_permintaan = $id;
        $service->total_item = 0;
        $service->total_harga = 0;
        $service->id_user = auth()->id();
        $service->save();

        session(['id_permintaan' => $service->id_permintaan]);
        session(['id_service' => $service->id_service]);

        return redirect()->route('sparepartdetail.index');
    }

    public function store(Request $request)
    {
        $service = Service::findorfail($request->id_service);
        $service->id_permintaan = session('id_permintaan');
        $service->total_item = $request->total_item;
        $service->total_harga = $request->total;
        $service->id_user = auth()->id();
        $service->update();

        $detail = Servicedetail::where('id_service', $service->id_service)->get();
        // $produk = Produk::find($detail->id_produk);
        foreach ($detail as $item) {

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
            
        }

        return redirect()->route('transaksi.selesai');
    }
}
