<?php

namespace App\Http\Controllers;
use App\Models\Member;
use App\Models\Petugas;
use App\Models\Permintaan;
use App\Models\Permintaandetail;
use App\Models\Barang;
use App\Models\Setting;
use App\Models\Lokasi;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all()->pluck('nama_lokasi', 'id_lokasi');
        $kendaraan = Member::all()->pluck('kode_kabin', 'kode_member');
        $mekanik = Petugas::where('kategori_tugas', 'Mekanik')->pluck('nama_petugas', 'id_petugas');
        return view('permintaan.index', compact('kendaraan', 'mekanik', 'lokasi'));
    }

    public function data()
    {
        $permintaan = DB::table('permintaan')
                ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                ->leftJoin('table_lokasi', 'table_lokasi.id_lokasi', '=', 'permintaan.id_lokasi')
                ->orderBy('kode_permintaan', 'desc')
                ->select('permintaan.*', 'member.kode_kabin', 'petugas.nama_petugas', 'nama_lokasi')
                ->get();

                return datatables()
                ->of($permintaan)
                ->addIndexColumn()
                ->addColumn('select_all', function ($permintaan) {
                    return '
                        <input type="checkbox" name="id_produk[]" value="'. $permintaan->id .'">
                    ';
                })
                ->addColumn('aksi', function ($permintaan) {
                    return '
                    <div class="btn-group">
                        <button type="button" onclick="editForm(`'. route('permintaan.update', $permintaan->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                        <button type="button" onclick="deleteData(`'. route('permintaan.destroy', $permintaan->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                    </div>
                    ';
                })
                ->rawColumns(['aksi','select_all'])
                ->make(true);
        

    }

    public function create(Request $request)
    {
        $permintaan = Permintaan::latest()->first() ?? new Permintaan();
        $kode_permintaan1 = substr($permintaan->kode_permintaan,2);
        $kode_permintaan = (int) $kode_permintaan1 +1;

        $permintaan = new Permintaan();
        $permintaan->tanggal = $request->tanggal;
        $permintaan->kode_permintaan = 'PM'.tambah_nol_didepan($kode_permintaan, 5);
        $permintaan->kode_customer = $request->kode_customer;
        $permintaan->id_lokasi = $request->id_lokasi;
        $permintaan->user = $request->user;
        $permintaan->Keluhan = $request->Keluhan;
        $permintaan->total_item = 0;
        $permintaan->total_harga = 0;
        $permintaan->id_mekanik = $request->id_mekanik;
        $permintaan->status = 'Submited';
        $permintaan->save();
        return response()->json('Data berhasil disimpan', 200);
    }

    public function sparepart($id)
    {
        $permintaan = Permintaan::find($id);
        session(['id_permintaan' => $permintaan->id]);
        return redirect()->route('permintaandetail.index');
    }

    public function store(Request $request)
    {
        
        $permintaan = Permintaan::findorfail($request->id_permintaan);
        $permintaan->total_item = $request->total_item;
        $permintaan->total_harga = $request->total;
        $permintaan->update();

        $detail = Permintaandetail::where('id_permintaan', $permintaan->id)->get();
        foreach ($detail as $item) {

            $produk = Barang::find($item->id_barang);
            $produk->stok -= $item->jumlah;
            $produk->update();
            
        }

        return redirect()->route('permintaan.selesai');
    }
    

    public function show($id)
    {
        $permintaan = Permintaan::find($id);

        return response()->json($permintaan);
    }

    public function update(Request $request, $id)
    {
        $permintaan = Permintaan::find($id);
        $permintaan->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy($id)
    {
        $permintaan = Permintaan::find($id);
        $permintaan->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id as $id) {
            $permintaan = Permintaan::find($id);
            $permintaan->delete();
        }

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('permintaan.selesai', compact('setting'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $permintaan = Permintaan::find(session('id_permintaan'));
        if (! $permintaan) {
            abort(404);
        }
        $detail = PermintaanDetail::with('barang')
            ->where('id_permintaan', session('id_permintaan'))
            ->get();

        $pdf = PDF::loadView('permintaan.nota_besar', compact('setting', 'permintaan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
