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
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_barangjadipesanan;
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokobumiayu;
use App\Models\Pengirimanpemesanan_tokocilacap;
use App\Models\Pengirimanpemesanan_tokopemalang;
use App\Models\Pengirimanpemesanan_tokoslawi;
use App\Models\Pengirimanpemesanan_tokotegal;
use App\Models\Stokpesanan_tokobanjaran;
use App\Models\Subklasifikasi;
use Maatwebsite\Excel\Facades\Excel;




class Inquery_pengirimanpesananController extends Controller{


    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;  // Ambil toko_id dari request

        $query = Pengiriman_barangjadipesanan::with(['produk.klasifikasi', 'toko']); // Pastikan toko diload

        if ($status) {
            $query->where('status', $status);
        }

        if ($toko_id) {
            $query->where('toko_id', $toko_id); // Tambahkan filter berdasarkan toko_id
        }

        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            $query->whereDate('tanggal_pengiriman', Carbon::today());
        }

        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
        $stokBarangJadi = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pengirimanpesanan');

        // Ambil daftar toko untuk dropdown
        $tokos = Toko::all();

        return view('admin.inquery_pengirimanpesanan.index', compact('stokBarangJadi', 'tokos'));
    }
    
    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadipesanan::where('id', $id)->value('kode_pengirimanpesanan');
        
        // Jika kode_pengirimanpesanan tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengirimanpesanan yang sama, termasuk relasi ke klasifikasi
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
        
        return view('admin.inquery_pengirimanpesanan.show', compact('groupedByKlasifikasi', 'firstItem'));
    }

    public function edit($id)
    {
        // Ambil pengiriman berdasarkan ID
        $pengiriman = Pengiriman_barangjadipesanan::findOrFail($id);

        // Ambil stok barang yang sesuai dengan kode pengiriman
        $stokBarangJadi = Pengiriman_barangjadipesanan::with(['produk', 'toko'])
            ->where('kode_pengirimanpesanan', $pengiriman->kode_pengirimanpesanan)
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

        // Ambil salah satu qrcode_pengiriman dan kode_produksi dari pengiriman dengan kode_pengirimanpesanan yang sama
        $qrcodePengiriman = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $pengiriman->kode_pengirimanpesanan)
            ->pluck('qrcode_pengiriman')
            ->first(); // Ambil entri pertama

        $kodeProduksi = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $pengiriman->kode_pengirimanpesanan)
            ->pluck('kode_produksi')
            ->first(); // Ambil entri pertama

        // Kirim data ke view
        return view('admin.inquery_pengirimanpesanan.edit', compact(
            'stokBarangJadi',
            'tokos',
            'pengiriman',
            'detailStokBarangjadi',
            'uniqueStokBarangjadi',
            'qrcodePengiriman', // Tambahkan qrcode_pengiriman ke view
            'kodeProduksi' // Tambahkan kode_produksi ke view
        ));
    }

    // public function update(Request $request, $id)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'kode_pengirimanpesanan' => 'required|string',
    //         'produk_id' => 'required|array',
    //         'produk_id.*' => 'required|integer',
    //         'jumlah' => 'required|array',
    //         'jumlah.*' => 'required|integer|min:1',
    //         'qrcode_pengiriman' => 'required|string', 
    //         'kode_produksi' => 'required|string', 
    //     ]);
    
    //     // Ambil data pengiriman berdasarkan ID
    //     $pengiriman = Pengiriman_barangjadipesanan::findOrFail($id);
    
    //     $pengiriman->kode_pengirimanpesanan = $request->kode_pengirimanpesanan;
    //     $pengiriman->tanggal_pengiriman = now(); 
    //     $pengiriman->qrcode_pengiriman = $request->qrcode_pengiriman; 
    //     $pengiriman->kode_produksi = $request->kode_produksi; 
    //     $pengiriman->save();
    
    //     // Mengambil detail pengiriman yang sudah ada berdasarkan kode_pengirimanpesanan
    //     $existingDetails = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $pengiriman->kode_pengirimanpesanan)
    //         ->get()
    //         ->keyBy('produk_id'); 
    
    //     foreach ($request->produk_id as $index => $produkId) {
    //         $jumlahBaru = $request->jumlah[$index];
    
    //         // Update atau buat detail pengiriman
    //         if (isset($existingDetails[$produkId])) {
    //             $pengirimanDetail = $existingDetails[$produkId];
    //             if ($pengirimanDetail->jumlah != $jumlahBaru) {
    //                 $pengirimanDetail->jumlah = $jumlahBaru;
    //                 $pengirimanDetail->save(); 
    //             }
    //         } else {
    //             // Buat detail baru hanya jika produk_id baru
    //             $pengirimanDetail = new Pengiriman_barangjadipesanan();
    //             $pengirimanDetail->kode_pengirimanpesanan = $pengiriman->kode_pengirimanpesanan;
    //             $pengirimanDetail->produk_id = $produkId;
    //             $pengirimanDetail->jumlah = $jumlahBaru;
    //             $pengirimanDetail->toko_id = $pengiriman->toko_id; 
    //             $pengirimanDetail->tanggal_pengiriman = now(); 
    //             $pengirimanDetail->qrcode_pengiriman = $request->qrcode_pengiriman; 
    //             $pengirimanDetail->kode_produksi = $request->kode_produksi; 
    //             $pengirimanDetail->status = 'unpost'; 
    //             $pengirimanDetail->save(); 
    //         }
    //     }
    
    //     // Jika toko_id adalah 1, update atau simpan di pengiriman_tokobanjaran
    //     if ($pengiriman->toko_id == 1) {
    //         foreach ($request->produk_id as $index => $produkId) {
    //             $jumlahBaru = $request->jumlah[$index];
    
    //             // Cek apakah ada pengiriman yang sudah ada di pengiriman_tokobanjaran
    //             $pengirimanTokobanjaran = Pengirimanpemesanan_tokobanjaran::where('kode_pengirimanpesanan', $pengiriman->kode_pengirimanpesanan)
    //                 ->where('produk_id', $produkId)
    //                 ->first();
    
    //             if ($pengirimanTokobanjaran) {
    //                 // Update data yang ada
    //                 $pengirimanTokobanjaran->jumlah = $jumlahBaru;
    //                 $pengirimanTokobanjaran->kode_produksi = $request->kode_produksi;
    //                 $pengirimanTokobanjaran->status = 'unpost'; 
    //                 $pengirimanTokobanjaran->tanggal_input = now();
    //                 $pengirimanTokobanjaran->save(); 
    //             } else {
    //                 $pengirimanTokobanjaran = new Pengirimanpemesanan_tokobanjaran();
    //                 $pengirimanTokobanjaran->produk_id = $produkId;
    //                 $pengirimanTokobanjaran->toko_id = $pengiriman->toko_id; 
    //                 $pengirimanTokobanjaran->kode_pengirimanpesanan = $pengiriman->kode_pengirimanpesanan;
    //                 $pengirimanTokobanjaran->jumlah = $jumlahBaru;
    //                 $pengirimanTokobanjaran->status = 'unpost'; 
    //                 $pengirimanTokobanjaran->tanggal_input = now();
    //                 $pengirimanTokobanjaran->kode_produksi = $request->kode_produksi;
    //                 $pengirimanTokobanjaran->pengiriman_barangjadi_id = $pengiriman->id; 
    //                 $pengirimanTokobanjaran->save(); 
    //             }
    //         }
    //     }
    
    //     // Redirect atau kembalikan dengan pesan sukses
    //     return redirect()->route('admin.inquery_pengirimanpesanan.index')
    //         ->with('success', 'Data pengiriman barang jadi berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_pengirimanpesanan' => 'required|string|max:255',
            'toko_id' => 'required|integer',
            'qrcode_pengiriman' => 'nullable|string|max:255',
            'produk_id' => 'required|array',
            'jumlah' => 'required|array',
            'tanggal_pengiriman' => 'required',
            'kode_produksi' => 'required|string|max:255', 
        ]);
    
        // Temukan pengiriman berdasarkan ID, jika tidak ada, redirect dengan pesan error
        $pengiriman = Pengiriman_barangjadipesanan::find($id);
        if (!$pengiriman) {
            return redirect()->route('admin.inquery_pengirimanbarangjadi.index')
                ->with('error', 'Data pengiriman tidak ditemukan atau telah dihapus.');
        }
    
        // Update kolom utama
        $pengiriman->kode_pengirimanpesanan = $validatedData['kode_pengirimanpesanan'];
        $pengiriman->tanggal_pengiriman = $validatedData['tanggal_pengiriman'];
        $pengiriman->toko_id = $validatedData['toko_id'];
        $pengiriman->qrcode_pengiriman = $validatedData['qrcode_pengiriman'];
        $pengiriman->kode_produksi = $validatedData['kode_produksi'];
        $pengiriman->save();
    
        // Loop untuk memperbarui produk_id dan jumlah
        foreach ($validatedData['produk_id'] as $index => $produkId) {
            $jumlah = $validatedData['jumlah'][$index];
    
            // Periksa data pengiriman yang ada untuk baris ini
            $existingPengiriman = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $validatedData['kode_pengirimanpesanan'])
                ->where('produk_id', $produkId)
                ->first();
    
            if ($existingPengiriman) {
                // Jika produk sudah ada, update jumlahnya
                $existingPengiriman->jumlah = $jumlah;
                $existingPengiriman->save();
            } else {
                // Jika produk baru, buat entry baru
                Pengiriman_barangjadipesanan::create([
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'kode_pengirimanpesanan' => $validatedData['kode_pengirimanpesanan'],
                    'toko_id' => $validatedData['toko_id'],
                    'qrcode_pengiriman' => $validatedData['qrcode_pengiriman'],
                    'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
                    'kode_produksi' => $validatedData['kode_produksi'],
                    'status' => 'unpost',
                ]);
            }
        }
    
        // Cek toko_id dan perbarui data toko terkait
        $this->updateTokoData($validatedData, $pengiriman);
    
        // Redirect dengan pesan sukses
        return redirect()->route('admin.inquery_pengirimanpesanan.index')
            ->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    private function updateTokoData($validatedData, $pengiriman)
{
    $tokoClassMapping = [
        1 => Pengirimanpemesanan_tokobanjaran::class,
        2 => Pengirimanpemesanan_tokotegal::class,
        3 => Pengirimanpemesanan_tokoslawi::class,
        4 => Pengirimanpemesanan_tokopemalang::class,
        5 => Pengirimanpemesanan_tokobumiayu::class,
        6 => Pengirimanpemesanan_tokocilacap::class,
    ];

    $tokoModel = $tokoClassMapping[$validatedData['toko_id']] ?? null;

    if ($tokoModel) {
        foreach ($validatedData['produk_id'] as $index => $produkId) {
            $jumlah = $validatedData['jumlah'][$index];

            // Cek apakah data toko terkait sudah ada
            $existingTokoData = $tokoModel::where('kode_pengirimanpesanan', $validatedData['kode_pengirimanpesanan'])
                ->where('produk_id', $produkId)
                ->first();

            if ($existingTokoData) {
                $existingTokoData->jumlah = $jumlah;
                $existingTokoData->save();
            } else {
                $tokoModel::create([
                    'kode_pengirimanpesanan' => $validatedData['kode_pengirimanpesanan'],
                    'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
                    'tanggal_input' => $validatedData['tanggal_pengiriman'],
                    'kode_produksi' => $validatedData['kode_produksi'],
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'toko_id' => $validatedData['toko_id'],
                    'pengiriman_barangjadi_id' => $pengiriman->id,
                    'status' => 'unpost',
                ]);
            }
        }
    }
}
    public function print($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadipesanan::where('id', $id)->value('kode_pengirimanpesanan');
            
        // Jika kode_pengirimanpesanan tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengirimanpesanan yang sama, termasuk relasi ke klasifikasi
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
        $pdf = FacadePdf::loadView('admin.inquery_pengirimanpesanan.print', compact('groupedByKlasifikasi', 'firstItem'));

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

    public function cetak_barcodepesanan($id)
    {
            // Ambil produk berdasarkan id
            $produk = Produk::findOrFail($id); 
        
            // Query untuk mengambil kode_produksi dari tabel pengiriman_barangjadi berdasarkan produk_id
            $pengiriman = Pengiriman_barangjadipesanan::where('produk_id', $id)->first();
        
            // Jika data pengiriman ditemukan, ambil kode_produksinya
            $kodeProduksi = $pengiriman ? $pengiriman->kode_produksi : null;
        
            // Ambil data klasifikasi dan subklasifikasi
            $klasifikasis = Klasifikasi::all();
            $subklasifikasis = Subklasifikasi::all();
        
            // Load view dengan data yang dibutuhkan, termasuk kode produksi
            $pdf = FacadePdf::loadView('admin.inquery_pengirimanpesanan.cetak_barcodepesanan', compact('produk', 'klasifikasis', 'subklasifikasis', 'kodeProduksi'));
        
            // Set ukuran kertas dan orientasi
            $pdf->setPaper([0, 0, 612, 400], 'portrait'); 
        
            // Stream PDF hasil cetak
            return $pdf->stream('penjualan.pdf');
    }

    public function unpost_pengirimanpesanan($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengirimanpemesanan_tokobanjaran::where('id', $id)->first();

        // Pastikan data ditemukan
        if (!$stok) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        // Ambil kode_pengiriman dan pengiriman_barangjadi_id dari stok yang diambil
        $kodePengiriman = $stok->kode_pengirimanpesanan;
        $pengirimanId = $stok->pengiriman_barangjadi_id;

        // Ambil pengiriman terkait dari tabel pengiriman_barangjadi
        $pengiriman = Pengiriman_barangjadipesanan::find($pengirimanId);

        // Pastikan data pengiriman ditemukan
        if (!$pengiriman) {
            return response()->json(['error' => 'Data pengiriman tidak ditemukan.'], 404);
        }

        // Ambil semua produk terkait dengan pengiriman
        $productsInPengiriman = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $kodePengiriman)->get();

        foreach ($productsInPengiriman as $pengirimanItem) {
            // Ambil stok yang ada di stok_tokobanjaran untuk produk ini
            $stokToko = Stokpesanan_tokobanjaran::where('produk_id', $pengirimanItem->produk_id)->first();
            
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

        // Update status untuk semua stok_tokobanjaran dengan kode_pengiriman yang sama
        Pengirimanpemesanan_tokobanjaran::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);

        // Update status untuk pengiriman_barangjadi
        Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);

        return response()->json(['success' => 'Berhasil mengubah status menjadi unpost dan memperbarui stok.']);
    }

    public function deleteprodukpengiriman($id)
    {
        // Temukan item berdasarkan ID
        $item = Pengiriman_barangjadipesanan::find($id);
    
        // Pastikan item ditemukan
        if (!$item) {
            return response()->json(['message' => 'Detail Faktur tidak ditemukan'], 404);
        }
    
        // Simpan kode_pengiriman dan produk_id untuk referensi
        $kodePengiriman = $item->kode_pengirimanpesanan;
        $produkId = $item->produk_id;
    
        // Hapus item dari pengiriman_barangjadi
        $item->delete();
    
        // Daftar tabel toko yang terkait
        $tokoTables = [
            Pengirimanpemesanan_tokobanjaran::class,
            Pengirimanpemesanan_tokoslawi::class,
            Pengirimanpemesanan_tokotegal::class,
            Pengirimanpemesanan_tokopemalang::class,
            Pengirimanpemesanan_tokobumiayu::class,
            Pengirimanpemesanan_tokocilacap::class,
        ];
    
        // Hapus produk terkait dari semua tabel toko berdasarkan kode_pengiriman dan produk_id
        foreach ($tokoTables as $table) {
            $table::where('kode_pengirimanpesanan', $kodePengiriman)
                ->where('produk_id', $produkId)
                ->delete();
        }
    
        return response()->json(['message' => 'Produk berhasil dihapus dari pengiriman pada semua toko.']);
    }
    

}

