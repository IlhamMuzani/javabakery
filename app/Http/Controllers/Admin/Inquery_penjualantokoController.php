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





class Inquery_penjualantokoController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter tanggal dari request
        $tanggalPenjualan = $request->input('tanggal_setoran');
        $tanggalAkhir = $request->input('tanggal_akhir');
    
        // Ambil semua data setoran penjualan dengan filter tanggal jika ada
        $setoranPenjualans = Setoran_penjualan::when($tanggalPenjualan, function ($query) use ($tanggalPenjualan, $tanggalAkhir) {
            return $query->whereDate('tanggal_setoran', '>=', $tanggalPenjualan)
                         ->whereDate('tanggal_setoran', '<=', $tanggalAkhir ?? $tanggalPenjualan);
        })
        ->orderBy('id', 'DESC')
        ->get();
    
        // Kirim data ke view
        return view('admin.inquery_penjualantoko.index', compact('setoranPenjualans'));
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

        return view('admin.setoran_tokobanjaran.create', compact(
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


    public function print($id)
{
    // Ambil data setoran penjualan berdasarkan id yang dipilih
    $setoran = Setoran_penjualan::with('toko')->findOrFail($id);

    // Pastikan data toko terkait tersedia
    $cabang = $setoran->toko->nama_toko?? 'Cabang Tidak Diketahui';
    $alamat = $setoran->toko->alamat?? 'Cabang Tidak Diketahui';

    // Load view untuk PDF dan kirimkan data
    $pdf = FacadePdf::loadView('admin.inquery_penjualantoko.print', compact('setoran', 'cabang','alamat'));

    // Return PDF stream agar langsung bisa ditampilkan
    return $pdf->stream('setoran_penjualan.pdf');
}


    

    public function printPenjualanKotor(Request $request) 
    {
        // Ambil parameter tanggal_penjualan dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
    
        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }
    
        // Ambil filter lain seperti status, toko_id, dll.
        $status = $request->get('status');
        $toko_id = $request->get('toko_id');
        $produk_id = $request->get('produk');
    
        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Pastikan menggunakan whereDate
            ->orderBy('tanggal_penjualan', 'desc');
    
        $inquery = $query->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;
    
                // Pastikan produk tidak null sebelum mengakses properti dan cocok dengan filter produk_id
                if ($produk && (!$produk_id || $produk->id == $produk_id)) {
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
    
                    // Jumlahkan jumlah dan total
                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;
    
                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
    
                    // Kalkulasi penjualan bersih (penjualan kotor - diskon)
                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }
    
        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });
    
        // Ambil data untuk filter
        $tokos = Toko::all(); // Model untuk tabel toko
        $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi
        $produks = Produk::all(); // Model untuk tabel produk
    
        // Dapatkan nama toko berdasarkan toko_id
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Pass raw dates ke view
        $startDate = $tanggal_penjualan;
    
        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
        $pdf = FacadePdf::loadView('admin.penjualan_toko.printpenjualankotor', [
            'finalResults' => $finalResults,
            'startDate' => $startDate,
            'branchName' => $branchName,
            'tokos' => $tokos,
            'produks' => $produks, // Tambahkan data produk
            'klasifikasis' => $klasifikasis,
        ]);
    
        // Output PDF ke browser
        return $pdf->stream('laporan_penjualan_produk.pdf');
    }



    public function unpost_penjualantoko($id)
    {
        // Ambil data setoran_penjualan berdasarkan ID
        $setoranPenjualan = Setoran_penjualan::where('id', $id)->first();
    
        if ($setoranPenjualan) {
            // Update status pada setoran_penjualan menjadi 'unpost'
            $setoranPenjualan->update([
                'status' => 'unpost',
            ]);
    
            // Update status pada penjualanproduk
            $affectedRows = Penjualanproduk::where('toko_id', $setoranPenjualan->toko_id)
                ->whereDate('tanggal_penjualan', $setoranPenjualan->tanggal_penjualan) // Gunakan whereDate
                ->update(['status' => 'posting']);
    
            // Periksa apakah data berhasil diperbarui
            if ($affectedRows > 0) {
                return back()->with('success', 'Status berhasil diubah menjadi unpost, dan status penjualanproduk diubah menjadi posting.');
            } else {
                return back()->with('error', 'Tidak ada data penjualanproduk yang sesuai untuk diperbarui.');
            }
        }
    
        return back()->with('error', 'Data setoran tidak ditemukan.');
    }
    
    public function posting_penjualantoko($id)
    {
        // Ambil data setoran_penjualan berdasarkan ID
        $setoranPenjualan = Setoran_penjualan::where('id', $id)->first();
    
        if ($setoranPenjualan) {
            // Update status pada setoran_penjualan menjadi 'unpost'
            $setoranPenjualan->update([
                'status' => 'posting',
            ]);
    
            // Update status pada penjualanproduk
            $affectedRows = Penjualanproduk::where('toko_id', $setoranPenjualan->toko_id)
                ->whereDate('tanggal_penjualan', $setoranPenjualan->tanggal_penjualan) // Gunakan whereDate
                ->update(['status' => 'selesai']);
    
            // Periksa apakah data berhasil diperbarui
            if ($affectedRows > 0) {
                return back()->with('success', 'Status berhasil diubah menjadi unpost, dan status penjualanproduk diubah menjadi posting.');
            } else {
                return back()->with('error', 'Tidak ada data penjualanproduk yang sesuai untuk diperbarui.');
            }
        }
    
        return back()->with('error', 'Data setoran tidak ditemukan.');
    }

    

    }

    


