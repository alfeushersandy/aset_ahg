<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\Barang;
use App\Models\Detail_pakai;
use App\Models\Kategori;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class BanController extends Controller
{
    public function index()
    {
        $kategori = Kategori::where('nama_kategori', 'Ban')->pluck('nama_kategori', 'id_kategori');
        return view('ban.index', compact('kategori'));
    }

    public function data()
    {
        $ban = Ban::with(['member', 'barang'])->get();
     
        return datatables()
            ->of($ban)
            ->addIndexColumn()
            ->addColumn('select_all', function ($ban) {
                return '
                    <input type="checkbox" name="id_ban[]" value="'. $ban->id_ban .'">
                ';
            })
            ->addColumn('kode_ban', function ($ban) {
                return '<span class="label label-success">'. $ban->kode_ban .'</span>';
            })
            ->addColumn('member', function ($ban) {
                return $ban->id_aset ? $ban->member->kode_kabin : "";
            })
            ->addColumn('tgl_pakai', function ($ban) {
                return $ban->tgl_pakai ? tanggal_indonesia($ban->tgl_pakai) : "";
            })
            ->addColumn('tgl_beli', function ($ban) {
                return tanggal_indonesia($ban->tgl_beli);
            })
            ->addColumn('nama_barang', function ($ban) {
                return $ban->barang[0]->nama_barang;
            })
            ->rawColumns(['kode_ban', 'select_all', 'member', 'nama_barang'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $items = [$request->all('ban')];
        foreach ($items[0]['ban'] as $value) {
            $ban = Ban::orderBy('kode_ban', 'DESC')->latest()->first() ?? new Ban();
            $kode_ban1 = substr($ban->kode_ban,4);
            $kode_ban = (int) $kode_ban1 +1;

            $request['kode_ban'] = 'BAN-'. tambah_nol_didepan($kode_ban, 6);

            $ban = Ban::create([
                'id_barang' => session('id_barang'),
                'nomor_seri' => $value["'nomor_seri'"],
                'kode_ban' => $request['kode_ban'],
                'tgl_beli' => $value["'tanggal_beli'"]
            ]);
        }

        $produk = Barang::create([
            'kode_barang' => session("produk.kode_barang"),
            'nama_barang' => session("produk.nama_barang"),
            'id_kategori' => session("produk.id_kategori"),
            'kelompok' => session("produk.kelompok"),
            'merk' => session("produk.merk"),
            'harga' => session("produk.harga"),
            'stok' => session("produk.stok"),

        ]);

        $request->session()->forget(['produk', 'id_barang']);
        return redirect()->route('barang.index');
    }

    public function show($id)
    {
        $produk = Ban::find($id);

        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $produk = Ban::find($id);
        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $produk = Ban::find($id);
        $produk->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_ban as $id) {
            $produk = Ban::find($id);
            $produk->delete();
        }

        return response(null, 204);
    }

    public function banPakai(){
        return view('ban_pakai.index');
    }

    public function dataPakai(){
        $detail_pakai = Detail_pakai::with(['permintaan', 'ban', 'member'])->get();

        return datatables()
        ->of($detail_pakai)
        ->addIndexColumn()
        ->addColumn('permintaan', function ($detail_pakai) {
            return '<span class="label label-success">' . $detail_pakai->permintaan[0]->kode_permintaan . '</span>';
        })
        ->addColumn('tgl_pakai', function ($detail_pakai) {
            return tanggal_indonesia($detail_pakai->tgl_pakai);
        })
        ->addColumn('kode_ban', function ($detail_pakai) {
            return $detail_pakai->ban->nomor_seri;
        })
        ->addColumn('id_aset', function ($detail_pakai) {
            return $detail_pakai->member[0]->kode_kabin;
        })
        ->addColumn('harga', function ($detail_pakai) {
            return format_uang($detail_pakai->harga);
        })
        ->rawColumns(['permintaan', 'kode_ban'])
        ->make(true);
    }


    

    
}
