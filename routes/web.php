<?php

use App\Http\Controllers\{
    DashboardController,
    KategoriController,
    LaporanController,
    ProdukController,
    MemberController,
    PengeluaranController,
    PembelianController,
    PembelianDetailController,
    PenjualanController,
    PenjualanDetailController,
    SettingController,
    SupplierController,
    UserController,
    MekanikController,
    PermintaanController,
    PemeriksaanController,
    ServiceController,
    SparepartController,
    SparepartdetailController,
    LokasiController,
    BarangController,
    PermintaandetailController,
    DepartemenController,
    MobilisasiController,
    MobilisasidetailController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/departemen/data', [DepartemenController::class, 'data'])->name('departemen.data');
        Route::resource('/departemen', DepartemenController::class);

        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/lokasi/data', [LokasiController::class, 'data'])->name('lokasi.data');
        Route::resource('/lokasi', LokasiController::class);

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::post('/produk/delete-selected', [ProdukController::class, 'deleteSelected'])->name('produk.delete_selected');
        Route::post('/produk/cetak-barcode', [ProdukController::class, 'cetakBarcode'])->name('produk.cetak_barcode');
        Route::resource('/produk', ProdukController::class);

        Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
        Route::post('/barang/delete-selected', [BarangController::class, 'deleteSelected'])->name('barang.delete_selected');
        Route::post('/barang/cetak-barcode', [BarangController::class, 'cetakBarcode'])->name('barang.cetak_barcode');
        Route::resource('/barang', BarangController::class);

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::get('/member/getcategory/{id}', [MemberController::class, 'getcategory'])->name('member.getcategory');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::resource('/member', MemberController::class);

        Route::get('/mekanik/data', [MekanikController::class, 'data'])->name('mekanik.data');
        Route::resource('/mekanik', MekanikController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/pengeluaran/data', [PengeluaranController::class, 'data'])->name('pengeluaran.data');
        Route::resource('/pengeluaran', PengeluaranController::class);

        Route::get('/pembelian/data', [PembelianController::class, 'data'])->name('pembelian.data');
        Route::get('/pembelian/{id}/create', [PembelianController::class, 'create'])->name('pembelian.create');
        Route::resource('/pembelian', PembelianController::class)
            ->except('create');

        Route::get('/pembelian_detail/{id}/data', [PembelianDetailController::class, 'data'])->name('pembelian_detail.data');
        Route::get('/pembelian_detail/loadform/{diskon}/{total}', [PembelianDetailController::class, 'loadForm'])->name('pembelian_detail.load_form');
        Route::resource('/pembelian_detail', PembelianDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/penjualan/data', [PenjualanController::class, 'data'])->name('penjualan.data');
        Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/{id}', [PenjualanController::class, 'show'])->name('penjualan.show');
        Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');
    });

    Route::group(['middleware' => 'level:1,2,3'], function () {
        Route::get('/transaksi/baru', [PenjualanController::class, 'create'])->name('transaksi.baru');
        Route::post('/transaksi/simpan', [PenjualanController::class, 'store'])->name('transaksi.simpan');
        Route::get('/transaksi/selesai', [PenjualanController::class, 'selesai'])->name('transaksi.selesai');
        Route::get('/transaksi/nota-kecil', [PenjualanController::class, 'notaKecil'])->name('transaksi.nota_kecil');
        Route::get('/transaksi/nota-besar', [PenjualanController::class, 'notaBesar'])->name('transaksi.nota_besar');

        Route::get('/transaksi/{id}/data', [PenjualanDetailController::class, 'data'])->name('transaksi.data');
        Route::get('/transaksi/loadform/{diskon}/{total}/{diterima}', [PenjualanDetailController::class, 'loadForm'])->name('transaksi.load_form');
        Route::resource('/transaksi', PenjualanDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/permintaan/data', [PermintaanController::class, 'data'])->name('permintaan.data');
        Route::post('/permintaan/create', [PermintaanController::class, 'create'])->name('permintaan.create');
        Route::get('/permintaan/{id}/sparepart', [PermintaanController::class, 'sparepart'])->name('permintaan.sparepart');
        Route::get('/permintaan/selesai', [PermintaanController::class, 'selesai'])->name('permintaan.selesai');
        Route::get('/permintaan/nota', [PermintaanController::class, 'notaBesar'])->name('permintaan.nota_besar');
        Route::resource('/permintaan', PermintaanController::class)
            ->except('create');

        Route::get('/mobilisasi/data', [MobilisasiController::class, 'data'])->name('mobilisasi.data');
        Route::post('/mobilisasi/create', [MobilisasiController::class, 'create'])->name('mobilisasi.create');
        Route::get('/mobilisasi/selesai', [MobilisasiController::class, 'selesai'])->name('mobilisasi.selesai');
        Route::get('/mobilisasi/nota', [MobilisasiController::class, 'notaBesar'])->name('mobilisasi.nota_besar');
        Route::resource('/mobilisasi', MobilisasiController::class);

        Route::get('/mobilisasidetail/{id}/data', [MobilisasidetailController::class, 'data'])->name('mobilisasidetail.data');
        Route::resource('/mobilisasidetail', MobilisasidetailController::class);

        Route::get('/permintaandetail/{id}/data', [PermintaandetailController::class, 'data'])->name('permintaandetail.data');
        Route::resource('/permintaandetail', PermintaandetailController::class);

        Route::get('/pemeriksaan/data', [PemeriksaanController::class, 'data'])->name('pemeriksaan.data');
        Route::get('/pemeriksaan/{id}/create', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
        Route::resource('/pemeriksaan', PemeriksaanController::class)
            ->except('create');
        
        Route::get('/service/data', [ServiceController::class, 'data'])->name('service.data');
        Route::get('/service/{id}/create', [ServiceController::class, 'create'])->name('service.create');
        Route::get('/service/{id}/update', [ServiceController::class, 'update'])->name('service.update');
        Route::get('/service/selesai', [ServiceController::class, 'selesai'])->name('service.selesai');
        Route::get('/service/history', [ServiceController::class, 'histori'])->name('service.history');
        Route::get('/service/history_all', [ServiceController::class, 'allArmada'])->name('service.allArmada');
        Route::get('/service/data/allunit', [ServiceController::class, 'allUnit'])->name('service.allUnit');
        Route::get('/service/laporan/{id}', [ServiceController::class, 'laporan'])->name('service.laporan');
        Route::get('/service/allUnit', [ServiceController::class, 'allUnit'])->name('service.allUnit');
        
        Route::get('/service/data/{id}', [ServiceController::class, 'Getdata'])->name('service.history2');
        Route::resource('/service', ServiceController::class)
            ->except('create','update','selesai');

        Route::get('/sparepart/{id_permintaan}/create', [SparepartController::class, 'create'])->name('sparepart.create');       
        Route::resource('/sparepart', SparepartController::class)
        ->except('create');

        Route::get('/sparepartdetail/loadform/{total}', [SparepartdetailController::class, 'loadForm'])->name('sparepartdetail.load_form');
        Route::get('/sparepartdetail/{id}/data', [SparepartDetailController::class, 'data'])->name('sparepartdetail.data');
        Route::resource('/sparepartdetail', SparepartdetailController::class);
    });

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });
 
    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });
});