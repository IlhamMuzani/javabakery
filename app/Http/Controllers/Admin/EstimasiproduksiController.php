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
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Detailestimasiproduksi;
use App\Models\Estimasiproduksi;
use Maatwebsite\Excel\Facades\Excel;




class EstimasiproduksiController extends Controller{


    // public function index(Request $request)
    // {
    //     $status = $request->status;
    //     $tanggal = $request->tanggal;
    //     $toko_id = $request->toko_id;

    //     // Cek apakah filter tanggal atau toko dipilih
    //     if (!$tanggal && !$toko_id) {
    //         // Jika tidak ada filter tanggal atau toko, tampilkan view tanpa data
    //         $permintaanProduks = collect(); // Kosongkan data
    //         $pemesananProduks = collect();  // Kosongkan data
    //     } else {
    //         // Memulai query dari Permintaanproduk dan Pemesananproduk
    //         $inquery = Permintaanproduk::query();
    //         $inquery1 = Pemesananproduk::query();

    //         // Filter berdasarkan tanggal untuk Permintaanproduk
    //         if ($tanggal) {
    //             $tanggal = Carbon::parse($tanggal)->startOfDay();
    //             $inquery->whereHas('detailpermintaanproduks', function($query) use ($tanggal) {
    //                 $query->whereDate('tanggal_permintaan', $tanggal);
    //             });

    //             // Filter berdasarkan tanggal untuk Pemesananproduk (satu hari setelah tanggal yang dipilih)
    //             $tanggalPemesanan = Carbon::parse($tanggal)->addDay(); // Menambahkan satu hari
    //             $inquery1->whereDate('tanggal_kirim', $tanggalPemesanan);
    //         }

    //         // Filter berdasarkan toko_id pada tabel detailpermintaanproduks
    //         if ($toko_id) {
    //             $inquery->whereHas('detailpermintaanproduks', function($query) use ($toko_id) {
    //                 $query->where('toko_id', $toko_id);
    //             });

    //             // Filter berdasarkan toko_id pada pemesanan produk
    //             $inquery1->whereHas('detailpemesananproduk', function($query) use ($toko_id) {
    //                 $query->where('toko_id', $toko_id);
    //             });
    //         }

    //         // Urutkan berdasarkan ID secara descending
    //         $inquery->orderBy('id', 'DESC');
    //         $inquery1->orderBy('id', 'DESC');

    //         // Eager load relasi dan ambil semua data
    //         $permintaanProduks = $inquery->with(['detailpermintaanproduks.toko', 'detailpermintaanproduks.produk', 'toko'])->get();
    //         $pemesananProduks = $inquery1->with(['detailpemesananproduk'])->get();
    //     }

    //     // Ambil semua data toko dan produk untuk dropdown filter
    //     $tokos = Toko::all();
    //     $produks = Produk::all();

    //     // Tampilkan ke view tanpa data jika tidak ada filter
    //     return view('admin.estimasi_produksi.index', compact('pemesananProduks', 'permintaanProduks', 'tokos', 'produks'));
    // }
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal = $request->tanggal;
        $toko_id = $request->toko_id;

        // Cek apakah filter tanggal dipilih
        if (!$tanggal) {
            // Jika tidak ada filter tanggal, tampilkan view tanpa data
            $permintaanProduks = collect(); // Kosongkan data
            $pemesananProduks = collect();  // Kosongkan data
        } else {
            // Memulai query dari Permintaanproduk dan Pemesananproduk
            $inquery = Permintaanproduk::query();
            $inquery1 = Pemesananproduk::query();

            // Filter berdasarkan tanggal untuk Permintaanproduk
            $tanggal = Carbon::parse($tanggal)->startOfDay();
            $inquery->whereHas('detailpermintaanproduks', function($query) use ($tanggal) {
                $query->whereDate('tanggal_permintaan', $tanggal);
            });

            // Filter berdasarkan tanggal untuk Pemesananproduk (satu hari setelah tanggal yang dipilih)
            $tanggalPemesanan = Carbon::parse($tanggal)->addDay(); // Menambahkan satu hari
            $inquery1->whereDate('tanggal_kirim', $tanggalPemesanan);

            // Filter berdasarkan toko_id hanya jika toko_id ada
            if ($toko_id) {
                $inquery->whereHas('detailpermintaanproduks', function($query) use ($toko_id) {
                    $query->where('toko_id', $toko_id);
                });

                $inquery1->whereHas('detailpemesananproduk', function($query) use ($toko_id) {
                    $query->where('toko_id', $toko_id);
                });
            }

            // Urutkan berdasarkan ID secara descending
            $inquery->orderBy('id', 'DESC');
            $inquery1->orderBy('id', 'DESC');

            // Eager load relasi dan ambil semua data
            $permintaanProduks = $inquery->with(['detailpermintaanproduks.toko', 'detailpermintaanproduks.produk', 'toko'])->get();
            $pemesananProduks = $inquery1->with(['detailpemesananproduk'])->get();
        }

