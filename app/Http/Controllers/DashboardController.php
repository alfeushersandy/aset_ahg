<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sparepart = Barang::groupBy('kelompok')->get();
        $kategori = Kategori::where('id_kategori', '!=', 1)->where('id_kategori', '!=', 8)->get();
        $kategori_c = Kategori::count();
        $produk = Barang::count();
        $member = Member::count();
        $lokasi = Lokasi::all();


        if (auth()->user()->level == 1 || auth()->user()->level == 2) {
            return view('admin.dashboard', compact('kategori', 'produk', 'member', 'lokasi', 'kategori_c', 'sparepart'));
        } else {
            return view('kasir.dashboard');
        }
    }
}
