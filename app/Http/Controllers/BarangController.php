<?php

namespace App\Http\Controllers;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;
use PDF;

class BarangController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');

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
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('barang.update', $produk->id_barang) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('barang.destroy', $produk->id_barang) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Barang::latest()->first() ?? new Barang();
        $request['kode_barang'] = 'P'. tambah_nol_didepan((int)$produk->id_barang +1, 6);

        $produk = Barang::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show($id)
    {
        $produk = Barang::find($id);

        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $produk = Barang::find($id);
        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
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
}
