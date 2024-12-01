<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
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
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;


class Grafik_penjualanController extends Controller
{

    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
        $produk_id = $request->produk; // Tambahkan filter produk

        // Query dasar untuk mengambil data Penjualanproduk
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id); // Filter berdasarkan toko
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$start, $end]); // Filter berdasarkan tanggal
            })
            ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $start);
            })
            ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $end);
            });

        // Eksekusi query dan dapatkan hasilnya
        $inquery = $query->get();

        // Gabungkan hasil berdasarkan toko dan tanggal
        $finalResults = [];
        $tokoResults = [];

        foreach ($inquery as $penjualan) {
            $toko = $penjualan->toko_id; // Ambil ID toko
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;

                if ($produk) {
                    if ($klasifikasi_id && $produk->klasifikasi_id != $klasifikasi_id) {
                        continue; // Lewati produk yang tidak sesuai klasifikasi
                    }

                    if ($produk_id && $produk->id != $produk_id) {
                        continue; // Lewati produk yang tidak sesuai filter produk
                    }

                    $tanggal = Carbon::parse($penjualan->tanggal_penjualan)->format('Y-m-d'); // Gunakan format tanggal

                    // Menghitung penjualan bersih
                    $penjualan_kotor = $detail->jumlah * $produk->harga;
                    $diskon = ($detail->diskon > 0) ? $detail->jumlah * $produk->harga * 0.10 : 0;
                    $penjualan_bersih = $penjualan_kotor - $diskon;

                    // Simpan ke finalResults berdasarkan tanggal
                    if (!isset($finalResults[$tanggal])) {
                        $finalResults[$tanggal] = [
                            'tanggal_penjualan' => $tanggal,
                            'penjualan_bersih' => 0, // Tambahkan untuk penjualan bersih
                        ];
                    }
                    $finalResults[$tanggal]['penjualan_bersih'] += $penjualan_bersih;

                    // Simpan ke tokoResults berdasarkan toko
                    if (!isset($tokoResults[$toko])) {
                        $tokoResults[$toko] = [
                            'toko_id' => $toko,
                            'penjualan_bersih' => 0,
                        ];
                    }
                    $tokoResults[$toko]['penjualan_bersih'] += $penjualan_bersih;
                }
            }
        }

        // Mengurutkan finalResults berdasarkan tanggal penjualan
        ksort($finalResults);

        // Ambil data untuk filter
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $produks = Produk::all();


        
        // Pass data ke view
        return view('admin.grafik_penjualan.index', [
            'finalResults' => $finalResults,
            'tokoResults' => $tokoResults, // Kirimkan hasil penjualan per toko
            'tokos' => $tokos,
            'produks' => $produks,
            'klasifikasis' => $klasifikasis,
            'startDate' => $tanggal_penjualan,
            'endDate' => $tanggal_akhir,
            'selectedToko' => $toko_id,
        ]);
    }

    // public function index(Request $request)
    // {
    //     // Ambil parameter filter dari request
    //     $status = $request->status;
    //     $tanggal_penjualan = $request->tanggal_penjualan;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $toko_id = $request->toko_id;
    //     $klasifikasi_id = $request->klasifikasi_id;
    //     $produk_id = $request->produk; // Tambahkan filter produk
    
    //     // Query dasar untuk mengambil data Penjualanproduk
    //     $query = Penjualanproduk::with('detailPenjualanProduk') // Hapus relasi produk
    //         ->when($status, function ($query, $status) {
    //             return $query->where('status', $status);
    //         })
    //         ->when($toko_id, function ($query, $toko_id) {
    //             return $query->where('toko_id', $toko_id); // Filter berdasarkan toko
    //         })
    //         ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
    //             $start = Carbon::parse($tanggal_penjualan)->startOfDay();
    //             $end = Carbon::parse($tanggal_akhir)->endOfDay();
    //             return $query->whereBetween('tanggal_penjualan', [$start, $end]); // Filter berdasarkan tanggal
    //         })
    //         ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
    //             $start = Carbon::parse($tanggal_penjualan)->startOfDay();
    //             return $query->where('tanggal_penjualan', '>=', $start);
    //         })
    //         ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
    //             $end = Carbon::parse($tanggal_akhir)->endOfDay();
    //             return $query->where('tanggal_penjualan', '<=', $end);
    //         });
    
    //     // Eksekusi query dan dapatkan hasilnya
    //     $inquery = $query->get();
    
    //     // Gabungkan hasil berdasarkan toko dan tanggal
    //     $finalResults = [];
    //     $tokoResults = [];
    //     $produkCounts = []; // Array untuk menghitung produk yang sering dibeli
    
    //     foreach ($inquery as $penjualan) {
    //         $toko = $penjualan->toko_id; // Ambil ID toko
    //         foreach ($penjualan->detailPenjualanProduk as $detail) {
    //             // Ambil nama_produk langsung dari detailPenjualanProduk
    //             $nama_produk = $detail->nama_produk; // Pastikan kolom ini ada di tabel detailPenjualanProduk
    
    //             if ($klasifikasi_id && $detail->klasifikasi_id != $klasifikasi_id) {
    //                 continue; // Lewati produk yang tidak sesuai klasifikasi
    //             }
    
    //             if ($produk_id && $detail->produk_id != $produk_id) {
    //                 continue; // Lewati produk yang tidak sesuai filter produk
    //             }
    
    //             $tanggal = Carbon::parse($penjualan->tanggal_penjualan)->format('Y-m-d'); // Gunakan format tanggal
    
    //             // Menghitung penjualan bersih
    //             $penjualan_kotor = $detail->jumlah * $detail->harga; // Ganti $produk->harga dengan $detail->harga
    //             $diskon = ($detail->diskon > 0) ? $detail->jumlah * $detail->harga * 0.10 : 0;
    //             $penjualan_bersih = $penjualan_kotor - $diskon;
    
    //             // Simpan ke finalResults berdasarkan tanggal
    //             if (!isset($finalResults[$tanggal])) {
    //                 $finalResults[$tanggal] = [
    //                     'tanggal_penjualan' => $tanggal,
    //                     'penjualan_bersih' => 0, // Tambahkan untuk penjualan bersih
    //                 ];
    //             }
    //             $finalResults[$tanggal]['penjualan_bersih'] += $penjualan_bersih;
    
    //             // Simpan ke tokoResults berdasarkan toko
    //             if (!isset($tokoResults[$toko])) {
    //                 $tokoResults[$toko] = [
    //                     'toko_id' => $toko,
    //                     'penjualan_bersih' => 0,
    //                 ];
    //             }
    //             $tokoResults[$toko]['penjualan_bersih'] += $penjualan_bersih;
    
    //             // Hitung jumlah produk yang terjual
    //             if (!isset($produkCounts[$detail->produk_id])) { // Ganti $produk->id dengan $detail->produk_id
    //                 $produkCounts[$detail->produk_id] = [
    //                     'nama_produk' => $nama_produk, // Ambil nama_produk dari detail
    //                     'jumlah' => 0,
    //                 ];
    //             }
    //             $produkCounts[$detail->produk_id]['jumlah'] += $detail->jumlah; // Tambah jumlah produk
    //         }
    //     }
    
    //     // Mengurutkan finalResults berdasarkan tanggal penjualan
    //     ksort($finalResults);
    
    //     // Mengurutkan produk berdasarkan jumlah yang terjual
    //     arsort($produkCounts); // Mengurutkan berdasarkan jumlah terjual
        
        
    //     $produkData = array_column($produkCounts, 'jumlah'); // Ambil jumlah produk terjual
    //     $produkNames = array_column($produkCounts, 'nama_produk'); // Ambil nama produk
    
    //     // Ambil data untuk filter
    //     $tokos = Toko::all();
    //     $klasifikasis = Klasifikasi::all();
    //     $produks = Produk::all();
    
    //     // Pass data ke view
    //     return view('admin.grafik_penjualan.index', [
    //         'finalResults' => $finalResults,
    //         'tokoResults' => $tokoResults, // Kirimkan hasil penjualan per toko
    //         'tokos' => $tokos,
    //         'produks' => $produks,
    //         'klasifikasis' => $klasifikasis,
    //         'startDate' => $tanggal_penjualan,
    //         'endDate' => $tanggal_akhir,
    //         'selectedToko' => $toko_id,
    //         'produkData' => $produkData, // Kirimkan data produk
    //         'produkNames' => $produkNames, // Kirimkan nama produk
    //     ]);
    // }
    



  
}