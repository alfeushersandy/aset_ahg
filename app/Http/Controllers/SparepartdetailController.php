<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicedetail;
use App\Models\Produk;
use App\Models\Permintaan;

class SparepartdetailController extends Controller
{
    public function index()
    {
        $id_service = session('id_service');
        $id_permintaan = session('id_permintaan');
        $produk = Produk::orderBy('nama_produk')->get();
        $permintaan = Permintaan::find($id_permintaan);

        if (! $permintaan) {
            abort(404);
        }

        return view('sparepartdetail.index', compact('id_service', 'produk', 'permintaan'));
    }

    public function data($id)
    {
        $detail = Servicedetail::with('produk')
            ->where('id_service', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] .'</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_beli']  = 'Rp. '. format_uang($item->biaya);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_service_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('sparepartdetail.destroy', $item->id_service_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->biaya * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">'. $total .'</div>
                <div class="total_item hide">'. $total_item .'</div>',
            'nama_produk' => '',
            'harga_beli'  => '',
            'jumlah'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new Servicedetail();
        $detail->id_service = $request->id_service;
        $detail->id_produk = $produk->id_produk;
        $detail->biaya = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_beli;
        $detail->save();


        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = Servicedetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->biaya * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = Servicedetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($total)
    {
        $bayar = $total;
        $data  = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar). ' Rupiah')
        ];

        return response()->json($data);
    }


    
}
