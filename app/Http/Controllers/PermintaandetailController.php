<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\Permintaandetail;
use App\Models\Ban;
use App\Models\Detail_pakai;
use Illuminate\Http\Request;

class PermintaandetailController extends Controller
{
    public function index($id)
    {
        // $id_barang_keluar = session('id_barang_keluar');
        $produk = Barang::orderBy('nama_barang')->get();
        $permintaan = Permintaan::where('id', $id)->first();
        $total = Permintaandetail::where('id_permintaan', $permintaan->id)->sum('subtotal');
        $total_item = Permintaandetail::where('id_permintaan', $permintaan->id)->count();

        if (! $permintaan) {
            abort(404);
        }

        return view('sparepartdetail.index', compact('produk', 'permintaan', 'total', 'total_item'));
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
            $row['jumlah']      = $item->jumlah;
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
            ->rawColumns(['aksi', 'kode_barang', 'jumlah', 'harga'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Barang::where('id_barang', $request->id_barang)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        if($request->id_kategori == 5){
            session(['sparepart' => $request->all()]);
            return response()->json('Data berhasil disimpan', 200);
        }else{
            $detail = new Permintaandetail();
            $detail->id_permintaan = $request->id_permintaan;
            $detail->id_barang = $request->id_barang;
            $detail->biaya = $request->harga;
            $detail->jumlah = $request->jumlah;
            $detail->subtotal = $request->harga * $request->jumlah;
            $detail->save();
    
    
            return redirect()->route('permintaandetail.index', $request->id_permintaan);
        }

    }

    public function update(Request $request, $id)
    {
        $detail = Permintaandetail::find($id);
        $detail->biaya = $request->biaya;
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

    public function getDetail($id_barang)
    {
        $ban = Ban::where('id_barang', $id_barang)->where('id_aset', NULL)->get();

        return datatables()
            ->of($ban)
            ->addIndexColumn()
            ->addColumn('select_all', function ($ban) {
                return '
                    <input type="checkbox" name="id_detail_barang[]" class="id_detail_barang" value="'. $ban->id_detail_barang .'">
                ';
            })
            ->rawColumns(['select_all'])
            ->make(true);
    }

    public function simpanBan(Request $request)
    {
        foreach ($request->checkedValues as $id) {
            $permintaan = Permintaan::where('id', session('sparepart.id_permintaan'))->first();
            $ban = Ban::find($id);

            $detail_pakai = new Detail_pakai();
            $detail_pakai->id_permintaan = session('sparepart.id_permintaan');
            $detail_pakai->tgl_pakai = session('sparepart.tanggal_pakai');
            $detail_pakai->id_detail_barang = $ban->id_detail_barang;
            $detail_pakai->harga = session('sparepart.harga');
            $detail_pakai->id_aset = $permintaan->member->id;
            $detail_pakai->save();

            $ban->tgl_pakai = date('Y-m-d');
            $ban->id_aset = $permintaan->member->id;
            $ban->update();
            
        }

        $detail = new Permintaandetail();
            $detail->id_permintaan = session('sparepart.id_permintaan');
            $detail->id_barang = session('sparepart.id_barang');
            $detail->biaya = session('sparepart.harga');
            $detail->jumlah = session('sparepart.jumlah');
            $detail->subtotal = session('sparepart.harga') * session('sparepart.jumlah');
            $detail->save();

            $request->session()->forget(['sparepart']);
    

        return response()->json('data berhasil ditambahkan', 200);
    }

}
