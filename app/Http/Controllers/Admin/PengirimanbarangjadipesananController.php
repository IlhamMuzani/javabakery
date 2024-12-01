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
use App\Models\Pengiriman_barangjadipesanan;
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokobanjaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Pengirimanpemesanan_tokobumiayu;
use App\Models\Pengirimanpemesanan_tokocilacap;
use App\Models\Pengirimanpemesanan_tokopemalang;
use App\Models\Pengirimanpemesanan_tokoslawi;
use App\Models\Pengirimanpemesanan_tokotegal;
use Maatwebsite\Excel\Facades\Excel;




class PengirimanbarangjadipesananController extends Controller{

    public function index()
    {
        // Mendapatkan tanggal hari ini
        $today = Carbon::today();
    
        $pengirimanBarangJadi = Pengiriman_barangjadipesanan::with('produk.klasifikasi')
            ->whereDate('created_at', $today) // Filter data berdasarkan tanggal hari ini
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pengiriman');
    
        return view('admin.pengiriman_barangjadipesanan.index', compact('pengirimanBarangJadi'));
    }


    public function create()
    {
        // Ambil data detail stok barang jadi dengan produk yang terkait
        $detailStokBarangjadi = Detail_stokbarangjadi::with('produk')
            ->get();
        
        // Akumulasi stok berdasarkan produk_id
        $uniqueStokBarangjadi = $detailStokBarangjadi->groupBy('produk_id')->map(function ($items) {
            $firstItem = $items->first(); // Ambil entri pertama
            $firstItem->stok = $items->sum('stok'); // Akumulasi stok
            return $firstItem;
        })->values();
        
        // Ambil klasifikasi yang terkait dengan produk yang ada
        $produkIds = $uniqueStokBarangjadi->pluck('produk_id')->toArray();
        $klasifikasiIds = $uniqueStokBarangjadi->pluck('klasifikasi_id')->toArray();
        
        $klasifikasis = Klasifikasi::whereIn('id', $klasifikasiIds)
            ->with(['produks' => function ($query) use ($produkIds) {
                $query->whereIn('id', $produkIds);
            }])
            ->get();
        
        // Ambil semua toko
        $tokos = Toko::all();
        
        return view('admin.pengiriman_barangjadipesanan.create', compact('klasifikasis', 'tokos', 'uniqueStokBarangjadi'));
    }
    
   

