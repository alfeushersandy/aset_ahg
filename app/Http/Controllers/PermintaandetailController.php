<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Permintaandetail;
use Illuminate\Http\Request;

class PermintaandetailController extends Controller
{
    public function index()
    {
        $id_permintaan = session('id_permintaan');
        $produk = Barang::orderBy('nama_barang')->get();
        $permintaan = Permintaan::find($id_permintaan);

        if (! $permintaan) {
            abort(404);
        }

        return view('sparepartdetail.index', compact('id_permintaan', 'produk', 'permintaan'));
    }

    public function data($id)
    {
        $detail = Permintaandetail::with('barang')
            ->where('id_permintaan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">'. $item->barang['kode_barang'] .'</span';
            $row['nama_barang'] = $item->barang['nama_barang'];
            $row['harga']  = 'Rp. '. format_uang($item->biaya);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_permintaan_detail .'" value="'. $item->jumlah .'">';
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('permintaandetail.destroy', $item->id_permintaan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
            ->rawColumns(['aksi', 'kode_barang', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Barang::where('id_barang', $request->id_produk)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new Permintaandetail();
        $detail->id_permintaan = $request->id_permintaan;
        $detail->id_barang = $produk->id_barang;
        $detail->biaya = $produk->harga;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga;
        $detail->save();


        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = Permintaandetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->biaya * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = Permintaandetail::find($id);
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
