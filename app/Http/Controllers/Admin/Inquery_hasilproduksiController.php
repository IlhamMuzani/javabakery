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
use App\Models\Stok_tokoslawi;
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
use App\Models\Stok_barangjadi;
use App\Models\Detail_stokbarangjadi;
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
use App\Models\Hasilproduksi;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Stok_tokobanjaran;
use App\Models\Subklasifikasi;
use Maatwebsite\Excel\Facades\Excel;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;




class Inquery_hasilproduksiController extends Controller{


    
    // public function index(Request $request)
    // {
    //     $status = $request->status;
    //     $tanggal_hasilproduksi = $request->tanggal_hasilproduksi;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $toko_id = $request->toko_id; 

    //     $query = Hasilproduksi::with(['produk.klasifikasi', 'toko', 'detailhasilproduksi.produk.klasifikasi']);

    //     if ($status) {
    //         $query->where('status', $status);
    //     }

    //     if ($toko_id) {
    //         $query->where('toko_id', $toko_id);
    //     }

    //     if ($tanggal_hasilproduksi && $tanggal_akhir) {
    //         $tanggal_hasilproduksi = Carbon::parse($tanggal_hasilproduksi)->startOfDay();
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $query->whereBetween('tanggal_hasilproduksi', [$tanggal_hasilproduksi, $tanggal_akhir]);
    //     } elseif ($tanggal_hasilproduksi) {
    //         $tanggal_hasilproduksi = Carbon::parse($tanggal_hasilproduksi)->startOfDay();
    //         $query->where('tanggal_hasilproduksi', '>=', $tanggal_hasilproduksi);
    //     } elseif ($tanggal_akhir) {
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $query->where('tanggal_hasilproduksi', '<=', $tanggal_akhir);
    //     } else {
    //         $query->whereDate('tanggal_hasilproduksi', Carbon::today());
    //     }

    //     $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_hasilproduksi');
    //     $tokos = Toko::all();

    //     return view('admin.inquery_hasilproduksi.index', compact('stokBarangJadi', 'tokos'));
    // }
    public function index(Request $request)
{
    $status = $request->status;
    $tanggal_hasilproduksi = $request->tanggal_hasilproduksi;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id; 

    $query = Hasilproduksi::with([
        'produk.klasifikasi', 
        'toko', 
        'detailhasilproduksi' => function($query) {
            $query->with('produk')->orderBy('kode_lama', 'asc'); // Mengurutkan berdasarkan kode_lama produk
        }
    ]);

    if ($status) {
        $query->where('status', $status);
    }

    if ($toko_id) {
        $query->where('toko_id', $toko_id);
    }

    if ($tanggal_hasilproduksi && $tanggal_akhir) {
        $tanggal_hasilproduksi = Carbon::parse($tanggal_hasilproduksi)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_hasilproduksi', [$tanggal_hasilproduksi, $tanggal_akhir]);
    } elseif ($tanggal_hasilproduksi) {
        $tanggal_hasilproduksi = Carbon::parse($tanggal_hasilproduksi)->startOfDay();
        $query->where('tanggal_hasilproduksi', '>=', $tanggal_hasilproduksi);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->where('tanggal_hasilproduksi', '<=', $tanggal_akhir);
    } else {
        $query->whereDate('tanggal_hasilproduksi', Carbon::today());
    }

    // Group hasil produksi by kode_hasilproduksi
    $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_hasilproduksi');
    $tokos = Toko::all();

    return view('admin.inquery_hasilproduksi.index', compact('stokBarangJadi', 'tokos'));
}


    public function showPrintQr($id)
    {


        $query = Pengiriman_barangjadi::with(['produk.klasifikasi', 'toko']);


        // Hitung jumlah pengiriman dengan status 'unpost'
        $unpostCount = Pengiriman_barangjadi::where('status', 'unpost')->count();

        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_hasilproduksi');
        $tokos = Toko::all();

        return view('admin.inquery_pengirimanbarangjadi.print_qr', compact('stokBarangJadi', 'tokos', 'unpostCount'));
    }
    


