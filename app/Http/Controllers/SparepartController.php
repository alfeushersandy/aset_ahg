<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Servicedetail;
use App\Models\Produk;

class SparepartController extends Controller
{
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
