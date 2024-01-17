<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Ban;
use App\Models\Detail_pakai;

class LaporanBanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::all();
        return view('laporan_ban.index',compact('member'));
    }

    public function getData($id)
    {
        $pakai_ban = Ban::with('member')->where('id_aset', $id)->get();

        return datatables()
        ->of($pakai_ban)
        ->addIndexColumn()
        ->addColumn('member', function($pakai_ban){
            return $pakai_ban->member->kode_kabin;
        })
        ->rawColumns(['member'])
        ->make(true);
    }

    public function historyBan()
    {
        $member = Member::all();
        return view('laporan_ban.history', compact('member'));
    }

    public function getPakaiBan($id, $tgl_awal, $tgl_akhir)
    {
        $pakai_ban = Detail_pakai::with('permintaan', 'member', 'ban')
                                ->where('id_aset', $id)
                                ->where('tgl_pakai', '>=', $tgl_awal)
                                ->where('tgl_pakai', '<=', $tgl_akhir)
                                ->get();
        
        return datatables()
        ->of($pakai_ban)
        ->addIndexColumn()
        ->addColumn('member', function($pakai_ban){
            return $pakai_ban->member->kode_kabin;
        })
        ->addColumn('permintaan', function($pakai_ban){
            return $pakai_ban->permintaan->kode_permintaan;
        })
        ->addColumn('ban', function($pakai_ban){
            return $pakai_ban->ban->nomor_seri;
        })
        ->rawColumns(['member', 'permintaan', 'ban'])
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
