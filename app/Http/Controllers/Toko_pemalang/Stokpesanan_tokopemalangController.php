<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Imports\StokBanjaranImport;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokobumiayu;
use App\Models\Stok_tokotegal;
use App\Models\Stokpesanan_tokopemalang;
use App\Models\Subklasifikasi;
use Maatwebsite\Excel\Facades\Excel;

class Stokpesanan_tokopemalangController extends Controller{

public function index(Request $request)
{
    
    $klasifikasis = Klasifikasi::all();
    $produkQuery = Produk::with(['klasifikasi', 'subklasifikasi']);

    // Filter berdasarkan klasifikasi_id
    if ($request->has('klasifikasi_id') && $request->klasifikasi_id) {
        $produkQuery->where('klasifikasi_id', $request->klasifikasi_id);
    }

    // Filter berdasarkan subklasifikasi_id
    if ($request->has('subklasifikasi_id') && $request->subklasifikasi_id) {
        $produkQuery->where('subklasifikasi_id', $request->subklasifikasi_id);
    }

    $produk = $produkQuery->get();

    $stok_tokobanjaran = Stokpesanan_tokopemalang::with('produk')->get();
    $stokGrouped = $stok_tokobanjaran->groupBy('produk_id')->map(function ($group) {
        $firstItem = $group->first();
        $totalJumlah = $group->sum('jumlah');
        $firstItem->jumlah = $totalJumlah;
        return $firstItem;
    })->values();

    $totalHarga = 0;
    $totalStok = 0;
    $totalSubTotal = 0;

    $produkWithStok = $produk->map(function ($item) use ($stokGrouped, &$totalHarga, &$totalStok, &$totalSubTotal) {
        $stokItem = $stokGrouped->firstWhere('produk_id', $item->id);
        $item->jumlah = $stokItem ? $stokItem->jumlah : 0;
        $subTotal = $item->jumlah * $item->harga;
        $item->subTotal = $subTotal;
        $totalHarga += $item->harga * $item->jumlah;
        $totalStok += $item->jumlah;
        $totalSubTotal += $subTotal;
        return $item;
    });

    // Kirim data subklasifikasi jika ada klasifikasi_id
    $subklasifikasis = $request->has('klasifikasi_id') 
        ? SubKlasifikasi::where('klasifikasi_id', $request->klasifikasi_id)->get() 
        : collect();

    return view('toko_pemalang.stokpesanan_tokopemalang.index', compact('produkWithStok', 'klasifikasis', 'subklasifikasis', 'totalHarga', 'totalStok', 'totalSubTotal'));
}



public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('toko_pemalang.stokpesanan_tokopemalang.create', compact('produks', 'tokos'));
}

    public function store(Request $request)
    {
        $request->validate([
            // 'toko_id' => 'required|exists:tokos,id',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produks,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1'
        ]);

        // $toko_id = $request->input('toko_id');
        $produk_ids = $request->input('produk_id');
        $jumlahs = $request->input('jumlah');

        foreach ($produk_ids as $index => $produk_id) {
            $stok = Stokpesanan_tokopemalang::where('produk_id', $produk_id)->first();

            if ($stok) {
                // Jika stok sudah ada, lakukan update jumlah
                $stok->update([
                    'jumlah' => $stok->jumlah + $jumlahs[$index], // Menambahkan jumlah baru ke jumlah yang ada
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
            } else {
                // Jika stok belum ada, buat entri baru
                Stokpesanan_tokopemalang::create([
                    // 'toko_id' => $toko_id,
                    'produk_id' => $produk_id,
                    'status' => 'posting',
                    'jumlah' => $jumlahs[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
            }
        }

        return redirect()->route('stokpesanan_tokopemalang.index')->with('success', 'Data stok barang berhasil disimpan.');
    }


    public function update(Request $request, $produk_id)
    {
        // Validasi input untuk memastikan jumlah adalah angka dan tidak kosong
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ]);

        // Temukan stok berdasarkan produk_id
        $stok = Stokpesanan_tokopemalang::where('produk_id', $produk_id)->first();

        // Jika stok tidak ditemukan, bisa memberikan respons error atau membuat entri stok baru
        if (!$stok) {
            return redirect()->route('stokpesanan_tokopemalang.index')->with('error', 'Stok untuk produk ini tidak ditemukan.');
        }

        // Update jumlah stok
        $stok->jumlah = $request->input('jumlah');
        $stok->save(); // Simpan perubahan

        // Redirect kembali ke halaman stok dengan pesan sukses
        return redirect()->route('stokpesanan_tokopemalang.index')->with('success', 'Stok produk berhasil diperbarui.');
    }
   

    public function deleteAll()
    {
        // Menghapus seluruh data pada kolom jumlah (stok) di tabel stok_tokobanjarans
        Stokpesanan_tokopemalang::query()->update(['jumlah' => 0]);

        return redirect()->back()->with('success', 'Semua data stok berhasil dihapus.');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx',
        ]);
    
        Excel::import(new StokBanjaranImport, $request->file('file_excel'));
    
        return redirect('toko_pemalang/stok_tokobanjaran')->with('success', 'Berhasil mengimpor produk dari Excel');
    }

}


 