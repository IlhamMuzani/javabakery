<?php

use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SlawiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\Admin\AddpelangganController;
use App\Http\Controllers\Admin\EstimasiproduksiController;
use App\Http\Controllers\Admin\HargajualController;
use App\Http\Controllers\Admin\Inquery_estimasiproduksiController;
use App\Http\Controllers\Admin\Inquery_hasilpenjualanController;
use App\Http\Controllers\Admin\Inquery_pemesananprodukController;
use App\Http\Controllers\Admin\Inquery_pemindahanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pengirimanbarangjadipesananController;
use App\Http\Controllers\Admin\Inquery_pengirimanpesananController;
use App\Http\Controllers\Admin\Inquery_penjualanprodukController;
use App\Http\Controllers\Admin\Inquery_penjualantokoController;
use App\Http\Controllers\Admin\Inquery_returbarangjadiController;
use App\Http\Controllers\Admin\Inquery_setoranpelunasanController;
use App\Http\Controllers\Admin\Inquery_stokbarangjadiController;
use App\Http\Controllers\Admin\PemesananprodukController;
use App\Http\Controllers\Admin\KlasifikasiController as AdminKlasifikasiController;
use App\Http\Controllers\Admin\Laporan_hasilpenjualanController;
use App\Http\Controllers\Admin\Laporan_pemesananprodukController;
use App\Http\Controllers\Admin\Laporan_pengirimanpesananController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PenjualanprodukController;
use App\Http\Controllers\Admin\PermintaanprodukController;
use App\Http\Controllers\Admin\Laporan_permintaanprodukController;
use App\Http\Controllers\Admin\Laporan_stoktokoController;
use App\Http\Controllers\Admin\PemusnahanbarangjadiController;
use App\Http\Controllers\Admin\Pengiriman_tokoslawiController;
use App\Http\Controllers\Admin\PengirimanbarangjadiController;
use App\Http\Controllers\Admin\PengirimanbarangjadipesananController;
use App\Http\Controllers\Admin\PenjualantokoController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\Setoran_pelunasanController;
use App\Http\Controllers\Admin\Stok_barangjadiController;
use App\Http\Controllers\Admin\Stok_tokoslawiController;
use App\Http\Controllers\Admin\SurathasilproduksiController;
use App\Http\Controllers\Admin\SuratperintahproduksiController;
use App\Http\Controllers\KategoriDropdownController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_setorantunaibanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_pemindahanbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_setoranpenjualanController;
use App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController;
use App\Http\Controllers\Toko_banjaran\PelunasanpemesananController;
use App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController;
use App\Http\Controllers\Toko_banjaran\Pengirimanpemesanan_tokobanjaranController;
use App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Setoran_tokobanjaranController;
use App\Http\Controllers\Toko_banjaran\Stok_tokobanjaranController;
use App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Inquery_setorantunaibumiayuController;
use App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController;
use App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Laporan_pemindahanbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Laporan_setoranpenjualanbmyController;
use App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController;
use App\Http\Controllers\Toko_bumiayu\PelunasanpemesananBmyController;
use App\Http\Controllers\Toko_bumiayu\PemesananprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController;
use App\Http\Controllers\Toko_bumiayu\Pengirimanpemesanan_tokobumiayuController;
use App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Setoran_tokobumiayuController;
use App\Http\Controllers\Toko_bumiayu\Stok_tokobumiayuController;
use App\Http\Controllers\Toko_bumiayu\Stokpesanan_tokobumiayuController;
use App\Http\Controllers\Toko_cilacap\Inquery_pemindahancilacapController;
use App\Http\Controllers\Toko_cilacap\Inquery_penjualanprodukcilacapController;
use App\Http\Controllers\Toko_cilacap\Inquery_returcilacapController;
use App\Http\Controllers\Toko_cilacap\Inquery_setorantunaicilacapController;
use App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController;
use App\Http\Controllers\Toko_cilacap\Laporan_pemesananprodukcilacapController;
use App\Http\Controllers\Toko_cilacap\Laporan_pemindahancilacapController;
use App\Http\Controllers\Toko_cilacap\Laporan_setoranpenjualanclcController;
use App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController;
use App\Http\Controllers\Toko_cilacap\PelangganController as Toko_cilacapPelangganController;
use App\Http\Controllers\Toko_cilacap\PelunasanpemesananClcController;
use App\Http\Controllers\Toko_cilacap\PemesananprodukcilacapController;
use App\Http\Controllers\Toko_cilacap\Pengiriman_tokocilacapController;
use App\Http\Controllers\Toko_cilacap\Pengirimanpemesanan_tokocilacapController;
use App\Http\Controllers\Toko_cilacap\PenjualanprodukcilacapController;
use App\Http\Controllers\Toko_cilacap\PermintaanprodukcilacapController;
use App\Http\Controllers\Toko_cilacap\Setoran_tokocilacapController;
use App\Http\Controllers\Toko_cilacap\Stok_tokocilacapController;
use App\Http\Controllers\Toko_cilacap\Stokpesanan_tokocilacapController;
use App\Http\Controllers\Toko_pemalang\Inquery_pemindahanpemalangController;
use App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController;
use App\Http\Controllers\Toko_pemalang\Inquery_setorantunaipemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_pemindahanpemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_setoranpenjualanpmlController;
use App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController;
use App\Http\Controllers\Toko_pemalang\PelunasanpemesananPmlController;
use App\Http\Controllers\Toko_pemalang\PemesananprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\Pengirimanpemesanan_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\PenjualanprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Setoran_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\Stok_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\Stokpesanan_tokopemalangController;
use App\Http\Controllers\Toko_slawi\Inquery_pemindahanslawiController;
use App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukController as Toko_slawiInquery_penjualanprodukController;
use App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukslawiController;
use App\Http\Controllers\Toko_slawi\Inquery_returslawiController;
use App\Http\Controllers\Toko_slawi\Inquery_setorantunaislawiController;
use App\Http\Controllers\Toko_slawi\Laporan_historislawiController;
use App\Http\Controllers\Toko_slawi\Laporan_pemesananprodukslawiController;
use App\Http\Controllers\Toko_slawi\Laporan_pemindahanslawiController;
use App\Http\Controllers\Toko_slawi\Laporan_setoranpenjualanslwController;
use App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController;
use App\Http\Controllers\Toko_slawi\PelunasanpemesananSlwController;
use App\Http\Controllers\Toko_slawi\PemesananprodukslawiController;
use App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController as Toko_slawiPengiriman_tokoslawiController;
use App\Http\Controllers\Toko_slawi\Pengirimanpemesanan_tokoslawiController;
use App\Http\Controllers\Toko_slawi\PenjualanprodukslawiController;
use App\Http\Controllers\Toko_slawi\PermintaanprodukslawiController;
use App\Http\Controllers\Toko_slawi\Retur_tokoslawiController;
use App\Http\Controllers\Toko_slawi\Setoran_tokoslawiController;
use App\Http\Controllers\Toko_slawi\Stok_tokoslawiController as Toko_slawiStok_tokoslawiController;
use App\Http\Controllers\Toko_slawi\Stokpesanan_tokoslawiController;
use App\Http\Controllers\Toko_tegal\Inquery_pemindahantegalController;
use App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController;
use App\Http\Controllers\Toko_tegal\Inquery_returtegalController;
use App\Http\Controllers\Toko_tegal\Inquery_setorantunaitegalController;
use App\Http\Controllers\Toko_tegal\Laporan_historitegalController;
use App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController;
use App\Http\Controllers\Toko_tegal\Laporan_pemindahantegalController;
use App\Http\Controllers\Toko_tegal\Laporan_pengirimanpemesanantokotegalController;
use App\Http\Controllers\Toko_tegal\Laporan_pengirimantokotegalController;
use App\Http\Controllers\Toko_tegal\Laporan_setoranpenjualantglController;
use App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController;
use App\Http\Controllers\Toko_tegal\PelunasanpemesananTglController;
use App\Http\Controllers\Toko_tegal\PemesananproduktegalController;
use App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController;
use App\Http\Controllers\Toko_tegal\PenjualanproduktegalController;
use App\Http\Controllers\Toko_tegal\PermintaanproduktegalController;
use App\Http\Controllers\Toko_tegal\Setoran_tokotegalController;
use App\Http\Controllers\Toko_tegal\Stok_tokotegalController;
use App\Http\Controllers\Toko_tegal\Stokpesanan_tokotegalController;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_tokopemalang;
use App\Models\Pengiriman_tokoslawi;
use App\Models\Pengiriman_tokotegal;
use App\Models\Pengirimanpemesanan_tokocilacap;
use App\Models\Pengirimanpemesanan_tokotegal;
use App\Models\Setoran_penjualan;
use App\Models\Stok_retur;
use App\Models\Stokpesanan_tokotegal;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|inde
*/

Route::get('/', [AuthController::class, 'index']);
Route::get('login', [AuthController::class, 'index']);
Route::get('loginn', [AuthController::class, 'tologin']);
Route::get('register', [AuthController::class, 'toregister']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'registeruser']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('check-user', [HomeController::class, 'check_user']);

