<?php

namespace App\Http\Controllers\Toko_cilacap;

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
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokocilacap;
use App\Models\Stok_tokocilacap;
use App\Models\Stok_tokotegal;
use App\Models\Stokpesanan_tokocilacap;
use App\Models\Stokpesanan_tokotegal;
use Maatwebsite\Excel\Facades\Excel;




class Pengirimanpemesanan_tokocilacapController extends Controller{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        $query = Pengirimanpemesanan_tokocilacap::with(['pengiriman_barangjadipesanan.produk.klasifikasi']);
    
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
                return $item->pengiriman_barangjadipesanan ? $item->pengiriman_barangjadipesanan->kode_pengirimanpesanan : 'undefined';
            });
    
        return view('toko_cilacap.pengirimanpemesanan_tokocilacap.index', compact('stokBarangJadi'));
    }


    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengirimanpemesanan_tokocilacap::where('id', $id)->value('kode_pengirimanpesanan');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengirimanpemesanan_tokocilacap::with(['produk.subklasifikasi', 'toko'])->where('kode_pengirimanpesanan', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('toko_cilacap.pengirimanpemesanan_tokocilacap.show', compact('pengirimanBarangJadi', 'firstItem'));
    }


    public function print($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengirimanpemesanan_tokocilacap::where('id', $id)->value('kode_pengirimanpesanan');
                
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengirimanpemesanan_tokocilacap::with(['produk.subklasifikasi', 'toko'])->where('kode_pengirimanpesanan', $detailStokBarangJadi)->get();

        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('toko_cilacap.pengirimanpemesanan_tokocilacap.print', compact('detailStokBarangJadi', 'pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }



    public function posting_pengirimanpemesanan($id)
    {
        // Ambil data stok_tokobanjaran berdasarkan ID
        $stok = Pengirimanpemesanan_tokocilacap::find($id);
    
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
    
        // Ambil semua produk yang terkait dengan pengiriman
        $productsInPengiriman = Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $kodePengiriman)->get();
    
        foreach ($productsInPengiriman as $pengirimanItem) {
            // Ambil detail stok barang jadi terkait produk ini
            $detailStoks = Detail_stokbarangjadi::where('produk_id', $pengirimanItem->produk_id)->get();
            $totalStok = $detailStoks->sum('stok');
    
            // Cek apakah stok cukup untuk jumlah pengiriman
            if ($totalStok < $pengirimanItem->jumlah) {
                return response()->json(['error' => 'Stok tidak cukup untuk melakukan posting.'], 400);
            }
    
            // Kurangi stok dari Detail_stokbarangjadi berdasarkan jumlah pengiriman
            $remaining = $pengirimanItem->jumlah;
            foreach ($detailStoks as $detailStok) {
                if ($remaining > 0) {
                    if ($detailStok->stok >= $remaining) {
                        $detailStok->stok -= $remaining;
                        $detailStok->save();
                        $remaining = 0; // Pengurangan stok sudah mencukupi
                    } else {
                        $remaining -= $detailStok->stok;
                        $detailStok->stok = 0;
                        $detailStok->save();
                    }
                } else {
                    break; // Jika tidak ada sisa yang perlu dikurangi, hentikan loop
                }
            }
    
            // Tambahkan jumlah ke stok di Stokpesanan_tokobanjaran
            $stokToko = Stokpesanan_tokocilacap::firstOrCreate(
                ['produk_id' => $pengirimanItem->produk_id],
                ['jumlah' => 0]
            );
            $stokToko->jumlah += $pengirimanItem->jumlah;
            $stokToko->save();
        }
    
        // Update status untuk semua stok_tokobanjaran dengan kode_pengiriman yang sama
        Pengirimanpemesanan_tokocilacap::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);
    
        // Update status untuk pengiriman_barangjadi
        Pengiriman_barangjadipesanan::where('kode_pengirimanpesanan', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);
    
        return response()->json(['success' => 'Berhasil mengubah status dan memperbarui stok.']);
    }
    




public function unpost_pengiriman($id)
        {
            // Ambil data stok_tokoslawi berdasarkan ID
    $stok = Stok_tokocilacap::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$stok) {
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // Ambil kode_pengiriman dari stok yang diambil
    $kodePengiriman = $stok->kode_pengiriman;
    
    // Ambil pengiriman_barangjadi_id dari stok yang diambil
    $pengirimanId = $stok->pengiriman_barangjadi_id;

    // Update status untuk semua stok_tokoslawi dengan kode_pengiriman yang sama
    Stok_tokocilacap::where('kode_pengiriman', $kodePengiriman)->update([
        'status' => 'unpost'
    ]);

    // Update status untuk pengiriman_barangjadi berdasarkan pengiriman_barangjadi_id
    Pengiriman_barangjadi::where('id', $pengirimanId)->update([
        'status' => 'unpost'
    ]);

    return response()->json(['success' => 'Berhasil mengubah status di stok_tokoslawi dan pengiriman_barangjadi.']);
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
        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $klasifikasis = Klasifikasi::all(); // Menyediakan daftar klasifikasi

        return view('toko_cilacap.stok_barangjadi.edit', compact('stok_barangjadi', 'klasifikasis'));
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
        
            return redirect('toko_cilacap/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
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
            return view('toko_cilacap.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}