    public function store(Request $request)
    {
        $tanggalPengiriman = $request->input('tanggal_pengiriman'); 
        $tokoId = $request->input('toko_id');
        $kode = $this->kode($tanggalPengiriman, $tokoId); // Panggil kode() dengan tanggal pengiriman dan toko_id
        $produkData = $request->input('produk_id', []);
        $jumlahData = $request->input('jumlah', []);
        $tokoId = $request->input('toko_id');
        $kodeProduksi = implode('', $request->input('kode_produksi', [])); // Menggabungkan kode produksi tanpa koma

        // Validasi tanggal pengiriman (opsional)
        $request->validate([
            'tanggal_pengiriman' => 'required|date', // Pastikan tanggal pengiriman diisi dan dalam format yang benar
        ]);

        // Mengatur jam sesuai dengan waktu saat ini
        $tanggalPengirimanDenganJam = Carbon::parse($tanggalPengiriman)->setTime(now()->hour, now()->minute);

        // Array untuk menyimpan ID pengiriman
        $pengirimanIds = [];

        foreach ($produkData as $key => $produkId) {
            $jumlah = $jumlahData[$key] ?? null;

            if (!is_null($jumlah) && $jumlah !== '') {
                // Ambil kode produk untuk pesan error
                $kodeProduk = Produk::where('id', $produkId)->value('kode_produk');

                // Simpan pengiriman dengan tanggal pengiriman dan kode produksi
                $pengiriman = Pengiriman_barangjadipesanan::create([
                    'kode_pengirimanpesanan' => $kode,
                    'qrcode_pengiriman' => 'https://javabakery.id/pengiriman_produk/' . $kode,
                    'produk_id' => $produkId,
                    'toko_id' => $tokoId,
                    'jumlah' => $jumlah,
                    'status' => 'unpost',
                    'tanggal_pengiriman' => $tanggalPengirimanDenganJam,
                    'kode_produksi' => $kodeProduksi,
                ]);

                // Buat catatan stok di toko terkait
                switch ($tokoId) {
                    case 1:
                        Pengirimanpemesanan_tokobanjaran::create([
                            'pengiriman_barangjadi_id' => $pengiriman->id,
                            'kode_pengirimanpesanan' => $kode,
                            'produk_id' => $produkId,
                            'jumlah' => $jumlah,
                            'status' => 'unpost',
                            'toko_id' => $tokoId,
                            'tanggal_input' => $tanggalPengirimanDenganJam,
                            'kode_produksi' => $kodeProduksi,
                        ]);
                        break;
                    case 2:
                        Pengirimanpemesanan_tokotegal::create([
                            'pengiriman_barangjadi_id' => $pengiriman->id,
                            'kode_pengirimanpesanan' => $kode,
                            'produk_id' => $produkId,
                            'jumlah' => $jumlah,
                            'status' => 'unpost',
                            'toko_id' => $tokoId,
                            'tanggal_input' => $tanggalPengirimanDenganJam,
                            'kode_produksi' => $kodeProduksi,
                        ]);
                        break;
                    case 3:
                        Pengirimanpemesanan_tokoslawi::create([
                            'pengiriman_barangjadi_id' => $pengiriman->id,
                            'kode_pengirimanpesanan' => $kode,
                            'produk_id' => $produkId,
                            'jumlah' => $jumlah,
                            'status' => 'unpost',
                            'toko_id' => $tokoId,
                            'tanggal_input' => $tanggalPengirimanDenganJam,
                            'kode_produksi' => $kodeProduksi,
                        ]);
                        break;
                    case 4:
                        Pengirimanpemesanan_tokopemalang::create([
                            'pengiriman_barangjadi_id' => $pengiriman->id,
                            'kode_pengirimanpesanan' => $kode,
                            'produk_id' => $produkId,
                            'jumlah' => $jumlah,
                            'status' => 'unpost',
                            'toko_id' => $tokoId,
                            'tanggal_input' => $tanggalPengirimanDenganJam,
                            'kode_produksi' => $kodeProduksi,
                        ]);
                        break;
                    case 5:
                        Pengirimanpemesanan_tokobumiayu::create([
                            'pengiriman_barangjadi_id' => $pengiriman->id,
                            'kode_pengirimanpesanan' => $kode,
                            'produk_id' => $produkId,
                            'jumlah' => $jumlah,
                            'status' => 'unpost',
                            'toko_id' => $tokoId,
                            'tanggal_input' => $tanggalPengirimanDenganJam,
                            'kode_produksi' => $kodeProduksi,
                        ]);
                        break;
                        case 6:
                            Pengirimanpemesanan_tokocilacap::create([
                                'pengiriman_barangjadi_id' => $pengiriman->id,
                                'kode_pengirimanpesanan' => $kode,
                                'produk_id' => $produkId,
                                'jumlah' => $jumlah,
                                'status' => 'unpost',
                                'toko_id' => $tokoId,
                                'tanggal_input' => $tanggalPengirimanDenganJam,
                                'kode_produksi' => $kodeProduksi,
                            ]);
                            break;
                    default:
                        return redirect()->back()->with('error', 'Toko ID tidak valid');
                }

                // Simpan ID pengiriman yang baru dibuat
                $pengirimanIds[] = $pengiriman->id;
            }
        }

        // Jika ada ID pengiriman yang baru dibuat, arahkan ke halaman show
        if (!empty($pengirimanIds)) {
            $firstId = $pengirimanIds[0]; // Ambil ID pengiriman yang pertama
            return redirect()->route('pengiriman_barangjadipesanan.show', $firstId)
                ->with('success', 'Berhasil menambahkan permintaan produk');
        }

        return redirect()->route('pengiriman_barangjadi.index')
            ->with('success', 'Berhasil menambahkan permintaan produk');
    }


    
   
    public function kode($tanggalPengiriman, $tokoId)
{
    // Atur prefix berdasarkan toko_id
    switch ($tokoId) {
        case 1:
            $prefix = 'JKpC'; // Toko Banjaran
            break;
        case 2:
            $prefix = 'JKpD'; // Toko Tegal
            break;
        case 3:
            $prefix = 'JKpB'; // Toko Slawi
            break;
        case 4:
            $prefix = 'JKpE'; // Toko Pemalang
            break;
        case 5:
            $prefix = 'JKpF'; // Toko Bumiayu
            break;
        case 6:
            $prefix = 'JKpG'; // Toko Bumiayu
            break;
        default:
            $prefix = 'JKp'; // Default prefix jika toko_id tidak valid
            break;
    }

    $year = Carbon::parse($tanggalPengiriman)->format('y'); // Dua digit terakhir dari tahun berdasarkan tanggal pengiriman
    $monthDay = Carbon::parse($tanggalPengiriman)->format('dm'); // Format bulan dan hari: MMDD berdasarkan tanggal pengiriman

    // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix yang sesuai
    $lastBarang = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', 'LIKE', $prefix . '%')
                                  ->whereDate('tanggal_pengiriman', Carbon::parse($tanggalPengiriman)) // Sesuaikan dengan tanggal pengiriman
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
        $detailStokBarangJadi = Pengiriman_barangjadipesanan::where('id', $id)->value('kode_pengirimanpesanan');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadipesanan::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengirimanpesanan', $detailStokBarangJadi)->get();
    
        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.pengiriman_barangjadipesanan.show', compact('groupedByKlasifikasi', 'firstItem'));
    }


    public function print($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadipesanan::where('id', $id)->value('kode_pengirimanpesanan');
            
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadipesanan::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengirimanpesanan', $detailStokBarangJadi)->get();

        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('admin.pengiriman_barangjadipesanan.print', compact('groupedByKlasifikasi', 'firstItem'));

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