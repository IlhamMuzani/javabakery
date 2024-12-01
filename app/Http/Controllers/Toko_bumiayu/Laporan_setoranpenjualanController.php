<?php

namespace App\Http\Controllers\Toko_bumiayu;

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
use App\Models\Dppemesanan;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Laporan_setoranpenjualanController extends Controller
{
    

    // public function index(Request $request)
    // {
    //     $status = $request->status;
    //     $tanggal_penjualan = $request->tanggal_penjualan;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $kasir = $request->kasir;

    //     // Query dasar untuk mengambil data penjualan produk
    //     $query = Penjualanproduk::query();

    //     // Filter berdasarkan status
    //     if ($status) {
    //         $query->where('status', $status);
    //     }

    //     // Filter berdasarkan tanggal penjualan
    //     if ($tanggal_penjualan && $tanggal_akhir) {
    //         $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    //     } elseif ($tanggal_penjualan) {
    //         $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //         $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    //     } elseif ($tanggal_akhir) {
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    //     } else {
    //         $query->whereDate('tanggal_penjualan', Carbon::today());
    //     }

    //     // Filter berdasarkan kasir
    //     if ($kasir) {
    //         $query->where('kasir', $kasir);
    //     }

    //     // Urutkan data berdasarkan ID secara descending
    //     $query->orderBy('id', 'DESC');

    //     // Ambil data penjualan produk
    //     $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

    //     // Buat query terpisah untuk menghitung total penjualan kotor
    //     $penjualan_kotor = Penjualanproduk::select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
    //         ->where('kasir', $kasir) // Sesuaikan dengan filter
    //         ->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]) // Sesuaikan dengan filter
    //         ->value('total');

    //     // Hitung total diskon penjualan (nominal_diskon)
    //     $diskon_penjualan = $query->sum('nominal_diskon');

    //     // Hitung penjualan bersih
    //     $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

    //     // Query terpisah untuk menghitung total deposit masuk dari tabel dppemesanan berdasarkan kasir
    //     $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
    //         if ($tanggal_penjualan && $tanggal_akhir) {
    //             $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
    //         } elseif ($tanggal_penjualan) {
    //             $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
    //         } elseif ($tanggal_akhir) {
    //             $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
    //         }
    //         // Filter berdasarkan kasir
    //         if ($kasir) {
    //             $q->where('kasir', $kasir);
    //         }
    //     })->sum('dp_pemesanan');

    //     // Query untuk menghitung total deposit keluar dari tabel dppemesanan yang terkait dengan penjualanproduk
    //     $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($kasir, $tanggal_penjualan, $tanggal_akhir) {
    //         if ($tanggal_penjualan && $tanggal_akhir) {
    //             $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    //         } elseif ($tanggal_penjualan) {
    //             $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    //         } elseif ($tanggal_akhir) {
    //             $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
    //         }
    //         // Filter berdasarkan kasir
    //         if ($kasir) {
    //             $q->where('kasir', $kasir);
    //         }
    //     })->sum('dp_pemesanan');

    //     // Hitung total dari berbagai metode pembayaran
    //     $mesin_edc = Penjualanproduk::where('metode_id', 1)
    //         ->where('kasir', $kasir)
    //         ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
    //         ->value('total');

    //     $qris = Penjualanproduk::where('metode_id', 17)
    //         ->where('kasir', $kasir)
    //         ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
    //         ->value('total');

    //     $gobiz = Penjualanproduk::where('metode_id', 2)
    //         ->where('kasir', $kasir)
    //         ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
    //         ->value('total');

    //     $transfer = Penjualanproduk::where('metode_id', 3)
    //         ->where('kasir', $kasir)
    //         ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
    //         ->value('total');

    //     $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);

    //     // Ambil semua data produk, toko, kasir, klasifikasi untuk dropdown
    //     $produks = Produk::all();
    //     $tokos = Toko::all();
    //     $klasifikasis = Klasifikasi::all();
    //     $kasirs = Penjualanproduk::select('kasir')->where('toko_id', 5)->distinct()->get();

    //     // Hitung total metode dan setoran
    //     $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
    //     $total_setoran = $total_penjualan - $total_metode;

    //     // Kembalikan view dengan data yang diperlukan
    //     return view('toko_bumiayu.laporan_setoranpenjualan.index', compact(
    //         'inquery',
    //         'kasirs',
    //         'penjualan_kotor',
    //         'diskon_penjualan',
    //         'penjualan_bersih',
    //         'deposit_masuk',
    //         'total_penjualan',
    //         'mesin_edc',
    //         'qris',
    //         'gobiz',
    //         'transfer',
    //         'total_setoran',
    //         'deposit_keluar'
    //     ));
    // }

    public function index(Request $request)
{
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $kasir = $request->kasir;

    // Query dasar untuk mengambil data penjualan produk
    $query = Penjualanproduk::query();

    // Filter berdasarkan status
    if ($status) {
        $query->where('status', $status);
    }

    // Filter berdasarkan tanggal penjualan
    if ($tanggal_penjualan && $tanggal_akhir) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    } else {
        $query->whereDate('tanggal_penjualan', Carbon::today());
    }

    // Filter berdasarkan kasir
    if ($kasir) {
        $query->where('kasir', $kasir);
    }

    // Urutkan data berdasarkan ID secara descending
    $query->orderBy('id', 'DESC');

    // Ambil data penjualan produk
    $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

    // Perbaikan logika menghitung diskon_penjualan
    $diskon_query = Penjualanproduk::query();
    if ($tanggal_penjualan && $tanggal_akhir) {
        $diskon_query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $diskon_query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $diskon_query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    }
    if ($kasir) {
        $diskon_query->where('kasir', $kasir);
    }
    $diskon_penjualan = $diskon_query->sum('nominal_diskon');

    // Perhitungan penjualan lainnya
    $penjualan_kotor = Penjualanproduk::select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
        ->where('kasir', $kasir)
        ->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir])
        ->value('total');

    $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

    // Query terpisah untuk menghitung total deposit masuk
    $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
        if ($tanggal_penjualan && $tanggal_akhir) {
            $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
        }
        if ($kasir) {
            $q->where('kasir', $kasir);
        }
    })->sum('dp_pemesanan');

    $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($kasir, $tanggal_penjualan, $tanggal_akhir) {
        if ($tanggal_penjualan && $tanggal_akhir) {
            $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }
        if ($kasir) {
            $q->where('kasir', $kasir);
        }
    })->sum('dp_pemesanan');

    $mesin_edc = Penjualanproduk::where('metode_id', 1)
        ->where('kasir', $kasir)
        ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
        ->value('total');

    $qris = Penjualanproduk::where('metode_id', 17)
        ->where('kasir', $kasir)
        ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
        ->value('total');

    $gobiz = Penjualanproduk::where('metode_id', 2)
        ->where('kasir', $kasir)
        ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
        ->value('total');

    $transfer = Penjualanproduk::where('metode_id', 3)
        ->where('kasir', $kasir)
        ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
        ->value('total');

    $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);

    $produks = Produk::all();
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();
    $kasirs = Penjualanproduk::select('kasir')->where('toko_id', 5)->distinct()->get();

    $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
    $total_setoran = $total_penjualan - $total_metode;

    return view('toko_bumiayu.laporan_setoranpenjualan.index', compact(
        'inquery',
        'kasirs',
        'penjualan_kotor',
        'diskon_penjualan',
        'penjualan_bersih',
        'deposit_masuk',
        'total_penjualan',
        'mesin_edc',
        'qris',
        'gobiz',
        'transfer',
        'total_setoran',
        'deposit_keluar'
    ));
}



    public function printReportsetoran(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $kasir = $request->kasir;
    
        // Query dasar untuk mengambil data penjualan produk
        $query = Penjualanproduk::query();
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal penjualan
        if ($tanggal_penjualan && $tanggal_akhir) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
        } else {
            $query->whereDate('tanggal_penjualan', Carbon::today());
        }
    
        // Filter berdasarkan kasir
        if ($kasir) {
            $query->where('kasir', $kasir);
        }
    
        // Urutkan data berdasarkan ID secara descending
        $query->orderBy('id', 'DESC');
    
        // Ambil data penjualan produk dengan relasi
        $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();
    
        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)'));

        
        // Hitung total diskon penjualan (nominal_diskon)
        $diskon_penjualan = $query->sum('nominal_diskon');
    
        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;
    
        // Query terpisah untuk menghitung total deposit_masuk dari tabel dppemesanan berdasarkan kasir pada pemesananproduk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
            if ($tanggal_penjualan && $tanggal_akhir) {
                $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            }
            if ($kasir) {
                $q->where('kasir', $kasir);
            }
        })->sum('dp_pemesanan');
    
          // Query untuk menghitung total deposit keluar dari tabel dppemesanan yang terkait dengan penjualanproduk
    $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($kasir, $tanggal_penjualan, $tanggal_akhir) {
        if ($tanggal_penjualan && $tanggal_akhir) {
            $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }
        // Filter berdasarkan kasir
        if ($kasir) {
            $q->where('kasir', $kasir);
        }
    })->sum('dp_pemesanan');
        // Pastikan variabel $kasir berasal dari request
        $kasir = $request->kasir;
    
        // Filter untuk Mesin EDC berdasarkan kasir
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->where('kasir', $kasir) // Filter berdasarkan kasir
            ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
            ->value('total');
    
        // Filter untuk Qris berdasarkan kasir
        $qris = Penjualanproduk::where('metode_id', 17)
            ->where('kasir', $kasir) // Filter berdasarkan kasir
            ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
            ->value('total');
    
        // Filter untuk GoBiz berdasarkan kasir
        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->where('kasir', $kasir) // Filter berdasarkan kasir
            ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
            ->value('total');
    
        // Filter untuk Transfer berdasarkan kasir
        $transfer = Penjualanproduk::where('metode_id', 3)
            ->where('kasir', $kasir) // Filter berdasarkan kasir
            ->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))
            ->value('total');
    
        // Hitung total penjualan
        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
    
        // Ambil semua data kasir yang unik
        $kasirs = Penjualanproduk::select('kasir')->distinct()->get();

        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;

        // Buat PDF dari view 'print'
        $pdf = FacadePdf::loadView('toko_bumiayu.laporan_setoranpenjualan.print', compact(
            'inquery',
            'kasirs',
            'penjualan_kotor',
            'diskon_penjualan',
            'penjualan_bersih',
            'deposit_masuk',
            'deposit_keluar',
            'total_penjualan',
            'mesin_edc',
            'qris',
            'gobiz',
            'transfer',
            'total_setoran'
        ));
    
        // Atur ukuran kertas dan orientasi
        $pdf->setPaper('a4', 'portrait');
    
        // Tampilkan PDF ke browser
        return $pdf->stream('laporan-penjualan.pdf');
    }
    


    
    


}   