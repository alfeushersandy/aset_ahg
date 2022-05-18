<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\Member;
use App\Models\Petugas;
use App\Models\Permintaan;
use App\Models\Permintaandetail;
use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $kendaraan = Member::all()->pluck('kode_kabin', 'kode_member');
        $mekanik = Petugas::all()->pluck('nama_petugas', 'id_petugas');
        return view('permintaan.index', compact('kendaraan', 'mekanik'));
    }

    public function data()
    {
        $permintaan = DB::table('permintaan')
                ->leftJoin('member','member.kode_member','=','permintaan.kode_customer')
                ->leftJoin('petugas','petugas.id_petugas','=','permintaan.id_mekanik')
                ->orderBy('kode_permintaan', 'desc')
                ->select('permintaan.*', 'member.kode_kabin', 'member.user', 'petugas.nama_petugas')
                ->get();

                return response()->json($permintaan);
        

    }

    public function create(Request $request)
    {
        $permintaan = Permintaan::latest()->first() ?? new Permintaan();
        $kode_permintaan1 = substr($permintaan->kode_permintaan,2);
        $kode_permintaan = (int) $kode_permintaan1 +1;

        $permintaan = new Permintaan();
        $permintaan->tanggal = $request->tanggal;
        $permintaan->kode_permintaan = 'PM'.tambah_nol_didepan($kode_permintaan, 5);
        $permintaan->kode_customer = $request->kode_kendaraan;
        $permintaan->keluhan = $request->keluhan;
        $permintaan->total_item = 0;
        $permintaan->total_harga = 0;
        $permintaan->id_mekanik = $request->mekanik;
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

    public function fcm()
    {
        send_notification_FCM();
    
    }
}
