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
use App\Models\Pengiriman_barangjadi;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class Inquery_hasilpenjualanController extends Controller
{


    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id; // Menambahkan filter toko_id
        $klasifikasi_id = $request->klasifikasi_id; // Menambahkan filter klasifikasi_id

        // Ambil data toko untuk dropdown
        $tokos = Toko::all();  // Mengambil semua data toko
        $klasifikasis = Klasifikasi::all(); // Mengambil semua data klasifikasi

        // Query dasar dengan relasi ke produk dan klasifikasi
        $query = Pengiriman_barangjadi::with('produk.klasifikasi');

        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }

        // Filter berdasarkan klasifikasi_id
        if ($klasifikasi_id) {
            $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                $q->where('klasifikasi_id', $klasifikasi_id);
            });
        }

        // Filter berdasarkan tanggal pengiriman
        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_pengiriman', Carbon::today());
        }

        // Ambil data dengan pengurutan berdasarkan tanggal pengiriman terbaru
        $stokBarangJadi = $query->orderBy('tanggal_pengiriman', 'desc')->get();

        // Kirim variabel ke view
        return view('admin.inquery_hasilpenjualan.index', compact('stokBarangJadi', 'tokos', 'klasifikasis'));
    }


    // public function barangKeluar(Request $request)
    // {
    //     $status = $request->status;
    //     $tanggal_penjualan = $request->tanggal_penjualan;
    //     $tanggal_akhir = $request->tanggal_akhir;

    //     $inquery = Penjualanproduk::with('detailPenjualanProduk.produk');

    //     if ($status) {
    //         $inquery->where('status', $status);
    //     }

    //     if ($tanggal_penjualan && $tanggal_akhir) {
    //         $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $inquery->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    //     } elseif ($tanggal_penjualan) {
    //         $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //         $inquery->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    //     } elseif ($tanggal_akhir) {
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $inquery->where('tanggal_penjualan', '<=', $tanggal_akhir);
    //     } else {
    //         $inquery->whereDate('tanggal_penjualan', Carbon::today());
    //     }

    //     // Ambil data penjualan
    //     $inquery = $inquery->get();

    //     // Gabungkan hasil berdasarkan produk_id
    //     $finalResults = [];

    //     foreach ($inquery as $penjualan) {
    //         foreach ($penjualan->detailPenjualanProduk as $detail) {
    //             $key = $detail->produk_id;

    //             if (!isset($finalResults[$key])) {
    //                 $finalResults[$key] = [
    //                     'tanggal_penjualan' => $penjualan->tanggal_penjualan,
    //                     'kode_lama' => $detail->produk->kode_lama,
    //                     'nama_produk' => $detail->produk->nama_produk,
    //                     'jumlah' => 0,
    //                     'diskon' => 0,
    //                     'total' => 0,
    //                 ];
    //             }

    //             // Jumlahkan jumlah dan total
    //             $finalResults[$key]['jumlah'] += $detail->jumlah;
    //             $finalResults[$key]['total'] += $detail->total;

    //             // Hitung diskon 10% dari total yang memiliki diskon
    //             if ($detail->diskon > 0) {
    //                 $finalResults[$key]['diskon'] += $detail->total * 0.10;
    //             }
    //         }
    //     }

    //     return view('admin.inquery_hasilpenjualan.barangkeluar', ['finalResults' => $finalResults]);
    // }

    public function barangKeluar(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

        $inquery = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id !== null, function ($query) use ($toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
            })
            ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('detailPenjualanProduk.produk', function ($query) use ($klasifikasi_id) {
                    return $query->where('klasifikasi_id', $klasifikasi_id);
                });
            });

        // Ambil data penjualan
        $inquery = $inquery->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];

        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $key = $detail->produk_id;

                if (!isset($finalResults[$key])) {
                    $finalResults[$key] = [
                        'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                        'kode_lama' => $detail->produk->kode_lama,
                        'nama_produk' => $detail->produk->nama_produk,
                        'harga' => $detail->produk->harga,
                        'jumlah' => 0,
                        'diskon' => 0,
                        'total' => 0,
                    ];
                }

                // Jumlahkan jumlah dan total
                $finalResults[$key]['jumlah'] += $detail->jumlah;
                $finalResults[$key]['total'] += $detail->total;

                // Hitung diskon 10% dari jumlah * harga
                if ($detail->diskon > 0) {
                    $diskonPerItem = $detail->harga * 0.10; // Diskon per unit
                    $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                }
            }
        }

        $tokos = Toko::all(); // Assuming Toko is a model for your toko table
        $klasifikasis = Klasifikasi::all(); // Assuming Klasifikasi is a model for your klasifikasi table

        return view('admin.inquery_hasilpenjualan.barangkeluar', [
            'finalResults' => $finalResults,
            'tokos' => $tokos,
            'klasifikasis' => $klasifikasis
        ]);
    }




    public function barangRetur(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Penjualanproduk::query();

        if ($status) {
            $inquery->where('status', $status);
        }

        if ($tanggal_penjualan && $tanggal_akhir) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $inquery->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->where('tanggal_penjualan', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $inquery->whereDate('tanggal_penjualan', Carbon::today());
        }

        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();

        return view('admin.inquery_hasilpenjualan.barangretur', compact('inquery'));
    }
}
