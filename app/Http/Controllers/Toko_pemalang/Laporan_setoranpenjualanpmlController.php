<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Models\Detailpenjualanproduk;
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





class Laporan_setoranpenjualanpmlController extends Controller
{
    
    
    
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

    // Filter berdasarkan kasir, hanya jika kasir dipilih
    if ($kasir) {
        $query->where('kasir', $kasir);
    } else {
        // Jika tidak memilih kasir, maka ambil data dengan toko_id = 1
        $query->where('toko_id', 4);
    }

    // Urutkan data berdasarkan ID secara descending
    $query->orderBy('id', 'DESC');

    // Ambil data penjualan produk
    $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

    // Buat query terpisah untuk menghitung total penjualan kotor
    $queryPenjualanKotor = Penjualanproduk::select(Penjualanproduk::raw(
        'SUM(CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'
    ));
    
    // Terapkan filter berdasarkan kasir atau toko_id
    if ($kasir) {
        $queryPenjualanKotor->where('kasir', $kasir);
    } else {
        $queryPenjualanKotor->where('toko_id', 4);
    }
    
    // Terapkan filter berdasarkan tanggal penjualan
    if ($tanggal_penjualan && $tanggal_akhir) {
        $queryPenjualanKotor->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $queryPenjualanKotor->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $queryPenjualanKotor->where('tanggal_penjualan', '<=', $tanggal_akhir);
    }
    
    // Ambil nilai total dari query penjualan kotor
    $penjualan_kotor = $queryPenjualanKotor->value('total');


    $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
        // Terapkan filter tanggal penjualan
        if ($tanggal_penjualan && $tanggal_akhir) {
            $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }

