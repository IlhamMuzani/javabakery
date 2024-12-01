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
use App\Models\Tokocilacap;
use App\Models\Barang;
use App\Models\Detailbarangjadi;
use App\Models\Detailpemesananproduk;
use App\Models\Detailpenjualanproduk;
use App\Models\Detail_stokbarangjadi;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokotegal;
use App\Models\Retur_tokoslawi;
use App\Models\Pemindahan_tokoslawi;
use App\Models\Pemindahan_tokotegal;
use App\Models\Pemindahan_tokobanjaran;
use App\Models\Pemindahan_tokobanjaranmasuk;
use App\Models\Pemindahan_barangjadi;
use App\Models\Retur_barangjadi;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Pemindahan_tokobumiayu;
use App\Models\Pemindahan_tokocilacap;
use App\Models\Pemindahan_tokopemalang;
use App\Models\Retur_barnagjadi;
use App\Models\Stok_tokobumiayu;
use App\Models\Stok_tokocilacap;
use App\Models\Stok_tokopemalang;
use Maatwebsite\Excel\Facades\Excel;

class Inquery_pemindahanbanjaranController extends Controller{

    
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        // Query untuk pemindahan_tokoslawi
        $query1 = Pemindahan_tokobanjaran::with('produk.klasifikasi');
    
        // Query untuk pemindahan_tokoslawimasuks
        $query2 = Pemindahan_tokobanjaranmasuk::with('produk.klasifikasi');
    
        if ($status) {
            $query1->where('status', $status);
            $query2->where('status', $status);
        }
    
        if ($tanggal_input && $tanggal_akhir) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query1->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
            $query2->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        } elseif ($tanggal_input) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $query1->where('tanggal_input', '>=', $tanggal_input);
            $query2->where('tanggal_input', '>=', $tanggal_input);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query1->where('tanggal_input', '<=', $tanggal_akhir);
            $query2->where('tanggal_input', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query1->whereDate('tanggal_input', Carbon::today());
            $query2->whereDate('tanggal_input', Carbon::today());
        }
    
        // Gabungkan kedua query menggunakan union
        $stokBarangJadi = $query1->union($query2)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pemindahan');
    
        return view('toko_banjaran.inquery_pemindahanbanjaran.index', compact('stokBarangJadi'));
    }






