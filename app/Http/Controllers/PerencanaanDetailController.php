<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Ban;
use App\Models\Perencanaan;
use App\Models\PerencanaanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerencanaanDetailController extends Controller
{
    public function index()
    {
        $id_rencana = session('id_rencana');
        $produk = Barang::orderBy('nama_barang')->get();
        $ban = Ban::orderBy('kode_ban')->get();
        $perencanaan = Perencanaan::with('member')->where('id_perencanaan', $id_rencana)->first();
        $total = PerencanaanDetail::where('id_perencanaan', $perencanaan->id_perencanaan)->sum('subtotal_perkiraan');
        $total_item =  PerencanaanDetail::where('id_perencanaan', $perencanaan->id_perencanaan)->sum('jumlah');

        if (! $perencanaan) {
            abort(404);
        }

        return view('perencanaandetail.index', compact('produk', 'ban', 'perencanaan', 'total', 'total_item'));
    }

    public function data($id)
    {
        $detail = PerencanaanDetail::with('barang')
            ->where('id_perencanaan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_barang'] = '<span class="label label-success">'. $item->barang['kode_barang'] .'</span';
            $row['nama_barang'] = $item->barang['nama_barang'];
            $row['harga']  = 'Rp. '. format_uang($item->biaya_perkiraan);
            $row['jumlah']      = $item->jumlah;
            $row['subtotal']    = 'Rp. '. format_uang($item->subtotal_perkiraan);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`'. route('perencanaan_detail.destroy', $item->id_perencanaan_detail) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $produk = Barang::where('id_barang', $request->id_barang)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new PerencanaanDetail();
        $detail->id_perencanaan = $request->id_perencanaan;
        $detail->id_barang = $request->id_barang;
        $detail->biaya_perkiraan = $request->harga;
        $detail->jumlah = $request->jumlah;
        $detail->sisa_terima = $request->jumlah;
        $detail->subtotal_perkiraan = $request->harga * $request->jumlah;
        $detail->status = 'Permintaan';
        $detail->input_by = Auth::user()->id;
        $detail->save();


        return redirect()->route('perencanaan_detail.index');
    }

    public function destroy($id)
    {
        $detail = PerencanaanDetail::find($id);
        session(['id_perencanaan' => $detail->id_perencanaan]);
        $detail->delete();

        $detail_barang = PerencanaanDetail::where('id_perencanaan_detail', $id)->get();
        if(!$detail_barang){
            $perencanaan = Perencanaan::find(session('id_perencanaan'));
            $perencanaan->delete();
        }
        
        return response()->json('data berhasil dihapus');
    }
}