        // Ambil semua data toko dan produk untuk dropdown filter
        $tokos = Toko::all();
        $produks = Produk::all();

        // Tampilkan ke view
        return view('admin.estimasi_produksi.index', compact('pemesananProduks', 'permintaanProduks', 'tokos', 'produks'));
    }



    public function kode()
    {
        $prefix = 'JE';
        $year = date('y'); // Dua digit terakhir dari tahun
        $monthDay = date('dm'); // Format bulan dan hari: MMDD
    
        // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix PBNJ
        $lastBarang = Estimasiproduksi::where('kode_estimasi', 'LIKE', $prefix . '%')
                                      ->whereDate('tanggal_estimasi', Carbon::today())
                                      ->orderBy('kode_estimasi', 'desc')
                                      ->first();
    
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_estimasi;
            $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }
    
        $formattedNum = sprintf("%03d", $num); 
        $newCode = $prefix . $monthDay . $year . $formattedNum;
        return $newCode;
    }

    public function edit($id)
    {
        $produks = Produk::all();
        $permintaanProduks = Permintaanproduk::with(['detailpermintaanproduks', 'toko'])->findOrFail($id);

        return view('admin.estimasi_produksi.update', compact('permintaanProduks', 'produks'));
    }


    public function update(Request $request, $id)
{
    $error_pesanans = array();
    $data_pembelians = collect();
    $toko_id = $request->get('toko_id'); // Ambil toko_id dari select form

    // Validasi dan pengolahan produk_id dan jumlah
    if ($request->has('produk_id')) {
        for ($i = 0; $i < count($request->produk_id); $i++) {
            $validasi_produk = Validator::make($request->all(), [
                'produk_id.' . $i => 'required',
                'jumlah.' . $i => 'required',
            ]);

            if ($validasi_produk->fails()) {
                array_push($error_pesanans, "Barang nomor " . ($i + 1) . " belum dilengkapi!");
            }

            $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
            $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];

            $data_pembelians->push([
                'permintaanproduk_id' => $request->detail_ids[$i] ?? null,
                'produk_id' => $produk_id,
                'jumlah' => $jumlah,
            ]);
        }
    }

    // Ambil data permintaan produk utama
    $permintaanProduk = Permintaanproduk::findOrFail($id);

    // Update transaksi utama
    $permintaanProduk->update([
        'kode_permintaan' => $request->kode_permintaan,
    ]);

    foreach ($data_pembelians as $data_pesanan) {
        $detailId = $data_pesanan['permintaanproduk_id'];

        if ($detailId) {
            Detailpermintaanproduk::where('id', $detailId)->update([
                'permintaanproduk_id' => $permintaanProduk->id,
                'produk_id' => $data_pesanan['produk_id'],
                'jumlah' => $data_pesanan['jumlah'],
            ]);
        } else {
            $existingDetail = Detailpermintaanproduk::where([
                'permintaanproduk_id' => $permintaanProduk->id,
                'produk_id' => $data_pesanan['produk_id'],
                'jumlah' => $data_pesanan['jumlah'],
            ])->first();

            if (!$existingDetail) {
                Detailpermintaanproduk::create([
                    'permintaanproduk_id' => $permintaanProduk->id,
                    'produk_id' => $data_pesanan['produk_id'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'toko_id' => $toko_id,
                    'tanggal_permintaan' => Carbon::now('Asia/Jakarta'),
                    'status' => 'unpost',
                ]);
            }
        }
    }

    $kode = $this->kode();

    // Membuat data baru di estimasi_produksi
    $estimasiProduksi = EstimasiProduksi::create([
        'estimasiproduksi_id' => $permintaanProduk->id,
        'kode_estimasi' => $kode,
        'qrcode_estimasi' => 'https://javabakery.id/estimasi_produksi/' . $kode,
        'tanggal_estimasi' => Carbon::now('Asia/Jakarta'),
        'status' => 'posting',

    ]);

    // Menyimpan data dari pemesananProduksFirst
    if ($request->has('produk1_id')) {
        for ($j = 0; $j < count($request->produk1_id); $j++) {
            $produk1_id = is_null($request->produk1_id[$j]) ? '' : $request->produk1_id[$j];
            $jumlah1 = is_null($request->jumlah1[$j]) ? '' : $request->jumlah1[$j];

            // Ambil produk berdasarkan produk_id
            $produk1 = Produk::find($produk1_id);
            
            DetailEstimasiProduksi::create([
                'estimasiproduksi_id' => $estimasiProduksi->id,
                'produk_id' => $produk1_id,
                'jumlah' => $jumlah1,
                'toko_id' => $toko_id,
                'tanggal_estimasi' => Carbon::now('Asia/Jakarta'),
                // 'status' => 'posting',
                'kode_lama' => $produk1->kode_lama, // Ambil kode lama dari produk
                'nama_produk' => $produk1->nama_produk, // Ambil nama produk dari produk
                'kategori' => 'pesanan', // Tambahkan kategori untuk pesanan
            ]);
        }
    }

    // Menyimpan data dari permintaanProduksFirst
    if ($request->has('produk_id')) {
        for ($k = 0; $k < count($request->produk_id); $k++) {
            $produk_id = is_null($request->produk_id[$k]) ? '' : $request->produk_id[$k];
            $jumlah = is_null($request->jumlah[$k]) ? '' : $request->jumlah[$k];

            // Ambil produk berdasarkan produk_id
            $produk = Produk::find($produk_id);
            
            DetailEstimasiProduksi::create([
                'estimasiproduksi_id' => $estimasiProduksi->id,
                'produk_id' => $produk_id,
                'jumlah' => $jumlah,
                'toko_id' => $toko_id,
                'tanggal_estimasi' => Carbon::now('Asia/Jakarta'),
                // 'status' => 'posting',
                'kode_lama' => $produk->kode_lama, // Ambil kode lama dari produk
                'nama_produk' => $produk->nama_produk, // Ambil nama produk dari produk
                'kategori' => 'permintaan', // Tambahkan kategori untuk permintaan
            ]);
        }
    }

    // Ambil detail permintaan produk termasuk toko
    $details = Detailestimasiproduksi::with('toko', 'produk.klasifikasi')->where('estimasiproduksi_id', $estimasiProduksi->id)->get();

    // Pastikan variabel $toko diambil dari salah satu detail yang ada
    $toko = $details->isNotEmpty() ? $details->first()->toko : null;

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $details->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Hitung total jumlah per divisi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data subklasifikasi berdasarkan klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    // Arahkan ke view show dengan ID dari estimasiProduksi
    return redirect()->route('estimasi_produksi.show', $estimasiProduksi->id)->with('success', 'Data berhasil diperbarui.');
}



