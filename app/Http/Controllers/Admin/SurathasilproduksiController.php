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
use App\Models\Detailhasilproduksi;
use App\Models\Estimasiproduksi;
use App\Models\Hasilproduksi;
use App\Models\Stok_hasilproduksi;
use App\Models\Stokhasilproduksi;
use Maatwebsite\Excel\Facades\Excel;




class SurathasilproduksiController extends Controller{

   
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_estimasi = $request->tanggal_estimasi;
        $tanggal_akhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

           // Cek apakah filter tanggal dipilih
           if (!$tanggal_estimasi && !$tanggal_akhir) {
            // Jika tidak ada filter tanggal, tampilkan view tanpa data
            $inquery = collect(); // Kosongkan data
            $groupedInquery = collect(); // Kosongkan data
        } else {
        $query = Estimasiproduksi::with(['detailestimasiproduksi.produk.klasifikasi', 'detailestimasiproduksi.toko']);

        // Filter berdasarkan status
        if ($status) {
            $query->whereHas('detailestimasiproduksi', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Filter berdasarkan tanggal permintaan
        if ($tanggal_estimasi && $tanggal_akhir) {
            $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_estimasi, $tanggal_akhir) {
                $query->whereBetween('tanggal_estimasi', [$tanggal_estimasi, $tanggal_akhir]);
            });
        } elseif ($tanggal_estimasi) {
            $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
            $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_estimasi) {
                $query->where('tanggal_estimasi', '>=', $tanggal_estimasi);
            });
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_akhir) {
                $query->where('tanggal_estimasi', '<=', $tanggal_akhir);
            });
        } else {
            $query->whereHas('detailestimasiproduksi', function ($query) {
                $query->whereDate('tanggal_estimasi', Carbon::today());
            });
        }

        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailestimasiproduksi', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }

        // Filter berdasarkan toko
        if ($toko_id) {
            $query->whereHas('detailestimasiproduksi', function ($query) use ($toko_id) {
                $query->where('toko_id', $toko_id);
            });
        }

        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('detailestimasiproduksi.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }

        $query->orderBy('id', 'DESC');

        $inquery = $query->get();

        // Mengelompokkan detail berdasarkan produk_id dan menjumlahkan jumlahnya
        $groupedInquery = $inquery->flatMap(function ($estimasiproduksi) {
            return $estimasiproduksi->detailestimasiproduksi;
        })->groupBy('produk_id')->map(function ($details) {
            $firstDetail = $details->first(); 
            $firstDetail->jumlah = $details->sum('jumlah'); 
            return $firstDetail;
        });
    }

        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();

        return view('admin.surathasilproduksi.index', compact('inquery','groupedInquery', 'produks', 'tokos', 'klasifikasis', 'klasifikasi_id'));
    }

    public function printReportestimasi(Request $request)
    {
        // Ambil filter dari request
        $klasifikasi_id = $request->get('klasifikasi_id');
        $tanggal = $request->get('tanggal_estimasi');
        $tanggal_akhir = $request->get('tanggal_akhir');

        // Buat query untuk mengambil data estimasi produksi
        $inquery = EstimasiProduksi::with(['detailestimasiproduksi' => function ($query) use ($klasifikasi_id) {
            if ($klasifikasi_id) {
                // Filter berdasarkan klasifikasi_id
                $query->whereHas('produk.klasifikasi', function ($q) use ($klasifikasi_id) {
                    $q->where('id', $klasifikasi_id);
                });
            }
        }])->get();

        // Persiapkan data untuk ditampilkan
        $groupedData = [];
        foreach ($inquery as $estimasiproduksi) {
            foreach ($estimasiproduksi->detailestimasiproduksi as $detail) {
                // Tentukan kategori berdasarkan 'kategori'
                $kategori = $detail->kategori; // 'permintaan' atau 'pemesanan'
                $produkId = $detail->produk_id;
                $klasifikasi = $detail->produk->klasifikasi->nama ?? 'N/A';

                // Filter hanya untuk produk dengan klasifikasi yang dipilih
                if ($klasifikasi_id && $detail->produk->klasifikasi_id != $klasifikasi_id) {
                    continue; // Lewati jika produk tidak sesuai klasifikasi yang dipilih
                }

                if (!isset($groupedData[$klasifikasi])) {
                    $groupedData[$klasifikasi] = [];
                }

                if (!isset($groupedData[$klasifikasi][$produkId])) {
                    $groupedData[$klasifikasi][$produkId] = [
                        'klasifikasi' => $klasifikasi,
                        'kode_lama' => $detail->kode_lama,
                        'nama_produk' => $detail->nama_produk,
                        'stok' => [1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-', 6 => '-'],
                        'pes' => [1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-', 6 => '-'],
                    ];
                }

                // Isi jumlah berdasarkan kategori
                if ($kategori === 'permintaan') {
                    $tokoId = 1; // Misalkan toko_id 1 untuk 'permintaan'
                    if ($groupedData[$klasifikasi][$produkId]['stok'][$tokoId] === '-') {
                        $groupedData[$klasifikasi][$produkId]['stok'][$tokoId] = $detail->jumlah;
                    } else {
                        $groupedData[$klasifikasi][$produkId]['stok'][$tokoId] += $detail->jumlah;
                    }
                } elseif ($kategori === 'pesanan') {
                    $tokoId = 2; // Misalkan toko_id 2 untuk 'pemesanan'
                    if ($groupedData[$klasifikasi][$produkId]['pes'][$tokoId] === '-') {
                        $groupedData[$klasifikasi][$produkId]['pes'][$tokoId] = $detail->jumlah;
                    } else {
                        $groupedData[$klasifikasi][$produkId]['pes'][$tokoId] += $detail->jumlah;
                    }
                }
            }
        }

        // Format tanggal untuk tampilan PDF
        $formattedStartDate = $tanggal ? Carbon::parse($tanggal)->translatedFormat('d F Y') : 'Tidak ada';
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') : 'Tidak ada';
        $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');

        // Generate PDF
        $pdf = FacadePdf::loadView('admin.surathasilproduksi.print', [
            'groupedData' => $groupedData,
            'klasifikasi_id' => $klasifikasi_id,
            'formattedStartDate' => $formattedStartDate,
            'formattedEndDate' => $formattedEndDate,
            'currentDateTime' => $currentDateTime,
        ]);

        return $pdf->stream('laporan_estimasi.pdf');
    }


    // public function saveRealisasi(Request $request)
    // {
    //     $kode = $this->kode();
    //     $qrcode_hasilproduksi =  'https://javabakery.id/hasil_produksi/' . $kode;

    //     $hasilproduksi = new Hasilproduksi();

    //     $hasilproduksi->kode_hasilproduksi = $kode;
    //     $hasilproduksi->qrcode_hasilproduksi = $qrcode_hasilproduksi;
    //     $hasilproduksi->toko_id = $request->toko_id;
    //     $hasilproduksi->status = 'pending'; 
    //     $hasilproduksi->tanggal_hasilproduksi = now();
    //     $hasilproduksi->save(); 
        
    //     foreach ($request->realisasi as $produk_id => $realisasi) {
    //         $kode_lama = $request->kode_lama[$produk_id];
    //         $nama_produk = $request->nama_produk[$produk_id];
    //         $jumlah = $request->jumlah[$produk_id];

    //         $detailHasilProduksi = new Detailhasilproduksi();
    //         $detailHasilProduksi->hasilproduksi_id = $hasilproduksi->id; 
    //         $detailHasilProduksi->produk_id = $produk_id;
    //         $detailHasilProduksi->kode_lama = $kode_lama;
    //         $detailHasilProduksi->nama_produk = $nama_produk;
    //         $detailHasilProduksi->jumlah = $jumlah;
    //         $detailHasilProduksi->realisasi = $realisasi;
    //         $detailHasilProduksi->save(); 
    //     }

    //     // Redirect ke halaman show hasil produksi
    //     return redirect()->route('surathasilproduksi.show', $hasilproduksi->id)
    //                     ->with('success', 'Data berhasil disimpan ke hasilproduksi dan detailhasilproduksi');
    // }
    public function saveRealisasi(Request $request)
{
    $kode = $this->kode();
    $qrcode_hasilproduksi =  'https://javabakery.id/hasil_produksi/' . $kode;

    $hasilproduksi = new Hasilproduksi();
    $hasilproduksi->kode_hasilproduksi = $kode;
    $hasilproduksi->qrcode_hasilproduksi = $qrcode_hasilproduksi;
    $hasilproduksi->toko_id = $request->toko_id;
    $hasilproduksi->status = 'posting'; 
    $hasilproduksi->tanggal_hasilproduksi = now();
    $hasilproduksi->save(); 
    
    foreach ($request->realisasi as $produk_id => $realisasi) {
        $kode_lama = $request->kode_lama[$produk_id];
        $nama_produk = $request->nama_produk[$produk_id];
        $jumlah = $request->jumlah[$produk_id];

        // Simpan detail hasil produksi
        $detailHasilProduksi = new Detailhasilproduksi();
        $detailHasilProduksi->hasilproduksi_id = $hasilproduksi->id; 
        $detailHasilProduksi->produk_id = $produk_id;
        $detailHasilProduksi->kode_lama = $kode_lama;
        $detailHasilProduksi->nama_produk = $nama_produk;
        $detailHasilProduksi->jumlah = $jumlah;
        $detailHasilProduksi->realisasi = $realisasi;
        $detailHasilProduksi->save(); 

        // Update stok hasil produksi
        $stok = Stokhasilproduksi::where('produk_id', $produk_id)->first();
        
        if ($stok) {
            // Jika stok sudah ada, tambahkan jumlahnya
            $stok->jumlah += $realisasi;
            $stok->save();
        } else {
            // Jika stok belum ada, buat stok baru
            $stokBaru = new Stokhasilproduksi();
            $stokBaru->produk_id = $produk_id;
            $stokBaru->jumlah = $realisasi;
            $stokBaru->save();
        }
    }

    // Redirect ke halaman show hasil produksi
    return redirect()->route('surathasilproduksi.show', $hasilproduksi->id)
                    ->with('success', 'Data berhasil disimpan ke hasilproduksi, detailhasilproduksi, dan stokhasilproduksi');
}




    public function kode()
    {
        $prefix = 'HP';
        $year = date('y'); // Dua digit terakhir dari tahun
        $monthDay = date('dm'); // Format bulan dan hari: MMDD

        // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix PBNJ
        $lastBarang = Hasilproduksi::where('kode_hasilproduksi', 'LIKE', $prefix . '%')
                                    ->whereDate('tanggal_hasilproduksi', Carbon::today())
                                    ->orderBy('kode_hasilproduksi', 'desc')
                                    ->first();

        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_hasilproduksi;
            $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }

        $formattedNum = sprintf("%03d", $num); 
        $newCode = $prefix . $monthDay . $year . $formattedNum;
        return $newCode;
    }

    // public function show($id)
    // {
    //     $hasilproduksi = Hasilproduksi::findOrFail($id); // Mengambil data hasil produksi berdasarkan ID
    //     $detailHasilProduksi = Detailhasilproduksi::where('hasilproduksi_id', $id)->get(); // Mengambil data detail hasil produksi terkait
    
    //     return view('admin.surathasilproduksi.show', compact('hasilproduksi', 'detailHasilProduksi'));
    // }
    
    public function show($id)
{
    $permintaanProduk = Hasilproduksi::find($id);
    $detailPermintaanProduks = Detailhasilproduksi::with('toko')->where('hasilproduksi_id', $id)->get();

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Menghitung total jumlah per klasifikasi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data Subklasifikasi berdasarkan Klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    // Mengambil nama toko dari salah satu detail permintaan produk
    $toko = $detailPermintaanProduks->first()->toko;

    return view('admin.surathasilproduksi.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi', 'toko'));
}


public function print($id)
{
    // $permintaanProduk = PermintaanProduk::where('id', $id)->firstOrFail();
    
    // $detailPermintaanProduks = $permintaanProduk->detailpermintaanproduks;
    $permintaanProduk = Hasilproduksi::find($id);
    $detailPermintaanProduks = Detailhasilproduksi::where('hasilproduksi_id', $id)->get();

    // Mengelompokkan produk berdasarkan divisi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama; // Ganti dengan nama divisi jika diperlukan
    });

    // Menghitung total jumlah per divisi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('realisasi');
    });
    $toko = $detailPermintaanProduks->first()->toko;

    $pdf = FacadePdf::loadView('admin.surathasilproduksi.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi','toko'));

    return $pdf->stream('surat_permintaan_produk.pdf');
}


}