public function posting_pemindahan($id)
{
    // Temukan data pemindahan berdasarkan ID
    $pemindahan = Pemindahan_tokobanjaranmasuk::findOrFail($id);

    // Cek apakah status saat ini adalah 'unpost'
    if ($pemindahan->status == 'unpost') {
        // Update status dan tanggal terima pada tabel pemindahan_tokoslawimasuk
        $pemindahan->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        // Update status dan tanggal terima pada tabel pemindahan_barangjadis
        Pemindahan_barangjadi::where('kode_pemindahan', $pemindahan->kode_pemindahan)
            ->update([
                'status' => 'posting',
                'tanggal_terima' => Carbon::now('Asia/Jakarta'),
            ]);

        // Update status dan tanggal terima pada tabel pemindahan_tokoslawimasuks
        Pemindahan_tokobanjaranmasuk::where('kode_pemindahan', $pemindahan->kode_pemindahan)
            ->update([
                'status' => 'posting',
                'tanggal_terima' => Carbon::now('Asia/Jakarta'),
            ]);
            
            $stok_banjaran = Stok_tokobanjaran::where('produk_id', $pemindahan->produk_id)->first();

            if ($stok_banjaran) {
                // Jika stok sudah ada, tambahkan jumlah
                $stok_banjaran->jumlah += $pemindahan->jumlah;
                $stok_banjaran->save();
            } else {
                // Jika stok belum ada, buat entri baru
                Stok_tokobanjaran::create([
                    'produk_id' => $pemindahan->produk_id,
                    'jumlah' => $pemindahan->jumlah,
                ]);
            }

        // Logika tambahan berdasarkan toko_id
        switch ($pemindahan->toko_id) {
            case 1: // Jika toko_id = 1, update pemindahan_tokobanjaran dan stok_tokobanjaran
                Pemindahan_tokobanjaran::where('kode_pemindahan', $pemindahan->kode_pemindahan)
                    ->update([
                        'status' => 'posting',
                        'tanggal_terima' => Carbon::now('Asia/Jakarta'),
                    ]);

                $stok_banjaran = Stok_tokobanjaran::where('produk_id', $pemindahan->produk_id)
                    ->where('jumlah', '>', 0)
                    ->orderBy('jumlah', 'asc')
                    ->get();

                $this->kurangiStok($stok_banjaran, $pemindahan->jumlah);
                break;

            case 2: // Jika toko_id = 2, update pemindahan_tokotegal dan stok_tokotegal
                Pemindahan_tokotegal::where('kode_pemindahan', $pemindahan->kode_pemindahan)
                    ->update([
                        'status' => 'posting',
                        'tanggal_terima' => Carbon::now('Asia/Jakarta'),
                    ]);

                $stok_tegal = Stok_tokotegal::where('produk_id', $pemindahan->produk_id)
                    ->where('jumlah', '>', 0)
                    ->orderBy('jumlah', 'asc')
                    ->get();

                $this->kurangiStok($stok_tegal, $pemindahan->jumlah);
                break;

            case 3: // Jika toko_id = 3, update pemindahan_tokoslawi dan tambahkan stok pada stok_tokoslawi
                Pemindahan_tokoslawi::where('kode_pemindahan', $pemindahan->kode_pemindahan)
                    ->update([
                        'status' => 'posting',
                        'tanggal_terima' => Carbon::now('Asia/Jakarta'),
                    ]);

                // Tambahkan stok pada tabel stok_tokoslawi
                $stok_slawi = Stok_tokoslawi::where('produk_id', $pemindahan->produk_id)
                    ->where('jumlah', '>', 0)
                    ->orderBy('jumlah', 'asc')
                    ->get();

                $this->kurangiStok($stok_slawi, $pemindahan->jumlah);
                break;

            case 4: // Jika toko_id = 3, update pemindahan_tokoslawi dan tambahkan stok pada stok_tokoslawi
                    Pemindahan_tokopemalang::where('kode_pemindahan', $pemindahan->kode_pemindahan)
                        ->update([
                            'status' => 'posting',
                            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
                        ]);
    
                    // Tambahkan stok pada tabel stok_tokoslawi
                    $stok_pemalang = Stok_tokopemalang::where('produk_id', $pemindahan->produk_id)
                        ->where('jumlah', '>', 0)
                        ->orderBy('jumlah', 'asc')
                        ->get();
    
                    $this->kurangiStok($stok_pemalang, $pemindahan->jumlah);
                    break;

                    case 5: // Jika toko_id = 3, update pemindahan_tokoslawi dan tambahkan stok pada stok_tokoslawi
                        Pemindahan_tokobumiayu::where('kode_pemindahan', $pemindahan->kode_pemindahan)
                            ->update([
                                'status' => 'posting',
                                'tanggal_terima' => Carbon::now('Asia/Jakarta'),
                            ]);
        
                        // Tambahkan stok pada tabel stok_tokoslawi
                        $stok_bumiayu = Stok_tokobumiayu::where('produk_id', $pemindahan->produk_id)
                            ->where('jumlah', '>', 0)
                            ->orderBy('jumlah', 'asc')
                            ->get();
        
                        $this->kurangiStok($stok_bumiayu, $pemindahan->jumlah);
                        break;

                        case 6: // Jika toko_id = 3, update pemindahan_tokoslawi dan tambahkan stok pada stok_tokoslawi
                            Pemindahan_tokocilacap::where('kode_pemindahan', $pemindahan->kode_pemindahan)
                                ->update([
                                    'status' => 'posting',
                                    'tanggal_terima' => Carbon::now('Asia/Jakarta'),
                                ]);
            
                            // Tambahkan stok pada tabel stok_tokoslawi
                            $stok_cilacap = Stok_tokocilacap::where('produk_id', $pemindahan->produk_id)
                                ->where('jumlah', '>', 0)
                                ->orderBy('jumlah', 'asc')
                                ->get();
            
                            $this->kurangiStok($stok_cilacap, $pemindahan->jumlah);
                            break;
                }

        return redirect()->route('pemindahan_tokocilacap.index')->with('success', 'Status berhasil diubah menjadi posting, stok telah diperbarui, dan tanggal terima telah disimpan.');
    }

    return redirect()->route('pemindahan_tokocilacap.index')->with('error', 'Status pemindahan tidak valid untuk diubah.');
}

private function kurangiStok($stok_items, $jumlah_yang_dibutuhkan)
{
    foreach ($stok_items as $stok) {
        if ($jumlah_yang_dibutuhkan <= 0) {
            break;
        }

        if ($stok->jumlah >= $jumlah_yang_dibutuhkan) {
            $stok->jumlah -= $jumlah_yang_dibutuhkan;
            $stok->save();
            $jumlah_yang_dibutuhkan = 0;
        } else {
            $jumlah_yang_dibutuhkan -= $stok->jumlah;
            $stok->jumlah = 0;
            $stok->save();
        }
    }
}

public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Pemindahan_tokobanjaran::where('id', $id)->value('kode_pemindahan');
    
    // Jika kode_pemindahan tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_pemindahan yang sama
    $pengirimanBarangJadi = Pemindahan_tokobanjaran::with(['produk.subklasifikasi', 'toko'])->where('kode_pemindahan', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('toko_banjaran.inquery_pemindahanbanjaran.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function print($id)
    {
        $detailStokBarangJadi = Pemindahan_tokobanjaran::where('id', $id)->value('kode_pemindahan');
    
        // Jika kode_pemindahan tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pemindahan yang sama
        $pengirimanBarangJadi = Pemindahan_tokobanjaran::with(['produk.subklasifikasi', 'toko'])->where('kode_pemindahan', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        $pdf = FacadePdf::loadView('toko_banjaran.inquery_pemindahanbanjaran.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_banjaran.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
    }

}


 