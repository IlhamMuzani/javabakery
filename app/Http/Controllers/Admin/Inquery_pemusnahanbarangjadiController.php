<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Retur_barangjadi;
use App\Models\Pemusnahan_barangjadi;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Retur_barnagjadi;
use App\Models\Stok_retur;
use Maatwebsite\Excel\Facades\Excel;

class Inquery_pemusnahanbarangjadiController extends Controller{


    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_retur = $request->tanggal_retur;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pemusnahan_barangjadi::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

            if ($tanggal_retur && $tanggal_akhir) {
                $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
            } elseif ($tanggal_retur) {
                $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
                $query->where('tanggal_retur', '>=', $tanggal_retur);
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->where('tanggal_retur', '<=', $tanggal_akhir);
            } else {
                // Jika tidak ada filter tanggal, tampilkan data hari ini
                $query->whereDate('tanggal_retur', Carbon::today());
            }

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');

            return view('admin.inquery_pemusnahanbarangjadi.index', compact('stokBarangJadi'));
    }

    

public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('toko_slawi.retur_tokoslawi.create', compact('produks', 'tokos'));
}


public function kode()
{
    $prefix = 'RSLW';
    $year = date('y'); // Dua digit terakhir dari tahun
    $date = date('md'); // Format bulan dan hari: MMDD

    // Mengambil kode retur terakhir yang dibuat pada hari yang sama
    $lastBarang = Retur_tokoslawi::whereDate('tanggal_input', Carbon::today())
                                  ->orderBy('kode_retur', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_retur;
        $lastNum = (int) substr($lastCode, strlen($prefix . $year . $date)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
    $newCode = $prefix . $year . $date . $formattedNum;
    return $newCode;
}


// public function unpost_pemusnahan(Request $request, $id)
// {
//     // Temukan pemusnahan berdasarkan ID
//     $pemusnahan = Pemusnahan_barangjadi::find($id);

//     // Jika pemusnahan ditemukan
//     if ($pemusnahan) {
//         // Ambil data terkait dari tabel retur_barangjadis
//         $returBarangJadiDetails = Retur_barangjadi::where('kode_retur', $pemusnahan->kode_retur)->get();

//         if ($pemusnahan->status === 'posting') {
//             // Jika status sudah 'posting', ubah menjadi 'unpost'
//             $pemusnahan->status = 'unpost';
//             $pemusnahan->save();

//             // Kembalikan jumlah produk pada retur_barangjadis
//             foreach ($returBarangJadiDetails as $detail) {
//                 // Kembalikan jumlah produk sesuai dengan jumlah pemusnahan
//                 $jumlahDikembalikan = $detail->jumlah + $pemusnahan->jumlah;

//                 // Perbarui jumlah produk
//                 $detail->jumlah = $jumlahDikembalikan;
//                 $detail->save();
//             }

//             return response()->json(['success' => true, 'message' => 'Status diubah menjadi unpost dan jumlah dikembalikan']);
//         } elseif ($pemusnahan->status === 'unpost') {
//             // Jika status sudah 'unpost', ubah menjadi 'posting'
//             $pemusnahan->status = 'posting';
//             $pemusnahan->save();

//             // Kurangi jumlah produk pada retur_barangjadis
//             foreach ($returBarangJadiDetails as $detail) {
//                 // Kurangi jumlah produk sesuai dengan jumlah pemusnahan
//                 $jumlahDikurangi = $detail->jumlah - $pemusnahan->jumlah;

//                 if ($jumlahDikurangi < 0) {
//                     return response()->json(['success' => false, 'message' => 'Jumlah produk tidak cukup'], 400);
//                 }

//                 // Perbarui jumlah produk
//                 $detail->jumlah = $jumlahDikurangi;
//                 $detail->save();
//             }

//             return response()->json(['success' => true, 'message' => 'Status diubah menjadi posting dan jumlah dikurangi']);
//         }

//         return response()->json(['success' => false, 'message' => 'Status tidak valid'], 400);
//     }

//     return response()->json(['success' => false], 404);
// }


public function posting_pemusnahan(Request $request, $id)
{
    // Temukan pemusnahan berdasarkan ID
    $pemusnahan = Pemusnahan_barangjadi::find($id);

    // Jika pemusnahan ditemukan
    if ($pemusnahan) {
        // Periksa apakah status sudah 'posting'
        if ($pemusnahan->status === 'posting') {
            return response()->json(['success' => false, 'message' => 'Status sudah posting'], 400);
        }

        // Ubah status menjadi 'posting'
        $pemusnahan->status = 'posting';
        $pemusnahan->tanggal_terima = Carbon::now('Asia/Jakarta');
        $pemusnahan->save();

        // Ambil data terkait dari tabel retur_barangjadis
        $returBarangJadiDetails = Stok_retur::where('kode_retur', $pemusnahan->kode_retur)->get();

        // Kurangi jumlah produk pada retur_barangjadis
        foreach ($returBarangJadiDetails as $detail) {
            // Kurangi jumlah produk sesuai dengan jumlah pemusnahan
            // Misalkan kamu memiliki kolom 'jumlah' di tabel pemusnahan yang menyimpan jumlah produk yang dimusnahkan
            $jumlahDikurangi = $detail->jumlah - $pemusnahan->jumlah;

            if ($jumlahDikurangi < 0) {
                return response()->json(['success' => false, 'message' => 'Jumlah produk tidak cukup'], 400);
            }

            // Perbarui jumlah produk
            $detail->jumlah = $jumlahDikurangi;
            $detail->save();
        }

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 404);
}




public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Pemusnahan_barangjadi::where('id', $id)->value('kode_retur');
    
    // Jika kode_retur tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_retur yang sama
    $pengirimanBarangJadi = Pemusnahan_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('admin.inquery_pemusnahanbarangjadi.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function print($id)
    {
        $detailStokBarangJadi = Pemusnahan_barangjadi::where('id', $id)->value('kode_retur');
    
        // Jika kode_retur tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_retur yang sama
        $pengirimanBarangJadi = Pemusnahan_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        $pdf = FacadePdf::loadView('admin.inquery_pemusnahanbarangjadi.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_slawi.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
        }

}


 