public function show($id)
{
    $permintaanProduk = Estimasiproduksi::find($id);
    if (!$permintaanProduk) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    $detailPermintaanProduks = Detailestimasiproduksi::with('produk.klasifikasi')->where('estimasiproduksi_id', $id)->get();

    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama; 
    });

    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    $toko = $detailPermintaanProduks->isNotEmpty() ? $detailPermintaanProduks->first()->toko : null;

    return view('admin.estimasi_produksi.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'toko'));
}

public function print($id)
{
    $permintaanProduk = Estimasiproduksi::find($id);
    $detailPermintaanProduks = Detailestimasiproduksi::where('estimasiproduksi_id', $id)->get();

    // Cek apakah detailPermintaanProduks tidak kosong
    if ($detailPermintaanProduks->isEmpty()) {
        // Tangani kasus ketika tidak ada detail permintaan produk
        return redirect()->back()->with('error', 'Tidak ada detail permintaan produk yang ditemukan.');
    }

    // Mengelompokkan produk berdasarkan divisi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama; // Ganti dengan nama divisi jika diperlukan
    });

    // Menghitung total jumlah per divisi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil toko dari detail permintaan produk pertama
    $toko = $detailPermintaanProduks->first()->toko;

    $pdf = FacadePdf::loadView('admin.estimasi_produksi.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'toko'));

    return $pdf->stream('surat_permintaan_produk.pdf');
}

public function deletedetailpermintaan($id)
{
    $item = Detailpermintaanproduk::find($id);
    $item->delete();
    return response()->json(['message' => 'Detail Permintaan not found'], 404);
}



}