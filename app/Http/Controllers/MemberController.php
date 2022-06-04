<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Kategori;
use App\Models\Setting;
use App\Models\Lokasi;
use App\Models\Kendaraandetail;
use App\Models\Departemen;
use Illuminate\Http\Request;
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
        $departemen = Departemen::all()->pluck('departemen', 'id_departemen');
        return view('member.index', compact('lokasi', 'departemen'));
    }

    public function getcategory($id){
        $kategori = Kategori::where('id_departemen', $id)->get();
        return response()->json($kategori);
    }

    public function data()
    {
        $member = Member::with('kategori', 'lokasi')->orderBy('id')->get();

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
                return $member->lokasi->nama_lokasi;
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
        $departemen = $request->departemen;
        $member = Member::orderBy('kode_member', 'DESC')->latest()->first() ?? new Member();
        $kode_member = (int) $member->kode_member +1;

        if($departemen == 1){
            $member = new Member();
            $member->kode_member = tambah_nol_didepan($kode_member, 5);
            $member->id_kategori = $request->id_kategori;
            $member->kode_kabin = $request->nama;
            $member->nopol = $request->no_pol;
            $member->user = $request->user;
            $member->id_lokasi = $request->id_lokasi;
            $member->save();
        }else if($departemen == 3){
            $member = new Member();
            $member->kode_member = tambah_nol_didepan($kode_member, 5);
            $member->id_kategori = $request->id_kategori;
            $member->kode_kabin = $request->nama_it;
            $member->nopol = $request->tipe_it;
            $member->user = $request->user_it;
            $member->id_lokasi = $request->id_lokasi_it;
            $member->save();
        }
        


        return response()->json('data berhasil disimpan', 200);
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
}
