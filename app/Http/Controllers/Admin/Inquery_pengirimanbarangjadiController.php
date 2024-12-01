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
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengiriman_tokobumiayu;
use App\Models\Pengiriman_tokocilacap;
use App\Models\Pengiriman_tokopemalang;
use App\Models\Pengiriman_tokoslawi;
use App\Models\Pengiriman_tokotegal;
use App\Models\Stok_tokobanjaran;
use App\Models\Subklasifikasi;
use Maatwebsite\Excel\Facades\Excel;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use SimpleSoftwareIO\QrCode\Facades\QrCode;





class Inquery_pengirimanbarangjadiController extends Controller{


    
    public function index(Request $request)
{
    $status = $request->status;
    $tanggal_pengiriman = $request->tanggal_pengiriman;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id; 

    $query = Pengiriman_barangjadi::with(['produk.klasifikasi', 'toko']);

    if ($status) {
        $query->where('status', $status);
    }

    if ($toko_id) {
        $query->where('toko_id', $toko_id);
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

    // Hitung jumlah pengiriman dengan status 'unpost'
    $unpostCount = Pengiriman_barangjadi::where('status', 'unpost')->count();

    $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pengiriman');
    $tokos = Toko::all();

    return view('admin.inquery_pengirimanbarangjadi.index', compact('stokBarangJadi', 'tokos', 'unpostCount'));
}

public function showPrintQr($id)
{


    $query = Pengiriman_barangjadi::with(['produk.klasifikasi', 'toko']);


    // Hitung jumlah pengiriman dengan status 'unpost'
    $unpostCount = Pengiriman_barangjadi::where('status', 'unpost')->count();

    $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pengiriman');
    $tokos = Toko::all();

    return view('admin.inquery_pengirimanbarangjadi.print_qr', compact('stokBarangJadi', 'tokos', 'unpostCount'));
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
        
        return view('admin.inquery_pengirimanbarangjadi.show', compact('groupedByKlasifikasi', 'firstItem'));
    }

    public function edit($id)
    {
        // Ambil pengiriman berdasarkan ID
        $pengiriman = Pengiriman_barangjadi::findOrFail($id);

        // Ambil stok barang yang sesuai dengan kode pengiriman
        $stokBarangJadi = Pengiriman_barangjadi::with(['produk', 'toko'])
            ->where('kode_pengiriman', $pengiriman->kode_pengiriman)
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

        // Ambil salah satu qrcode_pengiriman dan kode_produksi dari pengiriman dengan kode_pengiriman yang sama
        $qrcodePengiriman = Pengiriman_barangjadi::where('kode_pengiriman', $pengiriman->kode_pengiriman)
            ->pluck('qrcode_pengiriman')
            ->first(); // Ambil entri pertama

        $kodeProduksi = Pengiriman_barangjadi::where('kode_pengiriman', $pengiriman->kode_pengiriman)
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



    // public function update(Request $request, $id)
    // {
    //     // Validasi input
    //     $validatedData = $request->validate([
    //         'kode_pengiriman' => 'required|string|max:255',
    //         'toko_id' => 'required|integer',
    //         'qrcode_pengiriman' => 'nullable|string|max:255',
    //         'produk_id' => 'required|array',
    //         'jumlah' => 'required|array',
    //         'tanggal_pengiriman' => 'required',
    //         'kode_produksi' => 'required|string|max:255', 

    //     ]);

    //     // Temukan pengiriman berdasarkan ID
    //     $pengiriman = Pengiriman_barangjadi::findOrFail($id);

    //     // Update kolom utama
    //     $pengiriman->kode_pengiriman = $validatedData['kode_pengiriman'];
    //     $pengiriman->tanggal_pengiriman = $validatedData['tanggal_pengiriman'];
    //     $pengiriman->toko_id = $validatedData['toko_id'];
    //     $pengiriman->qrcode_pengiriman = $validatedData['qrcode_pengiriman'];
    //     $pengiriman->kode_produksi = $validatedData['kode_produksi'];

    //     // Simpan perubahan utama
    //     $pengiriman->save();

    //     // Loop untuk memperbarui produk_id dan jumlah di tabel pengiriman_barangjadi
    //     foreach ($validatedData['produk_id'] as $index => $produkId) {
    //         $jumlah = $validatedData['jumlah'][$index];

    //         // Cek jika produk_id sudah ada dalam pengiriman ini
    //         $existingPengiriman = Pengiriman_barangjadi::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //             ->where('produk_id', $produkId)
    //             ->first();

    //         if ($existingPengiriman) {
    //             // Jika produk sudah ada, update jumlahnya
    //             $existingPengiriman->jumlah = $jumlah;
    //             $existingPengiriman->save();
    //         } else {
    //             // Jika produk baru, buat entry baru
    //             $newPengiriman = Pengiriman_barangjadi::create([
    //                 'produk_id' => $produkId,
    //                 'jumlah' => $jumlah,
    //                 'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                 'toko_id' => $validatedData['toko_id'],
    //                 'qrcode_pengiriman' => $validatedData['qrcode_pengiriman'],
    //                 'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'], 
    //                 'kode_produksi' => $validatedData['kode_produksi'], 
    //                 'status' => 'unpost',
    //             ]);
    //         }
    //     }

    //     // Mengupdate data pada tabel pengiriman sesuai dengan toko_id
    //     switch ($validatedData['toko_id']) {
    //         case 1:
    //             // Lakukan update atau insert pada tabel pengiriman_tokobanjaran
    //             foreach ($validatedData['produk_id'] as $index => $produkId) {
    //                 $jumlah = $validatedData['jumlah'][$index];

    //                 // Cek apakah produk sudah ada pada tabel pengiriman_tokobanjaran
    //                 $pengirimanTokoBanjaran = Pengiriman_tokobanjaran::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //                     ->where('produk_id', $produkId)
    //                     ->first();

    //                 if ($pengirimanTokoBanjaran) {
    //                     // Jika produk sudah ada, update jumlahnya
    //                     $pengirimanTokoBanjaran->jumlah = $jumlah;
    //                     $pengirimanTokoBanjaran->save();
    //                 } else {
    //                     // Jika produk baru, tambahkan entri baru
    //                     Pengiriman_tokobanjaran::create([
    //                         'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                         'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
    //                         'kode_produksi' => $validatedData['kode_produksi'],
    //                         'tanggal_input' => $validatedData['tanggal_pengiriman'], 
    //                         'produk_id' => $produkId,
    //                         'jumlah' => $jumlah,
    //                         'toko_id' => $validatedData['toko_id'],
    //                         'pengiriman_barangjadi_id' => $pengiriman->id, 
    //                         'status' => 'unpost', 
    //                     ]);
    //                 }
    //             }
    //             break;
      
    //         case 2:
    //             // Lakukan update pada tabel pengiriman_tokotegal
    //             foreach ($validatedData['produk_id'] as $index => $produkId) {
    //                 $jumlah = $validatedData['jumlah'][$index];

    //                 $pengirimanTokoTegal = Pengiriman_tokotegal::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //                     ->where('produk_id', $produkId)
    //                     ->first();

    //                 if ($pengirimanTokoTegal) {
    //                     $pengirimanTokoTegal->jumlah = $jumlah;
    //                     $pengirimanTokoTegal->save();
    //                 } else {
    //                     Pengiriman_tokotegal::create([
    //                         'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                         'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
    //                         'tanggal_input' => $validatedData['tanggal_pengiriman'],
    //                         'kode_produksi' => $validatedData['kode_produksi'], 
    //                         'produk_id' => $produkId,
    //                         'jumlah' => $jumlah,
    //                         'toko_id' => $validatedData['toko_id'],
    //                         'pengiriman_barangjadi_id' => $pengiriman->id, 
    //                         'status' => 'unpost', 
    //                     ]);
    //                 }
    //             }
    //             break;

    //             case 3:
    //                 // Lakukan update pada tabel pengiriman_tokotegal
    //                 foreach ($validatedData['produk_id'] as $index => $produkId) {
    //                     $jumlah = $validatedData['jumlah'][$index];
        
    //                     $pengirimanTokoTegal = Pengiriman_tokoslawi::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //                         ->where('produk_id', $produkId)
    //                         ->first();
        
    //                     if ($pengirimanTokoTegal) {
    //                         $pengirimanTokoTegal->jumlah = $jumlah;
    //                         $pengirimanTokoTegal->save();
    //                     } else {
    //                         Pengiriman_tokoslawi::create([
    //                             'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                             'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
    //                             'tanggal_input' => $validatedData['tanggal_pengiriman'],
    //                             'kode_produksi' => $validatedData['kode_produksi'], 
    //                             'produk_id' => $produkId,
    //                             'jumlah' => $jumlah,
    //                             'toko_id' => $validatedData['toko_id'],
    //                             'pengiriman_barangjadi_id' => $pengiriman->id, 
    //                             'status' => 'unpost', 
    //                         ]);
    //                     }
    //                 }
    //                 break;

    //                 case 4:
    //                     // Lakukan update pada tabel pengiriman_tokotegal
    //                     foreach ($validatedData['produk_id'] as $index => $produkId) {
    //                         $jumlah = $validatedData['jumlah'][$index];
            
    //                         $pengirimanTokoTegal = Pengiriman_tokopemalang::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //                             ->where('produk_id', $produkId)
    //                             ->first();
            
    //                         if ($pengirimanTokoTegal) {
    //                             $pengirimanTokoTegal->jumlah = $jumlah;
    //                             $pengirimanTokoTegal->save();
    //                         } else {
    //                             Pengiriman_tokopemalang::create([
    //                                 'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                                 'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
    //                                 'tanggal_input' => $validatedData['tanggal_pengiriman'],
    //                                 'kode_produksi' => $validatedData['kode_produksi'], 
    //                                 'produk_id' => $produkId,
    //                                 'jumlah' => $jumlah,
    //                                 'toko_id' => $validatedData['toko_id'],
    //                                 'pengiriman_barangjadi_id' => $pengiriman->id, 
    //                                 'status' => 'unpost', 
    //                             ]);
    //                         }
    //                     }
    //                     break;

    //                     case 5:
    //                         // Lakukan update pada tabel pengiriman_tokotegal
    //                         foreach ($validatedData['produk_id'] as $index => $produkId) {
    //                             $jumlah = $validatedData['jumlah'][$index];
                
    //                             $pengirimanTokoTegal = Pengiriman_tokobumiayu::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //                                 ->where('produk_id', $produkId)
    //                                 ->first();
                
    //                             if ($pengirimanTokoTegal) {
    //                                 $pengirimanTokoTegal->jumlah = $jumlah;
    //                                 $pengirimanTokoTegal->save();
    //                             } else {
    //                                 Pengiriman_tokobumiayu::create([
    //                                     'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                                     'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
    //                                     'tanggal_input' => $validatedData['tanggal_pengiriman'], 
    //                                     'kode_produksi' => $validatedData['kode_produksi'],
    //                                     'produk_id' => $produkId,
    //                                     'jumlah' => $jumlah,
    //                                     'toko_id' => $validatedData['toko_id'],
    //                                     'pengiriman_barangjadi_id' => $pengiriman->id, 
    //                                     'status' => 'unpost', 
    //                                 ]);
    //                             }
    //                         }
    //                         break;

    //                         case 6:
    //                             // Lakukan update pada tabel pengiriman_tokotegal
    //                             foreach ($validatedData['produk_id'] as $index => $produkId) {
    //                                 $jumlah = $validatedData['jumlah'][$index];
                    
    //                                 $pengirimanTokoTegal = Pengiriman_tokocilacap::where('kode_pengiriman', $validatedData['kode_pengiriman'])
    //                                     ->where('produk_id', $produkId)
    //                                     ->first();
                    
    //                                 if ($pengirimanTokoTegal) {
    //                                     $pengirimanTokoTegal->jumlah = $jumlah;
    //                                     $pengirimanTokoTegal->save();
    //                                 } else {
    //                                     Pengiriman_tokocilacap::create([
    //                                         'kode_pengiriman' => $validatedData['kode_pengiriman'],
    //                                         'tanggal_pengiriman' => $validatedData['tanggal_pengiriman'],
    //                                         'tanggal_input' => $validatedData['tanggal_pengiriman'],
    //                                         'kode_produksi' => $validatedData['kode_produksi'], 
    //                                         'produk_id' => $produkId,
    //                                         'jumlah' => $jumlah,
    //                                         'toko_id' => $validatedData['toko_id'],
    //                                         'pengiriman_barangjadi_id' => $pengiriman->id, 
    //                                         'status' => 'unpost', 
    //                                     ]);
    //                                 }
    //                             }
    //                             break;
    //     }

    //     // Redirect atau kembali dengan pesan sukses
    //     return redirect()->route('admin.inquery_pengirimanbarangjadi.index')->with('success', 'Data pengiriman berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
{
    // Validasi input
    $validatedData = $request->validate([
        'kode_pengiriman' => 'required|string|max:255',
        'toko_id' => 'required|integer',
        'qrcode_pengiriman' => 'nullable|string|max:255',
        'produk_id' => 'required|array',
        'jumlah' => 'required|array',
        'tanggal_pengiriman' => 'required',
        'kode_produksi' => 'required|string|max:255', 
    ]);

    // Temukan pengiriman berdasarkan ID, jika tidak ada, redirect dengan pesan error
    $pengiriman = Pengiriman_barangjadi::find($id);
    if (!$pengiriman) {
        return redirect()->route('admin.inquery_pengirimanbarangjadi.index')
            ->with('error', 'Data pengiriman tidak ditemukan atau telah dihapus.');
    }

    // Update kolom utama
    $pengiriman->kode_pengiriman = $validatedData['kode_pengiriman'];
    $pengiriman->tanggal_pengiriman = $validatedData['tanggal_pengiriman'];
    $pengiriman->toko_id = $validatedData['toko_id'];
    $pengiriman->qrcode_pengiriman = $validatedData['qrcode_pengiriman'];
    $pengiriman->kode_produksi = $validatedData['kode_produksi'];
    $pengiriman->save();

    // Loop untuk memperbarui produk_id dan jumlah
    foreach ($validatedData['produk_id'] as $index => $produkId) {
        $jumlah = $validatedData['jumlah'][$index];

        // Periksa data pengiriman yang ada untuk baris ini
        $existingPengiriman = Pengiriman_barangjadi::where('kode_pengiriman', $validatedData['kode_pengiriman'])
            ->where('produk_id', $produkId)
            ->first();

        if ($existingPengiriman) {
            // Jika produk sudah ada, update jumlahnya
            $existingPengiriman->jumlah = $jumlah;
            $existingPengiriman->save();
        } else {
            // Jika produk baru, buat entry baru
            Pengiriman_barangjadi::create([
                'produk_id' => $produkId,
                'jumlah' => $jumlah,
                'kode_pengiriman' => $validatedData['kode_pengiriman'],
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
    return redirect()->route('admin.inquery_pengirimanbarangjadi.index')
        ->with('success', 'Data pengiriman berhasil diperbarui.');
}

private function updateTokoData($validatedData, $pengiriman)
{
    $tokoClassMapping = [
        1 => Pengiriman_tokobanjaran::class,
        2 => Pengiriman_tokotegal::class,
        3 => Pengiriman_tokoslawi::class,
        4 => Pengiriman_tokopemalang::class,
        5 => Pengiriman_tokobumiayu::class,
        6 => Pengiriman_tokocilacap::class,
    ];

    $tokoModel = $tokoClassMapping[$validatedData['toko_id']] ?? null;

    if ($tokoModel) {
        foreach ($validatedData['produk_id'] as $index => $produkId) {
            $jumlah = $validatedData['jumlah'][$index];

            // Cek apakah data toko terkait sudah ada
            $existingTokoData = $tokoModel::where('kode_pengiriman', $validatedData['kode_pengiriman'])
                ->where('produk_id', $produkId)
                ->first();

            if ($existingTokoData) {
                $existingTokoData->jumlah = $jumlah;
                $existingTokoData->save();
            } else {
                $tokoModel::create([
                    'kode_pengiriman' => $validatedData['kode_pengiriman'],
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


    public function unpost_pengirimanbarangjadi($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengiriman_tokobanjaran::where('id', $id)->first();

        // Pastikan data ditemukan
        if (!$stok) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        // Ambil kode_pengiriman dan pengiriman_barangjadi_id dari stok yang diambil
        $kodePengiriman = $stok->kode_pengiriman;
        $pengirimanId = $stok->pengiriman_barangjadi_id;

        // Ambil pengiriman terkait dari tabel pengiriman_barangjadi
        $pengiriman = Pengiriman_barangjadi::find($pengirimanId);

        // Pastikan data pengiriman ditemukan
        if (!$pengiriman) {
            return response()->json(['error' => 'Data pengiriman tidak ditemukan.'], 404);
        }

        // Ambil semua produk terkait dengan pengiriman
        $productsInPengiriman = Pengiriman_barangjadi::where('kode_pengiriman', $kodePengiriman)->get();

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

        // Update status untuk semua stok_tokobanjaran dengan kode_pengiriman yang sama
        Pengiriman_tokobanjaran::where('kode_pengiriman', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);

        // Update status untuk pengiriman_barangjadi
        Pengiriman_barangjadi::where('kode_pengiriman', $kodePengiriman)->update([
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
        
            // Ambil kode_pengiriman dari pengiriman yang diambil
            $kodePengiriman = $pengiriman->kode_pengiriman;
        
            // Update status untuk semua pengiriman_barangjadi dengan kode_pengiriman yang sama
            Pengiriman_barangjadi::where('kode_pengiriman', $kodePengiriman)->update([
                'status' => 'posting'
            ]);
        
            // Update status untuk semua stok_tokoslawi terkait dengan pengiriman_barangjadi_id
            Stok_tokoslawi::where('pengiriman_barangjadi_id', $id)->update([
                'status' => 'posting'
            ]);
        
            return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_pengiriman yang sama.']);
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

    //baru
    // public function cetak_barcode($id)
    // {
    //     // Ambil produk berdasarkan id
    //     $produk = Produk::findOrFail($id); 
    
    //     // Ambil data pengiriman termasuk 'jumlah' dan 'kode_produksi' dari tabel pengiriman_barangjadi berdasarkan produk_id
    //     $pengiriman = Pengiriman_barangjadi::where('produk_id', $id)->first();
    
    //     // Jika data pengiriman ditemukan, ambil kode_produksi dan jumlah
    //     $jumlah = $pengiriman ? $pengiriman->jumlah : 1; // Default 1 jika jumlah tidak ditemukan
    
    //     // Ambil data klasifikasi dan subklasifikasi
    //     $klasifikasis = Klasifikasi::all();
    //     $subklasifikasis = Subklasifikasi::all();
    
    //     // Generate QR code data
    //     $qrcode = new Writer(new ImageRenderer(new RendererStyle(50), new SvgImageBackEnd()));
    //     $qrcodeData = base64_encode($qrcode->writeString($produk->qrcode_produk));
    
    //     // Load view dengan data yang dibutuhkan
    //     $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis','qrcodeData', 'jumlah'));
    
    //     // Set ukuran kertas dan orientasi
    //     $pdf->setPaper([0, 0, 612, 400], 'portrait'); 
    
    //     // Stream PDF hasil cetak
    //     return $pdf->stream('penjualan.pdf');
    // }

    public function cetak_barcode($id)
{
    // Ambil produk berdasarkan id
    $produk = Produk::findOrFail($id);

    // Ambil semua data pengiriman yang terkait dengan produk_id
    $pengiriman = Pengiriman_barangjadi::where('produk_id', $id)->get();

    // Jika data pengiriman ditemukan, ambil jumlah dan kode_produksi
    // Default: jumlah = 1, kode_produksi = 'Tidak ada'
    $jumlah = $pengiriman->sum('jumlah');
    $kodeProduksi = $pengiriman->pluck('kode_produksi')->toArray(); // Pastikan ini array

    // Ambil data klasifikasi dan subklasifikasi
    $klasifikasis = Klasifikasi::all();
    $subklasifikasis = Subklasifikasi::all();

    // Generate QR code data
    $qrcode = new Writer(new ImageRenderer(new RendererStyle(50), new SvgImageBackEnd()));
    $qrcodeData = base64_encode($qrcode->writeString($produk->qrcode_produk));

    $dataProduk = [
        [
            'produk' => $produk, // Objek Produk
            'qrcodeData' => $qrcodeData, // Data QR Code
            'jumlah' => $jumlah, // Total jumlah barcode yang ingin dicetak
            'kodeProduksi' => $kodeProduksi, // Array kode produksi
        ]
    ];
    // Load view dengan data yang dibutuhkan
    $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_barcode', compact(
        'dataProduk'
    ));

    // Set ukuran kertas dan orientasi
    $pdf->setPaper([0, 0, 612, 500], 'portrait');

    // Stream PDF hasil cetak
    return $pdf->stream('penjualan.pdf');
}


    public function deleteprodukpengiriman($id)
{
    // Temukan item berdasarkan ID
    $item = Pengiriman_barangjadi::find($id);

    // Pastikan item ditemukan
    if (!$item) {
        return response()->json(['message' => 'Detail Faktur tidak ditemukan'], 404);
    }

    // Simpan kode_pengiriman dan produk_id untuk referensi
    $kodePengiriman = $item->kode_pengiriman;
    $produkId = $item->produk_id;

    // Hapus item dari pengiriman_barangjadi
    $item->delete();

    // Daftar tabel toko yang terkait
    $tokoTables = [
        Pengiriman_tokobanjaran::class,
        Pengiriman_tokoslawi::class,
        Pengiriman_tokotegal::class,
        Pengiriman_tokopemalang::class,
        Pengiriman_tokobumiayu::class,
        Pengiriman_tokocilacap::class,
    ];

    // Hapus produk terkait dari semua tabel toko berdasarkan kode_pengiriman dan produk_id
    foreach ($tokoTables as $table) {
        $table::where('kode_pengiriman', $kodePengiriman)
            ->where('produk_id', $produkId)
            ->delete();
    }

    return response()->json(['message' => 'Produk berhasil dihapus dari pengiriman pada semua toko.']);
}


    public function cetakSemuaBarcode(Request $request)
    {
        $selectedProducts = json_decode($request->input('selected_products'), true);
    
        $dataProduk = collect($selectedProducts)->map(function ($item) {
            // Cari produk dan data pengiriman berdasarkan produk_id dan kode_pengiriman
            $produk = Produk::find($item['produk_id']);
            $pengiriman = Pengiriman_barangjadi::where('produk_id', $item['produk_id'])
                ->where('kode_pengiriman', $item['kode_pengiriman'])
                ->first();
    
            if (!$produk || !$pengiriman) {
                return null;
            }
    
            $qrcode = new Writer(new ImageRenderer(new RendererStyle(50), new SvgImageBackEnd()));
            $qrcodeData = base64_encode($qrcode->writeString($produk->qrcode_produk));
    
            return [
                'produk' => $produk,
                'jumlah' => $pengiriman->jumlah,
                'kodeProduksi' => $pengiriman->kode_produksi,
                'qrcodeData' => $qrcodeData,
            ];
        })->filter(); // Filter out any null results
    
        $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_barcode', compact('dataProduk'));
        $pdf->setPaper([0, 0, 612, 400], 'portrait');
    
        return $pdf->stream('barcode_semua_produk.pdf');
    }
    



}