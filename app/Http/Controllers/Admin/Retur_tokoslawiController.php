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
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;

class Retur_tokoslawiController extends Controller{

    public function index()
    {
        // Ambil data retur_tokoslawi beserta relasi produk
        $retur_tokoslawi = Retur_tokoslawi::with('produk')->where('status', 'posting')->get();
    
        return view('admin.retur_tokoslawi.index', compact('retur_tokoslawi'));
    }
    

public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('admin.retur_tokoslawi.create', compact('produks', 'tokos'));
}

// public function store(Request $request)
// {
//     $request->validate([
//         // 'toko_id' => 'required|exists:tokos,id',
//         'produk_id' => 'required|array',
//         'produk_id.*' => 'exists:produks,id',
//         'jumlah' => 'required|array',
//         'jumlah.*' => 'integer|min:1',
//         'keterangan' => 'required|array',
//         'keterangan.*' => 'in:produk gagal,oper,sampel' // Validasi untuk keterangan
//     ]);

//     // $toko_id = $request->input('toko_id');
//     $kode = $this->kode();
//     $produk_ids = $request->input('produk_id');
//     $jumlahs = $request->input('jumlah');
//     $keterangans = $request->input('keterangan');

//     foreach ($produk_ids as $index => $produk_id) {
//         // Ambil stok yang sesuai dengan produk_id
//         $stok = Stok_tokoslawi::where('produk_id', $produk_id)->first();

//         if (!$stok) {
//             return redirect()->back()->with('error', 'Stok untuk produk dengan ID ' . $produk_id . ' tidak ditemukan.');
//         }

//         if ($stok->jumlah < $jumlahs[$index]) {
//             return redirect()->back()->with('error', 'Jumlah stok untuk produk ' . $stok->produk->nama_produk . ' tidak mencukupi.');
//         }

//         // Kurangi jumlah stok
//         $stok->jumlah -= $jumlahs[$index];
//         $stok->save();

//         // Simpan data retur_tokoslawi
//         Retur_tokoslawi::create([
//             // 'toko_id' => $toko_id,
//             'kode_retur' => $kode,
//             'produk_id' => $produk_id,
//             'status' => 'posting',
//             'jumlah' => $jumlahs[$index],
//             'keterangan' => $keterangans[$index],
//             'tanggal_input' => Carbon::now('Asia/Jakarta'),
//         ]);
//     }

//     return redirect()->route('retur_tokoslawi.index')->with('success', 'Data retur barang berhasil disimpan.');
// }
public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required|array',
        'produk_id.*' => 'exists:produks,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
        'keterangan' => 'required|array',
        'keterangan.*' => 'in:produk gagal,oper,sampel'
    ]);

    $kode = $this->kode();
    $produk_ids = $request->input('produk_id');
    $jumlahs = $request->input('jumlah');
    $keterangans = $request->input('keterangan');

    foreach ($produk_ids as $index => $produk_id) {
        $jumlah_yang_dibutuhkan = $jumlahs[$index];
        
        // Ambil semua stok yang sesuai dengan produk_id dan urutkan berdasarkan jumlah stok
        $stok_items = Stok_tokoslawi::where('produk_id', $produk_id)
            ->where('jumlah', '>', 0)
            ->orderBy('jumlah', 'asc')
            ->get();

        if ($stok_items->isEmpty()) {
            return redirect()->back()->with('error', 'Stok untuk produk dengan ID ' . $produk_id . ' tidak ditemukan.');
        }

        foreach ($stok_items as $stok) {
            if ($jumlah_yang_dibutuhkan <= 0) {
                break;
            }

            if ($stok->jumlah >= $jumlah_yang_dibutuhkan) {
                // Jika stok saat ini cukup untuk memenuhi kebutuhan
                $stok->jumlah -= $jumlah_yang_dibutuhkan;
                $stok->save();

                // Simpan data retur_tokoslawi
                Retur_tokoslawi::create([
                    'kode_retur' => $kode,
                    'produk_id' => $produk_id,
                    'status' => 'posting',
                    'jumlah' => $jumlah_yang_dibutuhkan,
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);

                $jumlah_yang_dibutuhkan = 0;
            } else {
                // Jika stok saat ini tidak cukup, kurangi jumlah stok ini dan lanjutkan ke stok berikutnya
                $jumlah_yang_dibutuhkan -= $stok->jumlah;

                // Simpan data retur_tokoslawi dengan stok yang tersedia
                Retur_tokoslawi::create([
                    'kode_retur' => $kode,
                    'produk_id' => $produk_id,
                    'status' => 'posting',
                    'jumlah' => $stok->jumlah,
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);

                $stok->jumlah = 0;
                $stok->save();
            }
        }

        if ($jumlah_yang_dibutuhkan > 0) {
            return redirect()->back()->with('error', 'Jumlah stok untuk produk ' . $stok->produk->nama_produk . ' tidak mencukupi.');
        }
    }

    return redirect()->route('retur_tokoslawi.index')->with('success', 'Data retur barang berhasil disimpan.');
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


// public function kode()
// {
//     $lastBarang = Retur_tokoslawi::latest()->first();
//     if (!$lastBarang) {
//         $num = 1;
//     } else {
//         $lastCode = $lastBarang->kode_retur;
//         $num = (int) substr($lastCode, strlen('SB')) + 1; 
//     }
//     $formattedNum = sprintf("%06s", $num);
//     $prefix = 'RSLW';
//     $newCode = $prefix . $formattedNum;
//     return $newCode;
// }



}


 