<?php

namespace App\Http\Controllers\Toko_slawi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Barang;
use App\Models\Detailbarangjadi;
use App\Models\Detailpemesananproduk;
use App\Models\Detailpenjualanproduk;
use App\Models\Detail_stokbarangjadi;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Stok_tokoslawi;
use App\Models\Retur_tokoslawi;
use App\Models\Pemindahan_tokoslawi;
use App\Models\Pemindahan_tokotegalmasuk;
use App\Models\Pemindahan_tokoslawimasuk;
use App\Models\Pemindahan_barangjadi;
use App\Models\Retur_barangjadi;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Pemindahan_tokobanjaranmasuk;
use App\Models\Pemindahan_tokobumiayumasuk;
use App\Models\Pemindahan_tokocilacapmasuk;
use App\Models\Pemindahan_tokopemalangmasuk;
use App\Models\Pemindahan_tokotegal;
use App\Models\Retur_barnagjadi;
use Maatwebsite\Excel\Facades\Excel;

class Pemindahan_tokoslawiController extends Controller{

    public function index()
    {
        $pemindahan_tokotegal = Pemindahan_tokoslawi::with('produk')
                            ->orderBy('created_at', 'desc')
                            ->get();
    
        return view('toko_slawi.pemindahan_tokoslawi.index', compact('pemindahan_tokotegal'));
    }
      

public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('toko_slawi.pemindahan_tokoslawi.create', compact('produks', 'tokos'));
}


// public function store(Request $request)
// {
//     $request->validate([
//         'produk_id' => 'required|array',
//         'produk_id.*' => 'exists:produks,id',
//         'jumlah' => 'required|array',
//         'jumlah.*' => 'integer|min:1',
//         'keterangan' => 'required|array',
//         'keterangan.*' => 'string',
//     ]);

//     $kode = $this->kode();
//     $produk_ids = $request->input('produk_id');
//     $jumlahs = $request->input('jumlah');
//     $keterangans = $request->input('keterangan');

//     foreach ($produk_ids as $index => $produk_id) {
//         // Simpan ke tabel pemindahan_tokoslawi
//         Pemindahan_tokoslawi::create([
//             'kode_pemindahan' => $kode,
//             'produk_id' => $produk_id,
//             'toko_id' => '3',  // Ganti sesuai dengan toko tujuan
//             'status' => 'unpost',
//             'jumlah' => $jumlahs[$index],
//             'keterangan' => $keterangans[$index],
//             'tanggal_input' => Carbon::now('Asia/Jakarta'),
//         ]);

//         // Simpan ke tabel pemindahan_barangjadis
//         Pemindahan_barangjadi::create([
//             'kode_pemindahan' => $kode,
//             'produk_id' => $produk_id,
//             'toko_id' => '2',  // Ganti sesuai dengan toko tujuan
//             'status' => 'unpost',
//             'jumlah' => $jumlahs[$index],
//             'keterangan' => $keterangans[$index],
//             'tanggal_input' => Carbon::now('Asia/Jakarta'),
//         ]);

//         // Simpan ke tabel berdasarkan keterangan
//         switch ($keterangans[$index]) {
//             case 'BANJARAN':
//                 Pemindahan_tokobanjaranmasuk::create([
//                     'kode_pemindahan' => $kode,
//                     'produk_id' => $produk_id,
//                     'toko_id' => '1',  // Ganti sesuai dengan ID toko BANJARAN
//                     'status' => 'unpost',
//                     'jumlah' => $jumlahs[$index],
//                     'keterangan' => $keterangans[$index],
//                     'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                 ]);
//                 break;
//             case 'TEGAL':
//                 Pemindahan_tokotegalmasuk::create([
//                     'kode_pemindahan' => $kode,
//                     'produk_id' => $produk_id,
//                     'toko_id' => '2',  // Ganti sesuai dengan ID toko TEGAL
//                     'status' => 'unpost',
//                     'jumlah' => $jumlahs[$index],
//                     'keterangan' => $keterangans[$index],
//                     'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                 ]);
//                 break;
//                 case 'SLAWI':
//                     Pemindahan_tokoslawimasuk::create([
//                         'kode_pemindahan' => $kode,
//                         'produk_id' => $produk_id,
//                         'toko_id' => '1',  // Ganti sesuai dengan ID toko TEGAL
//                         'status' => 'unpost',
//                         'jumlah' => $jumlahs[$index],
//                         'keterangan' => $keterangans[$index],
//                         'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                     ]);
//                     break;
//             // Tambahkan kasus lain jika ada toko lain yang perlu ditangani
//             default:
//                 // Tidak melakukan apa-apa jika keterangan tidak cocok
//                 break;
//         }
//     }

//     return redirect()->route('pemindahan_tokoslawi.index')->with('success', 'Data pemindahan barang berhasil disimpan.');
// }