        // Filter berdasarkan kasir jika ada
        if ($kasir) {
            $q->where('kasir', $kasir);
        } else {
            // Jika tidak memilih kasir, maka ambil data dengan toko_id = 4
            $q->where('toko_id', 4);
        }
    })->get()->sum(function ($detail) {
        $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga); // Hapus "Rp." dan "."
        $jumlah = $detail->jumlah;
        $diskon = $detail->diskon / 100; // Ubah diskon persen ke desimal

        return $harga * $jumlah * $diskon;
    });

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
        } else {
            // Jika tidak memilih kasir, maka ambil data dengan toko_id = 4
            $q->where('toko_id', 4);
        }
    })->sum('dp_pemesanan');

    // Query untuk menghitung total deposit keluar
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
        } else {
            $q->where('toko_id', 4);
        }
    })->sum('dp_pemesanan');

    // Hitung total dari berbagai metode pembayaran
    $metodePembayaran = function($metode_id, $tanggal_penjualan = null, $tanggal_akhir = null) use ($kasir) {
        // Query untuk penjualan produk
        $queryPenjualan = Penjualanproduk::where('metode_id', $metode_id);
    
        if ($kasir) {
            $queryPenjualan->where('kasir', $kasir);
        } else {
            $queryPenjualan->where('toko_id', 4);
        }
    
        if ($tanggal_penjualan && $tanggal_akhir) {
            $queryPenjualan->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $queryPenjualan->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $queryPenjualan->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }
    
        // Perhitungan total penjualan
        if ($metode_id == 1) {
            $totalPenjualan = $queryPenjualan
                ->select(Penjualanproduk::raw('SUM(CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED) - CAST(REGEXP_REPLACE(REPLACE(nominal_diskon, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'))
                ->value('total');
        } else {
            $totalPenjualan = $queryPenjualan
                ->select(Penjualanproduk::raw('SUM(CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'))
                ->value('total');
        }
    
        // Query untuk pemesanan produk, namun menggunakan dp_pemesanan dari tabel dppemesanan
        $queryPemesanan = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($metode_id, $kasir, $tanggal_penjualan, $tanggal_akhir) {
            $q->where('metode_id', $metode_id);
    
            if ($kasir) {
                $q->where('kasir', $kasir);
            } else {
                $q->where('toko_id', 4);
            }
    
            if ($tanggal_penjualan && $tanggal_akhir) {
                $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            }
        });
    
        // Perhitungan total dp_pemesanan
        $totalPemesanan = $queryPemesanan->sum('dp_pemesanan');
    
        return $totalPenjualan + $totalPemesanan;
    };


    // Panggil metodePembayaran dengan filter tanggal_penjualan dan tanggal_akhir
    $mesin_edc = $metodePembayaran(1, $tanggal_penjualan, $tanggal_akhir);
    $qris = $metodePembayaran(17, $tanggal_penjualan, $tanggal_akhir);
    $gobiz = $metodePembayaran(2, $tanggal_penjualan, $tanggal_akhir);
    $transfer = $metodePembayaran(3, $tanggal_penjualan, $tanggal_akhir);

    $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);

    // Ambil semua data produk, toko, kasir, klasifikasi untuk dropdown
    $produks = Produk::all();
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();

    $kasirs = Penjualanproduk::select('kasir')
        ->where('toko_id', 4) 
        ->distinct()
        ->get();

    // Hitung total metode dan setoran
    $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
    $total_setoran = $total_penjualan - $total_metode;

    // Kembalikan view dengan data yang diperlukan
    return view('toko_pemalang.laporan_setoranpenjualan.index', compact(
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

    public function printReportsetoranpml(Request $request)
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

        // Filter berdasarkan kasir yang dipilih
        if ($kasir) {
            $query->where('kasir', $kasir);
        }else {
            // Jika tidak memilih kasir, maka ambil data dengan toko_id = 4
            $query->where('toko_id', 4);
        }

        // Urutkan data berdasarkan ID secara descending
        $query->orderBy('id', 'DESC');

        // Ambil data penjualan produk dengan relasi
        $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

        // Buat query terpisah untuk menghitung total penjualan kotor
        $queryPenjualanKotor = Penjualanproduk::select(Penjualanproduk::raw(
            'SUM(CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'
        ));
        
        // Terapkan filter berdasarkan kasir atau toko_id
        if ($kasir) {
            $queryPenjualanKotor->where('kasir', $kasir);
        } else {
            $queryPenjualanKotor->where('toko_id', 4);
        }
        
        // Terapkan filter berdasarkan tanggal penjualan
        if ($tanggal_penjualan && $tanggal_akhir) {
            $queryPenjualanKotor->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $queryPenjualanKotor->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $queryPenjualanKotor->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }
        
        // Ambil nilai total dari query penjualan kotor
        $penjualan_kotor = $queryPenjualanKotor->value('total');

        // Hitung total diskon penjualan berdasarkan kasir dan tanggal_penjualan
        // Hitung total diskon penjualan berdasarkan kasir dan range tanggal_penjualan
$diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
    // Terapkan filter tanggal penjualan
    if ($tanggal_penjualan && $tanggal_akhir) {
        $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
    }

    // Filter berdasarkan kasir jika ada
    if ($kasir) {
        $q->where('kasir', $kasir);
    } else {
        // Jika tidak memilih kasir, maka ambil data dengan toko_id = 4
        $q->where('toko_id', 4);
    }
})->get()->sum(function ($detail) {
    $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga); // Hapus "Rp." dan "."
    $jumlah = $detail->jumlah;
    $diskon = $detail->diskon / 100; // Ubah diskon persen ke desimal

    return $harga * $jumlah * $diskon;
});

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
            }else {
                // Jika tidak memilih kasir, maka ambil data dengan toko_id = 4
                $q->where('toko_id', 4);
            }
        })->sum('dp_pemesanan');

        // Query untuk menghitung total deposit keluar
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
            }else {
                // Jika tidak memilih kasir, maka ambil data dengan toko_id = 4
                $q->where('toko_id', 4);
            }
        })->sum('dp_pemesanan');

        $metodePembayaran = function($metode_id, $tanggal_penjualan = null, $tanggal_akhir = null) use ($kasir) {
            // Query untuk penjualan produk
            $queryPenjualan = Penjualanproduk::where('metode_id', $metode_id);
        
            if ($kasir) {
                $queryPenjualan->where('kasir', $kasir);
            } else {
                $queryPenjualan->where('toko_id', 4);
            }
        
            if ($tanggal_penjualan && $tanggal_akhir) {
                $queryPenjualan->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $queryPenjualan->where('tanggal_penjualan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $queryPenjualan->where('tanggal_penjualan', '<=', $tanggal_akhir);
            }
        
            // Perhitungan total penjualan
            if ($metode_id == 1) {
                $totalPenjualan = $queryPenjualan
                    ->select(Penjualanproduk::raw('SUM(CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED) - CAST(REGEXP_REPLACE(REPLACE(nominal_diskon, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'))
                    ->value('total');
            } else {
                $totalPenjualan = $queryPenjualan
                    ->select(Penjualanproduk::raw('SUM(CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'))
                    ->value('total');
            }
        
            // Query untuk pemesanan produk, namun menggunakan dp_pemesanan dari tabel dppemesanan
            $queryPemesanan = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($metode_id, $kasir, $tanggal_penjualan, $tanggal_akhir) {
                $q->where('metode_id', $metode_id);
        
                if ($kasir) {
                    $q->where('kasir', $kasir);
                } else {
                    $q->where('toko_id', 4);
                }
        
                if ($tanggal_penjualan && $tanggal_akhir) {
                    $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
                } elseif ($tanggal_penjualan) {
                    $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
                } elseif ($tanggal_akhir) {
                    $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
                }
            });
        
            // Perhitungan total dp_pemesanan
            $totalPemesanan = $queryPemesanan->sum('dp_pemesanan');
        
            return $totalPenjualan + $totalPemesanan;
        };
        

        $mesin_edc = $metodePembayaran(1, $tanggal_penjualan, $tanggal_akhir);
        $qris = $metodePembayaran(17, $tanggal_penjualan, $tanggal_akhir);
        $gobiz = $metodePembayaran(2, $tanggal_penjualan, $tanggal_akhir); 
        $transfer = $metodePembayaran(3, $tanggal_penjualan, $tanggal_akhir); 

        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);

        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $kasirs = Penjualanproduk::select('kasir')->distinct()->get();

        // Hitung total metode dan setoran
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;

        // Return PDF dengan data yang diperlukan
        $pdf = FacadePdf::loadView('toko_pemalang.laporan_setoranpenjualan.print', compact(
            'inquery',
            'kasir',
            'penjualan_kotor',
            'penjualan_bersih',
            'diskon_penjualan',
            'total_penjualan',
            'deposit_masuk',
            'deposit_keluar',
            'mesin_edc',
            'qris',
            'gobiz',
            'transfer',
            'total_metode',
            'total_setoran'
        ));

        return $pdf->stream('laporan-setoran-penjualan.pdf');
    }


}   