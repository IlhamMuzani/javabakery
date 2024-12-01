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
use App\Models\Estimasiproduksi;
use Maatwebsite\Excel\Facades\Excel;




class Inquery_estimasiproduksiController extends Controller{

public function index(Request $request)
{
    $status = $request->status;
    $tanggal_estimasi = $request->tanggal_estimasi;
    $tanggal_akhir = $request->tanggal_akhir;

    $inquery = Estimasiproduksi::query();

    if ($status) {
        $inquery->where('status', $status);
    }

    if ($tanggal_estimasi && $tanggal_akhir) {
        $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->whereHas('detailestimasiproduksi', function($query) use ($tanggal_estimasi, $tanggal_akhir) {
            $query->whereBetween('tanggal_estimasi', [$tanggal_estimasi, $tanggal_akhir]);
        });
    } elseif ($tanggal_estimasi) {
        $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
        $inquery->whereHas('detailestimasiproduksi', function($query) use ($tanggal_estimasi) {
            $query->where('tanggal_estimasi', '>=', $tanggal_estimasi);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->whereHas('detailestimasiproduksi', function($query) use ($tanggal_akhir) {
            $query->where('tanggal_estimasi', '<=', $tanggal_akhir);
        });
    } else {
        // Jika tidak ada filter tanggal, ambil data hari ini
        $inquery->whereHas('detailestimasiproduksi', function($query) {
            $query->whereDate('tanggal_estimasi', Carbon::today());
        });
    }

    $inquery->orderBy('id', 'DESC');

    // Menggunakan with untuk eager loading relasi detailestimasiproduksi dan toko
    $permintaanProduks = $inquery->with(['detailestimasiproduksi', 'toko'])->get();

   return view('admin.inquery_estimasiproduksi.index', compact('permintaanProduks'));
}

public function destroy($id)
{
    $permintaanProduk = Estimasiproduksi::findOrFail($id);

    // Hapus detail permintaan produk terkait
    $permintaanProduk->detailestimasiproduksi()->delete();

    // Hapus permintaan produk itu sendiri
    $permintaanProduk->delete();

    return redirect()->route('inquery_estimasiproduksi.index')->with('success', 'Permintaan produk dan detail terkait berhasil dihapus.');
}


public function unpost_estimasiproduksi($id)
{
    $item = Estimasiproduksi::where('id', $id)->first();

    
        $item->update([
            'status' => 'unpost'
        ]);
    return back()->with('success', 'Berhasil');
}

}