    public function show($id)
    {
        // Ambil kode_hasilproduksi dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_hasilproduksi');
        
        // Jika kode_hasilproduksi tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_hasilproduksi yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_hasilproduksi', $detailStokBarangJadi)->get();
    
        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.inquery_pengirimanbarangjadi.show', compact('groupedByKlasifikasi', 'firstItem'));
    }

    public function edit($id)
    {
        // Ambil pengiriman berdasarkan ID
        $pengiriman = Pengiriman_barangjadi::findOrFail($id);

        // Ambil stok barang yang sesuai dengan kode pengiriman
        $stokBarangJadi = Pengiriman_barangjadi::with(['produk', 'toko'])
            ->where('kode_hasilproduksi', $pengiriman->kode_hasilproduksi)
            ->get();

        // Ambil detail stok barang jadi
        $detailStokBarangjadi = Detail_stokbarangjadi::with('produk')->get();

        // Mengakumulasi stok barang jadi
        $uniqueStokBarangjadi = $detailStokBarangjadi->groupBy('produk_id')->map(function ($items) {
            $firstItem = $items->first(); // Ambil entri pertama
            $firstItem->stok = $items->sum('stok'); // Akumulasi stok
            return $firstItem;
        })->values();

        // Ambil semua toko
        $tokos = Toko::all();

        // Ambil salah satu qrcode_pengiriman dan kode_produksi dari pengiriman dengan kode_hasilproduksi yang sama
        $qrcodePengiriman = Pengiriman_barangjadi::where('kode_hasilproduksi', $pengiriman->kode_hasilproduksi)
            ->pluck('qrcode_pengiriman')
            ->first(); // Ambil entri pertama

        $kodeProduksi = Pengiriman_barangjadi::where('kode_hasilproduksi', $pengiriman->kode_hasilproduksi)
            ->pluck('kode_produksi')
            ->first(); // Ambil entri pertama

        // Kirim data ke view
        return view('admin.inquery_pengirimanbarangjadi.edit', compact(
            'stokBarangJadi',
            'tokos',
            'pengiriman',
            'detailStokBarangjadi',
            'uniqueStokBarangjadi',
            'qrcodePengiriman', // Tambahkan qrcode_pengiriman ke view
            'kodeProduksi' // Tambahkan kode_produksi ke view
        ));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'kode_hasilproduksi' => 'required|string',
            'produk_id' => 'required|array',
            'produk_id.*' => 'required|integer',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'qrcode_pengiriman' => 'required|string', 
            'kode_produksi' => 'required|string', 
        ]);
    
        // Ambil data pengiriman berdasarkan ID
        $pengiriman = Pengiriman_barangjadi::findOrFail($id);
    
        $pengiriman->kode_hasilproduksi = $request->kode_hasilproduksi;
        $pengiriman->tanggal_hasilproduksi = now(); 
        $pengiriman->qrcode_pengiriman = $request->qrcode_pengiriman; 
        $pengiriman->kode_produksi = $request->kode_produksi; 
        $pengiriman->save();
    
        // Mengambil detail pengiriman yang sudah ada berdasarkan kode_hasilproduksi
        $existingDetails = Pengiriman_barangjadi::where('kode_hasilproduksi', $pengiriman->kode_hasilproduksi)
            ->get()
            ->keyBy('produk_id'); 
    
        foreach ($request->produk_id as $index => $produkId) {
            $jumlahBaru = $request->jumlah[$index];
    
            // Update atau buat detail pengiriman
            if (isset($existingDetails[$produkId])) {
                $pengirimanDetail = $existingDetails[$produkId];
                if ($pengirimanDetail->jumlah != $jumlahBaru) {
                    $pengirimanDetail->jumlah = $jumlahBaru;
                    $pengirimanDetail->save(); 
                }
            } else {
                // Buat detail baru hanya jika produk_id baru
                $pengirimanDetail = new Pengiriman_barangjadi();
                $pengirimanDetail->kode_hasilproduksi = $pengiriman->kode_hasilproduksi;
                $pengirimanDetail->produk_id = $produkId;
                $pengirimanDetail->jumlah = $jumlahBaru;
                $pengirimanDetail->toko_id = $pengiriman->toko_id; 
                $pengirimanDetail->tanggal_hasilproduksi = now(); 
                $pengirimanDetail->qrcode_pengiriman = $request->qrcode_pengiriman; 
                $pengirimanDetail->kode_produksi = $request->kode_produksi; 
                $pengirimanDetail->status = 'unpost'; 
                $pengirimanDetail->save(); 
            }
        }
    
        // Jika toko_id adalah 1, update atau simpan di pengiriman_tokobanjaran
        if ($pengiriman->toko_id == 1) {
            foreach ($request->produk_id as $index => $produkId) {
                $jumlahBaru = $request->jumlah[$index];
    
                // Cek apakah ada pengiriman yang sudah ada di pengiriman_tokobanjaran
                $pengirimanTokobanjaran = Pengiriman_tokobanjaran::where('kode_hasilproduksi', $pengiriman->kode_hasilproduksi)
                    ->where('produk_id', $produkId)
                    ->first();
    
                if ($pengirimanTokobanjaran) {
                    // Update data yang ada
                    $pengirimanTokobanjaran->jumlah = $jumlahBaru;
                    $pengirimanTokobanjaran->kode_produksi = $request->kode_produksi;
                    $pengirimanTokobanjaran->status = 'unpost'; 
                    $pengirimanTokobanjaran->tanggal_input = now();
                    $pengirimanTokobanjaran->save(); 
                } else {
                    $pengirimanTokobanjaran = new Pengiriman_tokobanjaran();
                    $pengirimanTokobanjaran->produk_id = $produkId;
                    $pengirimanTokobanjaran->toko_id = $pengiriman->toko_id; 
                    $pengirimanTokobanjaran->kode_hasilproduksi = $pengiriman->kode_hasilproduksi;
                    $pengirimanTokobanjaran->jumlah = $jumlahBaru;
                    $pengirimanTokobanjaran->status = 'unpost'; 
                    $pengirimanTokobanjaran->tanggal_input = now();
                    $pengirimanTokobanjaran->kode_produksi = $request->kode_produksi;
                    $pengirimanTokobanjaran->pengiriman_barangjadi_id = $pengiriman->id; 
                    $pengirimanTokobanjaran->save(); 
                }
            }
        }
    
        // Redirect atau kembalikan dengan pesan sukses
        return redirect()->route('admin.inquery_pengirimanbarangjadi.index')
            ->with('success', 'Data pengiriman barang jadi berhasil diperbarui.');
    }


    public function unpost_pengirimanbarangjadi($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengiriman_tokobanjaran::where('id', $id)->first();

        // Pastikan data ditemukan
        if (!$stok) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        // Ambil kode_hasilproduksi dan pengiriman_barangjadi_id dari stok yang diambil
        $kodePengiriman = $stok->kode_hasilproduksi;
        $pengirimanId = $stok->pengiriman_barangjadi_id;

        // Ambil pengiriman terkait dari tabel pengiriman_barangjadi
        $pengiriman = Pengiriman_barangjadi::find($pengirimanId);

        // Pastikan data pengiriman ditemukan
        if (!$pengiriman) {
            return response()->json(['error' => 'Data pengiriman tidak ditemukan.'], 404);
        }

        // Ambil semua produk terkait dengan pengiriman
        $productsInPengiriman = Pengiriman_barangjadi::where('kode_hasilproduksi', $kodePengiriman)->get();

        foreach ($productsInPengiriman as $pengirimanItem) {
            // Ambil stok yang ada di stok_tokobanjaran untuk produk ini
            $stokToko = Stok_tokobanjaran::where('produk_id', $pengirimanItem->produk_id)->first();
            
            if ($stokToko) {
                // Mengurangi jumlah pada stok_tokobanjaran sesuai jumlah pengiriman
                $stokToko->jumlah -= $pengirimanItem->jumlah;

                // Jika jumlah stok menjadi negatif, kembalikan error
                if ($stokToko->jumlah < 0) {
                    return response()->json(['error' => 'Stok tidak cukup untuk mengurangi jumlah produk dengan ID: ' . $pengirimanItem->produk_id], 400);
                }

                $stokToko->save();
            }

            // Ambil semua detail stok barang jadi untuk produk ini, urutkan dari yang paling baru
            $detailStoks = Detail_stokbarangjadi::where('produk_id', $pengirimanItem->produk_id)
                            ->orderBy('created_at', 'desc') // Menggunakan stok yang paling baru dahulu (LIFO)
                            ->get();

            $remaining = $pengirimanItem->jumlah;

            foreach ($detailStoks as $detailStok) {
                if ($remaining > 0) {
                    $detailStok->stok += $remaining; // Mengembalikan jumlah ke detail_stokbarangjadi
                    $detailStok->save();
                    $remaining = 0; // Pengembalian selesai
                } else {
                    break; // Jika tidak ada sisa pengembalian, keluar dari loop
                }
            }
        }

        // Update status untuk semua stok_tokobanjaran dengan kode_hasilproduksi yang sama
        Pengiriman_tokobanjaran::where('kode_hasilproduksi', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);

        // Update status untuk pengiriman_barangjadi
        Pengiriman_barangjadi::where('kode_hasilproduksi', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);

        return response()->json(['success' => 'Berhasil mengubah status menjadi unpost dan memperbarui stok.']);
    }

        
    public function posting_pengirimanbarangjadi($id)
    {
           // Ambil data pengiriman_barangjadi berdasarkan ID
            $pengiriman = Pengiriman_barangjadi::where('id', $id)->first();
        
            // Pastikan data ditemukan
            if (!$pengiriman) {
                return response()->json(['error' => 'Data tidak ditemukan.'], 404);
            }
        
            // Ambil kode_hasilproduksi dari pengiriman yang diambil
            $kodePengiriman = $pengiriman->kode_hasilproduksi;
        
            // Update status untuk semua pengiriman_barangjadi dengan kode_hasilproduksi yang sama
            Pengiriman_barangjadi::where('kode_hasilproduksi', $kodePengiriman)->update([
                'status' => 'posting'
            ]);
        
            // Update status untuk semua stok_tokoslawi terkait dengan pengiriman_barangjadi_id
            Stok_tokoslawi::where('pengiriman_barangjadi_id', $id)->update([
                'status' => 'posting'
            ]);
        
            return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_hasilproduksi yang sama.']);
    }

    public function print($id)
    {
            // Ambil kode_hasilproduksi dari pengiriman_barangjadi berdasarkan id
            $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_hasilproduksi');
                
            // Jika kode_hasilproduksi tidak ditemukan, tampilkan pesan error
            if (!$detailStokBarangJadi) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
            
            // Ambil semua data dengan kode_hasilproduksi yang sama, termasuk relasi ke klasifikasi
            $pengirimanBarangJadi = Pengiriman_barangjadi::with([
                'produk.subklasifikasi.klasifikasi', 
                'toko'
            ])->where('kode_hasilproduksi', $detailStokBarangJadi)->get();
    
            // Kelompokkan data berdasarkan klasifikasi
            $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
                return $item->produk->subklasifikasi->klasifikasi->nama;
            });
    
            // Ambil item pertama untuk informasi toko
            $firstItem = $pengirimanBarangJadi->first();
            $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.print', compact('groupedByKlasifikasi', 'firstItem'));
    
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


    // public function cetak_barcode($id)
    // {
    //     $produk = Produk::findOrFail($id); 

    //     $klasifikasis = Klasifikasi::all();
    //     $subklasifikasis = Subklasifikasi::all();
        

    //     $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis'));

    //     $pdf->setPaper([0, 0, 612, 400], 'portrait'); 
    //     return $pdf->stream('penjualan.pdf');
    // }

    public function cetak_barcode($id)
    {
        // Ambil produk berdasarkan id
        $produk = Produk::findOrFail($id); 

        // Query untuk mengambil kode_produksi dari tabel pengiriman_barangjadi berdasarkan produk_id
        $pengiriman = Pengiriman_barangjadi::where('produk_id', $id)->first();

        // Jika data pengiriman ditemukan, ambil kode_produksinya
        $kodeProduksi = $pengiriman ? $pengiriman->kode_produksi : null;

        // Ambil data klasifikasi dan subklasifikasi
        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();

        // Load view dengan data yang dibutuhkan, termasuk kode produksi
        $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis', 'kodeProduksi'));

        // Set ukuran kertas dan orientasi
        $pdf->setPaper([0, 0, 612, 400], 'portrait'); 

        // Stream PDF hasil cetak
        return $pdf->stream('penjualan.pdf');
    }

    // public function cetak_banyak_barcode(Request $request)
    // {
    //     // Ambil produk berdasarkan array id yang dipilih
    //     $produkIds = $request->input('selected_items', []);
    //     $produkList = Produk::whereIn('id', $produkIds)->get();
    
    //     // Ambil kode produksi untuk setiap produk dan generate QR code
    //     $produkList->map(function($produk) {
    //         $pengiriman = Pengiriman_barangjadi::where('produk_id', $produk->id)->first();
    //         $produk->kode_produksi = $pengiriman ? $pengiriman->kode_produksi : null;
    
    //         // Generate QR code
    //         $qrcode = new Writer(new ImageRenderer(new RendererStyle(60), new SvgImageBackEnd()));
    //         $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
    
    //         // Encode the QR code to base64 for Blade
    //         $produk->qrcode_base64 = base64_encode($qrcodeData);
    //         return $produk;
    //     });
    
    //     // Load view untuk cetak barcode
    //     $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_banyak_barcode', compact('produkList'));
    
    //     // Set ukuran kertas dan orientasi
    //     $pdf->setPaper([0, 0, 612, 400], 'portrait'); 
    
    //     // Stream PDF hasil cetak
    //     return $pdf->stream('banyak_barcode.pdf');
    // }

    public function cetak_banyak_barcode(Request $request)
    {
        // Ambil produk berdasarkan array id yang dipilih
        $produkIds = $request->input('selected_items', []);
        $produkList = Produk::whereIn('id', $produkIds)->get();
    
        // Koleksi untuk menyimpan produk yang akan dicetak
        $outputProdukList = collect(); 
    
        // Ambil jumlah pengiriman untuk setiap produk
        $jumlahPengiriman = Pengiriman_barangjadi::select('produk_id', DB::raw('SUM(jumlah) as total'))
            ->whereIn('produk_id', $produkIds)
            ->groupBy('produk_id')
            ->pluck('total', 'produk_id');
    
        // Proses setiap produk
        $produkList->each(function($produk) use ($outputProdukList, $jumlahPengiriman) {
            $produk->jumlah = $jumlahPengiriman->get($produk->id, 0); // Dapatkan jumlah atau 0 jika tidak ada
            $pengiriman = Pengiriman_barangjadi::where('produk_id', $produk->id)->first();
            $produk->kode_produksi = $pengiriman ? $pengiriman->kode_produksi : null;
    
            // Ulangi sesuai jumlah produk
            for ($i = 0; $i < $produk->jumlah; $i++) {
                // Generate QR code
                $qrcode = new Writer(new ImageRenderer(new RendererStyle(60), new SvgImageBackEnd()));
                $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
                
                // Encode the QR code to base64 for Blade
                $produk->qrcode_base64 = base64_encode($qrcodeData);
                
                // Clone produk untuk output dan simpan di koleksi
                $outputProdukList->push(clone $produk);
            }
        });
    
        // Kembali ke view dengan mengirimkan produkList
        return view('admin.inquery_pengirimanbarangjadi.cetak_banyak_barcode', compact('outputProdukList'));
    }
    
    


    
    public function deleteprodukpengiriman($id)
    {
        // Temukan item berdasarkan ID
        $item = Pengiriman_barangjadi::find($id);

        // Pastikan item ditemukan
        if (!$item) {
            return response()->json(['message' => 'Detail Faktur tidak ditemukan'], 404);
        }

        // Simpan kode_hasilproduksi untuk referensi
        $kodePengiriman = $item->kode_hasilproduksi;

        // Hapus item dari pengiriman_barangjadi
        $item->delete();

        // Hapus semua produk terkait dari pengiriman_tokobanjaran berdasarkan kode_hasilproduksi
        Pengiriman_tokobanjaran::where('kode_hasilproduksi', $kodePengiriman)
            ->where('produk_id', $item->produk_id) // Hanya menghapus yang sesuai produk_id
            ->delete();

        return response()->json(['message' => 'Produk berhasil dihapus dari pengiriman.']);
    }

    
}