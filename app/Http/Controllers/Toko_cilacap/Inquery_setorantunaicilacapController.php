<?php

namespace App\Http\Controllers\Toko_cilacap;

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
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Inquery_setorantunaicilacapController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter tanggal dari request
        $tanggalPenjualan = $request->input('tanggal_setoran');
        $tanggalAkhir = $request->input('tanggal_akhir');
    
        $setoranPenjualans = Setoran_penjualan::where('toko_id', 6)
            ->when($tanggalPenjualan, function ($query) use ($tanggalPenjualan, $tanggalAkhir) {
                return $query->whereDate('tanggal_setoran', '>=', $tanggalPenjualan)
                             ->whereDate('tanggal_setoran', '<=', $tanggalAkhir ?? $tanggalPenjualan);
            })
            ->orderBy('id', 'DESC')
            ->get();
    
        // Kirim data ke view
        return view('toko_cilacap.inquery_setorantunai.index', compact('setoranPenjualans'));
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

        return view('toko_cilacap.setoran_tokobanjaran.create', compact(
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


    public function getdata(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'tanggal_penjualan' => 'required|date',
        ]);
    
        // Ambil tanggal dari request
        $tanggalPenjualan = $request->input('tanggal_penjualan');
    
        // Query untuk menghitung penjualan kotor
        $penjualan_kotor = Penjualanproduk::whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        // Query untuk menghitung diskon penjualan dari detailpenjualanproduks
        $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
        })->get()->sum(function ($detail) {
            // Menghitung total diskon
            $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga); // Hapus "Rp." dan "." dari harga
            $jumlah = $detail->jumlah;
            $diskon = $detail->diskon / 100; // Ubah diskon persen menjadi desimal
    
            return $harga * $jumlah * $diskon; // Hitung diskon
        });
    
        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;
    
        // Hitung total deposit keluar
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
        })->sum('dp_pemesanan');
    
        // Hitung total deposit masuk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggalPenjualan) {
            $q->whereDate('tanggal_pemesanan', $tanggalPenjualan);
        })->sum('dp_pemesanan');
    
        // Hitung total dari berbagai metode pembayaran
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        $qris = Penjualanproduk::where('metode_id', 17)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        $transfer = Penjualanproduk::where('metode_id', 3)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        // Hitung total penjualan
        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;
    
        // Kembalikan hasil dalam format JSON untuk diproses di frontend
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


    public function store(Request $request)
    {
        // Validasi input dengan custom error messages
        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'penjualan_kotor' => 'required|numeric',
            'diskon_penjualan' => 'required|numeric',
            'penjualan_bersih' => 'required|numeric',
            'deposit_keluar' => 'required|numeric',
            'deposit_masuk' => 'required|numeric',
            'total_penjualan' => 'required|numeric',
            'mesin_edc' => 'required|numeric',
            'qris' => 'required|numeric',
            'gobiz' => 'required|numeric',
            'transfer' => 'required|numeric',
            'total_setoran' => 'required|numeric',
            'tanggal_setoran' => 'required|date',
            'nominal_setoran' => 'required|numeric',
            'plusminus' => 'required|numeric',
        ], [
            'tanggal_penjualan.required' => 'Tanggal penjualan tidak boleh kosong.',
            'penjualan_kotor.required' => 'Penjualan kotor tidak boleh kosong.',
            'diskon_penjualan.required' => 'Diskon penjualan tidak boleh kosong.',
            'penjualan_bersih.required' => 'Penjualan bersih tidak boleh kosong.',
            'deposit_keluar.required' => 'Deposit keluar tidak boleh kosong.',
            'deposit_masuk.required' => 'Deposit masuk tidak boleh kosong.',
            'total_penjualan.required' => 'Total penjualan tidak boleh kosong.',
            'mesin_edc.required' => 'Mesin EDC tidak boleh kosong.',
            'qris.required' => 'QRIS tidak boleh kosong.',
            'gobiz.required' => 'Gobiz tidak boleh kosong.',
            'transfer.required' => 'Transfer tidak boleh kosong.',
            'total_setoran.required' => 'Total setoran tidak boleh kosong.',
            'tanggal_setoran.required' => 'Tanggal setoran tidak boleh kosong.',
            'nominal_setoran.required' => 'Nominal setoran tidak boleh kosong.',
            'plusminus.required' => 'Kolom +/- tidak boleh kosong.',
            'numeric' => ':attribute harus berupa angka.',
            'date' => ':attribute harus berupa tanggal yang valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data ke database
        Setoran_penjualan::create([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'penjualan_kotor' => $request->penjualan_kotor,
            'diskon_penjualan' => $request->diskon_penjualan,
            'penjualan_bersih' => $request->penjualan_bersih,
            'deposit_keluar' => $request->deposit_keluar,
            'deposit_masuk' => $request->deposit_masuk,
            'total_penjualan' => $request->total_penjualan,
            'mesin_edc' => $request->mesin_edc,
            'qris' => $request->qris,
            'gobiz' => $request->gobiz,
            'transfer' => $request->transfer,
            'total_setoran' => $request->total_setoran,
            'tanggal_setoran' => $request->tanggal_setoran,
            'nominal_setoran' => $request->nominal_setoran,
            'plusminus' => $request->plusminus,
            'toko_id' => 1, // Menyimpan toko_id dengan nilai 1

        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    
//     public function print(Request $request)
// {
//     $status = $request->status;
//     $tanggal_penjualan = $request->tanggal_penjualan;
//     $tanggal_akhir = $request->tanggal_akhir;
//     $kasir = $request->kasir; // Kasir yang dipilih dari request
    
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

//     // Filter berdasarkan kasir yang dipilih
//     if ($kasir) {
//         $query->where('kasir', $kasir);
//     }

//     // Urutkan data berdasarkan ID secara descending
//     $query->orderBy('id', 'DESC');

//     // Ambil data penjualan produk dengan relasi
//     $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

//     // Buat query terpisah untuk menghitung total penjualan kotor
//     $penjualan_kotor = Penjualanproduk::select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)) as total'));

//     // Filter berdasarkan kasir
//     if ($kasir) {
//         $penjualan_kotor->where('kasir', $kasir);
//     }

//     // Filter tanggal
//     if ($tanggal_penjualan && $tanggal_akhir) {
//         $penjualan_kotor->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
//     }

//     $penjualan_kotor = $penjualan_kotor->value('total');

//     // Hitung total diskon penjualan berdasarkan kasir dan tanggal_penjualan
//     $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggal_penjualan, $kasir) {
//         $q->whereDate('tanggal_penjualan', $tanggal_penjualan);
        
//         // Filter berdasarkan kasir jika ada
//         if ($kasir) {
//             $q->where('kasir', $kasir);
//         }
//     })->get()->sum(function ($detail) {
//         $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga); // Hapus "Rp." dan "."
//         $jumlah = $detail->jumlah;
//         $diskon = $detail->diskon / 100; // Ubah diskon persen ke desimal

//         return $harga * $jumlah * $diskon;
//     });

//     $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

//     // Query terpisah untuk menghitung total deposit masuk
//     $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
//         if ($tanggal_penjualan && $tanggal_akhir) {
//             $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
//         } elseif ($tanggal_penjualan) {
//             $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
//         } elseif ($tanggal_akhir) {
//             $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
//         }
//         if ($kasir) {
//             $q->where('kasir', $kasir);
//         }
//     })->sum('dp_pemesanan');

//     // Query untuk menghitung total deposit keluar
//     $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($kasir, $tanggal_penjualan, $tanggal_akhir) {
//         if ($tanggal_penjualan && $tanggal_akhir) {
//             $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
//         } elseif ($tanggal_penjualan) {
//             $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
//         } elseif ($tanggal_akhir) {
//             $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
//         }
//         if ($kasir) {
//             $q->where('kasir', $kasir);
//         }
//     })->sum('dp_pemesanan');

//     // Hitung total dari berbagai metode pembayaran
//     $metodePembayaran = function($metode_id, $tanggal_penjualan = null, $tanggal_akhir = null) use ($kasir) {
//         $query = Penjualanproduk::where('metode_id', $metode_id);

//         if ($kasir) {
//             $query->where('kasir', $kasir);
//         }

//         if ($tanggal_penjualan && $tanggal_akhir) {
//             $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
//         } elseif ($tanggal_penjualan) {
//             $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
//         } elseif ($tanggal_akhir) {
//             $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
//         }

//         return $query->select(Penjualanproduk::raw('SUM(CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)) as total'))->value('total');
//     };

//     // Panggil metodePembayaran dengan filter tanggal_penjualan dan tanggal_akhir
//     $mesin_edc = $metodePembayaran(1, $tanggal_penjualan, $tanggal_akhir);
//     $qris = $metodePembayaran(17, $tanggal_penjualan, $tanggal_akhir);
//     $gobiz = $metodePembayaran(2, $tanggal_penjualan, $tanggal_akhir);
//     $transfer = $metodePembayaran(3, $tanggal_penjualan, $tanggal_akhir);

//     $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);

//     // Ambil semua data produk, toko, kasir, klasifikasi untuk dropdown
//     $produks = Produk::all();
//     $tokos = Toko::all();
//     $klasifikasis = Klasifikasi::all();
//     $kasirs = Penjualanproduk::select('kasir')->distinct()->get();

//     // Hitung total metode dan setoran
//     $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
//     $total_setoran = $total_penjualan - $total_metode;

//     // Return PDF dengan data yang diperlukan
//     $pdf = FacadePdf::loadView('toko_cilacap.inquery_setorantunai.print', compact(
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
//         'deposit_keluar',
//         'kasir' // Pastikan kasir yang dipilih juga dikirim ke view
//     ));
    
//     return $pdf->stream('laporan_setoran_penjualan.pdf');
// }

    public function print($id)
    {
        // Ambil data setoran penjualan berdasarkan id yang dipilih
        $setoranPenjualans = Setoran_penjualan::findOrFail($id);

        // Load view untuk PDF dan kirimkan data
        $pdf = FacadePdf::loadView('toko_cilacap.inquery_setorantunai.print', compact('setoranPenjualans'));

        // Return PDF stream agar langsung bisa ditampilkan
        return $pdf->stream('setoran_penjualan.pdf');
    }

    

    }

    


