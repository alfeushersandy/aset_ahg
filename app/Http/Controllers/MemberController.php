<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Kategori;
use App\Models\Setting;
use App\Models\Lokasi;
use App\Models\Kendaraandetail;
use App\Models\Departemen;
use App\Models\Itdetail;
use App\Models\Mobilisasidetail;
use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lokasi = Lokasi::all()->pluck('nama_lokasi', 'id_lokasi');
        if(Auth::user()->level == 1 || Auth::user()->level == 2 ){
            $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');
            $departemen = Departemen::all()->pluck('departemen', 'id_departemen');
        }else{
            $kategori = Kategori::where('id_departemen', Auth::user()->id_departemen)->pluck('nama_kategori', 'id_kategori');
            $departemen = Departemen::where('id_departemen', Auth::user()->id_departemen)->pluck('departemen', 'id_departemen');
        }
        
        return view('member.index', compact('lokasi', 'departemen', 'kategori'));
    }

    public function getcategory($id){
        $kategori = Kategori::where('nama_kategori', '!=', 'Spare Part')->where('nama_kategori', '!=', 'Ban')->get();
        return response()->json($kategori);
    }

    public function data()
    {
        if(Auth::user()->level == 1 || Auth::user()->level == 2){
            $member = Member::with('kategori','lokasi')->orderBy('id')->get();
        }else{
            $member = Member::with('lokasi')
                    ->whereHas('kategori', function($query){
                        return $query->where('id_departemen', '=', Auth::user()->id_departemen);
                    })
                    ->orderBy('id')->get();
        }

        return datatables()
            ->of($member)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_member[]" value="'. $produk->id_member .'">
                ';
            })
            ->addColumn('kode_member', function ($member) {
                return '<span class="label label-success">'. $member->kode_member .'</span>';
            })
            ->addColumn('nama_kategori', function ($member){
                return $member->kategori->nama_kategori;
            })
            ->addColumn('nama_lokasi', function ($member){
                return $member->id_lokasi ? $member->lokasi->nama_lokasi : '';
            })
            ->addColumn('aksi', function ($member) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('member.update', $member->id) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('member.destroy', $member->id) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_member'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $member = Member::orderBy('kode_member', 'DESC')->latest()->first() ?? new Member();
        $kode_member = (int) $member->kode_member +1;

        $member = new Member();
        $member->kode_member = tambah_nol_didepan($kode_member, 5);
        $member->id_kategori = $request->id_kategori;
        $member->kode_kabin = $request->nama;
        $member->merek = $request->merek;
        $member->nopol = $request->nopol;
        $member->asuransi = $request->asuransi;
        $member->serial_number = $request->serial_number;
        $member->tanggal_pembelian = $request->tanggal_pembelian;
        $member->harga_perolehan = $request->harga_perolehan;
        $member->user = $request->user;
        $member->id_lokasi = $request->id_lokasi_homebase;
        $member->id_home_base = $request->id_lokasi_homebase;
        if($request->user == NULL){
            $member->status = "Tersedia";
        }else{
            $member->status = "On Duty"; 
        }
        $member->save();


        return response()->json($member);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $member = Member::find($id);

        return response()->json($member);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $member = Member::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $member = Member::find($id);        
        $member->delete();

        return response(null, 204);
    }

    public function cetakMember(Request $request)
    {
        $datamember = collect(array());
        foreach ($request->id_member as $id) {
            $member = Member::find($id);
            $datamember[] = $member;
        }

        $datamember = $datamember->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('member.cetak', compact('datamember', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('member.pdf');
    }

    public function memberByLokasi($id_lokasi)
    {
        $lokasi = Lokasi::where('id_lokasi', $id_lokasi)->first();
        $member = Member::with('kategori', 'lokasi')->where('id_lokasi', $id_lokasi)->get();
        return view('member.detail', compact('member', 'lokasi'));
    }

    public function memberByKategori($id_kategori)
    {
        $kategori = Kategori::where('id_kategori', $id_kategori)->first();
        $member = Member::with('kategori', 'lokasi')->where('id_kategori', $id_kategori)->get();
        return view('member.kategori', compact('member', 'kategori'));
    }

    public function cetakByLokasi($id_lokasi){
        $setting = Setting::first();
        $lokasi = Lokasi::where('id_lokasi', $id_lokasi)->first();
        $member = Member::with('kategori', 'lokasi')->where('id_lokasi', $id_lokasi)->get();

        $pdf = PDF::loadView('member.laporan', compact('setting', 'member', 'lokasi'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream($lokasi->nama_lokasi . date('Y-m-d-his') .'.pdf');

    }

    public function cetakByKategori($id_kategori){
        $setting = Setting::first();
        $kategori = Kategori::where('id_kategori', $id_kategori)->first();
        $member = Member::with('kategori', 'lokasi')->where('id_kategori', $id_kategori)->get();

        $pdf = PDF::loadView('member.laporan_kategori', compact('setting', 'member', 'kategori'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream($kategori->nama_kategori . date('Y-m-d-his') .'.pdf');
    }

    public function detail(Request $request)
    {
        $kode_member = $request->query('kode_member');
        $member = Member::with(['lokasi', 'kategori'])->where('kode_member', $kode_member)->first();
        $mobilisasi = Mobilisasidetail::leftjoin('mobilisasi', 'mobilisasi.id_mobilisasi', 'mobilisasi_detail.id_mobilisasi')
                    ->join('member', 'member.id', 'mobilisasi_detail.id_aset')
                    ->where('mobilisasi_detail.id_aset', $member->id)
                    ->leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'mobilisasi_detail.lokasi_tujuan')
                    ->select('kode_mobilisasi', 'kode_kabin','nopol','nama_lokasi', 'mobilisasi_detail.user as user', 'mobilisasi_detail.tanggal_awal as tanggal_awal', 'mobilisasi_detail.tanggal_kembali as tanggal_kembali')
                    ->get();
        $service = Permintaan::leftjoin('table_lokasi', 'table_lokasi.id_lokasi', 'permintaan.id_lokasi')
                    ->where('permintaan.kode_customer', $kode_member)
                    ->get();


        return view('detail.index', [
            'member' => $member, 
            'mobilisasi' => $mobilisasi,
            'service' => $service
        ]);
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $member = Member::all();

        $pdf = PDF::loadView('member.laporan_all', compact('setting', 'member'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('data_aset-'. date('Y-m-d-his') .'.pdf');
    }

}
