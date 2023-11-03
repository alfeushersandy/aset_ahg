<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use Illuminate\Http\Request;
use App\Models\Perencanaan;
use App\Models\PerencanaanDetail;
use App\Models\PenerimaanDetail;
use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\Penerimaan_cart;
use Illuminate\Support\Facades\Auth;

class PenerimaanCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_perencanaan = session('id_perencanaan');
        $rencana = Perencanaan::where('id_perencanaan', $id_perencanaan)->first();
        $id_penerimaan = session('id_penerimaan');
        $penerimaan = Penerimaan::where('id_penerimaan', $id_penerimaan)->first();
        $perencanaan = PerencanaanDetail::with('barang')->where('id_perencanaan', $id_perencanaan)->where('sisa_terima', '!=', 0)->get();
        $total = Penerimaan_cart::whereBelongsTo(Auth::user())->get() ? Penerimaan_cart::whereBelongsTo(Auth::user())->sum('subtotal_terima') : 0 ;
        $cart = Penerimaan_cart::with('barang')->where('id_user', Auth::id())->where('id_perencanaan', session('id_perencanaan'))->get();
        if (! $perencanaan) {
            abort(404);
        }

        return view('penerimaan_detail.index', compact('perencanaan', 'total', 'penerimaan', 'rencana', 'cart'));
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
        $perencanaan_detail = PerencanaanDetail::where('id_perencanaan_detail', $request->id_perencanaan_detail)->first();
        $sisa_terima = $perencanaan_detail->jumlah - $request->jumlah_terima;
        $produk = Barang::where('id_barang', $request->id_barang)->first();
        if (! $produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        //ambil data di table cart
        $in_cart = Penerimaan_cart::with('barang')
                    ->whereBelongsTo($request->user())
                    ->where('id_barang', $request->id_barang)
                    ->first();

        if($request->id_kategori == 5){
            session(['barang_terima' => $request->all()]);
            return response()->json([
                'barang_terima' => session('barang_terima')
            ]);
        }else{
            //cek apakah barang sudah ditambahkan
        if($in_cart){
            if($in_cart->sisa_vol_terima == 0){
                return back()->with('warning', 'Tidak dapat menambahkan barang, sisa vol terima adalah 0');
            }else{
                if(($perencanaan_detail->sisa_terima - $in_cart->jumlah_terima) < 0 ) {
                    return back()->with('warning', 'Tidak dapat menambahkan barang, jumlah yang diterima melebihi sisa vol terima');
                }else{
                    $in_cart->jumlah_terima = $in_cart->jumlah_terima + $request->jumlah_terima;
                    $in_cart->update();
    
                    $in_cart->sisa_vol_terima = $perencanaan_detail->jumlah - $in_cart->jumlah_terima;
                    $in_cart->update();

                    if($perencanaan_detail->jumlah - $request->jumlah_terima == 0){
                        $in_cart->status_penerimaan = 'Sudah Diterima'; 
                        $in_cart->update();
                    }else{
                        $in_cart->status_penerimaan = 'Diterima sebagian'; 
                        $in_cart->update();
                    }

                    //update perencanaan detail di sisa terima 
                    $perencanaan_detail->sisa_terima = $sisa_terima;
                    $perencanaan_detail->biaya_realisasi = $in_cart->harga_terima;
                    $perencanaan_detail->subtotal = $in_cart->subtotal_terima;
                    if($sisa_terima == 0){
                        $perencanaan_detail->status = "Terealisasi";
                        $perencanaan_detail->update();
                    }elseif ($sisa_terima < 0 ){
                        return back()->with('warning', 'jumlah yang anda masukkan lebih dari sisa yang dapat diterima');
                    }else{
                        $perencanaan_detail->update();
                    }
    
                    $in_cart->subtotal_terima =  $in_cart->jumlah_terima * $in_cart->harga_terima;
                    $in_cart->update();

                    return back()->with('success', "Berhasil menambahkan barang");
    
                }
            }
        }else{
            $detail = new Penerimaan_cart();
            $detail->id_user = Auth::id();
            $detail->id_penerimaan = session('id_penerimaan');
            $detail->id_perencanaan = $request->id_perencanaan;
            $detail->id_perencanaan_detail = $request->id_perencanaan_detail;
            $detail->id_barang = $request->id_barang;
            $detail->jumlah_terima = $request->jumlah_terima;
            $detail->sisa_vol_terima = $sisa_terima;
            $detail->harga_terima = $request->harga_terima;
            $detail->subtotal_terima = $request->harga_terima * $request->jumlah_terima;
            if($perencanaan_detail->sisa_terima - $request->jumlah_terima == 0){
                $detail->status_penerimaan = 'Sudah Diterima'; 
            }else{
                $detail->status_penerimaan = 'Diterima sebagian'; 
            }
            $detail->penerima = session('penerimaan.penerima');
            $detail->save();

            //update perencanaan detail di sisa terima 
            $perencanaan_detail->sisa_terima = $sisa_terima;
            $perencanaan_detail->biaya_realisasi = $detail->harga_terima;
            $perencanaan_detail->subtotal = $detail->subtotal_terima;
            if($sisa_terima == 0){
                $perencanaan_detail->status = "Terealisasi";
                $perencanaan_detail->update();
            }elseif ($sisa_terima < 0 ){
                return back()->with('warning', 'jumlah yang anda masukkan lebih dari sisa yang dapat diterima');
            }else{
                $perencanaan_detail->update();
            }
            


                //update table rencana di biaya realisasi
                // $jumlah_realisasi = PerencanaanDetail::where('id_perencanaan', $detail->id_rencana)->sum('subtotal');
                // $penerimaan_detail = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->count();
                // $penerimaan_by_status = PerencanaanDetail::where('id_perencanaan', $detail->id_perencanaan)->where('status', 'Terealisasi')->count();
                // $perencanaan = Perencanaan::find($detail->id_perencanaan);
                // if($penerimaan_detail !== $penerimaan_by_status){
                //     $perencanaan->total_harga_realisasi = $jumlah_realisasi;
                //     $perencanaan->update();
                // }else{
                //     $perencanaan->total_harga_realisasi = $jumlah_realisasi;
                //     $perencanaan->status = "Seluruh Rencana Telah Terealisasi";
                //     $perencanaan->update();
                // }
    
                return redirect()->route('penerimaan_detail.index')->with('success', 'barang berhasil ditambahkan');
            }
        }
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
        $detail = Penerimaan_cart::find($id);
        $pr_detail = PerencanaanDetail::where('id_perencanaan_detail', $detail->id_perencanaan_detail)->first();
        $pr_detail->sisa_terima += $detail->jumlah_terima;
        $pr_detail->update();
        
        $detail->delete(); 
        
        return response(null, 204);
    }

    public function banStore(Request $request){
        $items = [$request->all('ban')];
        foreach ($items[0]['ban'] as $value) {
            $ban = Ban::orderBy('kode_ban', 'DESC')->latest()->first() ?? new Ban();
            $kode_ban1 = substr($ban->kode_ban,4);
            $kode_ban = (int) $kode_ban1 +1;

            $request['kode_ban'] = 'BAN-'. tambah_nol_didepan($kode_ban, 6);

            $ban = Ban::create([
                'id_barang' => session("barang_terima.id_barang"),
                'nomor_seri' => $value["'nomor_seri'"],
                'kode_ban' => $request['kode_ban'],
                'tgl_beli' => $value["'tanggal_beli'"]
            ]);
        }

        //ambil data di table cart
        $in_cart = Penerimaan_cart::with('barang')
                    ->whereBelongsTo($request->user())
                    ->where('id_barang', session('barang_terima.id_barang'))
                    ->first();
        $perencanaan_detail = PerencanaanDetail::where('id_perencanaan_detail', session("barang_terima.id_perencanaan_detail"))->first();
        $sisa_terima = $perencanaan_detail->sisa_terima - session('barang_terima.jumlah_terima');



        if($in_cart){
            $in_cart->jumlah_terima = $in_cart->jumlah_terima + session('barang_terima.jumlah_terima');
            $in_cart->update();

            $in_cart->sisa_vol_terima = $perencanaan_detail->jumlah - $in_cart->jumlah_terima;
            $in_cart->update();

            if($in_cart->sisa_vol_terima == 0){
                $in_cart->status_penerimaan = 'Sudah Diterima'; 
                $in_cart->update();
            }else{
                $in_cart->status_penerimaan = 'Diterima sebagian'; 
                $in_cart->update();
            }

            //update perencanaan detail di sisa terima 
            $perencanaan_detail->sisa_terima = $sisa_terima;
            $perencanaan_detail->biaya_realisasi = $in_cart->harga_terima;
            $perencanaan_detail->subtotal = $in_cart->subtotal_terima;
            if($sisa_terima == 0){
                $perencanaan_detail->status = "Terealisasi";
                $perencanaan_detail->update();
            }elseif ($sisa_terima < 0 ){
                return back()->with('warning', 'jumlah yang anda masukkan lebih dari sisa yang dapat diterima');
            }else{
                $perencanaan_detail->update();
            }

            $in_cart->subtotal_terima =  $in_cart->jumlah_terima * $in_cart->harga_terima;
            $in_cart->update();
        }else{
            //input list barang yang diterima 
            $detail = new Penerimaan_cart();
            $detail->id_user = Auth::user()->id;
            $detail->id_penerimaan = session('barang_terima.id_penerimaan');
            $detail->id_perencanaan = session('barang_terima.id_perencanaan');
            $detail->id_perencanaan_detail = session('barang_terima.id_perencanaan_detail');
            $detail->id_barang = session('barang_terima.id_barang');
            $detail->jumlah_terima = session('barang_terima.jumlah_terima');
            $detail->sisa_vol_terima = $sisa_terima;
            $detail->harga_terima = session('barang_terima.harga_terima');
            $detail->subtotal_terima = session('barang_terima.harga_terima') * session('barang_terima.jumlah_terima');;
            if($perencanaan_detail->sisa_terima - session('barang_terima.jumlah_terima') == 0){
                $detail->status_penerimaan = 'Sudah Diterima'; 
            }else{
                $detail->status_penerimaan = 'Diterima sebagian'; 
            }
            $detail->penerima = Auth::user()->id;
            $detail->save();
    
            //input list barang yang diterima 
            $perencanaan_detail = PerencanaanDetail::where('id_perencanaan_detail', session("barang_terima.id_perencanaan_detail"))->first();
            $sisa_terima = $perencanaan_detail->sisa_terima - session('barang_terima.jumlah_terima');
    
            $perencanaan_detail->sisa_terima = $sisa_terima;
            $perencanaan_detail->biaya_realisasi = $detail->harga_terima;
            $perencanaan_detail->subtotal = $detail->subtotal_terima;
            if($sisa_terima == 0){
                $perencanaan_detail->status = "Terealisasi";
            }
            $perencanaan_detail->update();
    
        }
        return redirect()->route('penerimaan_detail.index');
        
    }
}
