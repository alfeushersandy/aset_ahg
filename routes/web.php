<?php

use App\Http\Controllers\{
    BanController,
    DashboardController,
    KategoriController,
    LaporanController,
    MemberController,
    PenjualanController,
    SettingController,
    UserController,
    MekanikController,
    PermintaanController,
    PemeriksaanController,
    ServiceController,
    SparepartController,
    SparepartdetailController,
    LokasiController,
    BarangController,
    BarangdatangController,
    PermintaandetailController,
    DepartemenController,
    GudangController,
    KembaliController,
    MobilisasiController,
    MobilisasidetailController,
    PenerimaanCartController,
    PenerimaanDetailController,
    PerencanaanController,
    PerencanaanDetailController,
    PermintaanBarangController,
};
use App\Models\Perencanaan;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

    Route::group(['middleware' => 'level:1,2,3|web'], function () {
        Route::get('/departemen/data', [DepartemenController::class, 'data'])->name('departemen.data');
        Route::resource('/departemen', DepartemenController::class);

        Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');
        Route::resource('/kategori', KategoriController::class);

        Route::get('/lokasi/data', [LokasiController::class, 'data'])->name('lokasi.data');
        Route::resource('/lokasi', LokasiController::class);

        Route::get('/member/data', [MemberController::class, 'data'])->name('member.data');
        Route::get('/member/getcategory/{id}', [MemberController::class, 'getcategory'])->name('member.getcategory');
        Route::post('/member/cetak-member', [MemberController::class, 'cetakMember'])->name('member.cetak_member');
        Route::get('/member/{id_lokasi}/detail', [MemberController::class, 'memberByLokasi'])->name('member.bylokasi');
        Route::get('/member/{id_kategori}/kategori', [MemberController::class, 'memberByKategori'])->name('member.bykategori');
        Route::get('/member/{id_lokasi}/cetak', [MemberController::class, 'cetakByLokasi'])->name('member.cetak_lokasi');
        Route::get('/member/{id_kategori}/cetak_kategori', [MemberController::class, 'cetakByKategori'])->name('member.cetak_kategori');
        Route::get('/member/nota', [MemberController::class, 'notaBesar'])->name('member.nota_besar');
        Route::get('/member/detail', [MemberController::class, 'detail'])->name('member.detail');
        
        Route::resource('/member', MemberController::class);

        Route::get('/mekanik/data', [MekanikController::class, 'data'])->name('mekanik.data');
        Route::resource('/mekanik', MekanikController::class);

    });

    

    Route::group(['middleware' => 'level:1,2,3'], function () {

        Route::get('/permintaan/data', [PermintaanController::class, 'data'])->name('permintaan.data');
        Route::post('/permintaan/create', [PermintaanController::class, 'create'])->name('permintaan.create');
        Route::get('/permintaan/selesai/{id}', [PermintaanController::class, 'selesai'])->name('permintaan.selesai');
        Route::get('/permintaan/nota/{id}', [PermintaanController::class, 'notaBesar'])->name('permintaan.nota_besar');
        Route::get('/permintaan/detail/{id_permintaan}', [PermintaanController::class, 'detail'])->name('permintaan.detail');
        Route::get('/permintaan/form_selesai', [PermintaanController::class, 'selesai_form'])->name('permintaan.selesai_form');
        Route::get('/permintaan/form_cetak', [PermintaanController::class, 'formService'])->name('permintaan.cetak_form');
        Route::get('/permintaan/{id_permintaan}/form', [PermintaanController::class, 'formCetakService'])->name('permintaan.form_service');
        Route::resource('/permintaan', PermintaanController::class)
            ->except('create');

        Route::get('/perencanaan', [PerencanaanController::class, 'index'])->name('perencanaan.index');
        Route::post('/perencanaan/create', [PerencanaanController::class, 'create'])->name('perencanaan.create');
        Route::post('/perencanaan/store', [PerencanaanController::class, 'store'])->name('perencanaan.store');
        Route::get('/perencanaan/selesai', [PerencanaanController::class, 'selesai'])->name('perencanaan.selesai');
        Route::get('/perencanaan/nota', [PerencanaanController::class, 'notaBesar'])->name('perencanaan.nota_besar');
        Route::get('/perencanaan/laporan', [PerencanaanController::class, 'laporan'])->name('perencanaan.laporan');
        Route::get('/perencanaan/data/{id}/{tanggal_awal}/{tanggal_akhir}', [PerencanaanController::class, 'Getdata'])->name('perencanaan.getData');
        Route::get('/perencanaan/cetak/{id}/{tanggal_awal}/{tanggal_akhir}', [PerencanaanController::class, 'cetak_laporan'])->name('perencanaan.cetak_laporan');
        Route::get('/perencanaan/all', [PerencanaanController::class, 'allRencana'])->name('perencanaan.allRencana');
        Route::get('/perencanaan/data/allunit', [PerencanaanController::class, 'allUnit'])->name('perencanaan.allUnit');
        Route::get('/perencanaan/total/{tanggal_awal}/{tanggal_akhir}', [PerencanaanController::class, 'getTotal'])->name('perencanaan.getTotal');
        Route::get('/perencanaan/rekap/{tanggal_awal}/{tanggal_akhir}', [PerencanaanController::class, 'laporanRekap'])->name('perencanaan.laporanRekap');
        Route::get('/perencanaan/laporan/{tanggal_awal}/{tanggal_akhir}', [PerencanaanController::class, 'laporanAllUnit'])->name('perencanaan.laporanAllUnit');
        Route::get('/perencanaan/{tanggal_awal}/{tanggal_akhir}', [PerencanaanController::class, 'getAll'])->name('perencanaan.getAll');

        Route::get('/perencanaan/detail', [PerencanaanDetailController::class, 'index'])->name('perencanaan_detail.index');
        Route::post('/perencanaan/detail/store', [PerencanaanDetailController::class, 'store'])->name('perencanaan_detail.store');
        Route::get('/detail/{id}/data', [PerencanaanDetailController::class, 'data'])->name('perencanaan_detail.data');
        Route::delete('/perencanaan/{id}/delete', [PerencanaanDetailController::class, 'destroy'])->name('perencanaan_detail.destroy');

        Route::get('/perencanaan/barang', [PermintaanBarangController::class, 'index'])->name('permintaan_barang.index');


        Route::get('/penerimaan', [BarangdatangController::class, 'index'])->name('penerimaan.index');
        Route::post('/penerimaan/create', [BarangdatangController::class, 'create'])->name('penerimaan.create');
        Route::post('/penerimaan/simpan', [BarangdatangController::class, 'simpan'])->name('penerimaan.simpan');
        Route::get('/penerimaan/data', [BarangdatangController::class, 'data'])->name('penerimaan.data');
        Route::get('/penerimaan/edit/{id}', [BarangdatangController::class, 'edit'])->name('penerimaan.edit');
        Route::get('/penerimaan/show', [BarangdatangController::class, 'show'])->name('penerimaan.show');
        Route::get('/penerimaan/data-update/{id}', [BarangdatangController::class, 'data_Update'])->name('penerimaan.data_Update');
        Route::post('/penerimaan/update_form', [BarangdatangController::class, 'update_form'])->name('penerimaan.update_form');
        Route::get('/penerimaan/laporan', [BarangdatangController::class, 'laporan'])->name('penerimaan.laporan');
        Route::get('/penerimaan/data/{id}/{tanggal_awal}/{tanggal_akhir}', [BarangdatangController::class, 'Getdata'])->name('penerimaan.getData');
        Route::get('/penerimaan/cetak/{id}/{tanggal_awal}/{tanggal_akhir}', [BarangdatangController::class, 'cetak_laporan'])->name('penerimaan.cetak_laporan');
        Route::get('/penerimaan/all', [BarangdatangController::class, 'allRencana'])->name('penerimaan.allRencana');
        Route::get('/penerimaan/data/allunit', [BarangdatangController::class, 'allUnit'])->name('penerimaan.allUnit');
        Route::get('/penerimaan/total/{tanggal_awal}/{tanggal_akhir}', [BarangdatangController::class, 'getTotal'])->name('penerimaan.getTotal');
        Route::get('/penerimaan/rekap/{tanggal_awal}/{tanggal_akhir}', [BarangdatangController::class, 'laporanRekap'])->name('penerimaan.laporanRekap');
        Route::get('/penerimaan/laporan/{tanggal_awal}/{tanggal_akhir}', [BarangdatangController::class, 'laporanAllUnit'])->name('penerimaan.laporanAllUnit');
        Route::get('/penerimaan/{tanggal_awal}/{tanggal_akhir}', [BarangdatangController::class, 'getAll'])->name('penerimaan.getAll');

        Route::get('/penerimaandetail', [PenerimaanCartController::class, 'index'])->name('penerimaan_detail.index');
        Route::post('/penerimaandetail/store', [PenerimaanCartController::class, 'store'])->name('penerimaan_detail.store');
        Route::delete('/penerimaandetail/{id}/delete', [PenerimaanCartController::class, 'destroy'])->name('penerimaan_cart.destroy');
        Route::get('/penerimaandetail/{id}/data', [PenerimaanDetailController::class, 'data'])->name('penerimaan_detail.data');
        // Route::delete('/penerimaandetail/{id}/delete', [PenerimaanDetailController::class, 'data_destroy'])->name('penerimaan_detail.data_destroy');
        Route::post('/penerimaandetail/update', [PenerimaanDetailController::class, 'update'])->name('penerimaan_detail.update');
        // Route::post('/penerimaandetail/ban', [PenerimaanDetailController::class, 'banStore'])->name('penerimaan_detail.ban');
        Route::post('/penerimaandetail/ban', [PenerimaanCartController::class, 'banStore'])->name('penerimaan_detail.ban');

        Route::get('/mobilisasi/data', [MobilisasiController::class, 'data'])->name('mobilisasi.data');
        Route::post('/mobilisasi/create', [MobilisasiController::class, 'create'])->name('mobilisasi.create');
        Route::get('/mobilisasi/selesai', [MobilisasiController::class, 'selesai'])->name('mobilisasi.selesai');
        Route::get('/mobilisasi/nota', [MobilisasiController::class, 'notaBesar'])->name('mobilisasi.nota_besar');
        Route::get('/mobilisasi/report', [MobilisasiController::class, 'report'])->name('mobilisasi.report');
        Route::get('/report/getall', [MobilisasiController::class, 'getAll'])->name('mobilisasi.getAll');
        Route::get('/report/data/{id_lokasi}/{tanggal_awal}/{tanggal_akhir}', [MobilisasiController::class, 'reportData'])->name('mobilisasi.reportData');
        Route::get('/report/cetak/{id_lokasi}/{tanggal_awal}/{tanggal_akhir}', [MobilisasiController::class, 'laporan'])->name('mobilisasi.laporan');
        Route::get('/mobilisasi/detail/{id_mobilisasi}', [MobilisasiController::class, 'detail'])->name('mobilisasi.detail');
        Route::resource('/mobilisasi', MobilisasiController::class)
            ->except('create');

        Route::get('/mobilisasidetail/{id}/data', [MobilisasidetailController::class, 'data'])->name('mobilisasidetail.data');
        Route::get('/mobilisasidetail/aset', [MobilisasidetailController::class, 'aset'])->name('mobilisasidetail.aset');
        Route::resource('/mobilisasidetail', MobilisasidetailController::class)
            ->except('destroy');

        Route::controller(KembaliController::class)->group(function(){
            Route::get('/kembali', 'index')->name('kembali.index');
            Route::get('/kembali/data', 'data')->name('kembali.data');
            Route::get('/kembali/kembali/{detail}', 'kembali')->name('kembali.kembali');
            Route::delete('/kembali/{id}/destroy', 'destroy')->name('kembali.destroy');
        });

        Route::get('/gudang/keluar', [GudangController::class, 'index'])->name('gudang.index');
        Route::get('/gudang/kodeservice', [GudangController::class, 'getKodeService'])->name('gudang.service');
        Route::get('/gudang/create', [GudangController::class, 'create'])->name('gudang.create');

        Route::get('/permintaandetail/{id}/data', [PermintaandetailController::class, 'data'])->name('permintaandetail.data');
        Route::get('/permintaandetail/{id}/detail', [PermintaandetailController::class, 'getDetail'])->name('permintaandetail.getDetail');
        Route::post('/permintaandetail/simpan', [PermintaandetailController::class, 'simpanBan'])->name('permintaandetail.simpanBan');
        Route::resource('/permintaandetail', PermintaandetailController::class)
            ->except('index', 'show');
        Route::get('permintaandetail/{id}', [PermintaandetailController::class, 'index'])->name('permintaandetail.index');

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
        Route::get('/service/laporan/{id}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'laporan'])->name('service.laporan');
        Route::get('all_history/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'getAll'])->name('service.getAll');
        Route::get('total/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'getTotal'])->name('service.getTotal');
        Route::get('rekap/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'laporanRekap'])->name('service.laporanRekap');
        Route::get('laporan/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'laporanAllUnit'])->name('service.laporanAllUnit');
        
        Route::get('/service/sparepart', [ServiceController::class, 'detailByBarang'])->name('service.detail');
        Route::get('/service/barang/{id_barang}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'getByBarang'])->name('service.get_barang');
        Route::get('/service/cetak/{id_barang}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'cetakByBarang'])->name('service.cetakByBarang');
        Route::get('/service/data/{id}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'Getdata'])->name('service.history2');
        Route::resource('/service', ServiceController::class)
            ->except('create','update','selesai');

        Route::get('/sparepart/{id_permintaan}/create', [SparepartController::class, 'create'])->name('sparepart.create');       
        Route::get('/sparepart/data', [SparepartController::class, 'data'])->name('sparepart.data');
        Route::get('/sparepart/{tanggal_awal}/{tanggal_akhir}', [SparepartController::class, 'getAll'])->name('sparepart.getAll');
        Route::get('/sparepart/laporan/{tanggal_awal}/{tanggal_akhir}', [SparepartController::class, 'laporan'])->name('sparepart.laporan');       
        Route::resource('/sparepart', SparepartController::class)
        ->except('create');

    });

    Route::group(['middleware' => 'level:1'], function () {

        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::get('/shutdown', function(){return Artisan::call('down', ['--secret' => env("SECRET_KEY")]);});
        Route::get('/live', function(){return Artisan::call('up');});
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });
 
    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [UserController::class, 'updateProfil'])->name('user.update_profil');
    });

    Route::group(['middleware' => 'level:1,2,3,4'], function () {
        Route::get('/barang/data', [BarangController::class, 'data'])->name('barang.data');
        Route::post('/barang/delete-selected', [BarangController::class, 'deleteSelected'])->name('barang.delete_selected');
        Route::post('/barang/cetak-barcode', [BarangController::class, 'cetakBarcode'])->name('barang.cetak_barcode');
        Route::get('/barang/data/{kelompok}', [BarangController::class, 'byKelompok'])->name('barang.kelompok');
        Route::get('/barang/detail/{id_barang}', [BarangController::class, 'detail'])->name('barang.detail');
        Route::get('/barang/count/{id_barang}', [BarangController::class, 'countBan'])->name('barang.countBan');
        Route::get('/barang/getsession', [BarangController::class, 'getSession'])->name('barang.getSession');
        Route::get('/barang/updateban', [BarangController::class, 'updateBan'])->name('barang.updateBan');
        Route::resource('/barang', BarangController::class);

        Route::get('/ban/data', [BanController::class, 'data'])->name('ban.data');
        Route::get('/ban/pakai', [BanController::class, 'dataPakai'])->name('ban.dataPakai');
        Route::get('/ban/list-pakai', [BanController::class, 'banPakai'])->name('ban.banPakai');
        Route::post('/ban/insert', [BanController::class, 'insertBan'])->name('ban.insertBan');
        Route::resource('/ban', BanController::class);


        Route::get('/permintaan/{id}/sparepart', [PermintaanController::class, 'sparepart'])->name('permintaan.sparepart');
        Route::get('/permintaan/selesai/{id}', [PermintaanController::class, 'selesai'])->name('permintaan.selesai');
        Route::get('/permintaan/nota/{id}', [PermintaanController::class, 'notaBesar'])->name('permintaan.nota_besar');
        Route::post('/permintaan/store', [PermintaanController::class, 'store'])->name('permintaan.store');
        Route::get('/service/data', [ServiceController::class, 'data'])->name('service.data');
        
        Route::get('/service/sparepart', [ServiceController::class, 'detailByBarang'])->name('service.detail');
        Route::get('/service/barang/{id_barang}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'getByBarang'])->name('service.get_barang');
        Route::get('/service/cetak/{id_barang}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'cetakByBarang'])->name('service.cetakByBarang');
        Route::get('/service/data/{id}/{tanggal_awal}/{tanggal_akhir}', [ServiceController::class, 'Getdata'])->name('service.history2');
        Route::resource('/service', ServiceController::class)
            ->except('create', 'update');

        Route::get('/permintaandetail/{id}/data', [PermintaandetailController::class, 'data'])->name('permintaandetail.data');
        Route::resource('/permintaandetail', PermintaandetailController::class)
            ->except('index', 'show');
        Route::get('permintaandetail/{id}', [PermintaandetailController::class, 'index'])->name('permintaandetail.index');
    });
});