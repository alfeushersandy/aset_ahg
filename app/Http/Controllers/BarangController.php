<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use PDF;

class BarangController extends Controller
{
    public function index()
    {
        $kategori = Kategori::where('nama_kategori', 'Spare Part')->orWhere('nama_kategori', 'Ban')->pluck('nama_kategori', 'id_kategori');

        return view('barang.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Barang::with('kategori')->get();
     
        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $produk->id_barang .'">
                ';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">'. $produk->kode_barang .'</span>';
            })
            ->addColumn('nama_kategori', function ($produk) {
                return $produk->kategori->nama_kategori;
            })
            ->addColumn('harga', function ($produk) {
                return format_uang($produk->harga);
            })
            ->addColumn('stok', function ($produk) {
                return format_uang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                if(Auth::user()->level !== 4 && Auth::user()->level !== 5){
                    if($produk->id_kategori == 5){
                        return '
                            <div class="btn-group">
                                <button type="button" onclick="showDetail(`'. route('barang.detail', $produk->id_barang) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                                <button type="button" onclick="editForm(`'. route('barang.update', $produk->id_barang) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                                <button type="button" onclick="deleteData(`'. route('barang.destroy', $produk->id_barang) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>
                            ';
                    }else{
                        return '
                            <div class="btn-group">
                                <button type="button" onclick="showDetail(`'. route('barang.detail', $produk->id_barang) .'`)" class="btn btn-xs btn-success btn-flat" disabled><i class="fa fa-eye"></i></button>
                                <button type="button" onclick="editForm(`'. route('barang.update', $produk->id_barang) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                                <button type="button" onclick="deleteData(`'. route('barang.destroy', $produk->id_barang) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </div>
                            ';
                    }
                }else{
                    return '
                    <div class="btn-group">
                    <button type="button" onclick="showDetail(`'. route('barang.detail', $produk->id_barang) .'`)" class="btn btn-xs btn-success btn-flat"><i class="fa fa-eye"></i></button>
                    <button type="button" onclick="editForm(`'. route('barang.update', $produk->id_barang) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    </div>
                    ';

                }
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Barang::orderBy('kode_barang', 'DESC')->latest()->first() ?? new Barang();
        $id_barang = $produk->id_barang + 1;
        $request['kode_barang'] = 'P'. tambah_nol_didepan((int)$produk->id_barang +1, 6);

        if($request->id_kategori == 1){
            $produk = Barang::create($request->all());
            return response()->json('Data berhasil disimpan', 200);
        }else{
            session(['produk' => $request->all(), 'id_barang' => $id_barang]);
            return response()->json([
                'produk' => session('produk'),
                'id_barang' => session('id_barang')
            ]);
        }

        
    }

    public function show($id)
    {
        $produk = Barang::find($id);

        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $produk = Barang::find($id);
        if($request->id_kategori == 1){
            $produk->update($request->all());
            return response()->json('Data berhasil disimpan', 200);
        }else{
            session(['produk' => $request->all(), 'id_barang' => $id]);
            return response()->json([
                'produk' => session('produk'),
                'id_barang' => session('id_barang')
            ]);
        }
    }

    public function destroy($id)
    {
        $produk = Barang::find($id);
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_produk as $id) {
            $produk = Barang::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }

    public function cetakBarcode(Request $request)
    {
        $dataproduk = array();
        foreach ($request->id_produk as $id) {
            $produk = Barang::find($id);
            $dataproduk[] = $produk;
        }

        $no  = 1;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled'=>true,'isRemoteEnabled'=>true])
        ->loadView('barang.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('produk.pdf');
    }

    public function byKelompok($kelompok)
    {
        $barang = Barang::where('kelompok', $kelompok)->get();
        return view('barang.detail', compact('barang', 'kelompok'));
    }

    public function detail($id_barang)
    {
        $data = array();
        $no = 1;
        $total = 0;

        $barang = Barang::leftjoin('detail_barang', 'detail_barang.id_barang', 'barang.id_barang')
                         ->where('detail_barang.id_barang', $id_barang)
                         ->where('deleted_at', null)
                         ->get();
        
    return datatables()
            ->of($barang)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                return '
                        <div class="btn-group">
                            <button type="button" onclick="deleteBan(`'. route('ban.destroy', $barang->id_detail_barang) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                        </div>
                        ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function countBan($id_barang){
        $ban = Ban::where('id_barang', $id_barang)->count();
        return response()->json($ban);
    }

    public function getSession(){
        return response()->json([
            'produk' => session('produk'),
            'id_barang' => session('id_barang')
        ]);
    }

    public function updateban()
    {
        $id_barang = session('id_barang');
        $barang = Barang::find($id_barang);

        $barang->update([
            'nama_barang' => session('produk.nama_barang'),
            'id_kategori' => session('produk.id_kategori'),
            'satuan' => session('produk.satuan'),
            'kelompok' => session('produk.kelompok'),
            'merk' => session('produk.merk'),
            'harga' => session('produk.harga'),
            'stok' => session('produk.stok'),
        ]);

        session()->forget(['produk', 'id_barang']);

        return response()->json('data berhasil di update', 200);
    }
    
}
