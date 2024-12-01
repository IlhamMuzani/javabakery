<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use App\Models\Detailpemesananproduk;
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokotegal;
use App\Models\Stok_tokopemalang;
use App\Models\Stok_tokobumiayu;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Detail_stokbarangjadi;
use App\Models\Stok_barangjadi;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokobanjaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Estimasiproduksi;
use App\Models\Pengiriman_barangjadipesanan;
use App\Models\Pengiriman_tokotegal;
use App\Models\Pengirimanpemesanan_tokotegal;
use App\Models\Stokhasilproduksi;
use Maatwebsite\Excel\Facades\Excel;




class PengirimanbarangjadiController extends Controller{

    public function index()
    {
        // Mendapatkan tanggal hari ini
        $today = Carbon::today();
    
        $pengirimanBarangJadi = Pengiriman_barangjadi::with('produk.klasifikasi')
            ->whereDate('created_at', $today) // Filter data berdasarkan tanggal hari ini
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pengiriman');
    
        return view('admin.pengiriman_barangjadi.index', compact('pengirimanBarangJadi'));
    }


    public function create(Request $request)
    {
        $status = $request->status;
        $tanggal_estimasi = $request->tanggal_estimasi;
        $toko_id = $request->toko_id;
    
        // Cek apakah toko dipilih
        if ($toko_id) {
            // Query dasar dengan relasi yang diperlukan
            $query = Estimasiproduksi::with(['detailestimasiproduksi.produk.klasifikasi', 'detailestimasiproduksi.toko']);
    
            // Filter berdasarkan tanggal estimasi
            if ($tanggal_estimasi) {
                $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
                $query->whereHas('detailestimasiproduksi', function ($q) use ($tanggal_estimasi) {
                    $q->whereDate('tanggal_estimasi', $tanggal_estimasi);
                });
            }
    
            // Filter berdasarkan toko
            $query->whereHas('detailestimasiproduksi', function ($q) use ($toko_id) {
                $q->where('toko_id', $toko_id);
            });
    
            // Mendapatkan hasil query
            $inquery = $query->orderBy('id', 'DESC')->get();
        } else {
            // Kosongkan $inquery jika toko tidak dipilih
            $inquery = collect(); // Mengembalikan koleksi kosong
        }
    
        // Data tambahan yang akan ditampilkan di view
        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
    
        return view('admin.pengiriman_barangjadi.create', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
    }

// public function store(Request $request)
// {
//     // Ambil tanggal pengiriman dari input
//     $tanggalPengiriman = $request->input('tanggal_pengiriman'); 

//     // Validasi input dari form
//     $request->validate([
//         'produk_id' => 'required|array',
//         'produk_id.*' => 'exists:produks,id',
//         'toko_id' => 'required|exists:tokos,id',
//         'jumlah' => 'required|array',
//         'jumlah.*' => 'integer|min:1',
//         'kategori' => 'required|array', // Validasi untuk kategori
//         'kategori.*' => 'string|in:permintaan,pesanan', // Pastikan nilai kategori valid
//     ]);

//     $tanggalPengirimanDenganJam = Carbon::parse($tanggalPengiriman)->setTime(now()->hour, now()->minute);

//     // Ambil data dari request
//     $produk_ids = $request->produk_id;
//     $jumlahs = $request->jumlah;
//     $toko_id = $request->toko_id;
//     $kategori = $request->kategori; // Ambil nilai kategori
//     $kode = $this->kode($tanggalPengiriman); // Panggil kode() dengan tanggal pengiriman
//     $kode1 = $this->kode1($tanggalPengiriman); // Panggil kode untuk pengiriman pesanan

//     // Loop untuk menyimpan setiap item pengiriman
//     foreach ($produk_ids as $index => $produk_id) {
//         // Ambil stok dari tabel stokhasilproduksi berdasarkan produk_id
//         $stok = Stokhasilproduksi::where('produk_id', $produk_id)->first();

//         // Cek apakah stok mencukupi
//         if ($stok && $stok->jumlah >= $jumlahs[$index]) {
//             // Kurangi stok sesuai jumlah pengiriman
//             $stok->jumlah -= $jumlahs[$index];
//             $stok->save(); // Simpan perubahan stok

//             // Simpan data berdasarkan kategori
//             if ($kategori[$index] === 'permintaan') {
//                 // Simpan ke tabel pengiriman_barangjadi dan ambil ID-nya
//                 $pengiriman = Pengiriman_barangjadi::create([
//                     'produk_id' => $produk_id,
//                     'toko_id' => $toko_id,
//                     'kode_pengiriman' => $kode,
//                     'jumlah' => $jumlahs[$index],
//                     'tanggal_pengiriman' => $tanggalPengirimanDenganJam, // Set tanggal pengiriman
//                     'status' => 'unpost' 
//                 ]);

//                 // Simpan ke tabel tambahan berdasarkan toko_id
//                 if ($toko_id == 1) {
//                     Pengiriman_tokobanjaran::create([
//                         'produk_id' => $produk_id,
//                         'kode_pengiriman' => $kode,
//                         'jumlah' => $jumlahs[$index],
//                         'tanggal_input' => $tanggalPengirimanDenganJam,
//                         'status' => 'unpost',
//                         'toko_id' => 1,
//                         'pengiriman_barangjadi_id' => $pengiriman->id // Simpan pengiriman_barangjadi_id
//                     ]);
//                 } elseif ($toko_id == 2) {
//                     Pengiriman_tokotegal::create([
//                         'produk_id' => $produk_id,
//                         'kode_pengiriman' => $kode,
//                         'jumlah' => $jumlahs[$index],
//                         'tanggal_input' => $tanggalPengirimanDenganJam,
//                         'status' => 'unpost',
//                         'toko_id' => 2,
//                         'pengiriman_barangjadi_id' => $pengiriman->id // Simpan pengiriman_barangjadi_id
//                     ]);
//                 }

//             } elseif ($kategori[$index] === 'pesanan') {
//                 // Simpan ke tabel pengiriman_barangjadipesanan dan ambil ID-nya
//                 $pengirimanPesanan = Pengiriman_barangjadipesanan::create([
//                     'produk_id' => $produk_id,
//                     'toko_id' => $toko_id,
//                     'kode_pengirimanpesanan' => $kode1,
//                     'jumlah' => $jumlahs[$index],
//                     'tanggal_pengiriman' => $tanggalPengirimanDenganJam, // Set tanggal pengiriman
//                     'status' => 'unpost' 
//                 ]);

//                 // Simpan ke tabel tambahan berdasarkan toko_id
//                 if ($toko_id == 1) {
//                     Pengirimanpemesanan_tokobanjaran::create([
//                         'produk_id' => $produk_id,
//                         'kode_pengirimanpesanan' => $kode1,
//                         'jumlah' => $jumlahs[$index],
//                         'tanggal_input' => $tanggalPengirimanDenganJam,
//                         'status' => 'unpost',
//                         'toko_id' => 1,
//                         'pengiriman_barangjadi_id' => $pengirimanPesanan->id // Simpan pengiriman_barangjadi_id
//                     ]);
//                 } elseif ($toko_id == 2) {
//                     Pengirimanpemesanan_tokotegal::create([
//                         'produk_id' => $produk_id,
//                         'kode_pengirimanpesanan' => $kode1,
//                         'jumlah' => $jumlahs[$index],
//                         'tanggal_input' => $tanggalPengirimanDenganJam,
//                         'status' => 'unpost',
//                         'toko_id' => 2,
//                         'pengiriman_barangjadi_id' => $pengirimanPesanan->id // Simpan pengiriman_barangjadi_id
//                     ]);
//                 }
//             }
//         } else {
//             // Jika stok tidak cukup, kembalikan error
//             return redirect()->back()
//                 ->with('error', 'Stok tidak cukup untuk produk dengan ID: ' . $produk_id);
//         }
//     }

//     // Redirect ke halaman index dengan pesan sukses
//     return redirect()->route('pengiriman_barangjadi.index')
//                     ->with('success', 'Berhasil menambahkan permintaan produk, mengurangi stok, dan menyimpan data pengiriman.');
// }

public function store(Request $request)
{
    // Ambil tanggal pengiriman dari input
    $tanggalPengiriman = $request->input('tanggal_pengiriman'); 

    // Validasi input dari form
    $request->validate([
        'produk_id' => 'required|array',
        'produk_id.*' => 'exists:produks,id',
        'toko_id' => 'required|exists:tokos,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
        'kategori' => 'required|array', // Validasi untuk kategori
        'kategori.*' => 'string|in:permintaan,pesanan', // Pastikan nilai kategori valid
    ]);

    $tanggalPengirimanDenganJam = Carbon::parse($tanggalPengiriman)->setTime(now()->hour, now()->minute);

    // Ambil data dari request
    $produk_ids = $request->produk_id;
    $jumlahs = $request->jumlah;
    $toko_id = $request->toko_id;
    $kategori = $request->kategori; // Ambil nilai kategori
    $kode = $this->kode($tanggalPengiriman); // Panggil kode() dengan tanggal pengiriman
    $kode1 = $this->kode1($tanggalPengiriman); // Panggil kode untuk pengiriman pesanan

    // Loop untuk menyimpan setiap item pengiriman
    foreach ($produk_ids as $index => $produk_id) {
        // Ambil stok dari tabel stokhasilproduksi berdasarkan produk_id
        $stok = Stokhasilproduksi::where('produk_id', $produk_id)->first();

        // Cek apakah stok mencukupi
        if ($stok && $stok->jumlah >= $jumlahs[$index]) {
            // Tidak mengurangi stok sesuai jumlah pengiriman, hanya menyimpan data

            // Simpan data berdasarkan kategori
            if ($kategori[$index] === 'permintaan') {
                // Simpan ke tabel pengiriman_barangjadi dan ambil ID-nya
                $pengiriman = Pengiriman_barangjadi::create([
                    'produk_id' => $produk_id,
                    'toko_id' => $toko_id,
                    'kode_pengiriman' => $kode,
                    'jumlah' => $jumlahs[$index],
                    'tanggal_pengiriman' => $tanggalPengirimanDenganJam, // Set tanggal pengiriman
                    'status' => 'unpost' 
                ]);

                // Simpan ke tabel tambahan berdasarkan toko_id
                if ($toko_id == 1) {
                    Pengiriman_tokobanjaran::create([
                        'produk_id' => $produk_id,
                        'kode_pengiriman' => $kode,
                        'jumlah' => $jumlahs[$index],
                        'tanggal_input' => $tanggalPengirimanDenganJam,
                        'status' => 'unpost',
                        'toko_id' => 1,
                        'pengiriman_barangjadi_id' => $pengiriman->id // Simpan pengiriman_barangjadi_id
                    ]);
                } elseif ($toko_id == 2) {
                    Pengiriman_tokotegal::create([
                        'produk_id' => $produk_id,
                        'kode_pengiriman' => $kode,
                        'jumlah' => $jumlahs[$index],
                        'tanggal_input' => $tanggalPengirimanDenganJam,
                        'status' => 'unpost',
                        'toko_id' => 2,
                        'pengiriman_barangjadi_id' => $pengiriman->id // Simpan pengiriman_barangjadi_id
                    ]);
                }

            } elseif ($kategori[$index] === 'pesanan') {
                // Simpan ke tabel pengiriman_barangjadipesanan dan ambil ID-nya
                $pengirimanPesanan = Pengiriman_barangjadipesanan::create([
                    'produk_id' => $produk_id,
                    'toko_id' => $toko_id,
                    'kode_pengirimanpesanan' => $kode1,
                    'jumlah' => $jumlahs[$index],
                    'tanggal_pengiriman' => $tanggalPengirimanDenganJam, // Set tanggal pengiriman
                    'status' => 'unpost' 
                ]);

                // Simpan ke tabel tambahan berdasarkan toko_id
                if ($toko_id == 1) {
                    Pengirimanpemesanan_tokobanjaran::create([
                        'produk_id' => $produk_id,
                        'kode_pengirimanpesanan' => $kode1,
                        'jumlah' => $jumlahs[$index],
                        'tanggal_input' => $tanggalPengirimanDenganJam,
                        'status' => 'unpost',
                        'toko_id' => 1,
                        'pengiriman_barangjadi_id' => $pengirimanPesanan->id // Simpan pengiriman_barangjadi_id
                    ]);
                } elseif ($toko_id == 2) {
                    Pengirimanpemesanan_tokotegal::create([
                        'produk_id' => $produk_id,
                        'kode_pengirimanpesanan' => $kode1,
                        'jumlah' => $jumlahs[$index],
                        'tanggal_input' => $tanggalPengirimanDenganJam,
                        'status' => 'unpost',
                        'toko_id' => 2,
                        'pengiriman_barangjadi_id' => $pengirimanPesanan->id // Simpan pengiriman_barangjadi_id
                    ]);
                }
            }
        } else {
            // Jika stok tidak cukup, kembalikan error
            return redirect()->back()
                ->with('error', 'Stok tidak cukup untuk produk dengan ID: ' . $produk_id);
        }
    }

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('pengiriman_barangjadi.index')
                    ->with('success', 'Berhasil menambahkan permintaan produk, menyimpan data pengiriman.');
}




public function kode($tanggalPengiriman)
{
    $prefix = 'JK';
    $year = Carbon::parse($tanggalPengiriman)->format('y'); // Dua digit terakhir dari tahun berdasarkan tanggal pengiriman
    $monthDay = Carbon::parse($tanggalPengiriman)->format('dm'); // Format bulan dan hari: MMDD berdasarkan tanggal pengiriman

    // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix PBNJ
    $lastBarang = Pengiriman_barangjadi::where('kode_pengiriman', 'LIKE', $prefix . '%')
                                  ->whereDate('tanggal_pengiriman', Carbon::parse($tanggalPengiriman)) // Sesuaikan dengan tanggal pengiriman
                                  ->orderBy('kode_pengiriman', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_pengiriman;
        $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%03d", $num); 
    $newCode = $prefix . $monthDay . $year . $formattedNum;
    return $newCode;
}

public function kode1()
{
    $prefix = 'JKp';
    $year = date('y'); // Dua digit terakhir dari tahun
    $monthDay = date('dm'); // Format bulan dan hari: MMDD

    // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix PBNJ
    $lastBarang = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', 'LIKE', $prefix . '%')
                                  ->whereDate('tanggal_pengiriman', Carbon::today())
                                  ->orderBy('kode_pengirimanpesanan', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_pengirimanpesanan;
        $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%03d", $num); 
    $newCode = $prefix . $monthDay . $year . $formattedNum;
    return $newCode;
}

    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengiriman', $detailStokBarangJadi)->get();
    
        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.pengiriman_barangjadi.show', compact('groupedByKlasifikasi', 'firstItem'));
    }

    public function showPesanan($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengiriman', $detailStokBarangJadi)->get();
    
        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.pengiriman_barangjadi.showpesanan', compact('groupedByKlasifikasi', 'firstItem'));
    }
    
    public function print($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
            
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengiriman', $detailStokBarangJadi)->get();

        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('admin.pengiriman_barangjadi.print', compact('groupedByKlasifikasi', 'firstItem'));

        // Menambahkan nomor halaman di kanan bawah
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Page $pageNumber of $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 8;

            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);

            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('surat_permintaan_produk.pdf');
    }

    public function printpesanan($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
            
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengiriman', $detailStokBarangJadi)->get();

        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('admin.pengiriman_barangjadi.print', compact('groupedByKlasifikasi', 'firstItem'));

        // Menambahkan nomor halaman di kanan bawah
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Page $pageNumber of $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 8;

            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);

            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('surat_permintaan_produk.pdf');
    }



    public function unpost(Request $request, $id)
    {
        $permintaan = Detailpermintaanproduk::find($id);
    
        if ($permintaan) {
            $permintaan->status = 'posting';
            $permintaan->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false], 404);
    }
    
    

    public function edit($id)
    {
           
    }
        
    

    public function update(Request $request, $id)
    {
           
    }


        public function destroy($id)
        {
            DB::transaction(function () use ($id) {
                $pemesanan = Pemesananproduk::findOrFail($id);
        
                // Menghapus (soft delete) detail pemesanan terkait
                DetailPemesananProduk::where('pemesananproduk_id', $id)->delete();
        
                // Menghapus (soft delete) data pemesanan
                $pemesanan->delete();
            });
        
            return redirect('admin/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
        }
        
        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
    
            Excel::import(new ProdukImport, $request->file('file'));
    
            // Redirect to the form with success message
            return redirect()->route('form.produk')->with('success', 'Data produk berhasil diimpor.');
        }
    
        public function formProduk()
        {
            $klasifikasis = Klasifikasi::with('produks')->get();
            $importedData = session('imported_data', []);
            return view('admin.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}