<?php

namespace App\Http\Controllers\Toko_tegal;

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
use App\Models\Stok_barangjadi;
use App\Models\Stok_tokobanjaran;
use App\Models\Stokpesanan_tokobanjaran;
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
use App\Models\Stok_tokoslawi;
use App\Models\Pengiriman_tokotegal;
use App\Models\Pengirimanpemesanan_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokotegal;
use App\Models\Stok_tokotegal;
use App\Models\Stokhasilproduksi;
use App\Models\Stokpesanan_tokotegal;
use Maatwebsite\Excel\Facades\Excel;




class Pengiriman_tokotegalController extends Controller{


    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        // Mengambil data stok_tokoslawi dengan relasi pengiriman_barangjadi dan produk
        $query = Pengiriman_tokotegal::with(['pengiriman_barangjadi.produk.klasifikasi']);
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal_input dan tanggal_akhir
        if ($tanggal_input && $tanggal_akhir) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        } elseif ($tanggal_input) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $query->where('tanggal_input', '>=', $tanggal_input);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_input', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_input', Carbon::today());
        }
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pengiriman
        $stokBarangJadi = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                // Memeriksa apakah pengiriman_barangjadi ada sebelum mengakses kode_pengiriman
                return $item->pengiriman_barangjadi ? $item->pengiriman_barangjadi->kode_pengiriman : 'undefined';
            });
    
        return view('toko_tegal.pengiriman_tokotegal.index', compact('stokBarangJadi'));
    }

    public function pengiriman_pemesanan(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        $query = Pengirimanpemesanan_tokobanjaran::with(['pengiriman_barangjadi.produk.klasifikasi']);
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal_input dan tanggal_akhir
        if ($tanggal_input && $tanggal_akhir) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        } elseif ($tanggal_input) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $query->where('tanggal_input', '>=', $tanggal_input);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_input', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_input', Carbon::today());
        }
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pengiriman
        $stokBarangJadi = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                // Memeriksa apakah pengiriman_barangjadi ada sebelum mengakses kode_pengiriman
                return $item->pengiriman_barangjadi ? $item->pengiriman_barangjadi->kode_pengirimanpesanan : 'undefined';
            });
    
        return view('toko_tegal.pengiriman_tokotegal.pengiriman_pemesanan', compact('stokBarangJadi'));
    }
    
    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_tokotegal::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengiriman_tokotegal::with(['produk.subklasifikasi', 'toko'])->where('kode_pengiriman', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('toko_tegal.pengiriman_tokotegal.show', compact('pengirimanBarangJadi', 'firstItem'));
    }


    public function showpemesanan($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengirimanpemesanan_tokobanjaran::where('id', $id)->value('kode_pengirimanpesanan');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengirimanpemesanan_tokobanjaran::with(['produk.subklasifikasi', 'toko'])->where('kode_pengirimanpesanan', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('toko_tegal.pengiriman_tokotegal.showpemesanan', compact('pengirimanBarangJadi', 'firstItem'));
    }

    public function print($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_tokotegal::where('id', $id)->value('kode_pengiriman');
                
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengiriman_tokotegal::with(['produk.subklasifikasi', 'toko'])->where('kode_pengiriman', $detailStokBarangJadi)->get();

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('toko_tegal.pengiriman_tokotegal.print', compact('detailStokBarangJadi', 'pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }


    public function printpemesanan($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengirimanpemesanan_tokobanjaran::where('id', $id)->value('kode_pengirimanpesanan');
                
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengirimanpemesanan_tokobanjaran::with(['produk.subklasifikasi', 'toko'])->where('kode_pengirimanpesanan', $detailStokBarangJadi)->get();

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('toko_tegal.pengiriman_tokotegal.printpemesanan', compact('detailStokBarangJadi', 'pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }




    // baru
    public function posting_pengiriman($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengiriman_tokotegal::where('id', $id)->first();

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
            // Ambil stok barang jadi untuk produk ini
            $stokToko = Stok_tokotegal::where('produk_id', $pengirimanItem->produk_id)->first();
            if ($stokToko) {
                // Tambahkan jumlah ke stok_tokobanjaran
                $stokToko->jumlah += $pengirimanItem->jumlah;
                $stokToko->save();
            } else {
                return response()->json(['error' => 'Stok toko tidak ditemukan untuk produk dengan ID: ' . $pengirimanItem->produk_id], 404);
            }

            // Kurangi stok dari stokhasilproduksi
            $stokHasilProduksi = Stokhasilproduksi::where('produk_id', $pengirimanItem->produk_id)->first();
            if ($stokHasilProduksi && $stokHasilProduksi->jumlah >= $pengirimanItem->jumlah) {
                $stokHasilProduksi->jumlah -= $pengirimanItem->jumlah;
                $stokHasilProduksi->save(); // Simpan perubahan stok
            } else {
                return response()->json(['error' => 'Stok hasil produksi tidak cukup untuk produk dengan ID: ' . $pengirimanItem->produk_id], 400);
            }
        }

        // Update status untuk semua stok_tokobanjaran dengan kode_pengiriman yang sama
        Pengiriman_tokotegal::where('kode_pengiriman', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        // Update status untuk pengiriman_barangjadi
        Pengiriman_barangjadi::where('kode_pengiriman', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        return response()->json(['success' => 'Berhasil mengubah status, memperbarui stok, dan mengurangi stok hasil produksi.']);
    }


    // baru
    public function unpost_pengiriman($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengiriman_tokotegal::where('id', $id)->first();
    
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
            $stokToko = Stok_tokotegal::where('produk_id', $pengirimanItem->produk_id)->first();
            
            if ($stokToko) {
                // Mengurangi jumlah pada stok_tokobanjaran sesuai jumlah pengiriman
                $stokToko->jumlah -= $pengirimanItem->jumlah;
    
                // Jika jumlah stok menjadi negatif, kembalikan error
                if ($stokToko->jumlah < 0) {
                    return response()->json(['error' => 'Stok tidak cukup untuk mengurangi jumlah produk dengan ID: ' . $pengirimanItem->produk_id], 400);
                }
    
                $stokToko->save();
            }
    
            // Ambil stok dari stokhasilproduksi untuk produk ini
            $stokHasilProduksi = Stokhasilproduksi::where('produk_id', $pengirimanItem->produk_id)->first();
            
            if ($stokHasilProduksi) {
                // Mengembalikan jumlah ke stokhasilproduksi
                $stokHasilProduksi->jumlah += $pengirimanItem->jumlah;
                $stokHasilProduksi->save(); // Simpan perubahan stok
            } else {
                return response()->json(['error' => 'Stok hasil produksi tidak ditemukan untuk produk dengan ID: ' . $pengirimanItem->produk_id], 404);
            }
        }
    
        // Update status untuk semua stok_tokobanjaran dengan kode_pengiriman yang sama
        Pengiriman_tokotegal::where('kode_pengiriman', $kodePengiriman)->update([
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
    

 

    // baru
    public function posting_pengirimanpemesanan($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengirimanpemesanan_tokotegal::where('id', $id)->first();

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
            // Ambil stok barang jadi untuk produk ini
            $stokToko = Stokpesanan_tokotegal::where('produk_id', $pengirimanItem->produk_id)->first();
            if ($stokToko) {
                // Tambahkan jumlah ke stok_tokobanjaran
                $stokToko->jumlah += $pengirimanItem->jumlah;
                $stokToko->save();
            } else {
                return response()->json(['error' => 'Stok toko tidak ditemukan untuk produk dengan ID: ' . $pengirimanItem->produk_id], 404);
            }

            // Kurangi stok dari stokhasilproduksi
            $stokHasilProduksi = Stokhasilproduksi::where('produk_id', $pengirimanItem->produk_id)->first();
            if ($stokHasilProduksi && $stokHasilProduksi->jumlah >= $pengirimanItem->jumlah) {
                $stokHasilProduksi->jumlah -= $pengirimanItem->jumlah;
                $stokHasilProduksi->save(); // Simpan perubahan stok
            } else {
                return response()->json(['error' => 'Stok hasil produksi tidak cukup untuk produk dengan ID: ' . $pengirimanItem->produk_id], 400);
            }
        }

        // Update status untuk semua stok_tokobanjaran dengan kode_pengirimanpesanan yang sama
        Pengirimanpemesanan_tokotegal::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        // Update status untuk pengiriman_barangjadi
        Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        return response()->json(['success' => 'Berhasil mengubah status, memperbarui stok, dan mengurangi stok hasil produksi.']);
    }
    


    // baru
    public function unpost_pengirimanpemesanan($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengirimanpemesanan_tokotegal::where('id', $id)->first();
    
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
            $stokToko = Stokpesanan_tokotegal::where('produk_id', $pengirimanItem->produk_id)->first();
            
            if ($stokToko) {
                // Mengurangi jumlah pada stok_tokobanjaran sesuai jumlah pengiriman
                $stokToko->jumlah -= $pengirimanItem->jumlah;
    
                // Jika jumlah stok menjadi negatif, kembalikan error
                if ($stokToko->jumlah < 0) {
                    return response()->json(['error' => 'Stok tidak cukup untuk mengurangi jumlah produk dengan ID: ' . $pengirimanItem->produk_id], 400);
                }
    
                $stokToko->save();
            }
    
            // Ambil stok dari stokhasilproduksi untuk produk ini
            $stokHasilProduksi = Stokhasilproduksi::where('produk_id', $pengirimanItem->produk_id)->first();
            
            if ($stokHasilProduksi) {
                // Mengembalikan jumlah ke stokhasilproduksi
                $stokHasilProduksi->jumlah += $pengirimanItem->jumlah;
                $stokHasilProduksi->save(); // Simpan perubahan stok
            } else {
                return response()->json(['error' => 'Stok hasil produksi tidak ditemukan untuk produk dengan ID: ' . $pengirimanItem->produk_id], 404);
            }
        }
    
        // Update status untuk semua stok_tokobanjaran dengan kode_pengirimanpesanan yang sama
        Pengirimanpemesanan_tokotegal::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);
    
        // Update status untuk Pengiriman_barangjadipesanan
        Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'unpost',
            'tanggal_terima' => null, // Reset tanggal terima
        ]);
    
        return response()->json(['success' => 'Berhasil mengubah status menjadi unpost dan memperbarui stok.']);
    }


    

    public function edit($id)
    {
        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $klasifikasis = Klasifikasi::all(); // Menyediakan daftar klasifikasi

        return view('toko_tegal.stok_barangjadi.edit', compact('stok_barangjadi', 'klasifikasis'));
    }

    // Method untuk memproses update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'klasifikasi_id' => 'required|exists:klasifikasis,id',
            'produk' => 'required|array',
            'produk.*.stok' => 'required|integer|min:0',
        ]);

        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $stok_barangjadi->klasifikasi_id = $request->klasifikasi_id;
        $stok_barangjadi->save();

        // Update stok produk
        foreach ($request->produk as $produkId => $data) {
            // Lakukan update stok produk sesuai kebutuhan
            // Misalnya, update stok produk dalam pivot table jika ada
            $stok_barangjadi->produks()->updateExistingPivot($produkId, ['stok' => $data['stok']]);
        }

        return redirect()->route('stokbarangjadi.edit', $id)->with('success', 'Data berhasil diperbarui!');
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
        
            return redirect('toko_tegal/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
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
            return view('toko_tegal.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}