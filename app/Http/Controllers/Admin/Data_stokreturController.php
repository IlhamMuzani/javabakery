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
use App\Models\Retur_barangjadi;
use App\Models\Stok_retur;
use Maatwebsite\Excel\Facades\Excel;

class Data_stokreturController extends Controller{

    public function index()
    {
        // Ambil data retur_tokoslawi beserta relasi toko
        $stok_retur = Stok_retur::with('toko') // Tidak perlu dengan 'produk'
            ->where('status', 'posting')
            ->get()
            ->groupBy('produk_id') // Kelompokkan berdasarkan produk_id
            ->map(function ($group) {
                // Agregasi jumlah untuk produk dengan ID yang sama
                return [
                    'produk_id' => $group->first()->produk_id, // Ambil ID produk yang pertama
                    'nama_produk' => $group->first()->nama_produk, // Ambil nama_produk dari stok_retur
                    'toko' => $group->first()->toko, // Ambil data toko yang pertama
                    'jumlah' => $group->sum('jumlah') // Jumlahkan semua jumlah produk
                ];
            });
    
        return view('admin.data_stokretur.index', compact('stok_retur'));
    }
    
    
// public function index()
// {
//     // Mengambil semua produk beserta stok_barangjadi dan detail_stokbarangjadi, diurutkan berdasarkan klasifikasi
//     $produks = Produk::with(['stok_barangjadii.detail_stokbarangjadi'])
//         ->get()
//         ->sortBy(function($produk) {
//             $stok = $produk->stok_barangjadii->first();
//             if (!$stok) {
//                 return ''; // Default value if no stok_barangjadi
//             }

//             $detail = $stok->detail_stokbarangjadi->first();
//             if (!$detail) {
//                 return ''; // Default value if no detail_stokbarangjadi
//             }

//             $klasifikasi = $detail->produk->klasifikasi;
//             return $klasifikasi ? $klasifikasi->nama_klasifikasi : ''; // Ensure klasifikasi is not null
//         });

//     return view('admin.data_stokbarangjadi.index', compact('produks'));
// }


}


 