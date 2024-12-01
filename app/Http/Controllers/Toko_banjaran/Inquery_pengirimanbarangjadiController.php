<?php

namespace App\Http\Controllers\Toko_banjaran;

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
use Maatwebsite\Excel\Facades\Excel;




class Inquery_pengirimanbarangjadiController extends Controller{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_pengiriman = $request->tanggal_pengiriman;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pengiriman_barangjadi::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
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
                // Jika tidak ada filter tanggal, tampilkan data hari ini
                $query->whereDate('tanggal_pengiriman', Carbon::today());
            }

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pengiriman');

            return view('toko_banjaran.inquery_pengirimanbarangjadi.index', compact('stokBarangJadi'));
    }

    
    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengiriman_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_pengiriman', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('toko_banjaran.inquery_pengirimanbarangjadi.show', compact('pengirimanBarangJadi', 'firstItem'));
    }


        public function unpost_pengirimanbarangjadi($id)
        {
            // Ambil data stok barang berdasarkan ID
            $stok = Pengiriman_barangjadi::where('id', $id)->first();
        
            // Pastikan data ditemukan
            if (!$stok) {
                return back()->with('error', 'Data tidak ditemukan.');
            }
        
            // Ambil kode_input dari stok yang diambil
            $kodeInput = $stok->kode_pengiriman;
        
            // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
            Pengiriman_barangjadi::where('kode_pengiriman', $kodeInput)->update([
                'status' => 'unpost'
            ]);
            return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
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

        // public function posting_pengirimanbarangjadi($id)
        // {
        //     // Ambil data stok barang berdasarkan ID
        //     $stok = Pengiriman_barangjadi::where('id', $id)->first();

        //     // Pastikan data ditemukan
        //     if (!$stok) {
        //         return back()->with('error', 'Data tidak ditemukan.');
        //     }

        //     // Ambil kode_input dari stok yang diambil
        //     $kodeInput = $stok->kode_pengiriman;

        //     // Update status untuk semua stok dengan kode_pengiriman yang sama di tabel Pengiriman_barangjadi
        //     Pengiriman_barangjadi::where('kode_pengiriman', $kodeInput)->update([
        //         'status' => 'posting'
        //     ]);

        //     return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
        // }


    public function print($id)
    {
        // $permintaanProduk = PermintaanProduk::where('id', $id)->firstOrFail();
        
        // $detailPermintaanProduks = $permintaanProduk->detailpermintaanproduks;
        $permintaanProduk = PermintaanProduk::find($id);
        $detailPermintaanProduks = DetailPermintaanProduk::where('permintaanproduk_id', $id)->get();
    
        // Mengelompokkan produk berdasarkan divisi
        $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
            return $item->produk->klasifikasi->nama; // Ganti dengan nama divisi jika diperlukan
        });
    
        // Menghitung total jumlah per divisi
        $totalPerDivisi = $produkByDivisi->map(function($produks) {
            return $produks->sum('jumlah');
        });
        $toko = $detailPermintaanProduks->first()->toko;

        $pdf = FacadePdf::loadView('toko_banjaran.permintaan_produk.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi','toko'));

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
        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $klasifikasis = Klasifikasi::all(); // Menyediakan daftar klasifikasi

        return view('toko_banjaran.stok_barangjadi.edit', compact('stok_barangjadi', 'klasifikasis'));
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
        
            return redirect('toko_banjaran/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
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
            return view('toko_banjaran.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}