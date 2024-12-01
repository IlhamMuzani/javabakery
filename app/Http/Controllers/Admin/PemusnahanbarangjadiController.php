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
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Detail_stokbarangjadi;
use App\Models\Stok_barangjadi;
use App\Models\Pengiriman_barangjadi;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Pemusnahan_barangjadi;
use App\Models\Retur_barangjadi;
use Maatwebsite\Excel\Facades\Excel;




class PemusnahanbarangjadiController extends Controller{

    // public function index()
    // {
    //     $pemusnahanBarangJadi = Pemusnahan_barangjadi::with('produk.klasifikasi')
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->groupBy('kode_pemusnahan');
    
    //     return view('admin.pemusnahan_barangjadi.index', compact('pemusnahanBarangJadi'));
    // }
    
    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_retur = $request->tanggal_retur;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pemusnahan_barangjadi::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

            if ($tanggal_retur && $tanggal_akhir) {
                $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
            } elseif ($tanggal_retur) {
                $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
                $query->where('tanggal_retur', '>=', $tanggal_retur);
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->where('tanggal_retur', '<=', $tanggal_akhir);
            } else {
                // Jika tidak ada filter tanggal, tampilkan data hari ini
                $query->whereDate('tanggal_retur', Carbon::today());
            }

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');

            return view('admin.pemusnahan_barangjadi.index', compact('stokBarangJadi'));
    }
    public function getReturData()
    {
        // Mengambil data unik berdasarkan kode_retur
        $returData = Retur_barangjadi::select('kode_retur', 'tanggal_retur', 'tanggal_terima', 'keterangan')
            ->groupBy('kode_retur', 'tanggal_retur', 'tanggal_terima', 'keterangan')
            ->get();
        
        return response()->json($returData);
    }
    
    public function getProductsByKodeRetur($kodeRetur)
    {
        // Mengambil semua produk yang terkait dengan kode_retur yang diberikan
        $products = Retur_barangjadi::where('kode_retur', $kodeRetur)->get();
        return response()->json($products);
    }
    
    public function create()
    {
        // Fetch all products
        $produks = Produk::all();
        $tokos = Toko::all();
        $Retur = Retur_barangjadi::all();
    
        return view('admin.pemusnahan_barangjadi.create', compact('produks', 'tokos', 'Retur'));
    }
    


public function store(Request $request)
{
    $kode = $this->kode();
    $produkData = $request->input('produk_id', []);
    $jumlahData = $request->input('jumlah', []);
    $tokoId = $request->input('toko_id');

    // Array untuk menyimpan ID pengiriman
    $pengirimanIds = [];

    foreach ($produkData as $key => $produkId) {
        $jumlah = $jumlahData[$key] ?? null;

        if (!is_null($jumlah) && $jumlah !== '') {
            $detailStoks = Detail_stokbarangjadi::where('produk_id', $produkId)->get();
            $totalStok = $detailStoks->sum('stok');

            $kodeProduk = Produk::where('id', $produkId)->value('kode_produk');

            if ($totalStok >= $jumlah) {
                $remaining = $jumlah;

                foreach ($detailStoks as $detailStok) {
                    if ($detailStok->stok >= $remaining) {
                        $detailStok->stok -= $remaining;
                        $detailStok->save();
                        break;
                    } else {
                        $remaining -= $detailStok->stok;
                        $detailStok->stok = 0;
                        $detailStok->save();
                    }
                }

                $pengiriman = Pengiriman_barangjadi::create([
                    'kode_pengiriman' => $kode,
                    'qrcode_pengiriman' => 'https://javabakery.id/permintaan_produk/' . $kode,
                    'produk_id' => $produkId,
                    'toko_id' => $tokoId,
                    'jumlah' => $jumlah,
                    'status' => 'posting',
                    'tanggal_pengiriman' => Carbon::now('Asia/Jakarta'),
                ]);

                // Simpan ID pengiriman yang baru dibuat
                $pengirimanIds[] = $pengiriman->id;
            } else {
                return redirect()->back()
                    ->with('error', 'Stok tidak cukup untuk kode produk ' . $kodeProduk);
            }
        }
    }

    // Jika ada ID pengiriman yang baru dibuat, arahkan ke halaman show
    if (!empty($pengirimanIds)) {
        $firstId = $pengirimanIds[0]; // Ambil ID pengiriman yang pertama
        return redirect()->route('pengiriman_barangjadi.show', $firstId)
            ->with('success', 'Berhasil menambahkan permintaan produk');
    }

    return redirect()->route('pengiriman_barangjadi.index')
        ->with('success', 'Berhasil menambahkan permintaan produk');
}


    public function kode()
    {
        $lastBarang = Pengiriman_barangjadi::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_pengiriman;
            $num = (int) substr($lastCode, strlen('SB')) + 1; 
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'JX';
        $newCode = $prefix . $formattedNum;
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
        
        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Pengiriman_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_pengiriman', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.pengiriman_barangjadi.show', compact('pengirimanBarangJadi', 'firstItem'));
    }

    
    public function print($id)
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
        $pdf = FacadePdf::loadView('admin.pengiriman_barangjadi.print', compact('detailStokBarangJadi', 'pengirimanBarangJadi', 'firstItem'));

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