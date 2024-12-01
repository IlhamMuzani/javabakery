<?php

namespace App\Http\Controllers\Toko_bumiayu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use Illuminate\Support\Facades\DB;
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
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Setoran_tokobumiayuController extends Controller
{

    public function index(Request $request)
    {
        $tanggalPenjualan = $request->input('tanggal_penjualan');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $setoranPenjualans = Setoran_penjualan::where('toko_id', 5) 
            ->when($tanggalPenjualan, function ($query) use ($tanggalPenjualan, $tanggalAkhir) {
                return $query->whereDate('tanggal_setoran', '>=', $tanggalPenjualan)
                            ->whereDate('tanggal_setoran', '<=', $tanggalAkhir ?? $tanggalPenjualan);
            })
            ->orderBy('id', 'asc')
            ->get();

        // Kirim data ke view
        return view('toko_bumiayu.setoran_tokobumiayu.index', compact('setoranPenjualans'));
    }
    
    
    public function create(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $kasir = $request->kasir;

        // Ambil semua data produk, toko, kasir, klasifikasi untuk dropdown
        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $kasirs = Penjualanproduk::select('kasir')->distinct()->get();

        // Buat query dasar untuk menghitung total penjualan kotor
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
        }

        // Filter berdasarkan kasir
        if ($kasir) {
            $query->where('kasir', $kasir);
        }

        // Hitung total penjualan kotor
        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)'));

        // Hitung total diskon penjualan (nominal_diskon)
        $diskon_penjualan = $query->sum('nominal_diskon');

        // Hitung penjualan bersih
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

        // Hitung total deposit keluar
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

        // Hitung total dari berbagai metode pembayaran
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $qris = Penjualanproduk::where('metode_id', 17)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $transfer = Penjualanproduk::where('metode_id', 3)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;

        return view('toko_bumiayu.setoran_tokobumiayu.create', compact(
            'produks',
            'tokos',
            'klasifikasis',
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

    public function getdatabumiayu(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'tanggal_penjualan' => 'required|date',
        ]);

        // Ambil tanggal dan toko_id dari request
        $tanggalPenjualan = Carbon::parse($request->input('tanggal_penjualan'))->startOfDay();
        $tokoId = $request->input('toko_id');

        // Query untuk menghitung penjualan kotor dengan filter toko
        $query = Penjualanproduk::whereDate('tanggal_penjualan', $tanggalPenjualan);
        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }

        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));

        // Menghitung diskon penjualan
        $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
            if ($tokoId) {
                $q->where('toko_id', $tokoId);
            }
        })->get()->sum(function ($detail) {
            $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga);
            $jumlah = $detail->jumlah;
            $diskon = $detail->diskon / 100;

            return $harga * $jumlah * $diskon;
        });

        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

        // Hitung total deposit keluar
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
            if ($tokoId) {
                $q->where('toko_id', $tokoId);
            }
        })->sum('dp_pemesanan');

        // Hitung total deposit masuk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_pemesanan', $tanggalPenjualan);
            if ($tokoId) {
                $q->where('toko_id', $tokoId);
            }
        })->sum('dp_pemesanan');

        $metodePembayaran = function ($metode_id, $tanggalPenjualan) use ($tokoId) {
            // Query untuk penjualan produk
            $queryPenjualan = Penjualanproduk::where('metode_id', $metode_id)
                ->whereDate('tanggal_penjualan', $tanggalPenjualan);
    
            if ($tokoId) {
                $queryPenjualan->where('toko_id', $tokoId);
            }
    
            // Perhitungan total penjualan
            if ($metode_id == 1) {
                $totalPenjualan = $queryPenjualan->select(Penjualanproduk::raw(
                    'SUM(CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED) - CAST(REGEXP_REPLACE(REPLACE(nominal_diskon, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'
                ))->value('total');
            } else {
                $totalPenjualan = $queryPenjualan->select(Penjualanproduk::raw(
                    'SUM(CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)) as total'
                ))->value('total');
            }
    
            // Query untuk dp pemesanan
            $totalPemesanan = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($metode_id, $tanggalPenjualan, $tokoId) {
                $q->where('metode_id', $metode_id)
                    ->whereDate('tanggal_pemesanan', $tanggalPenjualan);
    
                if ($tokoId) {
                    $q->where('toko_id', $tokoId);
                }
            })->sum('dp_pemesanan');
    
            return $totalPenjualan + $totalPemesanan;
        };
    
        // Panggil metodePembayaran dengan metode_id yang relevan
        $mesin_edc = $metodePembayaran(1, $tanggalPenjualan);
        $qris = $metodePembayaran(17, $tanggalPenjualan);
        $gobiz = $metodePembayaran(2, $tanggalPenjualan);
        $transfer = $metodePembayaran(3, $tanggalPenjualan);
    
        // Hitung total penjualan
        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;
    
        return response()->json([
            'penjualan_kotor' => number_format($penjualan_kotor, 0, ',', '.'),
            'diskon_penjualan' => number_format($diskon_penjualan, 0, ',', '.'),
            'penjualan_bersih' => number_format($penjualan_bersih, 0, ',', '.'),
            'deposit_keluar' => number_format($deposit_keluar, 0, ',', '.'),
            'deposit_masuk' => number_format($deposit_masuk, 0, ',', '.'),
            'mesin_edc' => number_format($mesin_edc, 0, ',', '.'),
            'qris' => number_format($qris, 0, ',', '.'),
            'gobiz' => number_format($gobiz, 0, ',', '.'),
            'transfer' => number_format($transfer, 0, ',', '.'),
            'total_penjualan' => number_format($total_penjualan, 0, ',', '.'),
            'total_metode' => number_format($total_metode, 0, ',', '.'),
            'total_setoran' => number_format($total_setoran, 0, ',', '.'),
        ]);
    }
    
    public function printPenjualanKotorbmy(Request $request)
    {
        // Ambil parameter tanggal_penjualan dan toko_id dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
        $toko_id = $request->get('toko_id'); // Mengambil toko_id dari query string

        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }

        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id); // Filter berdasarkan toko_id
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Filter berdasarkan tanggal
            ->orderBy('tanggal_penjualan', 'asc'); // Urutkan berdasarkan tanggal

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;

                if ($produk) {
                    $key = $produk->id; // Menggunakan ID produk sebagai key
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0,
                            'penjualan_bersih' => 0,
                        ];
                    }

                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;

                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10;
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }

                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }

        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });

        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }

        // Menampilkan halaman biasa (bukan PDF)
        return view('toko_bumiayu.setoran_tokobumiayu.printpenjualantoko', [
            'finalResults' => $finalResults,
            'startDate' => $tanggal_penjualan,
            'branchName' => $branchName,
        ]);
    }

    public function printFakturPenjualanbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Query data berdasarkan filter
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                return $query->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan));
            })
            ->orderBy('tanggal_penjualan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_penjualan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturpenjualantoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturdepositKeluarbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');

        // Query data berdasarkan filter
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->whereNotNull('dppemesanan_id') // Tambahkan kondisi ini
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                return $query->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan));
            })
            ->orderBy('tanggal_penjualan', 'asc');

        $inquery = $query->get();

        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }

        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_penjualan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal

        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturdepositkeluartoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);

        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPenjualanMesinedcbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Query data berdasarkan filter
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->where('metode_id', 1) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                return $query->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan));
            })
            ->orderBy('tanggal_penjualan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_penjualan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturpenjualantoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPemesananMesinedcbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Jika tanggal_penjualan tidak diisi, gunakan tanggal_pemesanan sebagai fallback
        $tanggal_pemesanan = $tanggal_penjualan ?: $request->get('tanggal_pemesanan');
    
        // Query data berdasarkan filter
        $query = Pemesananproduk::with('detailPemesananProduk.produk')
            ->where('metode_id', 1) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_pemesanan, function ($query, $tanggal_pemesanan) {
                return $query->whereDate('tanggal_pemesanan', Carbon::parse($tanggal_pemesanan));
            })
            ->orderBy('tanggal_pemesanan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_pemesanan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturdepositmasuktoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }
    
    public function printFakturPenjualanQrisbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Query data berdasarkan filter
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->where('metode_id', 17) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                return $query->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan));
            })
            ->orderBy('tanggal_penjualan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_penjualan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturpenjualantoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPemesananQrisbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Jika tanggal_penjualan tidak diisi, gunakan tanggal_pemesanan sebagai fallback
        $tanggal_pemesanan = $tanggal_penjualan ?: $request->get('tanggal_pemesanan');
    
        // Query data berdasarkan filter
        $query = Pemesananproduk::with('detailPemesananProduk.produk')
            ->where('metode_id', 17) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_pemesanan, function ($query, $tanggal_pemesanan) {
                return $query->whereDate('tanggal_pemesanan', Carbon::parse($tanggal_pemesanan));
            })
            ->orderBy('tanggal_pemesanan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_pemesanan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturdepositmasuktoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPenjualanTransferbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Query data berdasarkan filter
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->where('metode_id', 3) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                return $query->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan));
            })
            ->orderBy('tanggal_penjualan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_penjualan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturpenjualantoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPemesananTransferbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Jika tanggal_penjualan tidak diisi, gunakan tanggal_pemesanan sebagai fallback
        $tanggal_pemesanan = $tanggal_penjualan ?: $request->get('tanggal_pemesanan');
    
        // Query data berdasarkan filter
        $query = Pemesananproduk::with('detailPemesananProduk.produk')
            ->where('metode_id', 3) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_pemesanan, function ($query, $tanggal_pemesanan) {
                return $query->whereDate('tanggal_pemesanan', Carbon::parse($tanggal_pemesanan));
            })
            ->orderBy('tanggal_pemesanan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_pemesanan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturdepositmasuktoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPenjualanGobizbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Query data berdasarkan filter
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->where('metode_id', 2) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                return $query->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan));
            })
            ->orderBy('tanggal_penjualan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_penjualan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturpenjualantoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturPemesananGobizbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Jika tanggal_penjualan tidak diisi, gunakan tanggal_pemesanan sebagai fallback
        $tanggal_pemesanan = $tanggal_penjualan ?: $request->get('tanggal_pemesanan');
    
        // Query data berdasarkan filter
        $query = Pemesananproduk::with('detailPemesananProduk.produk')
            ->where('metode_id', 2) // Tambahkan filter untuk metode_id = 1
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_pemesanan, function ($query, $tanggal_pemesanan) {
                return $query->whereDate('tanggal_pemesanan', Carbon::parse($tanggal_pemesanan));
            })
            ->orderBy('tanggal_pemesanan', 'asc');
    
        $inquery = $query->get();
    
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal (hanya satu tanggal)
        $startDate = $tanggal_pemesanan; 
        $endDate = null; // Tidak ada tanggal akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturdepositmasuktoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }

    public function printFakturdepositMasukbmy(Request $request)
    {
        $tanggal_penjualan = $request->get('tanggal_penjualan');
        $toko_id = $request->get('toko_id');
    
        // Jika tanggal_penjualan tidak diisi, gunakan tanggal_pemesanan sebagai fallback
        $tanggal_pemesanan = $tanggal_penjualan ?: $request->get('tanggal_pemesanan');
    
        // Query data berdasarkan filter
        $query = Pemesananproduk::with(['detailpemesananproduk.produk', 'dppemesanan'])
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_pemesanan, function ($query, $tanggal_pemesanan) {
                return $query->whereDate('tanggal_pemesanan', Carbon::parse($tanggal_pemesanan));
            })
            ->orderBy('tanggal_pemesanan', 'asc');

        $inquery = $query->get();

    
        // Menentukan nama cabang/toko
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Set periode tanggal
        $startDate = $tanggal_pemesanan; // Menggunakan tanggal_pemesanan sebagai pengganti tanggal_penjualan
        $endDate = null; // Tidak ada rentang akhir karena hanya satu tanggal
    
        // Buat PDF
        return view('toko_bumiayu.setoran_tokobumiayu.printfakturdepositmasuktoko', [
            'inquery' => $inquery,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'branchName' => $branchName,
        ]);
    
        return $pdf->stream('faktur_penjualan.pdf');
    }
      
    public function printPenjualanDiskonbmy(Request $request)
    {
        // Ambil parameter tanggal_penjualan dan toko_id dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
        $toko_id = $request->get('toko_id'); // Mengambil toko_id dari query string

        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }

        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id); // Filter berdasarkan toko_id
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Filter berdasarkan tanggal
            ->orderBy('tanggal_penjualan', 'asc'); // Urutkan berdasarkan tanggal

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;

                if ($produk) {
                    $key = $produk->id; // Menggunakan ID produk sebagai key
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0,
                            'penjualan_bersih' => 0,
                        ];
                    }

                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;

                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10;
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }

                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }

        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });

        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }

        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
        $pdf = FacadePdf::loadView('toko_bumiayu.setoran_tokobumiayu.printpenjualantoko', [
            'finalResults' => $finalResults,
            'startDate' => $tanggal_penjualan,
            'branchName' => $branchName,
        ]);

        return $pdf->stream('laporan_penjualan_produk.pdf');
    }

    public function printPenjualanBersihbmy(Request $request)
    {
        // Ambil parameter tanggal_penjualan dan toko_id dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
        $toko_id = $request->get('toko_id'); // Mengambil toko_id dari query string

        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }

        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id); // Filter berdasarkan toko_id
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Filter berdasarkan tanggal
            ->orderBy('tanggal_penjualan', 'asc'); // Urutkan berdasarkan tanggal

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;

                if ($produk) {
                    $key = $produk->id; // Menggunakan ID produk sebagai key
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0,
                            'penjualan_bersih' => 0,
                        ];
                    }

                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;

                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10;
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }

                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }

        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });

        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }

        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
        $pdf = FacadePdf::loadView('toko_bumiayu.setoran_tokobumiayu.printpenjualantoko', [
            'finalResults' => $finalResults,
            'startDate' => $tanggal_penjualan,
            'branchName' => $branchName,
        ]);

        return $pdf->stream('laporan_penjualan_produk.pdf');
    }


    public function show($id)
    {
        $penjualan = Penjualanproduk::with('toko', 'metodepembayaran')->findOrFail($id); // Eager load relasi
        $pelanggans = Pelanggan::all();
        $tokos = $penjualan->toko;
    
        $pdf = FacadePdf::loadView('toko_bumiayu.setoran_tokobumiayu.detail', compact('penjualan', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('penjualan.pdf');
    }

    public function show1($id)
    {
        $pemesanan = Pemesananproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();
        
        $dp = $pemesanan->dppemesanan;
        $tokos = $pemesanan->toko;
    
        $pdf = FacadePdf::loadView('toko_bumiayu.setoran_tokobumiayu.detailpemesanan', compact('pemesanan', 'tokos', 'pelanggans','dp'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('pemesanan.pdf');
    }

    public function show2($id)
    {
        $inquery = Penjualanproduk::with(['toko', 'metodepembayaran', 'dppemesanan.pemesananproduk', 'pelunasan'])
                    ->findOrFail($id); // Eager load relasi pelunasan
        $pelanggans = Pelanggan::all();
        $tokos = $inquery->toko;
    
        $pdf = FacadePdf::loadView('toko_bumiayu.setoran_tokobumiayu.detaildepositkeluar', compact('inquery', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('penjualan.pdf');
    }


    public function store(Request $request)
    {
        // Validasi input dengan custom error messages
        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'total_setoran' => 'required',
            'tanggal_setoran' => 'required|date',
            'nominal_setoran' => 'required',
            'toko_id' => 'required|exists:tokos,id', // Validasi bahwa toko_id harus ada di tabel tokos
        ], [
            // Custom error messages
            'tanggal_penjualan.required' => 'Tanggal penjualan tidak boleh kosong.',
            'total_setoran.required' => 'Total setoran tidak boleh kosong.',
            'tanggal_setoran.required' => 'Tanggal setoran tidak boleh kosong.',
            'nominal_setoran.required' => 'Nominal setoran tidak boleh kosong.',
            'toko_id.required' => 'Toko harus dipilih.',
            'toko_id.exists' => 'Toko yang dipilih tidak valid.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Fungsi untuk menghilangkan format angka
        $removeFormat = function ($value) {
            return (int)str_replace(['.', ','], '', $value); // Hilangkan titik dan koma
        };
    
        // Simpan data ke database
        $setoran = Setoran_penjualan::create([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'penjualan_kotor' => $removeFormat($request->penjualan_kotor),
            'diskon_penjualan' => $removeFormat($request->diskon_penjualan),
            'penjualan_bersih' => $removeFormat($request->penjualan_bersih),
            'deposit_keluar' => $removeFormat($request->deposit_keluar),
            'deposit_masuk' => $removeFormat($request->deposit_masuk),
            'total_penjualan' => $removeFormat($request->total_penjualan),
            'mesin_edc' => $removeFormat($request->mesin_edc),
            'qris' => $removeFormat($request->qris),
            'gobiz' => $removeFormat($request->gobiz),
            'transfer' => $removeFormat($request->transfer),
            'total_setoran' => $removeFormat($request->total_setoran),
            'tanggal_setoran' => $request->tanggal_setoran,
            'tanggal_setoran2' => $request->tanggal_setoran2,
            'nominal_setoran' => $removeFormat($request->nominal_setoran),
            'nominal_setoran2' => $removeFormat($request->nominal_setoran2),
            'plusminus' => $removeFormat($request->plusminus),
            'toko_id' => 5, 
            'status' => 'posting',
            'no_fakturpenjualantoko' => $this->kode(), 
        ]);
    
        // Update status penjualanproduk menjadi 'selesai' berdasarkan toko_id dan tanggal_penjualan
        Penjualanproduk::where('toko_id', $request->toko_id)
            ->whereDate('tanggal_penjualan', $request->tanggal_penjualan)
            ->update(['status' => 'selesai']);
    
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('setoran_tokobumiayu.index')->with('success', 'Data berhasil disimpan dan status penjualan berhasil diperbarui!');
    }

    public function kode()
    {
        $prefix = 'FTF';
    
        $year = date('y'); // Tahun dua digit
        $monthDay = date('dm'); // Bulan dan tanggal
    
        // Cari kode terakhir berdasarkan prefix dan tanggal
        $lastBarang = Setoran_penjualan::where('no_fakturpenjualantoko', 'LIKE', $prefix . $monthDay . $year . '%')
                                        ->orderBy('no_fakturpenjualantoko', 'desc') // Urutkan dari yang terbaru
                                        ->first();
    
        // Tentukan urutan berikutnya
        if (!$lastBarang) {
            $num = 1; // Jika belum ada kode, mulai dari 1
        } else {
            $lastCode = $lastBarang->no_fakturpenjualantoko;
            $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Ambil nomor urut terakhir
            $num = $lastNum + 1; // Tambahkan 1 untuk nomor urut berikutnya
        }
    
        $formattedNum = sprintf("%04d", $num); // Format menjadi 4 digit
        $newCode = $prefix . $monthDay . $year . $formattedNum;
    
        return $newCode;
    }
    }

    