Route::get('produk/{kode}', [\App\Http\Controllers\ProdukController::class, 'detail']);


Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);

    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index']);
    Route::post('profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update']);

    Route::resource('akses', \App\Http\Controllers\Admin\AksesController::class);
    Route::get('akses/access/{id}', [\App\Http\Controllers\Admin\AksesController::class, 'access']);
    Route::post('akses-access/{id}', [\App\Http\Controllers\Admin\AksesController::class, 'access_user']);

    Route::resource('karyawan', \App\Http\Controllers\Admin\KaryawanController::class);

    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::get('user/karyawan/{id}', [\App\Http\Controllers\Admin\UserController::class, 'karyawan']);

    Route::resource('kartu', \App\Http\Controllers\Admin\KartuController::class);

    Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Admin\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    // Route::post('admin/pelanggan/import', [PelangganController::class, 'import'])->name('pelanggan.import');
    Route::post('admin/pelanggan/import', [PelangganController::class, 'importPelanggan'])->name('pelanggan.import');

    Route::resource('departemen', \App\Http\Controllers\Admin\DepartemenController::class);

    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class);
    Route::get('klasifikasi/get_klasifikasi/{id}', [\App\Http\Controllers\Admin\BarangController::class, 'get_klasifikasi']);

    Route::resource('klasifikasi', \App\Http\Controllers\Admin\KlasifikasiController::class);
    Route::get('/klasifikasi/{id}/sub', [\App\Http\Controllers\Admin\KlasifikasiController::class, 'getSubCategories']);
    Route::get('klasifikasi/get_subklasifikasi/{id}', [\App\Http\Controllers\Admin\KlasifikasiController::class, 'get_subklasifikasi']);

    Route::resource('addkategori', \App\Http\Controllers\Admin\AddkategoriController::class);
    Route::post('tambahkategori', [\App\Http\Controllers\Admin\AddkategoriController::class, 'tambahkategori']);

    Route::resource('addsub', \App\Http\Controllers\Admin\AddsubController::class);
    Route::get('addsub/get_subklasifikasi/{id}', [\App\Http\Controllers\Admin\AddsubController::class, 'get_subklasifikasi']);

    Route::resource('subklasifikasi', \App\Http\Controllers\Admin\SubklasifikasiController::class);

    Route::resource('member', \App\Http\Controllers\Admin\MemberController::class);

    Route::resource('input', \App\Http\Controllers\Admin\InputController::class);

    Route::resource('produk', \App\Http\Controllers\Admin\ProdukController::class);
    Route::post('admin/produk/import', [ProdukController::class, 'import'])->name('produk.import');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/subklasifikasi/fetch', [ProdukController::class, 'fetch'])->name('subklasifikasi.fetch');
    Route::get('admin/produk/{id}/print', [ProdukController::class, 'print'])->name('produk.print');
    Route::get('admin/produk/{id}/cetak_barcode', [ProdukController::class, 'cetak_barcode'])->name('produk.cetak_barcode');




    Route::resource('toko', \App\Http\Controllers\Admin\TokoController::class);

    Route::resource('hargajual', \App\Http\Controllers\Admin\HargajualController::class);
    Route::get('admin/hargajual/all', [HargajualController::class, 'all'])->name('admin.hargajual.all');
    Route::post('/admin/update-harga', [HargajualController::class, 'updateHarga'])->name('update.harga');
    Route::post('/admin/update-hargaTgl', [HargajualController::class, 'updateHargaTgl'])->name('update.hargaTgl');
    Route::post('/admin/update-hargaPml', [HargajualController::class, 'updateHargaPml'])->name('update.hargaPml');
    Route::post('/admin/update-hargaSlw', [HargajualController::class, 'updateHargaSlw'])->name('update.hargaSlw');
    Route::post('/admin/update-hargaBmy', [HargajualController::class, 'updateHargaBmy'])->name('update.hargaBmy');
    Route::post('/admin/update-hargaClc', [HargajualController::class, 'updateHargaClc'])->name('update.hargaClc');
    Route::get('admin/hargajual/show', [App\Http\Controllers\Admin\HargajualController::class, 'show'])->name('show');
    Route::get('/cetak-pdf', [HargajualController::class, 'cetakPdf'])->name('cetak.pdf');
    Route::get('/admin/hargajual/filter', [HargajualController::class, 'all'])->name('admin.hargajual.filter');
    Route::get('/produk/perubahan', [HargajualController::class, 'showPerubahanProduk'])->name('produk.showPerubahan');
    Route::get('/print-reporthargajual', [HargajualController::class, 'print'])->name('print.reporthargajual');
    Route::get('indextegal', [\App\Http\Controllers\Admin\HargajualController::class, 'indextegal'])->name('indextegal');



    Route::resource('pemesanan_produk', \App\Http\Controllers\Admin\PemesananprodukController::class);
    Route::get('/admin/pemesanan_produk/cetak/{id}', [PemesananProdukController::class, 'cetak'])->name('admin.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananProdukController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Admin\PemesananprodukController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/admin/pemesanan_produk/update/{id}', [PemesananprodukController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/admin/pemesanan_produk/cetak-pdf{id}', [PemesananprodukController::class, 'cetakPdf'])->name('admin.pemesanan_produk.cetak-pdf');
    Route::delete('admin/pemesanan_produk/{id}', [PemesananProdukController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/admin/pemesanan_produk/{id}/cetak', [PemesananProdukController::class, 'cetak'])->name('admin.pemesanan_produk.cetak');



    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Admin\Inquery_pemesananprodukController::class);
    Route::get('/admin/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('admin.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Admin\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Admin\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananproduk', \App\Http\Controllers\Admin\Laporan_pemesananprodukController::class);
    Route::get('print_pemesanan', [\App\Http\Controllers\Admin\Laporan_pemesananprodukController::class, 'print_pemesanan']);
    Route::get('printReportpemesanan', [Laporan_pemesananprodukController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobal', [\App\Http\Controllers\Admin\Laporan_pemesananprodukController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobal', [Laporan_pemesananprodukController::class, 'printReportPemesananglobal'])->name('printReportPemesananglobal');
    Route::get('printReportpemesananglobal1', [Laporan_pemesananprodukController::class, 'printReportPemesananglobal1'])->name('printReportPemesananglobal1');
    Route::get('/laporan/pemesanan', [Laporan_pemesananprodukController::class, 'printReportPemesananglobal'])->name('laporan.pemesanan.printglobal');


    Route::resource('penjualan_produk', \App\Http\Controllers\Admin\PenjualanprodukController::class);
    // Route::get('/admin/penjualan_produk/cetak/{id}', [PenjualanprodukController::class, 'cetak'])->name('admin.penjualan_produk.cetak');
    // Route::get('/admin/penjualan_produk/cetak-pdf{id}', [PenjualanprodukController::class, 'cetakPdf'])->name('admin.penjualan_produk.cetak-pdf');
    // Route::get('/admin/penjualan_produk/pelunasan', [PenjualanprodukController::class, 'pelunasan'])->name('admin.penjualan_produk.pelunasan');
    // Route::get('admin/penjualan_produk/create', [PenjualanProdukController::class, 'create'])->name('admin.penjualan_produk.create');
    // Route::get('/admin/penjualan_produk/pelunasan', [PenjualanprodukController::class, 'pelunasan'])->name('admin.penjualan_produk.pelunasan');
    // Route::get('/products/{tokoId}', [PenjualanprodukController::class, 'getProductsByToko'])->name('products.byToko');
    // Route::get('/fetch-data-by-kode', [PenjualanprodukController::class, 'fetchDataByKode'])->name('admin.penjualan_produk.fetchData');
    // Route::get('/metodepembayaran/{id}', [PenjualanprodukController::class, 'getMetodePembayaran']);
    // Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Admin\PenjualanprodukController::class, 'metode']);

    Route::resource('penjualan_toko', \App\Http\Controllers\Admin\PenjualantokoController::class);
    Route::post('/get-penjualan', [PenjualantokoController::class, 'getdata'])->name('getdata');
    Route::get('/print-penjualantoko-kotor', [PenjualantokoController::class, 'printPenjualanKotor'])->name('print.penjualantoko.kotor');
    Route::get('/print-fakturpenjualantoko', [PenjualantokoController::class, 'printFakturpenjualan'])->name('print.fakturpenjualantoko');
    Route::get('/print-fakturpenjualanmesinedc', [PenjualantokoController::class, 'printFakturpenjualanMesinedc'])->name('print.fakturpenjualanmesinedc');
    Route::get('/print-fakturpemesananmesinedc', [PenjualantokoController::class, 'printFakturpemesananMesinedc'])->name('print.fakturpemesananmesinedc');
    Route::get('/print-fakturpenjualanqris', [PenjualantokoController::class, 'printFakturpenjualanQris'])->name('print.fakturpenjualanqris');
    Route::get('/print-fakturpemesananqris', [PenjualantokoController::class, 'printFakturpemesananQris'])->name('print.fakturpemesananqris');
    Route::get('/print-fakturpenjualantransfer', [PenjualantokoController::class, 'printFakturpenjualanTransfer'])->name('print.fakturpenjualantransfer');
    Route::get('/print-fakturpemesanantransfer', [PenjualantokoController::class, 'printFakturpemesananTransfer'])->name('print.fakturpemesanantransfer');
    Route::get('/print-fakturpenjualangobiz', [PenjualantokoController::class, 'printFakturpenjualanGobiz'])->name('print.fakturpenjualangobiz');
    Route::get('/print-fakturpemesanangobiz', [PenjualantokoController::class, 'printFakturpemesananGobiz'])->name('print.fakturpemesanangobiz');
    Route::get('/print-fakturdepositmasuktoko', [PenjualantokoController::class, 'printFakturdepositMasuk'])->name('print.fakturdepositmasuktoko');
    Route::get('/print-fakturdepositkeluartoko', [PenjualantokoController::class, 'printFakturdepositKeluar'])->name('print.fakturdepositkeluartoko');
    Route::get('/print-penjualantoko-diskon', [PenjualantokoController::class, 'printPenjualanDiskon'])->name('print.penjualantoko.diskon');
    Route::get('/print-penjualantoko-bersih', [PenjualantokoController::class, 'printPenjualanBersih'])->name('print.penjualantoko.bersih');
    Route::get('/penjualan_toko/{id}', [PenjualantokoController::class, 'show'])->name('penjualan_toko.show');
    Route::get('penjualanproduk/detail/{id}', [PenjualantokoController::class, 'show3'])->name('penjualanproduk.detail');
    Route::get('penjualanproduk/detaildepositkeluar/{id}', [PenjualantokoController::class, 'show2'])->name('penjualanproduk.detaildepositkeluar');
    Route::get('pemesananproduk/detailpemesanan/{id}', [PenjualantokoController::class, 'show1'])->name('pemesananproduk.detailpemesanan');
    Route::get('/penjualan_toko/{id}/print', [PenjualantokoController::class, 'print'])->name('penjualan_toko.print');

    Route::resource('inquery_penjualantoko', \App\Http\Controllers\Admin\Inquery_penjualantokoController::class);
    Route::get('/admin/inquery_penjualantoko/{id}/print', [Inquery_penjualantokoController::class, 'print'])->name('inquery_penjualantoko.print');
    Route::get('inquery_penjualantoko/unpost_penjualantoko/{id}', [\App\Http\Controllers\Admin\Inquery_penjualantokoController::class, 'unpost_penjualantoko']);
    Route::get('inquery_penjualantoko/posting_penjualantoko/{id}', [\App\Http\Controllers\Admin\Inquery_penjualantokoController::class, 'posting_penjualantoko']);

    Route::resource('laporan_penjualantoko', \App\Http\Controllers\Admin\Laporan_penjualantokoController::class);
    Route::get('printReportpenjualanToko', [\App\Http\Controllers\Admin\Laporan_penjualantokoController::class, 'printReportpenjualanToko']);


    Route::resource('laporan_setoranpelunasan', \App\Http\Controllers\Admin\Laporan_setoranpelunasanController::class);
    Route::get('printReportpelunasanToko', [\App\Http\Controllers\Admin\Laporan_setoranpelunasanController::class, 'printReportpelunasanToko']);


    Route::resource('inquery_penjualanproduk', \App\Http\Controllers\Admin\Inquery_penjualanprodukController::class);
    Route::get('/admin/inquery_penjualanproduk', [Inquery_penjualanprodukController::class, 'index'])->name('admin.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanproduk/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Admin\Inquery_penjualanprodukController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanproduk/posting_penjualanproduk/{id}', [\App\Http\Controllers\Admin\Inquery_penjualanprodukController::class, 'posting_penjualanproduk']);
    Route::get('/inquery_penjualanproduk/{id}/edit', [Inquery_penjualanprodukController::class, 'edit'])->name('inquery_penjualanproduk.edit');
    Route::post('/inquery_penjualanproduk/{id}/update', [Inquery_penjualanprodukController::class, 'update'])->name('inquery_penjualanproduk.update');
    Route::post('/hapus-produk', [Inquery_penjualanprodukController::class, 'hapusProduk'])->name('hapus.produk');
    Route::get('/metodepembayaran/{id}', [Inquery_penjualanprodukController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Admin\Inquery_penjualanprodukController::class, 'metode']);

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Admin\Laporan_penjualanprodukController::class);
    Route::get('printReportpenjualan', [\App\Http\Controllers\Admin\Laporan_penjualanprodukController::class, 'printReportpenjualan']);
    Route::get('printReportglobal', [\App\Http\Controllers\Admin\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Admin\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Admin\PermintaanprodukController::class);
    Route::post('admin/permintaan_produk', [PermintaanprodukController::class, 'store']);
    Route::get('admin/permintaan_produk', [PermintaanprodukController::class, 'show']);
    Route::post('admin/permintaan_produk/import', [ProdukController::class, 'import'])->name('permintaan_produk.import');
    Route::get('/permintaan-produk/{id}/print', [PermintaanProdukController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Admin\PermintaanprodukController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Admin\PermintaanprodukController::class, 'posting_permintaanproduk']);
    Route::delete('admin/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('admin.permintaan_produk.destroy');

    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Admin\Inquery_permintaanprodukController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Admin\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('printReport2', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'printReporttoko']);
    Route::get('indexpermintaanrinci', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'indexpermintaanrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::resource('stok_barangjadi', \App\Http\Controllers\Admin\Stok_barangjadiController::class);
    Route::get('/stok_barangjadi/{id}/print', [Stok_barangjadiController::class, 'print'])->name('stok_barangjadi.print');
    Route::get('stok_barangjadi/unpost_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Stok_barangjadiController::class, 'unpost_stokbarangjadi']);
    Route::get('stok_barangjadi/posting_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Stok_barangjadiController::class, 'posting_stokbarangjadi']);
    Route::post('stok_barangjadi/import', [Stok_barangjadiController::class, 'import'])->name('stok_barangjadi.import');

    Route::resource('stok_hasilproduksi', \App\Http\Controllers\Admin\Stok_hasilproduksiController::class);

    Route::resource('inquery_stokbarangjadi', \App\Http\Controllers\Admin\Inquery_stokbarangjadiController::class);
    Route::get('inquery_stokbarangjadi/unpost_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_stokbarangjadiController::class, 'unpost_stokbarangjadi']);
    Route::get('inquery_stokbarangjadi/posting_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_stokbarangjadiController::class, 'posting_stokbarangjadi']);
    Route::get('admin/inquery_stokbarangjadi/{id}/edit', [Inquery_stokbarangjadiController::class, 'edit'])->name('stokbarangjadi.edit');
    Route::put('admin/inquery_stokbarangjadi/{id}', [Inquery_stokbarangjadiController::class, 'update'])->name('stokbarangjadi.update');

    Route::resource('laporan_stokbarangjadi', \App\Http\Controllers\Admin\Laporan_stokbarangjadiController::class);
    Route::get('print1', [\App\Http\Controllers\Admin\Laporan_stokbarangjadiController::class, 'printReport']);

    Route::resource('laporan_stoktoko', \App\Http\Controllers\Admin\Laporan_stoktokoController::class);
    Route::get('stoktokopesanan', [\App\Http\Controllers\Admin\Laporan_stoktokoController::class, 'stoktokopesanan']);
    Route::get('printstoktoko', [\App\Http\Controllers\Admin\Laporan_stoktokoController::class, 'printReport']);
    Route::get('printstoktokopesanan', [\App\Http\Controllers\Admin\Laporan_stoktokoController::class, 'printReportstokpesanan']);
    Route::get('printExcelStok', [Laporan_stoktokoController::class, 'exportExcelStok'])->name('printExcelStok');



    Route::resource('data_deposit', \App\Http\Controllers\Admin\DepositController::class);

    Route::resource('inquery_deposit', \App\Http\Controllers\Admin\Inquery_depositController::class);

    Route::resource('laporan_deposit', \App\Http\Controllers\Admin\Laporan_depositController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'printReportsaldo']);

    Route::resource('data_stokbarangjadi', \App\Http\Controllers\Admin\Data_stokbarangjadiController::class);

    Route::resource('data_stokretur', \App\Http\Controllers\Admin\Data_stokreturController::class);

    Route::resource('pengiriman_barangjadi', \App\Http\Controllers\Admin\PengirimanbarangjadiController::class);
    Route::get('/pengiriman_barangjadi/{id}/print', [PengirimanbarangjadiController::class, 'print'])->name('pengiriman_barangjadi.print');
    Route::get('admin/pengiriman_barangjadi/create', [PengirimanbarangjadiController::class, 'create'])->name('admin.pengiriman_barangjadi.create');
    Route::get('/admin/pengiriman_barangjadi/pengiriman_pemesanan', [PengirimanbarangjadiController::class, 'pengiriman_pemesanan'])->name('admin.pengiriman_barangjadi.pengiriman_pemesanan');
    Route::post('admin/pengiriman_barangjadi/pengiriman_pemesanan', [PengirimanbarangjadiController::class, 'SimpanPengirimanpemesanan'])->name('pengiriman_barangjadi.pengirimanpemesanan.simpan');
    // Route::get('pengiriman_barangjadi/{id}', [PengirimanbarangjadiController::class, 'showPesanan'])->name('pengiriman_barangjadi.showpesanan');
    Route::post('admin/pengiriman_barangjadi/store', [PengirimanBarangJadiController::class, 'store'])->name('admin.pengiriman_barangjadi.store');


    Route::resource('pengiriman_barangjadipesanan', \App\Http\Controllers\Admin\PengirimanbarangjadipesananController::class);
    Route::get('admin/pengiriman_barangjadipesanan/create', [PengirimanbarangjadipesananController::class, 'create'])->name('admin.pengiriman_barangjadipesanan.create');
    Route::get('/pengiriman_barangjadipesanan/{id}/print', [PengirimanbarangjadipesananController::class, 'print'])->name('pengiriman_barangjadipesanan.print');

    Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class);
    Route::get('/inquery_pengirimanbarangjadi/{id}/print', [Inquery_pengirimanbarangjadiController::class, 'print'])->name('inquery_pengirimanbarangjadi.print');
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);
    Route::get('admin/inquery_pengirimanbarangjadi/{id}/{jumlah}/cetak_barcode', [Inquery_pengirimanbarangjadiController::class, 'cetak_barcode'])->name('inquery_pengirimanbarangjadi.cetak_barcode');
    Route::get('admin/inquery_pengirimanbarangjadi', [Inquery_pengirimanbarangjadiController::class, 'index'])->name('admin.inquery_pengirimanbarangjadi.index');
    Route::delete('inquery_pengirimanbarangjadi/deleteprodukpengiriman/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'deleteprodukpengiriman']);
    Route::get('/admin/inquery_pengirimanbarangjadi/print_qr/{id}', [Inquery_pengirimanbarangjadiController::class, 'showPrintQr'])->name('inquery_pengirimanbarangjadi.print_qr');
    Route::post('admin/inquery_pengirimanbarangjadi/cetak_banyak_barcode', [Inquery_pengirimanbarangjadiController::class, 'cetakBanyakBarcode'])->name('inquery_pengirimanbarangjadi.cetak_banyak_barcode');
    Route::get('admin/inquery_pengirimanbarangjadi/cetak_semua_barcode/{stokBarangJadiId}', [Inquery_pengirimanbarangjadiController::class, 'cetakSemuaBarcode'])->name('inquery_pengirimanbarangjadi.cetak_semua_barcode');
    Route::post('/admin/inquery_pengirimanbarangjadi/cetakSemuaBarcode', [Inquery_pengirimanbarangjadiController::class, 'cetakSemuaBarcode'])->name('inquery_pengirimanbarangjadi.cetakSemuaBarcode');

    Route::resource('inquery_pengirimanpesanan', \App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class);
    Route::get('/inquery_pengirimanpesanan/{id}/print', [Inquery_pengirimanpesananController::class, 'print'])->name('inquery_pengirimanpesanan.print');
    Route::get('admin/inquery_pengirimanpesanan/{id}/cetak_barcodepesanan', [Inquery_pengirimanpesananController::class, 'cetak_barcodepesanan'])->name('inquery_pengirimanpesanan.cetak_barcodepesanan');
    Route::get('admin/inquery_pengirimanpesanan', [Inquery_pengirimanpesananController::class, 'index'])->name('admin.inquery_pengirimanpesanan.index');
    Route::get('inquery_pengirimanpesanan/unpost_pengirimanpesanan/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class, 'unpost_pengirimanpesanan']);
    Route::get('inquery_pengirimanpesanan/posting_pengirimanpesanan/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class, 'posting_pengirimanpesanan']);
    Route::delete('inquery_pengirimanpesanan/deleteprodukpengiriman/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class, 'deleteprodukpengiriman']);


    Route::resource('laporan_pengirimanbarangjadi', \App\Http\Controllers\Admin\Laporan_pengirimanbarangjadiController::class);
    Route::get('print', [\App\Http\Controllers\Admin\Laporan_pengirimanbarangjadiController::class, 'printReport']);


    Route::resource('laporan_pengirimanpesanan', \App\Http\Controllers\Admin\Laporan_pengirimanpesananController::class);
    Route::get('printpesanan', [\App\Http\Controllers\Admin\Laporan_pengirimanpesananController::class, 'printReport']);

    Route::resource('retur_barangjadi', \App\Http\Controllers\Admin\ReturbarangjadiController::class);

    Route::resource('inquery_returbarangjadi', \App\Http\Controllers\Admin\Inquery_returbarangjadiController::class);
    Route::get('inquery_returbarangjadi/unpost_retur/{id}', [\App\Http\Controllers\Admin\Inquery_returbarangjadiController::class, 'unpost_retur']);
    Route::get('inquery_returbarangjadi/posting_retur/{id}', [\App\Http\Controllers\Admin\Inquery_returbarangjadiController::class, 'posting_retur']);
    Route::get('/inquery_returbarangjadi/{id}/print', [Inquery_returbarangjadiController::class, 'print'])->name('inquery_returbarangjadi.print');

    Route::resource('pemindahan_barangjadi', \App\Http\Controllers\Admin\Pemindahan_barangjadiController::class);


    Route::resource('pemusnahan_barangjadi', \App\Http\Controllers\Admin\PemusnahanbarangjadiController::class);
    Route::get('/getReturData', [PemusnahanbarangjadiController::class, 'getReturData'])->name('getReturData');
    Route::get('admin/getProductsByKodeRetur/{kodeRetur}', [PemusnahanbarangjadiController::class, 'getProductsByKodeRetur']);
    Route::get('admin/get-products-by-kode-retru/{kodeRetur}', [PemusnahanbarangjadiController::class, 'getProductsByKodeRetur'])->name('getProductsByKodeRetur');

    Route::resource('inquery_pemusnahanbarangjadi', \App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController::class);
    Route::get('inquery_pemusnahanbarangjadi/unpost_pemusnahan/{id}', [\App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController::class, 'unpost_pemusnahan']);
    Route::get('inquery_pemusnahanbarangjadi/posting_pemusnahan/{id}', [\App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController::class, 'posting_pemusnahan']);
    Route::get('/inquery_pemusnahanbarangjadi/{id}/print', [Inquery_pemusnahanbarangjadiController::class, 'print'])->name('inquery_pemusnahanbarangjadi.print');

    Route::resource('estimasi_produksi', \App\Http\Controllers\Admin\EstimasiproduksiController::class);
    Route::get('admin/estimasi_produksi/{estimasi_produksi}', [EstimasiProduksiController::class, 'show'])->name('estimasi_produksi.show');
    Route::get('admin/estimasi_produksi/{id}/edit', [EstimasiProduksiController::class, 'edit'])->name('estimasi_produksi.edit');
    Route::get('/estimasi_produksi/{id}/print', [EstimasiproduksiController::class, 'print'])->name('estimasi_produksi.print');
    Route::delete('estimasi_produksi/deletedetailpermintaan/{id}', [\App\Http\Controllers\Admin\EstimasiproduksiController::class, 'deletedetailpermintaan']);
    Route::post('admin/estimasi_produksi', [EstimasiproduksiController::class, 'store'])->name('estimasi_produksi.store');



    Route::resource('surat_perintahproduksi', \App\Http\Controllers\Admin\SuratperintahproduksiController::class);
    // Route::get('admin/printReportestimasi', [SuratperintahproduksiController::class, 'printReportestimasi'])->name('printReportestimasi');
    Route::get('printReportestimasi', [\App\Http\Controllers\Admin\SuratperintahproduksiController::class, 'printReportestimasi']);
    // Route::get('/estimasi-produksi/cetak', [EstimasiProduksiController::class, 'cetak'])->name('estimasi.produksi.cetak');
    Route::get('printReportestimasirinci', [\App\Http\Controllers\Admin\SuratperintahproduksiController::class, 'printReportestimasirinci']);

    Route::resource('surat_hasilproduksi', \App\Http\Controllers\Admin\SurathasilproduksiController::class);
    Route::post('/save-realisasi', [SurathasilproduksiController::class, 'saveRealisasi'])->name('saveRealisasi');
    Route::get('/surathasilproduksi/{id}', [SurathasilproduksiController::class, 'show'])->name('surathasilproduksi.show');
    Route::get('/hasilproduksi/{id}/print', [SurathasilproduksiController::class, 'print'])->name('hasilproduksi.print');

    Route::resource('inquery_hasilproduksi', \App\Http\Controllers\Admin\Inquery_hasilproduksiController::class);


    Route::resource('inquery_estimasiproduksi', \App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class);
    Route::delete('admin/inquery_estimasiproduksi/{id}', [Inquery_estimasiproduksiController::class, 'destroy'])->name('admin.inquery_estimasiproduksi.destroy');
    Route::get('inquery_estimasiproduksi/unpost_estimasiproduksi/{id}', [\App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class, 'unpost_estimasiproduksi']);
    Route::get('inquery_estimasiproduksi/posting_estimasiproduksi/{id}', [\App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class, 'posting_estimasiproduksi']);

    Route::resource('laporan_estimasiproduksi', \App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class);
    Route::get('printReport', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReport']);
    Route::get('printReportPermintaan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPermintaan']);
    Route::get('printReportPermintaantoko', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPermintaantoko']);
    Route::get('printReportPemesanan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPemesanan']);
    Route::get('printReportPemesanantoko', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPemesanantoko']);
    Route::get('printReportAll', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportAll']);
    Route::get('indexpemesanan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'indexpemesanan']);
    Route::get('indexpermintaan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'indexpermintaan']);

    Route::resource('inquery_pemindahanbarangjadi', \App\Http\Controllers\Admin\Inquery_pemindahanbarangjadiController::class);
    Route::get('/inquery_pemindahanbarangjadi/{id}/print', [Inquery_pemindahanbarangjadiController::class, 'print'])->name('inquery_pemindahanbarangjadi.print');
    Route::get('/inquery_pemindahanbarangjadi/{id}/print', [Inquery_pemindahanbarangjadiController::class, 'print'])->name('inquery_pemindahanbarangjadi.print');

    Route::resource('laporan_returbarangjadi', \App\Http\Controllers\Admin\Laporan_returbarangjadiController::class);
    Route::get('printReportretur', [\App\Http\Controllers\Admin\Laporan_returbarangjadiController::class, 'printReportretur']);

    Route::resource('laporan_pemindahanbarangjadi', \App\Http\Controllers\Admin\Laporan_pemindahanbarangjadiController::class);
    Route::get('printReportpemindahan/{id}', [\App\Http\Controllers\Admin\Laporan_pemindahanbarangjadiController::class, 'printReportpemindahan']);

    Route::resource('laporan_pemusnahanbarangjadi', \App\Http\Controllers\Admin\Laporan_pemusnahanbarangjadiController::class);
    Route::get('printReportpemusnahan', [\App\Http\Controllers\Admin\Laporan_pemusnahanbarangjadiController::class, 'printReportpemusnahan']);

    Route::resource('inquery_hasilpenjualan', \App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class, 'barangKeluar']);
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class, 'barangRetur']);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class, 'barangKeluar'])->name('barangKeluar');

    Route::resource('laporan_hasilpenjualan', \App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class);
    Route::get('barangMasukpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangMasukpesanan']);
    Route::get('barangMasukpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangMasukpesanan'])->name('barangMasukpesanan');
    Route::get('barangMasuksemua', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangMasuksemua'])->name('barangMasuksemua');
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluar']);
    Route::get('barangKeluarRinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluarRinci']);
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangRetur']);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluar'])->name('barangKeluar');
    Route::get('barangKeluarRinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluarRinci'])->name('barangKeluarRinci');
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangRetur'])->name('barangRetur');
    Route::get('/print-report', [Laporan_hasilpenjualanController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBm', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBm']);
    Route::get('printLaporanBmpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBmpesanan']);
    Route::get('printLaporanBmsemua', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBmsemua']);
    Route::get('printLaporanBK', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBK']);
    Route::get('printLaporanBKrinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBKrinci']);
    Route::get('printLaporanBR', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBR']);
    Route::get('printExcelBm', [Laporan_hasilpenjualanController::class, 'exportExcel'])->name('printExcelBm');
    Route::get('printExcelBk', [Laporan_hasilpenjualanController::class, 'exportExcelBK'])->name('printExcelBk');
    Route::get('printExcelBr', [Laporan_hasilpenjualanController::class, 'exportExcelBR'])->name('printExcelBr');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_hasilpenjualanController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');
    Route::get('barangOperantoko', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangOperantoko']);

    Route::resource('setoran_pelunasan', \App\Http\Controllers\Admin\Setoran_pelunasanController::class);
    Route::post('/get-penjualan1', [Setoran_pelunasanController::class, 'getdata1'])->name('getdata1');
    Route::get('/print-penjualan-kotor', [Setoran_pelunasanController::class, 'printPenjualanKotor'])->name('print.penjualan.kotor');
    Route::get('/print-diskon-penjualan', [Setoran_pelunasanController::class, 'printDiskonPenjualan'])->name('print.diskon.penjualan');
    Route::get('/print-deposit-keluar', [Setoran_pelunasanController::class, 'printDepositKeluar'])->name('print.deposit.keluar');
    Route::post('/setoran_pelunasan/store', [Setoran_pelunasanController::class, 'store'])->name('setoran_pelunasan.store');
    Route::post('/setoran_pelunasan/update-status', [Setoran_pelunasanController::class, 'updateStatus'])->name('setoran_pelunasan.update_status');
    Route::get('admin/setoran_pelunasan/{id}', [Setoran_pelunasanController::class, 'show'])->name('admin.setoran_pelunasan.show');
    Route::get('/setoran_pelunasan/{id}/print', [Setoran_pelunasanController::class, 'print'])->name('setoran_pelunasan.print');

    Route::resource('inquery_setoranpelunasan', \App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class);
    Route::get('inquery_setoranpelunasan/unpost_setorantunai/{id}', [\App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class, 'unpost_setorantunai']);
    Route::get('inquery_setoranpelunasan/posting_setorantunai/{id}', [\App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class, 'posting_setorantunai']);
    Route::get('inquery_setoranpelunasan/approve_setorantunai/{id}', [\App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class, 'approve_setorantunai']);
    Route::get('/admin/inquery_setoranpelunasan/{id}/print', [Inquery_setoranpelunasanController::class, 'print'])->name('inquery_setoranpelunasan.print');
    Route::get('/inquery_setoranpelunasan/edit/{id}', [Inquery_setoranpelunasanController::class, 'edit'])->name('setoranpelunasan.edit');
    Route::post('/inquery_setoranpelunasan/update-status', [Inquery_setoranpelunasanController::class, 'updateStatus'])->name('inquery_setoranpelunasan.update_status');

    Route::resource('grafik_penjualan', \App\Http\Controllers\Admin\Grafik_penjualanController::class);

    Route::resource('inquery_perubahanharga', \App\Http\Controllers\Admin\Inquery_perubahanhargaController::class);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Admin\Metode_pembayaranController::class);
});


Route::middleware('toko_banjaran')->prefix('toko_banjaran')->group(function () {
    // Route::get('/', [\App\Http\Controllers\Toko_slawi\DashboardController::class, 'index']);
    // Route::resource('karyawan', \App\Http\Controllers\Toko_slawi\KaryawanController::class);
    Route::get('/', [\App\Http\Controllers\Toko_banjaran\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_banjaran\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_banjaran\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');

    Route::resource('produk', \App\Http\Controllers\Toko_banjaran\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController::class);
    Route::get('/toko_banjaran/pemesanan_produk/cetak/{id}', [PemesananprodukbanjaranController::class, 'cetak'])->name('toko_banjaran.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananprodukbanjaranController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukbanjaranController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_banjaran/pemesanan_produk/update/{id}', [PemesananprodukbanjaranController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_banjaran/pemesanan_produk/cetak-pdf{id}', [PemesananprodukbanjaranController::class, 'cetakPdf'])->name('toko_banjaran.pemesanan_produk.cetak-pdf');
    Route::delete('toko_banjaran/pemesanan_produk/{id}', [PemesananprodukbanjaranController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_banjaran/pemesanan_produk/{id}/cetak', [PemesananprodukbanjaranController::class, 'cetak'])->name('toko_banjaran.pemesanan_produk.cetak');
    Route::get('/toko_banjaran/pemesanan-produk/create', [PemesananprodukbanjaranController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class);
    Route::get('/toko_banjaran/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_banjaran.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukbnjr', \App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController::class);
    Route::get('print_pemesananbnjr', [\App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController::class, 'print_pemesanan']);
    Route::get('printReportpemesananbnjr', [Laporan_pemesananprodukController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalbnjr', [\App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalbnjr', [Laporan_pemesananprodukbanjaranController::class, 'printReportpemesananglobalbnjr'])->name('printReportpemesananglobalbnjr');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController::class);
    Route::get('/toko_banjaran/penjualan_produk/cetak/{id}', [PenjualanprodukbanjaranController::class, 'cetak'])->name('toko_banjaran.penjualan_produk.cetak');
    Route::get('/toko_banjaran/penjualan_produk/cetak-pdf{id}', [PenjualanprodukbanjaranController::class, 'cetakPdf'])->name('toko_banjaran.penjualan_produk.cetak-pdf');
    Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('toko_banjaran/penjualan_produk/create', [PenjualanprodukbanjaranController::class, 'create'])->name('toko_banjaran.penjualan_produk.create');
    // Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukbanjaranController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukbanjaranController::class, 'fetchDataByKode'])->name('toko_banjaran.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukbanjaranController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController::class, 'metode']);
    Route::post('toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukbanjaranController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukbanjaranController::class, 'fetchProductData'])->name('toko_banjaran.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukbanjaranController::class, 'getProduk']);
    Route::get('/get-produks', [PenjualanprodukbanjaranController::class, 'getProduks']);
    Route::get('/produk/search', [PenjualanprodukbanjaranController::class, 'search'])->name('produk.search');
    Route::get('/cari-produk', [PenjualanprodukbanjaranController::class, 'cariProduk1'])->name('cari.produk');
    Route::get('/search-product', [PenjualanprodukbanjaranController::class, 'searchProduct']);





    Route::resource('pelunasan_pemesanan', \App\Http\Controllers\Toko_banjaran\PelunasanpemesananController::class);
    Route::get('/toko_banjaran/pelunasan_pemesanan/cetak-pdf{id}', [PelunasanpemesananController::class, 'cetakPdf'])->name('toko_banjaran.pelunasan_pemesanan.cetak-pdf');
    Route::get('/pelunasan-pemesanan/cetak/{id}', [PelunasanpemesananController::class, 'cetak'])->name('toko_banjaran.pelunasan_pemesanan.cetak');

    Route::resource('inquery_penjualanprodukbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class);
    Route::get('/toko_banjaran/inquery_penjualanprodukbanajran', [Inquery_penjualanprodukbanjaranController::class, 'index'])->name('toko_banjaran.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukbanjaran/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukbanjaran/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class, 'posting_penjualanproduk']);
    Route::get('/toko_banjaran/inquery_penjualanprodukbanjaran/cetak-pdf/{id}', [Inquery_penjualanprodukbanjaranController::class, 'cetakPdf'])->name('toko_banjaran.inquery_penjualanprodukbanjaran.cetak-pdf');
    Route::post('/toko_banjaran/inquery_penjualanprodukbanjaran/{id}/update', [Inquery_penjualanprodukbanjaranController::class, 'update'])->name('inquery_penjualanprodukbanjaran.update');
    Route::get('metodebayarbanjaran/metode/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class, 'metode']);
    Route::delete('/toko_banjaran/inquery_penjualanproduk/{id}', [Inquery_penjualanprodukbanjaranController::class, 'destroy'])
        ->name('toko_banjaran.inquery_penjualanproduk.destroy');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController::class);
    Route::post('toko_banjaran/permintaan_produk', [PermintaanprodukbanjaranController::class, 'store']);
    Route::get('toko_banjaran/permintaan_produk/{id}', [PermintaanprodukbanjaranController::class, 'show'])->name('permintaan_produk.show');
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukbanjaranController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController::class, 'posting_permintaanproduk']);
    // Route::delete('Toko_banjaran/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('Toko_banjaran.permintaan_produk.destroy');
    Route::post('toko_banjaran/permintaan/import', [PermintaanprodukbanjaranController::class, 'import'])->name('permintaan.import');

    Route::resource('inquery_pelunasanbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_pelunasanbanjaranController::class);


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_banjaran\Inquery_permintaanprodukController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_banjaran\Metode_pembayaranController::class);


    Route::resource('stok_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Stok_tokobanjaranController::class);
    Route::delete('/toko_banjaran/stok_tokobanjaran/deleteAll', [Stok_tokobanjaranController::class, 'deleteAll'])->name('stok_tokobanjaran.deleteAll');
    Route::post('toko_banjaran/stok_tokobanjaran/import', [Stok_tokobanjaranController::class, 'import'])->name('stok_tokobanjaran.import');
    Route::get('toko_banjaran/stok_tokobanjaran/{id}/edit', [Stok_tokobanjaranController::class, 'edit'])->name('stok_tokobanjaran.edit');

    Route::put('toko_banjaran/stok_tokobanjaran/{id}', [Stok_tokobanjaranController::class, 'update'])->name('stok_tokobanjaran.update');

    Route::resource('stokpesanan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Stokpesanan_tokobanjaranController::class);


    Route::resource('pengiriman_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class);
    Route::get('pengiriman_tokobanjaran/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokobanjaran/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokobanjaran/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokobanjaran/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokobanjaran/{id}/print', [Pengiriman_tokobanjaranController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/printpemesanan/{id}', [Pengiriman_tokobanjaranController::class, 'printpemesanan'])->name('pengiriman_tokobanjaran.printpemesanan');
    Route::get('toko_banjaran/pengiriman_tokobanjaran/index', [Pengiriman_tokobanjaranController::class, 'index'])->name('toko_banjaran.pengiriman_tokobanjaran.index');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/pengiriman_pemesanan', [Pengiriman_tokobanjaranController::class, 'pengiriman_pemesanan'])->name('toko_banjaran.pengiriman_tokobanjaran.pengiriman_pemesanan');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/showpemesanan/{id}', [Pengiriman_tokobanjaranController::class, 'showpemesanan'])->name('toko_banjaran.pengiriman_tokobanjaran.showpemesanan');

    Route::resource('pengirimanpemesanan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pengirimanpemesanan_tokobanjaranController::class);
    Route::get('/pengirimanpemesanan_tokobanjaran/print/{id}', [Pengirimanpemesanan_tokobanjaranController::class, 'print'])->name('pengirimanpemesanan_tokobanjaran.print');


    Route::resource('retur_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Retur_tokobanjaranController::class);

    Route::resource('inquery_returbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController::class);
    Route::get('inquery_returbanjaran/unpost_retur/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController::class, 'unpost_retur']);
    Route::get('inquery_returbanjaran/posting_retur/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController::class, 'posting_retur']);
    Route::get('/inquery_returbanjaran/{id}/print', [Inquery_returbanjaranController::class, 'print'])->name('inquery_returbanjaran.print');


    Route::resource('pemindahan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pemindahan_tokobanjaranController::class);

    Route::resource('inquery_pemindahanbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController::class);
    Route::get('inquery_pemindahanbanjaran/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahanbanjaran/{id}/print', [Inquery_pemindahanbanjaranController::class, 'print'])->name('inquery_pemindahanbanjaran.print');

    Route::resource('laporan_pemindahanbanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_pemindahanbanjaranController::class);
    Route::get('printReportpemindahanBnj/{id}', [\App\Http\Controllers\Toko_banjaran\Laporan_pemindahanbanjaranController::class, 'printReportpemindahanBnj']);

    Route::resource('laporan_stoktokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class);
    Route::get('printstoktokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'printReport']);
    Route::get('printexcelstoktokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'exportExcel']);
    Route::get('stoktokopesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'stoktokopesananbanjaran']);
    Route::get('printstoktokopesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'printReportstokpesananbanjaran']);
    Route::get('semuastoktokobanjaran', [Laporan_stoktokobanjaranController::class, 'semuaStokTokoBanjaran'])->name('laporan.semuaStokTokoBanjaran');
    Route::get('printsemuastoktokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'printReportsemuastokbanjaran']);
    Route::get('printexcelsemuabanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'exportExcelsemua']);
    Route::get('printexcelstokpesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'exportExcelpesanan']);



    Route::resource('laporan_pengirimantokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_pengirimantokobanjaranController::class);
    Route::get('printpengirimantokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_pengirimantokobanjaranController::class, 'printReport']);

    Route::resource('setoran_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Setoran_tokobanjaranController::class);
    Route::post('toko_banjaran/setoran_tokobanjaran', [Setoran_tokobanjaranController::class, 'store'])->name('setoran.store');
    Route::post('/get-penjualanbanjaran', [Setoran_tokobanjaranController::class, 'getdatabanjaran'])->name('getdatabanjaran');
    Route::get('/print-penjualantoko-kotorbnj', [Setoran_tokobanjaranController::class, 'printPenjualanKotorbnj'])->name('print.penjualantoko.kotorbnj');
    Route::get('/print-fakturpenjualantokobnj', [Setoran_tokobanjaranController::class, 'printFakturpenjualanbnj'])->name('print.fakturpenjualantokobnj');
    Route::get('/print-fakturpenjualanmesinedcbnj', [Setoran_tokobanjaranController::class, 'printFakturpenjualanMesinedcbnj'])->name('print.fakturpenjualanmesinedcbnj');
    Route::get('/print-fakturpemesananmesinedcbnj', [Setoran_tokobanjaranController::class, 'printFakturpemesananMesinedcbnj'])->name('print.fakturpemesananmesinedcbnj');
    Route::get('/print-fakturpenjualanqrisbnj', [Setoran_tokobanjaranController::class, 'printFakturpenjualanQrisbnj'])->name('print.fakturpenjualanqrisbnj');
    Route::get('/print-fakturpemesananqrisbnj', [Setoran_tokobanjaranController::class, 'printFakturpemesananQrisbnj'])->name('print.fakturpemesananqrisbnj');
    Route::get('/print-fakturpenjualantransferbnj', [Setoran_tokobanjaranController::class, 'printFakturpenjualanTransferbnj'])->name('print.fakturpenjualantransferbnj');
    Route::get('/print-fakturpemesanantransferbnj', [Setoran_tokobanjaranController::class, 'printFakturpemesananTransferbnj'])->name('print.fakturpemesanantransferbnj');
    Route::get('/print-fakturpenjualangobizbnj', [Setoran_tokobanjaranController::class, 'printFakturpenjualanGobizbnj'])->name('print.fakturpenjualangobizbnj');
    Route::get('/print-fakturpemesanangobizbnj', [Setoran_tokobanjaranController::class, 'printFakturpemesananGobizbnj'])->name('print.fakturpemesanangobizbnj');
    Route::get('/print-fakturdepositmasuktokobnj', [Setoran_tokobanjaranController::class, 'printFakturdepositMasukbnj'])->name('print.fakturdepositmasuktokobnj');
    Route::get('/print-fakturdepositkeluartokobnj', [Setoran_tokobanjaranController::class, 'printFakturdepositKeluarbnj'])->name('print.fakturdepositkeluartokobnj');
    Route::get('/print-penjualantoko-diskonbnj', [Setoran_tokobanjaranController::class, 'printPenjualanDiskonbnj'])->name('print.penjualantoko.diskonbnj');
    Route::get('/print-penjualantoko-bersihbnj', [Setoran_tokobanjaranController::class, 'printPenjualanBersihbnj'])->name('print.penjualantoko.bersihbnj');
    Route::get('penjualanprodukbnj/detail/{id}', [Setoran_tokobanjaranController::class, 'show'])->name('penjualanprodukbnj.detail');
    Route::get('penjualanprodukbnj/detaildepositkeluar/{id}', [Setoran_tokobanjaranController::class, 'show2'])->name('penjualanprodukbnj.detaildepositkeluar');
    Route::get('pemesananprodukbnj/detailpemesanan/{id}', [Setoran_tokobanjaranController::class, 'show1'])->name('pemesananprodukbnj.detailpemesanan');

    Route::resource('laporan_setorantokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_setoranpenjualanController::class);
    Route::get('printReportsetoran', [Laporan_setoranpenjualanController::class, 'printReportsetoran'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_depositbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_depositbanjaranController::class);

    Route::resource('laporan_depositbanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'printReportsaldo']);

    Route::resource('inquery_setorantunaibanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_setorantunaibanjaranController::class);
    Route::get('/toko_banjaran/inquery_setorantunaibanjaran/{id}/print', [Inquery_setorantunaibanjaranController::class, 'print'])->name('inquery_setorantunaibanjaran.print');

    Route::resource('laporan_setorantunaibanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_setorantunaibanjaranController::class);

    Route::resource('laporan_returbanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_returbanjaranController::class);
    Route::get('printReportreturbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_returbanjaranController::class, 'printReportreturbanjaran']);

    Route::resource('laporan_historibanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class);
    Route::get('barangMasukpesananbanjaran', [Laporan_historibanjaranController::class, 'barangMasukpesananbanjaran'])->name('barangMasukpesananbanjaran');
    Route::get('barangMasuksemuabanjaran', [Laporan_historibanjaranController::class, 'barangMasuksemuabanjaran'])->name('barangMasuksemuabanjaran');
    Route::get('printLaporanBmbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBmbanjaran']);
    Route::get('printLaporanBmpesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBmpesananbanjaran']);
    Route::get('printLaporanBmsemuabanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBmsemuabanjaran']);
    Route::get('printExcelBmbanjaran', [Laporan_historibanjaranController::class, 'exportExcel'])->name('printExcelBmbanjaran');
    Route::get('printExcelBmpesananbanjaran', [Laporan_historibanjaranController::class, 'exportExcelBMpesanan'])->name('printExcelBmpesananbanjaran');
    Route::get('printExcelBmsemuabanjaran', [Laporan_historibanjaranController::class, 'exportExcelBMsemua'])->name('printExcelBmsemuabanjaran');

    Route::get('barangKeluarbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangKeluarbanjaran'])->name('barangKeluarbanjaran');
    Route::get('barangKeluarRincibanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangKeluarRincibanjaran'])->name('barangKeluarRincibanjaran');
    Route::get('printLaporanBKbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBKbanjaran']);
    Route::get('printLaporanBKrincibanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBKrincibanjaran']);
    Route::get('printExcelBkbanjaran', [Laporan_historibanjaranController::class, 'exportExcelBK'])->name('printExcelBkbanjaran');

    Route::get('barangReturbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangReturbanjaran'])->name('barangReturbanjaran');
    Route::get('/print-report', [Laporan_historibanjaranController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBRbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBRbanjaran']);
    Route::get('printExcelBrbanjaran', [Laporan_historibanjaranController::class, 'exportExcelBR'])->name('printExcelBrbanjaran');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historibanjaranController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

    Route::get('barangOperbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangOperbanjaran'])->name('barangOperbanjaran');
    Route::get('barangOperanbanjaranMasuk', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangOperanbanjaranMasuk'])->name('barangOperanbanjaranMasuk');
    Route::get('printLaporanBObanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBObanjaran']);
    Route::get('printLaporanBObanjaranMasuk', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBObanjaranMasuk']);

    Route::get('inquery_pelunasanbanjaran/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pelunasanbanjaranController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_pelunasanbanjaran/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pelunasanbanjaranController::class, 'posting_penjualanproduk']);
    Route::delete('inquery_pelunasanbanjaran/deletedetail/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pelunasanbanjaranController::class, 'deletedetail']);
    Route::delete('inquery_pemesananbanjaran/deletedetail/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class, 'deletedetail']);
});

Route::middleware('toko_tegal')->prefix('toko_tegal')->group(function () {

    Route::get('/', [\App\Http\Controllers\Toko_tegal\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_tegal\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_tegal\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    Route::get('toko_tegal/pelanggan', [PelangganController::class, 'index'])->name('toko_tegal.pelanggan');

    Route::resource('produk', \App\Http\Controllers\Toko_tegal\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_tegal\PemesananproduktegalController::class);
    Route::get('/toko_tegal/pemesanan_produk/cetak/{id}', [PemesananproduktegalController::class, 'cetak'])->name('toko_tegal.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananproduktegalController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_tegal\PemesananproduktegalController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananproduktegalController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_tegal/pemesanan_produk/update/{id}', [PemesananproduktegalController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_tegal/pemesanan_produk/cetak-pdf{id}', [PemesananproduktegalController::class, 'cetakPdf'])->name('toko_tegal.pemesanan_produk.cetak-pdf');
    Route::delete('toko_tegal/pemesanan_produk/{id}', [PemesananproduktegalController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_tegal/pemesanan_produk/{id}/cetak', [PemesananproduktegalController::class, 'cetak'])->name('toko_tegal.pemesanan_produk.cetak');
    Route::get('/toko_tegal/pemesanan-produk/create', [PemesananproduktegalController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_tegal\Inquery_pemesananprodukController::class);
    Route::get('/toko_tegal/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_banjaran.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananproduktgl', \App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController::class);
    Route::get('print_pemesanantgl', [\App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController::class, 'print_pemesanan']);
    Route::get('printReportpemesanantgl', [Laporan_pemesananproduktegalController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobaltgl', [\App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobaltgl', [Laporan_pemesananproduktegalController::class, 'printReportpemesananglobaltgl'])->name('printReportpemesananglobaltgl');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_tegal\PenjualanproduktegalController::class);
    Route::get('/toko_tegal/penjualan_produk/cetak/{id}', [PenjualanproduktegalController::class, 'cetak'])->name('toko_tegal.penjualan_produk.cetak');
    Route::get('/toko_tegal/penjualan_produk/cetak-pdf{id}', [PenjualanproduktegalController::class, 'cetakPdf'])->name('toko_tegal.penjualan_produk.cetak-pdf');
    Route::get('/toko_tegal/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'pelunasan'])->name('toko_tegal.penjualan_produk.pelunasan');
    Route::get('toko_tegal/penjualan_produk/create', [PenjualanproduktegalController::class, 'create'])->name('toko_tegal.penjualan_produk.create');
    Route::get('/toko_tegal/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'pelunasan'])->name('toko_tegal.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanproduktegalController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanproduktegalController::class, 'fetchDataByKode'])->name('toko_tegal.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanproduktegalController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_tegal\PenjualanproduktegalController::class, 'metode']);
    Route::post('toko_tegal/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanproduktegalController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanproduktegalController::class, 'fetchProductData'])->name('toko_tegal.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanproduktegalController::class, 'searchProduct']);


    Route::resource('pelunasan_pemesananTgl', \App\Http\Controllers\Toko_tegal\PelunasanpemesananTglController::class);
    Route::get('/toko_tegal/pelunasan_pemesananTgl/cetak-pdf{id}', [PelunasanpemesananTglController::class, 'cetakPdf'])->name('toko_tegal.pelunasan_pemesananTgl.cetak-pdf');
    Route::get('/pelunasan-pemesananTgl/cetak/{id}', [PelunasanpemesananTglController::class, 'cetak'])->name('toko_tegal.pelunasan_pemesananTgl.cetak');
    Route::get('/pelunasan_pemesananTgl', [PelunasanPemesananTglController::class, 'index'])->name('toko_tegal.pelunasan_pemesananTgl.index');

    Route::resource('inquery_penjualanproduktegal', \App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class);
    Route::get('/toko_tegal/inquery_penjualanproduktegal', [Inquery_penjualanproduktegalController::class, 'index'])->name('toko_tegal.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanproduktegal/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanproduktegal/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class, 'posting_penjualanproduk']);
    Route::get('/toko_tegal/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanproduktegalController::class, 'cetakPdf'])->name('toko_tegal.inquery_penjualanproduk.cetak-pdf');
    Route::post('/toko_tegal/inquery_penjualanproduktegal/{id}/update', [Inquery_penjualanproduktegalController::class, 'update'])->name('inquery_penjualanproduktegal.update');
    Route::get('metodebayartegal/metode/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class, 'metode']);
    Route::delete('/toko_tegal/inquery_penjualanproduk/{id}', [Inquery_penjualanproduktegalController::class, 'destroy'])
        ->name('toko_tegal.inquery_penjualanproduk.destroy');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_tegal\PermintaanproduktegalController::class);
    Route::post('toko_tegal/permintaan_produk', [PermintaanproduktegalController::class, 'store']);
    // Route::get('toko_tegal/permintaan_produk', [PermintaanproduktegalController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanproduktegalController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_tegal\PermintaanproduktegalController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_tegal\PermintaanproduktegalController::class, 'posting_permintaanproduk']);
    Route::post('toko_tegal/permintaan/importtegal', [PermintaanproduktegalController::class, 'importtegal'])->name('permintaan.importtegal');


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_tegal\Inquery_permintaanprodukController::class);

    Route::resource('inquery_pelunasantegal', \App\Http\Controllers\Toko_tegal\Inquery_pelunasantegalController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_tegal\Metode_pembayaranController::class);


    Route::resource('stok_tokotegal', \App\Http\Controllers\Toko_tegal\Stok_tokotegalController::class);
    Route::delete('/toko_tegal/stok_tokotegal/deleteAll', [Stok_tokotegalController::class, 'deleteAll'])->name('stok_tokotegal.deleteAll');
    Route::post('toko_tegal/stok_tokotegal/import', [Stok_tokotegalController::class, 'import'])->name('stok_tokotegal.import');
    Route::post('toko_tegal/stok_tokotegal/updateBatch', [Stok_tokotegalController::class, 'updateBatch'])->name('stok_tokotegal.updateBatch');

    Route::resource('stokpesanan_tokotegal', \App\Http\Controllers\Toko_tegal\Stokpesanan_tokotegalController::class);
    Route::delete('/toko_tegal/stokpesanan_tokotegal/deleteAll', [Stokpesanan_tokotegalController::class, 'deleteAll'])->name('stokpesanan_tokotegal.deleteAll');

    Route::resource('pengiriman_tokotegal', \App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class);
    Route::get('pengiriman_tokotegal/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokotegal/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokotegal/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokotegal/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokotegal/{id}/print', [Pengiriman_tokotegalController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_tegal/pengiriman_tokotegal/printpemesanan/{id}', [Pengiriman_tokotegalController::class, 'printpemesanan'])->name('pengiriman_tokotegal.printpemesanan');
    Route::get('toko_tegal/pengiriman_tokotegal/index', [Pengiriman_tokotegalController::class, 'index'])->name('toko_tegal.pengiriman_tokotegal.index');
    Route::get('/toko_tegal/pengiriman_tokotegal/pengiriman_pemesanan', [Pengiriman_tokotegalController::class, 'pengiriman_pemesanan'])->name('toko_tegal.pengiriman_tokotegal.pengiriman_pemesanan');
    Route::get('/toko_tegal/pengiriman_tokotegal/showpemesanan/{id}', [Pengiriman_tokotegalController::class, 'showpemesanan'])->name('toko_tegal.pengiriman_tokotegal.showpemesanan');

    Route::resource('pengirimanpemesanan_tokotegal', \App\Http\Controllers\Toko_tegal\Pengirimanpemesanan_tokotegalController::class);
    Route::get('/pengirimanpemesanan_tokotegal/print/{id}', [Pengirimanpemesanan_tokotegal::class, 'print'])->name('pengirimanpemesanan_tokotegal.print');


    Route::resource('retur_tokotegal', \App\Http\Controllers\Toko_tegal\Retur_tokotegalController::class);

    Route::resource('inquery_returtegal', \App\Http\Controllers\Toko_tegal\Inquery_returtegalController::class);
    Route::get('inquery_returtegal/unpost_retur/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_returtegalController::class, 'unpost_retur']);
    Route::get('inquery_returtegal/posting_retur/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_returtegalController::class, 'posting_retur']);
    Route::get('/inquery_returtegal/{id}/print', [Inquery_returtegalController::class, 'print'])->name('inquery_returtegal.print');


    Route::resource('pemindahan_tokotegal', \App\Http\Controllers\Toko_tegal\Pemindahan_tokotegalController::class);

    Route::resource('inquery_pemindahantegal', \App\Http\Controllers\Toko_tegal\Inquery_pemindahantegalController::class);
    Route::get('inquery_pemindahantegal/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pemindahantegalController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahantegal/{id}/print', [Inquery_pemindahantegalController::class, 'print'])->name('inquery_pemindahantegal.print');

    Route::resource('laporan_pemindahantegal', \App\Http\Controllers\Toko_tegal\Laporan_pemindahantegalController::class);
    Route::get('printReportpemindahanTgl/{id}', [\App\Http\Controllers\Toko_tegal\Laporan_pemindahantegalController::class, 'printReportpemindahanTgl']);

    Route::resource('laporan_stoktokotegal', \App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class);
    Route::get('printstoktokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'printReport']);
    Route::get('printexcelstoktokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'exportExcel']);
    Route::get('stoktokopesanantegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'stoktokopesanantegal']);
    Route::get('printstoktokopesanantegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'printReportstokpesanantegal']);
    Route::get('printexcelstokpesanantegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'exportExcelpesanan']);
    Route::get('semuastoktokotegal', [Laporan_stoktokotegalController::class, 'semuaStokTokoTegal'])->name('laporan.semuaStokTokoTegal');
    Route::get('printsemuastoktokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'printReportsemuastoktegal']);
    Route::get('printexcelsemuategal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'exportExcelsemua']);

    Route::resource('laporan_pengirimantokotegal', \App\Http\Controllers\Toko_tegal\Laporan_pengirimantokotegalController::class);
    Route::get('printpengirimantokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_pengirimantokotegalController::class, 'printReport']);
    Route::get('toko_tegal/laporan_pengirimantokotegal/index', [Laporan_pengirimantokotegalController::class, 'index'])->name('toko_tegal.laporan_pengirimantokotegal.index');

    Route::resource('laporan_pemesanantokotegal', \App\Http\Controllers\Toko_tegal\Laporan_pengirimanpemesanantokotegalController::class);
    Route::get('printpengirimanpemesananTgl', [\App\Http\Controllers\Toko_tegal\Laporan_pengirimanpemesanantokotegalController::class, 'printReport']);
    Route::get('toko_tegal/laporan_pemesanantokotegal/index', [Laporan_pengirimanpemesanantokotegalController::class, 'index'])->name('toko_tegal.laporan_pemesanantokotegal.index');

    Route::resource('setoran_tokotegal', \App\Http\Controllers\Toko_tegal\Setoran_tokotegalController::class);
    Route::post('toko_tegal/setoran_tokotegal', [Setoran_tokotegalController::class, 'store'])->name('setoran.store');
    Route::post('/get-penjualantegal', [Setoran_tokotegalController::class, 'getdatategal'])->name('getdatategal');
    Route::get('/print-penjualantoko-kotortgl', [Setoran_tokotegalController::class, 'printPenjualanKotortgl'])->name('print.penjualantoko.kotortgl');
    Route::get('/print-fakturpenjualantokotgl', [Setoran_tokotegalController::class, 'printFakturpenjualantgl'])->name('print.fakturpenjualantokotgl');
    Route::get('/print-fakturpenjualanmesinedctgl', [Setoran_tokotegalController::class, 'printFakturpenjualanMesinedctgl'])->name('print.fakturpenjualanmesinedctgl');
    Route::get('/print-fakturpemesananmesinedctgl', [Setoran_tokotegalController::class, 'printFakturpemesananMesinedctgl'])->name('print.fakturpemesananmesinedctgl');
    Route::get('/print-fakturpenjualanqristgl', [Setoran_tokotegalController::class, 'printFakturpenjualanQristgl'])->name('print.fakturpenjualanqristgl');
    Route::get('/print-fakturpemesananqristgl', [Setoran_tokotegalController::class, 'printFakturpemesananQristgl'])->name('print.fakturpemesananqristgl');
    Route::get('/print-fakturpenjualantransfertgl', [Setoran_tokotegalController::class, 'printFakturpenjualanTransfertgl'])->name('print.fakturpenjualantransfertgl');
    Route::get('/print-fakturpemesanantransfertgl', [Setoran_tokotegalController::class, 'printFakturpemesananTransfertgl'])->name('print.fakturpemesanantransfertgl');
    Route::get('/print-fakturpenjualangobiztgl', [Setoran_tokotegalController::class, 'printFakturpenjualanGobiztgl'])->name('print.fakturpenjualangobiztgl');
    Route::get('/print-fakturpemesanangobiztgl', [Setoran_tokotegalController::class, 'printFakturpemesananGobiztgl'])->name('print.fakturpemesanangobiztgl');
    Route::get('/print-fakturdepositmasuktokotgl', [Setoran_tokotegalController::class, 'printFakturdepositMasuktgl'])->name('print.fakturdepositmasuktokotgl');
    Route::get('/print-fakturdepositkeluartokotgl', [Setoran_tokotegalController::class, 'printFakturdepositKeluartgl'])->name('print.fakturdepositkeluartokotgl');
    Route::get('/print-penjualantoko-diskontgl', [Setoran_tokotegalController::class, 'printPenjualanDiskontgl'])->name('print.penjualantoko.diskontgl');
    Route::get('/print-penjualantoko-bersihtgl', [Setoran_tokotegalController::class, 'printPenjualanBersihtgl'])->name('print.penjualantoko.bersihtgl');
    Route::get('penjualanproduktgl/detail/{id}', [Setoran_tokotegalController::class, 'show'])->name('penjualanproduktgl.detail');
    Route::get('penjualanproduktgl/detaildepositkeluar/{id}', [Setoran_tokotegalController::class, 'show2'])->name('penjualanproduktgl.detaildepositkeluar');
    Route::get('pemesananproduktgl/detailpemesanan/{id}', [Setoran_tokotegalController::class, 'show1'])->name('pemesananproduktgl.detailpemesanan');


    Route::resource('laporan_setorantokotegal', \App\Http\Controllers\Toko_tegal\Laporan_setoranpenjualantglController::class);
    Route::get('printReportsetorantgl', [Laporan_setoranpenjualantglController::class, 'printReportsetorantgl'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_deposittegal', \App\Http\Controllers\Toko_tegal\Inquery_deposittegalController::class);

    Route::resource('laporan_deposittegal', \App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'printReportsaldo']);

    Route::resource('inquery_setorantunaitegal', \App\Http\Controllers\Toko_tegal\Inquery_setorantunaitegalController::class);
    Route::get('/toko_tegal/inquery_setorantunaitegal/{id}/print', [Inquery_setorantunaitegalController::class, 'print'])->name('inquery_setorantunaitegal.print');

    Route::resource('laporan_setorantunaitegal', \App\Http\Controllers\Toko_tegal\Laporan_setorantunaitegalController::class);

    Route::resource('laporan_returtegal', \App\Http\Controllers\Toko_tegal\Laporan_returtegalController::class);
    Route::get('printReportreturtegal', [\App\Http\Controllers\Toko_tegal\Laporan_returtegalController::class, 'printReportreturtegal']);

    Route::resource('laporan_historitegal', \App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class);
    Route::get('barangMasukpesanantegal', [Laporan_historitegalController::class, 'barangMasukpesanantegal'])->name('barangMasukpesanantegal');
    Route::get('barangMasuksemuategal', [Laporan_historitegalController::class, 'barangMasuksemuategal'])->name('barangMasuksemuategal');
    Route::get('printLaporanBmtegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBmtegal']);
    Route::get('printLaporanBmpesanantegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBmpesanantegal']);
    Route::get('printLaporanBmsemuategal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBmsemuategal']);
    Route::get('printExcelBmtegal', [Laporan_historitegalController::class, 'exportExcel'])->name('printExcelBmtegal');
    Route::get('printExcelBmpesanantegal', [Laporan_historitegalController::class, 'exportExcelBMpesanan'])->name('printExcelBmpesanantegal');
    Route::get('printExcelBmsemuategal', [Laporan_historitegalController::class, 'exportExcelBMsemua'])->name('printExcelBmsemuategal');

    Route::get('barangKeluartegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'barangKeluartegal'])->name('barangKeluartegal');
    Route::get('barangKeluarRincitegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'barangKeluarRincitegal'])->name('barangKeluarRincitegal');
    Route::get('printLaporanBKtegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBKtegal']);
    Route::get('printLaporanBKrincitegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBKrincitegal']);
    Route::get('printExcelBktegal', [Laporan_historitegalController::class, 'exportExcelBK'])->name('printExcelBktegal');

    Route::get('barangReturtegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'barangReturtegal'])->name('barangReturtegal');
    Route::get('/print-report', [Laporan_historitegalController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBRtegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBRtegal']);
    Route::get('printExcelBrtegal', [Laporan_historitegalController::class, 'exportExcelBR'])->name('printExcelBrtegal');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historitegalController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

    Route::get('barangOpertegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'barangOpertegal'])->name('barangOpertegal');
    Route::get('barangOperantegalMasuk', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'barangOperantegalMasuk'])->name('barangOperantegalMasuk');
    Route::get('printLaporanBOtegal', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBOtegal']);
    Route::get('printLaporanBOtegalMasuk', [\App\Http\Controllers\Toko_tegal\Laporan_historitegalController::class, 'printLaporanBOtegalMasuk']);



    Route::get('inquery_pelunasantegal/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pelunasantegalController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_pelunasantegal/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pelunasantegalController::class, 'posting_penjualanproduk']);
    Route::delete('inquery_pelunasantegal/deletedetail/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pelunasantegalController::class, 'deletedetail']);
    Route::delete('inquery_pemesanantegal/deletedetail/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pelunasantegalController::class, 'deletedetail']);
});

Route::middleware('toko_slawi')->prefix('toko_slawi')->group(function () {

    Route::get('/', [\App\Http\Controllers\Toko_slawi\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_slawi\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_slawi\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    Route::get('toko_slawi/pelanggan', [PelangganController::class, 'index'])->name('toko_slawi.pelanggan');

    Route::resource('produk', \App\Http\Controllers\Toko_slawi\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_slawi\PemesananprodukslawiController::class);
    Route::get('/toko_slawi/pemesanan_produk/cetak/{id}', [PemesananprodukslawiController::class, 'cetak'])->name('toko_slawi.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananprodukslawiController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_slawi\PemesananprodukslawiController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukslawiController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_slawi/pemesanan_produk/update/{id}', [PemesananprodukslawiController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_slawi/pemesanan_produk/cetak-pdf{id}', [PemesananprodukslawiController::class, 'cetakPdf'])->name('toko_slawi.pemesanan_produk.cetak-pdf');
    Route::delete('toko_slawi/pemesanan_produk/{id}', [PemesananprodukslawiController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_slawi/pemesanan_produk/{id}/cetak', [PemesananprodukslawiController::class, 'cetak'])->name('toko_slawi.pemesanan_produk.cetak');
    Route::get('/toko_slawi/pemesanan-produk/create', [PemesananprodukslawiController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_slawi\Inquery_pemesananprodukController::class);
    Route::get('/toko_slawi/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_slawi.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukslw', \App\Http\Controllers\Toko_slawi\Laporan_pemesananprodukslawiController::class);
    Route::get('print_pemesanaslwl', [\App\Http\Controllers\Toko_slawi\Laporan_pemesananprodukslawiController::class, 'print_pemesanan']);
    Route::get('printReportpemesananslw', [Laporan_pemesananprodukslawiController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalslw', [\App\Http\Controllers\Toko_slawi\Laporan_pemesananprodukslawiController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalslw', [Laporan_pemesananprodukslawiController::class, 'printReportpemesananglobalslw'])->name('printReportpemesananglobalslw');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_slawi\PenjualanprodukslawiController::class);
    Route::get('/toko_slawi/penjualan_produk/cetak/{id}', [PenjualanprodukslawiController::class, 'cetak'])->name('toko_slawi.penjualan_produk.cetak');
    Route::get('/toko_slawi/penjualan_produk/cetak-pdf{id}', [PenjualanprodukslawiController::class, 'cetakPdf'])->name('toko_slawi.penjualan_produk.cetak-pdf');
    Route::get('/toko_slawi/penjualan_produk/pelunasan', [PenjualanprodukslawiController::class, 'pelunasan'])->name('toko_slawi.penjualan_produk.pelunasan');
    Route::get('toko_slawi/penjualan_produk/create', [PenjualanprodukslawiController::class, 'create'])->name('toko_slawi.penjualan_produk.create');
    Route::get('/toko_slawi/penjualan_produk/pelunasan', [PenjualanprodukslawiController::class, 'pelunasan'])->name('toko_slawi.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukslawiController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukslawiController::class, 'fetchDataByKode'])->name('toko_slawi.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukslawiController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_slawi\PenjualanprodukslawiController::class, 'metode']);
    Route::post('toko_slawi/penjualan_produk/pelunasan', [PenjualanprodukslawiController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukslawiController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukslawiController::class, 'fetchProductData'])->name('toko_slawi.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukslawiController::class, 'searchProduct']);


    Route::resource('pelunasan_pemesananSlw', \App\Http\Controllers\Toko_slawi\PelunasanpemesananSlwController::class);
    Route::get('/toko_slawi/pelunasan_pemesananSlw/cetak-pdf{id}', [PelunasanpemesananSlwController::class, 'cetakPdf'])->name('toko_slawi.pelunasan_pemesananSlw.cetak-pdf');
    Route::get('/pelunasan-pemesananSlw/cetak/{id}', [PelunasanpemesananSlwController::class, 'cetak'])->name('toko_slawi.pelunasan_pemesananSlw.cetak');
    Route::get('/pelunasan_pemesananSlw', [PelunasanpemesananSlwController::class, 'index'])->name('toko_slawi.pelunasan_pemesananSlw.index');

    Route::resource('inquery_penjualanprodukslawi', \App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukslawiController::class);
    Route::get('/toko_slawi/inquery_penjualanprodukslawi', [Inquery_penjualanprodukslawiController::class, 'index'])->name('toko_slawi.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukslawi/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukslawiController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukslawi/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukslawiController::class, 'posting_penjualanproduk']);
    Route::get('/toko_slawi/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukslawiController::class, 'cetakPdf'])->name('toko_slawi.inquery_penjualanproduk.cetak-pdf');
    Route::post('/toko_slawi/inquery_penjualanprodukslawi/{id}/update', [Inquery_penjualanprodukslawiController::class, 'update'])->name('inquery_penjualanprodukslawi.update');
    Route::get('metodebayarslawi/metode/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukslawiController::class, 'metode']);
    Route::delete('/toko_slawi/inquery_penjualanproduk/{id}', [Inquery_penjualanprodukslawiController::class, 'destroy'])
        ->name('toko_slawi.inquery_penjualanproduk.destroy');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_slawi\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_slawi\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_slawi\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_slawi\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_slawi\PermintaanprodukslawiController::class);
    Route::post('toko_slawi/permintaan_produk', [PermintaanprodukslawiController::class, 'store']);
    // Route::get('toko_slawi/permintaan_produk', [PermintaanprodukslawiController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukslawiController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_slawi\PermintaanprodukslawiController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_slawi\PermintaanprodukslawiController::class, 'posting_permintaanproduk']);
    Route::post('toko_slawi/permintaan/importslawi', [PermintaanprodukslawiController::class, 'importslawi'])->name('permintaan.importslawi');


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_slawi\Inquery_permintaanprodukController::class);

    Route::resource('inquery_pelunasanslawi', \App\Http\Controllers\Toko_slawi\Inquery_pelunasanslawiController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_slawi\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_slawi\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_slawi\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_slawi\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_slawi\Metode_pembayaranController::class);


    // Route::resource('stok_tokoslawi', \App\Http\Controllers\Toko_slawi\Stok_tokoslawiController::class);
    // Route::delete('/toko_slawi/stok_tokoslawi/deleteAll', [Stok_tokoslawiController::class, 'deleteAll'])->name('stok_tokoslawi.deleteAll');
    // Route::post('toko_slawi/stok_tokoslawi/import', [Stok_tokoslawiController::class, 'import'])->name('stok_tokoslawi.import');
    // Route::delete('/toko_slawi/stok_tokoslawi/deleteAll', [Toko_slawiStok_tokoslawiController::class, 'deleteAll'])->name('stok_tokobanjaran.deleteAll');

    Route::resource('stok_tokoslawi', \App\Http\Controllers\Toko_slawi\Stok_tokoslawiController::class);
    Route::delete('/toko_slawi/stok_tokoslawi/deleteAll', [Toko_slawiStok_tokoslawiController::class, 'deleteAll'])->name('stok_tokoslawi.deleteAll');
    Route::post('toko_slawi/stok_tokoslawi/import', [Toko_slawiStok_tokoslawiController::class, 'import'])->name('stok_tokoslawi.import');

    Route::resource('stokpesanan_tokoslawi', \App\Http\Controllers\Toko_slawi\Stokpesanan_tokoslawiController::class);
    Route::delete('/toko_slawi/stokpesanan_tokoslawi/deleteAll', [Stokpesanan_tokoslawiController::class, 'deleteAll'])->name('stokpesanan_tokoslawi.deleteAll');

    Route::resource('pengiriman_tokoslawi', \App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController::class);
    Route::get('pengiriman_tokoslawi/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokoslawi/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokoslawi/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokoslawi/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokoslawi/{id}/print', [Pengiriman_tokoslawiController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_slawi/pengiriman_tokoslawi/printpemesanan/{id}', [Pengiriman_tokoslawiController::class, 'printpemesanan'])->name('pengiriman_tokoslawi.printpemesanan');
    Route::get('toko_slawi/pengiriman_tokoslawi/index', [Pengiriman_tokoslawiController::class, 'index'])->name('toko_slawi.pengiriman_tokoslawi.index');
    Route::get('/toko_slawi/pengiriman_tokoslawi/pengiriman_pemesanan', [Pengiriman_tokoslawiController::class, 'pengiriman_pemesanan'])->name('toko_slawi.pengiriman_tokoslawi.pengiriman_pemesanan');
    Route::get('/toko_slawi/pengiriman_tokoslawi/showpemesanan/{id}', [Pengiriman_tokoslawiController::class, 'showpemesanan'])->name('toko_slawi.pengiriman_tokoslawi.showpemesanan');

    Route::resource('pengirimanpemesanan_tokoslawi', \App\Http\Controllers\Toko_slawi\Pengirimanpemesanan_tokoslawiController::class);
    Route::get('/pengirimanpemesanan_tokoslawi/print/{id}', [Pengirimanpemesanan_tokoslawiController::class, 'print'])->name('pengirimanpemesanan_tokoslawi.print');


    Route::resource('retur_tokoslawi', \App\Http\Controllers\Toko_slawi\Retur_tokoslawiController::class);

    Route::resource('inquery_returslawi', \App\Http\Controllers\Toko_slawi\Inquery_returslawiController::class);
    Route::get('inquery_returslawi/unpost_retur/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_returslawiController::class, 'unpost_retur']);
    Route::get('inquery_returslawi/posting_retur/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_returslawiController::class, 'posting_retur']);
    Route::get('/inquery_returslawi/{id}/print', [Inquery_returslawiController::class, 'print'])->name('inquery_returslawi.print');


    Route::resource('pemindahan_tokoslawi', \App\Http\Controllers\Toko_slawi\Pemindahan_tokoslawiController::class);

    Route::resource('inquery_pemindahanslawi', \App\Http\Controllers\Toko_slawi\Inquery_pemindahanslawiController::class);
    Route::get('inquery_pemindahanslawi/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pemindahanslawiController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahanslawi/{id}/print', [Inquery_pemindahanslawiController::class, 'print'])->name('inquery_pemindahanslawi.print');

    Route::resource('laporan_pemindahanslawi', \App\Http\Controllers\Toko_slawi\Laporan_pemindahanslawiController::class);
    Route::get('printReportpemindahanSlw/{id}', [\App\Http\Controllers\Toko_slawi\Laporan_pemindahanslawiController::class, 'printReportpemindahanSlw']);

    Route::resource('laporan_stoktokoslawi', \App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class);
    Route::get('printstoktokoslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'printReport']);
    Route::get('printexcelstoktokoslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'exportExcel']);
    Route::get('stoktokopesananslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'stoktokopesananslawi']);
    Route::get('printstoktokopesananslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'printReportstokpesananslawi']);
    Route::get('semuastoktokoslawi', [Laporan_stoktokoslawiController::class, 'semuaStokTokoSlawi'])->name('laporan.semuaStokTokoSlawi');
    Route::get('printsemuastoktokoslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'printReportsemuastokslawi']);
    Route::get('printexcelsemuaslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'exportExcelsemua']);
    Route::get('printexcelstokpesananslawi', [\App\Http\Controllers\Toko_slawi\Laporan_stoktokoslawiController::class, 'exportExcelpesanan']);

    Route::resource('laporan_pengirimantokoslawi', \App\Http\Controllers\Toko_slawi\Laporan_pengirimantokoslawiController::class);
    Route::get('printpengirimantokoslawi', [\App\Http\Controllers\Toko_slawi\Laporan_pengirimantokoslawiController::class, 'printReport']);


    Route::resource('setoran_tokoslawi', \App\Http\Controllers\Toko_slawi\Setoran_tokoslawiController::class);
    Route::post('toko_slawi/setoran_tokoslawi', [Setoran_tokoslawiController::class, 'store'])->name('setoran.store');
    Route::post('/get-penjualanslawi', [Setoran_tokoslawiController::class, 'getdataslawi'])->name('getdataslawi');
    Route::get('/print-penjualantoko-kotorslw', [Setoran_tokoslawiController::class, 'printPenjualanKotorslw'])->name('print.penjualantoko.kotorslw');
    Route::get('/print-fakturpenjualantokoslw', [Setoran_tokoslawiController::class, 'printFakturpenjualanslw'])->name('print.fakturpenjualantokoslw');
    Route::get('/print-fakturpenjualanmesinedcslw', [Setoran_tokoslawiController::class, 'printFakturpenjualanMesinedcslw'])->name('print.fakturpenjualanmesinedcslw');
    Route::get('/print-fakturpemesananmesinedcslw', [Setoran_tokoslawiController::class, 'printFakturpemesananMesinedcslw'])->name('print.fakturpemesananmesinedcslw');
    Route::get('/print-fakturpenjualanqrisslw', [Setoran_tokoslawiController::class, 'printFakturpenjualanQrisslw'])->name('print.fakturpenjualanqrisslw');
    Route::get('/print-fakturpemesananqrisslw', [Setoran_tokoslawiController::class, 'printFakturpemesananQrisslw'])->name('print.fakturpemesananqrisslw');
    Route::get('/print-fakturpenjualantransferslw', [Setoran_tokoslawiController::class, 'printFakturpenjualanTransferslw'])->name('print.fakturpenjualantransferslw');
    Route::get('/print-fakturpemesanantransferslw', [Setoran_tokoslawiController::class, 'printFakturpemesananTransferslw'])->name('print.fakturpemesanantransferslw');
    Route::get('/print-fakturpenjualangobizslw', [Setoran_tokoslawiController::class, 'printFakturpenjualanGobizslw'])->name('print.fakturpenjualangobizslw');
    Route::get('/print-fakturpemesanangobizslw', [Setoran_tokoslawiController::class, 'printFakturpemesananGobizslw'])->name('print.fakturpemesanangobizslw');
    Route::get('/print-fakturdepositmasuktokoslw', [Setoran_tokoslawiController::class, 'printFakturdepositMasukslw'])->name('print.fakturdepositmasuktokoslw');
    Route::get('/print-fakturdepositkeluartokoslw', [Setoran_tokoslawiController::class, 'printFakturdepositKeluarslw'])->name('print.fakturdepositkeluartokoslw');
    Route::get('/print-penjualantoko-diskonslw', [Setoran_tokoslawiController::class, 'printPenjualanDiskonslw'])->name('print.penjualantoko.diskonslw');
    Route::get('/print-penjualantoko-bersihslw', [Setoran_tokoslawiController::class, 'printPenjualanBersihslw'])->name('print.penjualantoko.bersihslw');
    Route::get('penjualanprodukslw/detail/{id}', [Setoran_tokoslawiController::class, 'show'])->name('penjualanprodukslw.detail');
    Route::get('penjualanprodukslw/detaildepositkeluar/{id}', [Setoran_tokoslawiController::class, 'show2'])->name('penjualanprodukslw.detaildepositkeluar');
    Route::get('pemesananprodukslw/detailpemesanan/{id}', [Setoran_tokoslawiController::class, 'show1'])->name('pemesananprodukslw.detailpemesanan');

    Route::resource('laporan_setorantokoslawi', \App\Http\Controllers\Toko_slawi\Laporan_setoranpenjualanslwController::class);
    Route::get('printReportsetoranslw', [Laporan_setoranpenjualanslwController::class, 'printReportsetoranslw'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_depositslawi', \App\Http\Controllers\Toko_slawi\Inquery_depositslawiController::class);

    Route::resource('laporan_depositslawi', \App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_slawi\Laporan_depositslawiController::class, 'printReportsaldo']);

    Route::resource('inquery_setorantunaislawi', \App\Http\Controllers\Toko_slawi\Inquery_setorantunaislawiController::class);
    Route::get('/toko_slawi/inquery_setorantunaislawi/{id}/print', [Inquery_setorantunaislawiController::class, 'print'])->name('inquery_setorantunaislawi.print');

    Route::resource('laporan_setorantunaislawi', \App\Http\Controllers\Toko_slawi\Laporan_setorantunaislawiController::class);

    Route::resource('laporan_returslawi', \App\Http\Controllers\Toko_slawi\Laporan_returslawiController::class);
    Route::get('printReportreturslawi', [\App\Http\Controllers\Toko_slawi\Laporan_returslawiController::class, 'printReportreturslawi']);

    Route::resource('laporan_historislawi', \App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class);
    Route::get('barangMasukpesananslawi', [Laporan_historislawiController::class, 'barangMasukpesananslawi'])->name('barangMasukpesananslawi');
    Route::get('barangMasuksemuaslawi', [Laporan_historislawiController::class, 'barangMasuksemuaslawi'])->name('barangMasuksemuaslawi');
    Route::get('printLaporanBmslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBmslawi']);
    Route::get('printLaporanBmpesananslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBmpesananslawi']);
    Route::get('printLaporanBmsemuaslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBmsemuaslawi']);
    Route::get('printExcelBmslawi', [Laporan_historislawiController::class, 'exportExcelslawi'])->name('printExcelBmslawi');
    Route::get('printExcelBmpesananslawi', [Laporan_historislawiController::class, 'exportExcelBMpesananslawi'])->name('printExcelBmpesananslawi');
    Route::get('printExcelBmsemuaslawi', [Laporan_historislawiController::class, 'exportExcelBMsemuaslawi'])->name('printExcelBmsemuaslawi');

    Route::get('barangKeluarslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'barangKeluarslawi'])->name('barangKeluarslawi');
    Route::get('barangKeluarRincislawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'barangKeluarRincislawi'])->name('barangKeluarRincislawi');
    Route::get('printLaporanBKslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBKslawi']);
    Route::get('printLaporanBKrincislawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBKrincislawi']);
    Route::get('printExcelBkslawi', [Laporan_historislawiController::class, 'exportExcelBK'])->name('printExcelBkslawi');

    // Route::get('barangReturbanjaran', [\App\Http\Controllers\Toko_slawi\Laporan_historibanjaranController::class, 'barangReturbanjaran']);
    Route::get('barangReturslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'barangReturslawi'])->name('barangReturslawi');
    Route::get('/print-report', [Laporan_historislawiController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBRslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBRslawi']);
    Route::get('printExcelBrslawi', [Laporan_historislawiController::class, 'exportExcelBR'])->name('printExcelBrslawi');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historislawiController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

    Route::get('barangOperslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'barangOperslawi'])->name('barangOperslawi');
    Route::get('barangOperanslawiMasuk', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'barangOperanslawiMasuk'])->name('barangOperanslawiMasuk');
    Route::get('printLaporanBOslawi', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBOslawi']);
    Route::get('printLaporanBOslawiMasuk', [\App\Http\Controllers\Toko_slawi\Laporan_historislawiController::class, 'printLaporanBOslawiMasuk']);


    Route::get('inquery_pelunasanslawi/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pelunasanslawiController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_pelunasanslawi/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pelunasanslawiController::class, 'posting_penjualanproduk']);
    Route::delete('inquery_pelunasanslawi/deletedetail/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pelunasanslawiController::class, 'deletedetail']);
    Route::delete('inquery_pemesananslawi/deletedetail/{id}', [\App\Http\Controllers\Toko_slawi\Inquery_pelunasanslawiController::class, 'deletedetail']);
});


Route::middleware('toko_pemalang')->prefix('toko_pemalang')->group(function () {
    Route::get('/', [\App\Http\Controllers\Toko_pemalang\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_pemalang\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_pemalang\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');

    Route::resource('produk', \App\Http\Controllers\Toko_pemalang\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_pemalang\PemesananprodukpemalangController::class);
    Route::get('/toko_pemalang/pemesanan_produk/cetak/{id}', [PemesananprodukpemalangController::class, 'cetak'])->name('toko_pemalang.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananprodukbanjaranController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_pemalang\PemesananprodukpemalangController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukpemalangController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_pemalang/pemesanan_produk/update/{id}', [PemesananprodukbanjaranController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_pemalang/pemesanan_produk/cetak-pdf{id}', [PemesananprodukbanjaranController::class, 'cetakPdf'])->name('toko_pemalang.pemesanan_produk.cetak-pdf');
    Route::delete('toko_pemalang/pemesanan_produk/{id}', [PemesananprodukbanjaranController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_pemalang/pemesanan_produk/{id}/cetak', [PemesananprodukbanjaranController::class, 'cetak'])->name('toko_pemalang.pemesanan_produk.cetak');
    Route::get('/toko_banjaran/pemesanan-produk/create', [PemesananprodukbanjaranController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_pemalang\Inquery_pemesananprodukController::class);
    Route::get('/toko_pemalang/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_pemalang.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukpml', \App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController::class);
    Route::get('print_pemesananpml', [\App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController::class, 'print_pemesanan']);
    Route::get('printReportpemesananpml', [Laporan_pemesananprodukpemalangController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalpml', [\App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalpml', [Laporan_pemesananprodukpemalangController::class, 'printReportpemesananglobalpml'])->name('printReportpemesananglobalpml');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_pemalang\PenjualanprodukpemalangController::class);
    Route::get('/toko_pemalang/penjualan_produk/cetak/{id}', [PenjualanprodukpemalangController::class, 'cetak'])->name('toko_pemalang.penjualan_produk.cetak');
    Route::get('/toko_pemalang/penjualan_produk/cetak-pdf{id}', [PenjualanprodukpemalangController::class, 'cetakPdf'])->name('toko_pemalang.penjualan_produk.cetak-pdf');
    Route::get('/toko_pemalang/penjualan_produk/pelunasan', [PenjualanprodukpemalangController::class, 'pelunasan'])->name('toko_pemalang.penjualan_produk.pelunasan');
    Route::get('toko_pemalang/penjualan_produk/create', [PenjualanprodukpemalangController::class, 'create'])->name('toko_pemalang.penjualan_produk.create');
    // Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukpemalangController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukpemalangController::class, 'fetchDataByKode'])->name('toko_pemalang.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukpemalangController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_pemalang\PenjualanprodukpemalangController::class, 'metode']);
    Route::post('toko_pemalang/penjualan_produk/pelunasan', [PenjualanprodukpemalangController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukpemalangController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukpemalangController::class, 'fetchProductData'])->name('toko_pemalang.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukpemalangController::class, 'getProduk']);
    Route::get('/get-produks', [PenjualanprodukpemalangController::class, 'getProduks']);
    Route::get('/produk/search', [PenjualanprodukpemalangController::class, 'search'])->name('produk.search');
    Route::get('/cari-produk', [PenjualanprodukpemalangController::class, 'cariProduk1'])->name('cari.produk');
    Route::get('/search-product', [PenjualanprodukpemalangController::class, 'searchProduct']);

    Route::resource('pelunasan_pemesananPml', \App\Http\Controllers\Toko_pemalang\PelunasanpemesananPmlController::class);
    Route::get('/toko_pemalang/pelunasan_pemesananPml/cetak-pdf{id}', [PelunasanpemesananPmlController::class, 'cetakPdf'])->name('toko_pemalang.pelunasan_pemesananPml.cetak-pdf');
    Route::get('/pelunasan-pemesananPml/cetak/{id}', [PelunasanpemesananPmlController::class, 'cetak'])->name('toko_pemalang.pelunasan_pemesananPml.cetak');

    Route::resource('inquery_penjualanprodukpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class);
    Route::get('/toko_pemalang/inquery_penjualanprodukpemalang', [Inquery_penjualanprodukpemalangController::class, 'index'])->name('toko_pemalang.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukpemalang/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukpemalang/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class, 'posting_penjualanproduk']);
    Route::get('/toko_pemalang/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukpemalangController::class, 'cetakPdf'])->name('toko_pemalang.inquery_penjualanproduk.cetak-pdf');
    Route::post('/toko_pemalang/inquery_penjualanprodukpemalang/{id}/update', [Inquery_penjualanprodukpemalangController::class, 'update'])->name('inquery_penjualanprodukpemalang.update');
    Route::get('metodebayarpemalang/metode/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class, 'metode']);
    Route::delete('/toko_pemalang/inquery_penjualanproduk/{id}', [Inquery_penjualanprodukpemalangController::class, 'destroy'])
        ->name('toko_pemalang.inquery_penjualanproduk.destroy');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController::class);
    Route::post('toko_pemalang/permintaan_produk', [PermintaanprodukpemalangController::class, 'store']);
    // Route::get('toko_pemalang/permintaan_produk', [PermintaanprodukpemalangController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukpemalangController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController::class, 'posting_permintaanproduk']);
    // Route::delete('Toko_pemalang/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('Toko_pemalang.permintaan_produk.destroy');
    Route::post('toko_pemalang/permintaan/importpemalang', [PermintaanprodukpemalangController::class, 'importpemalang'])->name('permintaan.importpemalang');

    Route::resource('inquery_pelunasanpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_pelunasanpemalangController::class);


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_pemalang\Inquery_permintaanprodukController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_pemalang\Metode_pembayaranController::class);


    Route::resource('stok_tokopemalang', \App\Http\Controllers\Toko_pemalang\Stok_tokopemalangController::class);
    Route::delete('/toko_pemalang/stok_tokopemalang/deleteAll', [Stok_tokopemalangController::class, 'deleteAll'])->name('stok_tokopemalang.deleteAll');
    Route::post('toko_pemalang/stok_tokopemalang/import', [Stok_tokopemalangController::class, 'import'])->name('stok_tokopemalang.import');
    Route::get('toko_pemalang/stok_tokopemalang/{id}/edit', [Stok_tokopemalangController::class, 'edit'])->name('stok_tokopemalang.edit');

    Route::resource('stokpesanan_tokopemalang', \App\Http\Controllers\Toko_pemalang\Stokpesanan_tokopemalangController::class);
    Route::delete('/toko_pemalang/stokpesanan_tokopemalang/deleteAll', [Stokpesanan_tokopemalangController::class, 'deleteAll'])->name('stokpesanan_tokopemalang.deleteAll');

    Route::resource('stokpesanan_tokopemalang', \App\Http\Controllers\Toko_pemalang\Stokpesanan_tokopemalangController::class);


    Route::resource('pengiriman_tokopemalang', \App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class);
    Route::get('pengiriman_tokopemalang/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokopemalang/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokopemalang/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokopemalang/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokopemalang/{id}/print', [Pengiriman_tokopemalangController::class, 'print'])->name('pengiriman_tokopemalang.print');
    Route::get('/toko_pemalang/pengiriman_tokopemalang/printpemesanan/{id}', [Pengiriman_tokopemalangController::class, 'printpemesanan'])->name('pengiriman_tokopemalang.printpemesanan');
    Route::get('toko_pemalang/pengiriman_tokopemalang/index', [Pengiriman_tokopemalangController::class, 'index'])->name('toko_pemalang.pengiriman_tokopemalang.index');
    Route::get('/toko_pemalang/pengiriman_tokopemalang/pengiriman_pemesanan', [Pengiriman_tokopemalangController::class, 'pengiriman_pemesanan'])->name('toko_pemalang.pengiriman_tokopemalang.pengiriman_pemesanan');
    Route::get('/toko_pemalang/pengiriman_tokopemalang/showpemesanan/{id}', [Pengiriman_tokopemalangController::class, 'showpemesanan'])->name('toko_pemalang.pengiriman_tokopemalang.showpemesanan');

    Route::resource('pengirimanpemesanan_tokopemalang', \App\Http\Controllers\Toko_pemalang\Pengirimanpemesanan_tokopemalangController::class);
    Route::get('/pengirimanpemesanan_tokopemalang/print/{id}', [Pengirimanpemesanan_tokopemalangController::class, 'print'])->name('pengirimanpemesanan_tokopemalang.print');


    Route::resource('retur_tokopemalang', \App\Http\Controllers\Toko_pemalang\Retur_tokopemalangController::class);

    Route::resource('inquery_returpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController::class);
    Route::get('inquery_returpemalang/unpost_retur/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController::class, 'unpost_retur']);
    Route::get('inquery_returpemalang/posting_retur/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController::class, 'posting_retur']);
    Route::get('/inquery_returpemalang/{id}/print', [Inquery_returpemalangController::class, 'print'])->name('inquery_returpemalang.print');


    Route::resource('pemindahan_tokopemalang', \App\Http\Controllers\Toko_pemalang\Pemindahan_tokopemalangController::class);

    Route::resource('inquery_pemindahanpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_pemindahanpemalangController::class);
    Route::get('inquery_pemindahanpemalang/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pemindahanpemalangController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahanpemalang/{id}/print', [Inquery_pemindahanpemalangController::class, 'print'])->name('inquery_pemindahanpemalang.print');

    Route::resource('laporan_pemindahanpemalang', \App\Http\Controllers\Toko_pemalang\Laporan_pemindahanpemalangController::class);
    Route::get('printReportpemindahanPml/{id}', [\App\Http\Controllers\Toko_pemalang\Laporan_pemindahanpemalangController::class, 'printReportpemindahanPml']);

    Route::resource('laporan_stoktokopemalang', \App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class);
    Route::get('printstoktokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'printReport']);
    Route::get('printexcelstoktokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'exportExcel']);
    Route::get('stoktokopesananpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'stoktokopesananpemalang']);
    Route::get('printstoktokopesananpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'printReportstokpesananpemalang']);
    Route::get('semuastoktokopemalang', [Laporan_stoktokopemalangController::class, 'semuaStokTokoPemalang'])->name('laporan.semuaStokTokopemalang');
    Route::get('printsemuastoktokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'printReportsemuastokpemalang']);
    Route::get('printexcelsemuapemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'exportExcelsemua']);
    Route::get('printexcelstokpesananpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'exportExcelpesanan']);


    Route::resource('laporan_pengirimantokopemalang', \App\Http\Controllers\Toko_pemalang\Laporan_pengirimantokopemalangController::class);
    Route::get('printpengirimantokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_pengirimantokopemalangController::class, 'printReport']);

    Route::resource('setoran_tokopemalang', \App\Http\Controllers\Toko_pemalang\Setoran_tokopemalangController::class);
    Route::post('toko_pemalang/setoran_tokopemalang', [Setoran_tokopemalangController::class, 'store'])->name('setoran.store');
    Route::post('/get-penjualanpemalang', [Setoran_tokopemalangController::class, 'getdatapemalang'])->name('getdatapemalang');
    Route::get('/print-penjualantoko-kotorpml', [Setoran_tokopemalangController::class, 'printPenjualanKotorpml'])->name('print.penjualantoko.kotorpml');
    Route::get('/print-fakturpenjualantokopml', [Setoran_tokopemalangController::class, 'printFakturpenjualanpml'])->name('print.fakturpenjualantokopml');
    Route::get('/print-fakturpenjualanmesinedcpml', [Setoran_tokopemalangController::class, 'printFakturpenjualanMesinedcpml'])->name('print.fakturpenjualanmesinedcpml');
    Route::get('/print-fakturpemesananmesinedcpml', [Setoran_tokopemalangController::class, 'printFakturpemesananMesinedcpml'])->name('print.fakturpemesananmesinedcpml');
    Route::get('/print-fakturpenjualanqrispml', [Setoran_tokopemalangController::class, 'printFakturpenjualanQrispml'])->name('print.fakturpenjualanqrispml');
    Route::get('/print-fakturpemesananqrispml', [Setoran_tokopemalangController::class, 'printFakturpemesananQrispml'])->name('print.fakturpemesananqrispml');
    Route::get('/print-fakturpenjualantransferpml', [Setoran_tokopemalangController::class, 'printFakturpenjualanTransferpml'])->name('print.fakturpenjualantransferpml');
    Route::get('/print-fakturpemesanantransfertgl', [Setoran_tokopemalangController::class, 'printFakturpemesananTransferpml'])->name('print.fakturpemesanantransferpml');
    Route::get('/print-fakturpenjualangobizpml', [Setoran_tokopemalangController::class, 'printFakturpenjualanGobizpml'])->name('print.fakturpenjualangobizpml');
    Route::get('/print-fakturpemesanangobizpml', [Setoran_tokopemalangController::class, 'printFakturpemesananGobizpml'])->name('print.fakturpemesanangobizpml');
    Route::get('/print-fakturdepositmasuktokopml', [Setoran_tokopemalangController::class, 'printFakturdepositMasukpml'])->name('print.fakturdepositmasuktokopml');
    Route::get('/print-fakturdepositkeluartokopml', [Setoran_tokopemalangController::class, 'printFakturdepositKeluarpml'])->name('print.fakturdepositkeluartokopml');
    Route::get('/print-penjualantoko-diskonpml', [Setoran_tokopemalangController::class, 'printPenjualanDiskonpml'])->name('print.penjualantoko.diskonpml');
    Route::get('/print-penjualantoko-bersihpml', [Setoran_tokopemalangController::class, 'printPenjualanBersihpml'])->name('print.penjualantoko.bersihpml');
    Route::get('penjualanprodukpml/detail/{id}', [Setoran_tokopemalangController::class, 'show'])->name('penjualanprodukpml.detail');
    Route::get('penjualanprodukpml/detaildepositkeluar/{id}', [Setoran_tokopemalangController::class, 'show2'])->name('penjualanprodukpml.detaildepositkeluar');
    Route::get('pemesananprodukpml/detailpemesanan/{id}', [Setoran_tokopemalangController::class, 'show1'])->name('pemesananprodukpml.detailpemesanan');


    Route::resource('laporan_setorantokopemalang', \App\Http\Controllers\Toko_pemalang\Laporan_setoranpenjualanpmlController::class);
    Route::get('printReportsetoranpml', [Laporan_setoranpenjualanpmlController::class, 'printReportsetoranpml'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_depositpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_depositpemalangController::class);

    Route::resource('laporan_depositpemalang', \App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'printReportsaldo']);

    Route::resource('inquery_setorantunaipemalang', \App\Http\Controllers\Toko_pemalang\Inquery_setorantunaipemalangController::class);
    Route::get('/toko_pemalang/inquery_setorantunaipemalang/{id}/print', [Inquery_setorantunaipemalangController::class, 'print'])->name('inquery_setorantunaipemalang.print');

    Route::resource('laporan_setorantunaipemalang', \App\Http\Controllers\Toko_pemalang\Laporan_setorantunaipemalangController::class);

    Route::resource('laporan_returpemalang', \App\Http\Controllers\Toko_pemalang\Laporan_returpemalangController::class);
    Route::get('printReportreturpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_returpemalangController::class, 'printReportreturpemalang']);

    Route::resource('laporan_historipemalang', \App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class);
    Route::get('barangMasukpesananpemalang', [Laporan_historipemalangController::class, 'barangMasukpesananpemalang'])->name('barangMasukpesananpemalang');
    Route::get('barangMasuksemuapemalang', [Laporan_historipemalangController::class, 'barangMasuksemuapemalang'])->name('barangMasuksemuapemalang');
    Route::get('printLaporanBmpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBmpemalang']);
    Route::get('printLaporanBmpesananpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBmpesananpemalang']);
    Route::get('printLaporanBmsemuapemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBmsemuapemalang']);
    Route::get('printExcelBmpemalang', [Laporan_historipemalangController::class, 'exportExcel'])->name('printExcelBmpemalang');
    Route::get('printExcelBmpesananpemalang', [Laporan_historipemalangController::class, 'exportExcelBMpesanan'])->name('printExcelBmpesananpemalang');
    Route::get('printExcelBmsemuapemalang', [Laporan_historipemalangController::class, 'exportExcelBMsemua'])->name('printExcelBmsemuapemalang');

    Route::get('barangKeluarpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'barangKeluarpemalang'])->name('barangKeluarpemalang');
    Route::get('barangKeluarRincipemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'barangKeluarRincipemalang'])->name('barangKeluarRincipemalang');
    Route::get('printLaporanBKpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBKpemalang']);
    Route::get('printLaporanBKrincipemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBKrincipemalang']);
    Route::get('printExcelBkpemalang', [Laporan_historipemalangController::class, 'exportExcelBK'])->name('printExcelBkpemalang');

    Route::get('barangReturpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'barangReturpemalang'])->name('barangReturpemalang');
    Route::get('/print-report', [Laporan_historipemalangController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBRpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBRpemalang']);
    Route::get('printExcelBrpemalang', [Laporan_historipemalangController::class, 'exportExcelBR'])->name('printExcelBrpemalang');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historipemalangController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

    Route::get('barangOperpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'barangOperpemalang'])->name('barangOperpemalang');
    Route::get('barangOperanpemalangMasuk', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'barangOperapemalangMasuk'])->name('barangOperanpemalangMasuk');
    Route::get('printLaporanBOpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBOpemalang']);
    Route::get('printLaporanBOpemalangMasuk', [\App\Http\Controllers\Toko_pemalang\Laporan_historipemalangController::class, 'printLaporanBOpemalangMasuk']);
});


Route::middleware('toko_bumiayu')->prefix('toko_bumiayu')->group(function () {

    Route::get('/', [\App\Http\Controllers\Toko_bumiayu\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_bumiayu\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_bumiayu\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    Route::get('admin/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan');

    Route::resource('produk', \App\Http\Controllers\Toko_bumiayu\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_bumiayu\PemesananprodukbumiayuController::class);
    Route::get('/toko_bumiayu/pemesanan_produk/cetak/{id}', [PemesananproduktegalController::class, 'cetak'])->name('toko_bumiayu.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananproduktegalController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_bumiayu\PemesananprodukbumiayuController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananproduktegalController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_bumiayu/pemesanan_produk/update/{id}', [PemesananproduktegalController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_bumiayu/pemesanan_produk/cetak-pdf{id}', [PemesananproduktegalController::class, 'cetakPdf'])->name('toko_bumiayu.pemesanan_produk.cetak-pdf');
    Route::delete('toko_bumiayu/pemesanan_produk/{id}', [PemesananproduktegalController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_bumiayu/pemesanan_produk/{id}/cetak', [PemesananproduktegalController::class, 'cetak'])->name('toko_bumiayu.pemesanan_produk.cetak');
    Route::get('/toko_bumiayu/pemesanan-produk/create', [PemesananproduktegalController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_bumiayu\Inquery_pemesananprodukController::class);
    Route::get('/toko_bumiayu/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_banjaran.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukbmy', \App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController::class);
    Route::get('print_pemesananbmy', [\App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController::class, 'print_pemesanan']);
    Route::get('printReportpemesananbmy', [Laporan_pemesananprodukbumiayuController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalbmy', [\App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalbmy', [Laporan_pemesananprodukbumiayuController::class, 'printReportpemesananglobalbmy'])->name('printReportpemesananglobalbmy');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController::class);
    Route::get('/toko_bumiayu/penjualan_produk/cetak/{id}', [PenjualanprodukbumiayuController::class, 'cetak'])->name('toko_bumiayu.penjualan_produk.cetak');
    Route::get('/toko_bumiayu/penjualan_produk/cetak-pdf{id}', [PenjualanprodukbumiayuController::class, 'cetakPdf'])->name('toko_bumiayu.penjualan_produk.cetak-pdf');
    Route::get('/toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'pelunasan'])->name('toko_bumiayu.penjualan_produk.pelunasan');
    Route::get('toko_bumiayu/penjualan_produk/create', [PenjualanprodukbumiayuController::class, 'create'])->name('toko_bumiayu.penjualan_produk.create');
    Route::get('/toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'pelunasan'])->name('toko_bumiayu.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukbumiayuController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukbumiayuController::class, 'fetchDataByKode'])->name('toko_bumiayu.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukbumiayuController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController::class, 'metode']);
    Route::post('toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukbumiayuController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukbumiayuController::class, 'fetchProductData'])->name('toko_bumiayu.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukbumiayuController::class, 'searchProduct']);


    Route::resource('pelunasan_pemesananBmy', \App\Http\Controllers\Toko_bumiayu\PelunasanpemesananBmyController::class);
    Route::get('/toko_bumiayu/pelunasan_pemesananBmy/cetak-pdf{id}', [PelunasanpemesananBmyController::class, 'cetakPdf'])->name('toko_bumiayu.pelunasan_pemesananBmy.cetak-pdf');
    Route::get('/pelunasan-pemesananTgl/cetak/{id}', [PelunasanpemesananBmyController::class, 'cetak'])->name('toko_bumiayu.pelunasan_pemesananBmy.cetak');
    Route::get('/pelunasan_pemesananBmy', [PelunasanpemesananBmyController::class, 'index'])->name('toko_bumiayu.pelunasan_pemesananTgl.index');

    Route::resource('inquery_penjualanprodukbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class);
    Route::get('/toko_bumiayu/inquery_penjualanprodukbumiayu', [Inquery_penjualanprodukbumiayuController::class, 'index'])->name('toko_bumiayu.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukbumiayu/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukbumiayu/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class, 'posting_penjualanproduk']);
    Route::get('/toko_bumiayu/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukbumiayuController::class, 'cetakPdf'])->name('toko_bumiayu.inquery_penjualanproduk.cetak-pdf');
    Route::post('/toko_bumiayu/inquery_penjualanprodukbumiayu/{id}/update', [Inquery_penjualanprodukbumiayuController::class, 'update'])->name('inquery_penjualanprodukbumiayu.update');
    Route::get('metodebayarbumiayu/metode/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class, 'metode']);
    Route::delete('/toko_bumiayu/inquery_penjualanproduk/{id}', [Inquery_penjualanprodukbumiayuController::class, 'destroy'])
        ->name('toko_bumiayu.inquery_penjualanproduk.destroy');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController::class);
    Route::post('toko_bumiayu/permintaan_produk', [PermintaanprodukbumiayuController::class, 'store']);
    // Route::get('toko_bumiayu/permintaan_produk', [PermintaanprodukbumiayuController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukbumiayuController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController::class, 'posting_permintaanproduk']);
    Route::post('toko_bumiayu/permintaan/importbumiayu', [PermintaanprodukbumiayuController::class, 'importbumiayu'])->name('permintaan.importbumiayu');


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_bumiayu\Inquery_permintaanprodukController::class);

    Route::resource('inquery_pelunasanbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_pelunasanbumiayuController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_bumiayu\Metode_pembayaranController::class);


    Route::resource('stok_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Stok_tokobumiayuController::class);
    Route::delete('/toko_bumiayu/stok_tokobumiayu/deleteAll', [Stok_tokobumiayuController::class, 'deleteAll'])->name('stok_tokobumiayu.deleteAll');
    Route::post('toko_bumiayu/stok_tokobumiayu/import', [Stok_tokobumiayuController::class, 'import'])->name('stok_tokobumiayu.import');

    Route::resource('stokpesanan_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Stokpesanan_tokobumiayuController::class);
    Route::delete('/toko_bumiayu/stokpesanan_tokobumiayu/deleteAll', [Stokpesanan_tokobumiayuController::class, 'deleteAll'])->name('stokpesanan_tokobumiayu.deleteAll');


    Route::resource('pengiriman_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class);
    Route::get('pengiriman_tokobumiayu/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokobumiayu/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokobumiayu/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokobumiayu/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokobumiayu/{id}/print', [Pengiriman_tokobumiayuController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_bumiayu/pengiriman_tokobumiayu/printpemesanan/{id}', [Pengiriman_tokobumiayuController::class, 'printpemesanan'])->name('pengiriman_tokobumiayu.printpemesanan');
    Route::get('toko_bumiayu/pengiriman_tokobumiayu/index', [Pengiriman_tokobumiayuController::class, 'index'])->name('toko_bumiayu.pengiriman_tokobumiayu.index');
    Route::get('/toko_bumiayu/pengiriman_tokobumiayu/pengiriman_pemesanan', [Pengiriman_tokobumiayuController::class, 'pengiriman_pemesanan'])->name('toko_bumiayu.pengiriman_tokobumiayu.pengiriman_pemesanan');
    Route::get('/toko_bumiayu/pengiriman_tokobumiayu/showpemesanan/{id}', [Pengiriman_tokobumiayuController::class, 'showpemesanan'])->name('toko_bumiayu.pengiriman_tokobumiayu.showpemesanan');

    Route::resource('pengirimanpemesanan_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Pengirimanpemesanan_tokobumiayuController::class);
    Route::get('/pengirimanpemesanan_tokobumiayu/print/{id}', [Pengirimanpemesanan_tokobumiayuController::class, 'print'])->name('pengirimanpemesanan_tokobumiayu.print');


    Route::resource('retur_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Retur_tokobumiayuController::class);

    Route::resource('inquery_returbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController::class);
    Route::get('inquery_returbumiayu/unpost_retur/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController::class, 'unpost_retur']);
    Route::get('inquery_returbumiayu/posting_retur/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController::class, 'posting_retur']);
    Route::get('/inquery_returbumiayu/{id}/print', [Inquery_returbumiayuController::class, 'print'])->name('inquery_returbumiayu.print');


    Route::resource('pemindahan_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Pemindahan_tokobumiayuController::class);

    Route::resource('inquery_pemindahanbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbumiayuController::class);
    Route::get('inquery_pemindahanbumiayu/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbumiayuController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahanbumiayu/{id}/print', [Inquery_pemindahanbumiayuController::class, 'print'])->name('inquery_pemindahanbumiayu.print');

    Route::resource('laporan_pemindahanbumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_pemindahanbumiayuController::class);
    Route::get('/toko_bumiayu/print_report', [Laporan_pemindahanbumiayuController::class, 'printReport'])->name('print.report');
    Route::get('printReportpemindahanBmy/{id}', [\App\Http\Controllers\Toko_bumiayu\Laporan_pemindahanbumiayuController::class, 'printReportpemindahanBmy']);

    Route::resource('laporan_stoktokobumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class);
    Route::get('printstoktokobumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'printReport']);
    Route::get('printexcelstoktokobumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'exportExcel']);
    Route::get('stoktokopesananbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'stoktokopesananbumiayu']);
    Route::get('printstoktokopesananbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'printReportstokpesananbumiayu']);
    Route::get('semuastoktokobumiayu', [Laporan_stoktokobumiayuController::class, 'semuaStokTokoBumiayu'])->name('laporan.semuaStokTokoBumiayu');
    Route::get('printsemuastoktokobumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'printReportsemuastokbumiayu']);
    Route::get('printexcelsemuabumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'exportExcelsemua']);
    Route::get('printexcelstokpesananbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobumiayuController::class, 'exportExcelpesanan']);

    Route::resource('laporan_pengirimantokobumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_pengirimantokobumiayuController::class);
    Route::get('printpengirimantokobumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_pengirimantokobumiayuController::class, 'printReport']);

    Route::resource('setoran_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Setoran_tokobumiayuController::class);
    Route::post('toko_bumiayu/setoran_tokobumiayu', [Setoran_tokobumiayuController::class, 'store'])->name('setoran.store');
    Route::post('/get-penjualanbumiayu', [Setoran_tokobumiayuController::class, 'getdatabumiayu'])->name('getdatabumiayu');
    Route::get('/print-penjualantoko-kotorbmy', [Setoran_tokobumiayuController::class, 'printPenjualanKotorbmy'])->name('print.penjualantoko.kotorbmy');
    Route::get('/print-fakturpenjualantokobmy', [Setoran_tokobumiayuController::class, 'printFakturpenjualanbmy'])->name('print.fakturpenjualantokobmy');
    Route::get('/print-fakturpenjualanmesinedcbmy', [Setoran_tokobumiayuController::class, 'printFakturpenjualanMesinedcbmy'])->name('print.fakturpenjualanmesinedcbmy');
    Route::get('/print-fakturpemesananmesinedcbmy', [Setoran_tokobumiayuController::class, 'printFakturpemesananMesinedcbmy'])->name('print.fakturpemesananmesinedcbmy');
    Route::get('/print-fakturpenjualanqrisbmy', [Setoran_tokobumiayuController::class, 'printFakturpenjualanQrisbmy'])->name('print.fakturpenjualanqrisbmy');
    Route::get('/print-fakturpemesananqrisbmy', [Setoran_tokobumiayuController::class, 'printFakturpemesananQrisbmy'])->name('print.fakturpemesananqrisbmy');
    Route::get('/print-fakturpenjualantransferbmy', [Setoran_tokobumiayuController::class, 'printFakturpenjualanTransferbmy'])->name('print.fakturpenjualantransferbmy');
    Route::get('/print-fakturpemesanantransferbmy', [Setoran_tokobumiayuController::class, 'printFakturpemesananTransferbmy'])->name('print.fakturpemesanantransferbmy');
    Route::get('/print-fakturpenjualangobizbmy', [Setoran_tokobumiayuController::class, 'printFakturpenjualanGobizbmy'])->name('print.fakturpenjualangobizbmy');
    Route::get('/print-fakturpemesanangobizbmy', [Setoran_tokobumiayuController::class, 'printFakturpemesananGobizbmy'])->name('print.fakturpemesanangobizbmy');
    Route::get('/print-fakturdepositmasuktokobmy', [Setoran_tokobumiayuController::class, 'printFakturdepositMasukbmy'])->name('print.fakturdepositmasuktokobmy');
    Route::get('/print-fakturdepositkeluartokobmy', [Setoran_tokobumiayuController::class, 'printFakturdepositKeluarbmy'])->name('print.fakturdepositkeluartokobmy');
    Route::get('/print-penjualantoko-diskonbmy', [Setoran_tokobumiayuController::class, 'printPenjualanDiskonbmy'])->name('print.penjualantoko.diskonbmy');
    Route::get('/print-penjualantoko-bersihbmy', [Setoran_tokobumiayuController::class, 'printPenjualanBersihbmy'])->name('print.penjualantoko.bersihbmy');
    Route::get('penjualanprodukbmy/detail/{id}', [Setoran_tokobumiayuController::class, 'show'])->name('penjualanprodukbmy.detail');
    Route::get('penjualanprodukbmy/detaildepositkeluar/{id}', [Setoran_tokobumiayuController::class, 'show2'])->name('penjualanprodukbmy.detaildepositkeluar');
    Route::get('pemesananprodukbmy/detailpemesanan/{id}', [Setoran_tokobumiayuController::class, 'show1'])->name('pemesananprodukbmy.detailpemesanan');


    Route::resource('laporan_setorantokobumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_setoranpenjualanbmyController::class);
    Route::get('printReportsetoranbmy', [Laporan_setoranpenjualanbmyController::class, 'printReportsetoranbmy'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_depositbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_depositbumiayuController::class);

    Route::resource('laporan_depositbumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'printReportsaldo']);

    Route::resource('inquery_setorantunaibumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_setorantunaibumiayuController::class);
    Route::get('/toko_bumiayu/inquery_setorantunaibumiayu/{id}/print', [Inquery_setorantunaibumiayuController::class, 'print'])->name('inquery_setorantunaibumiayu.print');

    Route::resource('laporan_setorantunaibumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_setorantunaibumiayuController::class);

    Route::resource('laporan_returbumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_returbumiayuController::class);
    Route::get('printReportreturbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_returbumiayuController::class, 'printReportreturbumiayu']);

    Route::resource('laporan_historibumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class);
    Route::get('barangMasukpesananbumiayu', [Laporan_historibumiayuController::class, 'barangMasukpesananbumiayu'])->name('barangMasukpesananbumiayu');
    Route::get('barangMasuksemuabumiayu', [Laporan_historibumiayuController::class, 'barangMasuksemuabumiayu'])->name('barangMasuksemuabumiayu');
    Route::get('printLaporanBmbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBmbumiayu']);
    Route::get('printLaporanBmpesananbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBmpesananbumiayu']);
    Route::get('printLaporanBmsemuabumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBmsemuabumiayu']);
    Route::get('printExcelBmbumiayu', [Laporan_historibumiayuController::class, 'exportExcel'])->name('printExcelBmbumiayu');
    Route::get('printExcelBmpesananbumiayu', [Laporan_historibumiayuController::class, 'exportExcelBMpesanan'])->name('printExcelBmpesananbumiayu');
    Route::get('printExcelBmsemuabumiayu', [Laporan_historibumiayuController::class, 'exportExcelBMsemua'])->name('printExcelBmsemuabumiayu');

    Route::get('barangKeluarbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'barangKeluarbumiayu'])->name('barangKeluarbumiayu');
    Route::get('barangKeluarRincibumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'barangKeluarRincibumiayu'])->name('barangKeluarRincibumiayu');
    Route::get('printLaporanBKbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBKbumiayu']);
    Route::get('printLaporanBKrincibumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBKrincibumiayu']);
    Route::get('printExcelBkbumiayu', [Laporan_historibumiayuController::class, 'exportExcelBK'])->name('printExcelBkbumiayu');

    Route::get('barangReturbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'barangReturbumiayu'])->name('barangReturbumiayu');
    Route::get('/print-report', [Laporan_historibumiayuController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBRbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBRbumiayu']);
    Route::get('printExcelBrbumiayu', [Laporan_historibumiayuController::class, 'exportExcelBR'])->name('printExcelBrbumiayu');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historibumiayuController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

    Route::get('barangOperbumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'barangOperbumiayu'])->name('barangOperbumiayu');
    Route::get('barangOperanbumiayuMasuk', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'barangOperanbumiayuMasuk'])->name('barangOperanbumiayuMasuk');
    Route::get('printLaporanBObumiayu', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBObumiayu']);
    Route::get('printLaporanBObumiayuMasuk', [\App\Http\Controllers\Toko_bumiayu\Laporan_historibumiayuController::class, 'printLaporanBObumiayuMasuk']);
});

Route::middleware('toko_cilacap')->prefix('toko_cilacap')->group(function () {

    Route::get('/', [\App\Http\Controllers\Toko_cilacap\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_cilacap\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_cilacap\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [Toko_cilacapPelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    Route::get('admin/pelanggan', [Toko_cilacapPelangganController::class, 'index'])->name('admin.pelanggan');

    Route::resource('produk', \App\Http\Controllers\Toko_cilacap\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_cilacap\PemesananprodukcilacapController::class);
    Route::get('/toko_cilacap/pemesanan_produk/cetak/{id}', [PemesananprodukcilacapController::class, 'cetak'])->name('toko_cilacap.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananprodukcilacapController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_cilacap\PemesananprodukcilacapController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukcilacapController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_cilacap/pemesanan_produk/update/{id}', [PemesananprodukcilacapController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_cilacap/pemesanan_produk/cetak-pdf{id}', [PemesananprodukcilacapController::class, 'cetakPdf'])->name('toko_cilacap.pemesanan_produk.cetak-pdf');
    Route::delete('toko_cilacap/pemesanan_produk/{id}', [PemesananprodukcilacapController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_cilacap/pemesanan_produk/{id}/cetak', [PemesananprodukcilacapController::class, 'cetak'])->name('toko_cilacap.pemesanan_produk.cetak');
    Route::get('/toko_cilacap/pemesanan-produk/create', [PemesananprodukcilacapController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_cilacap\Inquery_pemesananprodukController::class);
    Route::get('/toko_cilacap/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_banjaran.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukclc', \App\Http\Controllers\Toko_cilacap\Laporan_pemesananprodukcilacapController::class);
    Route::get('print_pemesananclc', [\App\Http\Controllers\Toko_cilacap\Laporan_pemesananprodukcilacapController::class, 'print_pemesanan']);
    Route::get('printReportpemesananclc', [Laporan_pemesananprodukcilacapController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalclc', [\App\Http\Controllers\Toko_cilacap\Laporan_pemesananprodukcilacapController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalclc', [Laporan_pemesananprodukcilacapController::class, 'printReportpemesananglobalclc'])->name('printReportpemesananglobalclc');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_cilacap\PenjualanprodukcilacapController::class);
    Route::get('/toko_cilacap/penjualan_produk/cetak/{id}', [PenjualanprodukcilacapController::class, 'cetak'])->name('toko_cilacap.penjualan_produk.cetak');
    Route::get('/toko_cilacap/penjualan_produk/cetak-pdf{id}', [PenjualanprodukcilacapController::class, 'cetakPdf'])->name('toko_cilacap.penjualan_produk.cetak-pdf');
    Route::get('/toko_cilacap/penjualan_produk/pelunasan', [PenjualanprodukcilacapController::class, 'pelunasan'])->name('toko_cilacap.penjualan_produk.pelunasan');
    Route::get('toko_cilacap/penjualan_produk/create', [PenjualanprodukcilacapController::class, 'create'])->name('toko_cilacap.penjualan_produk.create');
    Route::get('/toko_cilacap/penjualan_produk/pelunasan', [PenjualanprodukcilacapController::class, 'pelunasan'])->name('toko_cilacap.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukcilacapController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukcilacapController::class, 'fetchDataByKode'])->name('toko_cilacap.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukcilacapController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_cilacap\PenjualanprodukcilacapController::class, 'metode']);
    Route::post('toko_cilacap/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanproduktegalController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanproduktegalController::class, 'fetchProductData'])->name('toko_cilacap.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanproduktegalController::class, 'searchProduct']);


    Route::resource('pelunasan_pemesananClc', \App\Http\Controllers\Toko_cilacap\PelunasanpemesananClcController::class);
    Route::get('/toko_cilacap/pelunasan_pemesananClc/cetak-pdf{id}', [PelunasanpemesananClcController::class, 'cetakPdf'])->name('toko_cilacap.pelunasan_pemesananClc.cetak-pdf');
    Route::get('/pelunasan-pemesananClc/cetak/{id}', [PelunasanpemesananClcController::class, 'cetak'])->name('toko_cilacap.pelunasan_pemesananClc.cetak');
    Route::get('/pelunasan_pemesananClc', [PelunasanpemesananClcController::class, 'index'])->name('toko_cilacap.pelunasan_pemesananClc.index');

    Route::resource('inquery_penjualanprodukcilacap', \App\Http\Controllers\Toko_cilacap\Inquery_penjualanprodukcilacapController::class);
    Route::get('/toko_cilacap/inquery_penjualanprodukcilacap', [Inquery_penjualanprodukcilacapController::class, 'index'])->name('toko_cilacap.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukcilacap/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_penjualanprodukcilacapController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukcilacap/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_penjualanprodukcilacapController::class, 'posting_penjualanproduk']);
    Route::get('/toko_cilacap/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukcilacapController::class, 'cetakPdf'])->name('toko_cilacap.inquery_penjualanproduk.cetak-pdf');
    Route::post('/toko_cilacap/inquery_penjualanprodukcilacap/{id}/update', [Inquery_penjualanprodukcilacapController::class, 'update'])->name('inquery_penjualanprodukcilacap.update');
    Route::get('metodebayarcilacap/metode/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_penjualanprodukcilacapController::class, 'metode']);
    Route::delete('/toko_cilacap/inquery_penjualanproduk/{id}', [Inquery_penjualanprodukcilacapController::class, 'destroy'])
        ->name('toko_cilacap.inquery_penjualanproduk.destroy');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_cilacap\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_cilacap\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_cilacap\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_cilacap\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_cilacap\PermintaanprodukcilacapController::class);
    Route::post('toko_cilacap/permintaan_produk', [PermintaanprodukcilacapController::class, 'store']);
    // Route::get('toko_cilacap/permintaan_produk', [PermintaanprodukcilacapController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukcilacapController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_cilacap\PermintaanprodukcilacapController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_cilacap\PermintaanprodukcilacapController::class, 'posting_permintaanproduk']);
    Route::post('toko_cilacap/permintaan/importcilacap', [PermintaanprodukcilacapController::class, 'importcilacap'])->name('permintaan.importcilacap');


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_cilacap\Inquery_permintaanprodukController::class);

    Route::resource('inquery_pelunasancilacap', \App\Http\Controllers\Toko_cilacap\Inquery_pelunasancilacapController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_cilacap\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_cilacap\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_cilacap\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_cilacap\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_cilacap\Metode_pembayaranController::class);


    Route::resource('stok_tokocilacap', \App\Http\Controllers\Toko_cilacap\Stok_tokocilacapController::class);
    Route::delete('/toko_cilacap/stok_tokocilacap/deleteAll', [Stok_tokocilacapController::class, 'deleteAll'])->name('stok_tokocilacap.deleteAll');
    Route::post('toko_cilacap/stok_tokocilacap/import', [Stok_tokocilacapController::class, 'import'])->name('stok_tokocilacap.import');

    Route::resource('stokpesanan_tokocilacap', \App\Http\Controllers\Toko_cilacap\Stokpesanan_tokocilacapController::class);
    Route::delete('/toko_cilacap/stokpesanan_tokocilacap/deleteAll', [Stokpesanan_tokocilacapController::class, 'deleteAll'])->name('stokpesanan_tokocilacap.deleteAll');

    Route::resource('pengiriman_tokocilacap', \App\Http\Controllers\Toko_cilacap\Pengiriman_tokocilacapController::class);
    Route::get('pengiriman_tokocilacap/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_cilacap\Pengiriman_tokocilacapController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokocilacap/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_cilacap\Pengiriman_tokocilacapController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokocilacap/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_cilacap\Pengiriman_tokocilacapController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokocilacap/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_cilacap\Pengiriman_tokocilacapController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokocilacap/{id}/print', [Pengiriman_tokocilacapController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_cilacap/pengiriman_tokocilacap/printpemesanan/{id}', [Pengiriman_tokocilacapController::class, 'printpemesanan'])->name('pengiriman_tokocilacap.printpemesanan');
    Route::get('toko_cilacap/pengiriman_tokocilacap/index', [Pengiriman_tokocilacapController::class, 'index'])->name('toko_cilacap.pengiriman_tokocilacap.index');
    Route::get('/toko_cilacap/pengiriman_tokocilacap/pengiriman_pemesanan', [Pengiriman_tokocilacapController::class, 'pengiriman_pemesanan'])->name('toko_cilacap.pengiriman_tokocilacap.pengiriman_pemesanan');
    Route::get('/toko_cilacap/pengiriman_tokocilacap/showpemesanan/{id}', [Pengiriman_tokocilacapController::class, 'showpemesanan'])->name('toko_cilacap.pengiriman_tokocilacap.showpemesanan');

    Route::resource('pengirimanpemesanan_tokocilacap', \App\Http\Controllers\Toko_cilacap\Pengirimanpemesanan_tokocilacapController::class);
    Route::get('/pengirimanpemesanan_tokocilacap/print/{id}', [Pengirimanpemesanan_tokocilacapController::class, 'print'])->name('pengirimanpemesanan_tokocilacap.print');


    Route::resource('retur_tokocilacap', \App\Http\Controllers\Toko_cilacap\Retur_tokocilacapController::class);

    Route::resource('inquery_returcilacap', \App\Http\Controllers\Toko_cilacap\Inquery_returcilacapController::class);
    Route::get('inquery_returcilacap/unpost_retur/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_returcilacapController::class, 'unpost_retur']);
    Route::get('inquery_returcilacap/posting_retur/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_returcilacapController::class, 'posting_retur']);
    Route::get('/inquery_returcilacap/{id}/print', [Inquery_returcilacapController::class, 'print'])->name('inquery_returcilacap.print');

    Route::resource('pemindahan_tokocilacap', \App\Http\Controllers\Toko_cilacap\Pemindahan_tokocilacapController::class);

    Route::resource('inquery_pemindahancilacap', \App\Http\Controllers\Toko_cilacap\Inquery_pemindahancilacapController::class);
    Route::get('inquery_pemindahancilacap/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_cilacap\Inquery_pemindahancilacapController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahancilacap/{id}/print', [Inquery_pemindahancilacapController::class, 'print'])->name('inquery_pemindahancilacap.print');

    Route::resource('laporan_pemindahancilacap', \App\Http\Controllers\Toko_cilacap\Laporan_pemindahancilacapController::class);
    Route::get('printReportpemindahanClc/{id}', [\App\Http\Controllers\Toko_cilacap\Laporan_pemindahancilacapController::class, 'printReportpemindahanClc']);

    Route::resource('laporan_stoktokocilacap', \App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class);
    Route::get('printstoktokocilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'printReport']);
    Route::get('printexcelstoktokocilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'exportExcel']);
    Route::get('stoktokopesanancilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'stoktokopesanancilacap']);
    Route::get('printstoktokopesanancilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'printReportstokpesanantegal']);
    Route::get('semuastoktokocilacap', [Laporan_stoktokocilacapController::class, 'semuaStokTokoCilacap'])->name('laporan.semuaStokTokoCilacap');
    Route::get('printsemuastoktokocilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'printReportsemuastokcilacap']);
    Route::get('printexcelsemuacilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'exportExcelsemua']);
    Route::get('printexcelstokpesanancilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_stoktokocilacapController::class, 'exportExcelpesanan']);

    Route::resource('laporan_pengirimantokocilacap', \App\Http\Controllers\Toko_cilacap\Laporan_pengirimantokocilacapController::class);
    Route::get('printpengirimantokocilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_pengirimantokocilacapController::class, 'printReport']);

    Route::resource('setoran_tokocilacap', \App\Http\Controllers\Toko_cilacap\Setoran_tokocilacapController::class);
    Route::post('toko_cilacap/setoran_tokocilacap', [Setoran_tokocilacapController::class, 'store'])->name('setoran.store');
    Route::post('/get-penjualancilacap', [Setoran_tokocilacapController::class, 'getdatacilacap'])->name('getdatacilacap');
    Route::get('/print-penjualantoko-kotorclc', [Setoran_tokocilacapController::class, 'printPenjualanKotorclc'])->name('print.penjualantoko.kotorclc');
    Route::get('/print-fakturpenjualantokoclc', [Setoran_tokocilacapController::class, 'printFakturpenjualanclc'])->name('print.fakturpenjualantokoclc');
    Route::get('/print-fakturpenjualanmesinedcclc', [Setoran_tokocilacapController::class, 'printFakturpenjualanMesinedcclc'])->name('print.fakturpenjualanmesinedcclc');
    Route::get('/print-fakturpemesananmesinedcclc', [Setoran_tokocilacapController::class, 'printFakturpemesananMesinedcclc'])->name('print.fakturpemesananmesinedcclc');
    Route::get('/print-fakturpenjualanqrisclc', [Setoran_tokocilacapController::class, 'printFakturpenjualanQrisclc'])->name('print.fakturpenjualanqrisclc');
    Route::get('/print-fakturpemesananqrisclc', [Setoran_tokocilacapController::class, 'printFakturpemesananQrisclc'])->name('print.fakturpemesananqrisclc');
    Route::get('/print-fakturpenjualantransferclc', [Setoran_tokocilacapController::class, 'printFakturpenjualanTransferclc'])->name('print.fakturpenjualantransferclc');
    Route::get('/print-fakturpemesanantransferclc', [Setoran_tokocilacapController::class, 'printFakturpemesananTransferclc'])->name('print.fakturpemesanantransferclc');
    Route::get('/print-fakturpenjualangobizclc', [Setoran_tokocilacapController::class, 'printFakturpenjualanGobizclc'])->name('print.fakturpenjualangobizclc');
    Route::get('/print-fakturpemesanangobizclc', [Setoran_tokocilacapController::class, 'printFakturpemesananGobizclc'])->name('print.fakturpemesanangobizclc');
    Route::get('/print-fakturdepositmasuktokoclc', [Setoran_tokocilacapController::class, 'printFakturdepositMasukclc'])->name('print.fakturdepositmasuktokoclc');
    Route::get('/print-fakturdepositkeluartokoclc', [Setoran_tokocilacapController::class, 'printFakturdepositKeluarclc'])->name('print.fakturdepositkeluartokoclc');
    Route::get('/print-penjualantoko-diskonclc', [Setoran_tokocilacapController::class, 'printPenjualanDiskonclc'])->name('print.penjualantoko.diskonclc');
    Route::get('/print-penjualantoko-bersihclc', [Setoran_tokocilacapController::class, 'printPenjualanBersihclc'])->name('print.penjualantoko.bersihclc');
    Route::get('penjualanprodukclc/detail/{id}', [Setoran_tokocilacapController::class, 'show'])->name('penjualanprodukclc.detail');
    Route::get('penjualanprodukclc/detaildepositkeluar/{id}', [Setoran_tokocilacapController::class, 'show2'])->name('penjualanprodukclc.detaildepositkeluar');
    Route::get('pemesananprodukclc/detailpemesanan/{id}', [Setoran_tokocilacapController::class, 'show1'])->name('pemesananprodukclc.detailpemesanan');


    Route::resource('laporan_setorantokocilacap', \App\Http\Controllers\Toko_cilacap\Laporan_setoranpenjualanclcController::class);
    Route::get('printReportsetoranclc', [Laporan_setoranpenjualanclcController::class, 'printReportsetoranclc'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_deposittegal', \App\Http\Controllers\Toko_cilacap\Inquery_depositcilacapController::class);

    Route::resource('laporan_depositcilacap', \App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_cilacap\Laporan_depositcilacapController::class, 'printReportsaldo']);

    Route::resource('inquery_setorantunaicilacap', \App\Http\Controllers\Toko_cilacap\Inquery_setorantunaicilacapController::class);
    Route::get('/toko_cilacap/inquery_setorantunaicilacap/{id}/print', [Inquery_setorantunaicilacapController::class, 'print'])->name('inquery_setorantunaicilacap.print');

    Route::resource('laporan_setorantunaicilacap', \App\Http\Controllers\Toko_cilacap\Laporan_setorantunaicilacapController::class);

    Route::resource('laporan_returcilacap', \App\Http\Controllers\Toko_cilacap\Laporan_returcilacapController::class);
    Route::get('printReportreturcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_returcilacapController::class, 'printReportreturcilacap']);

    Route::resource('laporan_historicilacap', \App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class);
    Route::get('barangMasukpesanancilacap', [Laporan_historicilacapController::class, 'barangMasukpesanancilacap'])->name('barangMasukpesanancilacap');
    Route::get('barangMasuksemuacilacap', [Laporan_historicilacapController::class, 'barangMasuksemuacilacap'])->name('barangMasuksemuacilacap');
    Route::get('printLaporanBmcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBmcilacap']);
    Route::get('printLaporanBmpesanancilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBmpesanancilacap']);
    Route::get('printLaporanBmsemuacilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBmsemuacilacap']);
    Route::get('printExcelBmcilacap', [Laporan_historicilacapController::class, 'exportExcel'])->name('printExcelBmcilacap');
    Route::get('printExcelBmpesanancilacap', [Laporan_historicilacapController::class, 'exportExcelBMpesanan'])->name('printExcelBmpesanancilacap');
    Route::get('printExcelBmsemuacilacap', [Laporan_historicilacapController::class, 'exportExcelBMsemua'])->name('printExcelBmsemuacilacap');

    Route::get('barangKeluarcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'barangKeluarcilacap'])->name('barangKeluarcilacap');
    Route::get('barangKeluarRincicilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'barangKeluarRincicilacap'])->name('barangKeluarRincicilacap');
    Route::get('printLaporanBKcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBKcilacap']);
    Route::get('printLaporanBKrincicilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBKrincicilacap']);
    Route::get('printExcelBkcilacap', [Laporan_historicilacapController::class, 'exportExcelBK'])->name('printExcelBkcilacap');

    Route::get('barangReturcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'barangReturcilacap'])->name('barangReturcilacap');
    Route::get('/print-report', [Laporan_historicilacapController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBRcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBRcilacap']);
    Route::get('printExcelBrcilacap', [Laporan_historicilacapController::class, 'exportExcelBR'])->name('printExcelBrcilacap');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historicilacapController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

    Route::get('barangOpercilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'barangOpercilacap'])->name('barangOpercilacap');
    Route::get('barangOperancilacapMasuk', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'barangOperacilacapMasuk'])->name('barangOperancilacapMasuk');
    Route::get('printLaporanBOcilacap', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBOcilacap']);
    Route::get('printLaporanBOcilacapMasuk', [\App\Http\Controllers\Toko_cilacap\Laporan_historicilacapController::class, 'printLaporanBOcilacapMasuk']);
});