public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required|array',
        'produk_id.*' => 'exists:produks,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
        'keterangan' => 'required|array',
        'keterangan.*' => 'string',
    ]);

    $kode = $this->kode();
    $produk_ids = $request->input('produk_id');
    $jumlahs = $request->input('jumlah');
    $keterangans = $request->input('keterangan');

    foreach ($produk_ids as $index => $produk_id) {
        // Simpan ke tabel pemindahan_tokoslawi
        Pemindahan_tokoslawi::create([
            'kode_pemindahan' => $kode,
            'produk_id' => $produk_id,
            'toko_id' => '3',  // Ganti sesuai dengan toko tujuan
            'status' => 'unpost',
            'jumlah' => $jumlahs[$index],
            'keterangan' => $keterangans[$index],
            'tanggal_input' => Carbon::now('Asia/Jakarta'),
        ]);

        // Simpan ke tabel pemindahan_barangjadis
        Pemindahan_barangjadi::create([
            'kode_pemindahan' => $kode,
            'produk_id' => $produk_id,
            'toko_id' => '3',  // Ganti sesuai dengan toko tujuan
            'status' => 'unpost',
            'jumlah' => $jumlahs[$index],
            'keterangan' => $keterangans[$index],
            'tanggal_input' => Carbon::now('Asia/Jakarta'),
        ]);

        // Simpan ke tabel berdasarkan keterangan
        switch ($keterangans[$index]) {
            case 'BANJARAN':
                Pemindahan_tokobanjaranmasuk::create([
                    'kode_pemindahan' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '3',  // Ganti sesuai dengan ID toko BANJARAN
                    'status' => 'unpost',
                    'jumlah' => $jumlahs[$index],
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
                break;
            case 'TEGAL':
                Pemindahan_tokotegalmasuk::create([
                    'kode_pemindahan' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '3',  // Ganti sesuai dengan ID toko TEGAL
                    'status' => 'unpost',
                    'jumlah' => $jumlahs[$index],
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
                break;
                case 'SLAWI':
                    Pemindahan_tokoslawimasuk::create([
                        'kode_pemindahan' => $kode,
                        'produk_id' => $produk_id,
                        'toko_id' => '3',  // Ganti sesuai dengan ID toko TEGAL
                        'status' => 'unpost',
                        'jumlah' => $jumlahs[$index],
                        'keterangan' => $keterangans[$index],
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                    case 'PEMALANG':
                        Pemindahan_tokopemalangmasuk::create([
                            'kode_pemindahan' => $kode,
                            'produk_id' => $produk_id,
                            'toko_id' => '3',  // Ganti sesuai dengan ID toko TEGAL
                            'status' => 'unpost',
                            'jumlah' => $jumlahs[$index],
                            'keterangan' => $keterangans[$index],
                            'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        ]);
                    break;
                    case 'BUMIAYU':
                        Pemindahan_tokobumiayumasuk::create([
                            'kode_pemindahan' => $kode,
                            'produk_id' => $produk_id,
                            'toko_id' => '3',  // Ganti sesuai dengan ID toko TEGAL
                            'status' => 'unpost',
                            'jumlah' => $jumlahs[$index],
                            'keterangan' => $keterangans[$index],
                            'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        ]);
                    break;
                    case 'CILACAP':
                        Pemindahan_tokocilacapmasuk::create([
                            'kode_pemindahan' => $kode,
                            'produk_id' => $produk_id,
                            'toko_id' => '3',  // Ganti sesuai dengan ID toko TEGAL
                            'status' => 'unpost',
                            'jumlah' => $jumlahs[$index],
                            'keterangan' => $keterangans[$index],
                            'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        ]);
                    break;            
                        
                        default:
                // Tidak melakukan apa-apa jika keterangan tidak cocok
                break;
        }
    }

    return redirect()->route('pemindahan_tokoslawi.index')->with('success', 'Data pemindahan barang berhasil disimpan.');
}



public function kode()
{
    $prefix = 'FOB';
    $year = date('y'); // Dua digit terakhir dari tahun
    $date = date('dm'); // Format bulan dan hari: MMDD

    // Mengambil kode retur terakhir yang dibuat pada hari yang sama
    $lastBarang = Pemindahan_tokoslawi::whereDate('tanggal_input', Carbon::today())
                                  ->orderBy('kode_pemindahan', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_pemindahan;
        $lastNum = (int) substr($lastCode, strlen($prefix  . $date . $year)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
    $newCode = $prefix  . $date . $year . $formattedNum;
    return $newCode;
}


public function unpost_retur($id)
{
    // Ambil data stok barang berdasarkan ID
    $stok = Retur_tokoslawi::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$stok) {
        return back()->with('error', 'Data tidak ditemukan.');
    }

    // Ambil kode_input dari stok yang diambil
    $kodeInput = $stok->kode_retur;

    // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
    Retur_tokoslawi::where('kode_retur', $kodeInput)->update([
        'status' => 'unpost'
    ]);
    return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
}


public function posting_retur($id)
{
   // Ambil data Retur_tokoslawi berdasarkan ID
    $pengiriman = Retur_tokoslawi::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$pengiriman) {
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // Ambil kode_retur dari pengiriman yang diambil
    $kodePengiriman = $pengiriman->kode_retur;

    // Update status untuk semua Retur_tokoslawi dengan kode_retur yang sama
    Retur_tokoslawi::where('kode_retur', $kodePengiriman)->update([
        'status' => 'posting'
    ]);

    // Update status untuk semua stok_tokoslawi terkait dengan Retur_tokoslawi_id
    Stok_tokoslawi::where('pengiriman_barangjadi_id', $id)->update([
        'status' => 'posting'
    ]);

    return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_retur yang sama.']);
}

public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Retur_tokoslawi::where('id', $id)->value('kode_retur');
    
    // Jika kode_retur tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_retur yang sama
    $pengirimanBarangJadi = Retur_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('toko_slawi.inquery_returbanjaran.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function print($id)
    {
        $detailStokBarangJadi = Retur_tokoslawi::where('id', $id)->value('kode_retur');
    
        // Jika kode_retur tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_retur yang sama
        $pengirimanBarangJadi = Retur_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        $pdf = FacadePdf::loadView('toko_slawi.inquery_returbanjaran.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_slawi.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
        }

}


 