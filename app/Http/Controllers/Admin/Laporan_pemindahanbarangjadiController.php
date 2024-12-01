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
use App\Models\Detail_stokbarangjadi;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Stok_tokoslawi;
use App\Models\Retur_tokoslawi;
use App\Models\Pemindahan_tokoslawi;
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
use App\Models\Retur_barnagjadi;
use Maatwebsite\Excel\Facades\Excel;

class Laporan_pemindahanbarangjadiController extends Controller{

    public function index(Request $request)
    {
        $status = $request->input('status');
        $tanggal_input = $request->input('tanggal_input');
        $tanggal_akhir = $request->input('tanggal_akhir');
    
        $query = Pemindahan_barangjadi::with('produk.klasifikasi');
    
        if ($status) {
            $query->where('status', $status);
        }
    
        // Validasi dan konversi tanggal input
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
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pemindahan
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');
    
        return view('admin.laporan_pemindahanbarangjadi.index', compact('stokBarangJadi'));
    }
    


    public function printReportpemindahan(Request $request)
{
    $status = $request->input('status');
    $tanggal_input = $request->input('tanggal_input');
    $tanggal_akhir = $request->input('tanggal_akhir');

    $query = Pemindahan_barangjadi::with('produk.klasifikasi');

    if ($status) {
        $query->where('status', $status);
    }

    // Validasi dan konversi tanggal input
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

    // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pemindahan
    $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');
    $formattedStartDate = $tanggal_input ? Carbon::parse($tanggal_input)->format('d-m-Y') : 'N/A';
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

    // Inisialisasi DOMPDF
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Jika menggunakan URL eksternal untuk gambar atau CSS

    $dompdf = new \Dompdf\Dompdf($options);

    // Memuat konten HTML dari view
    $html = view('admin.laporan_pemindahanbarangjadi.print', [
        'stokBarangJadi' => $stokBarangJadi, 
        'tanggal_input' => $tanggal_input,
        'tanggal_akhir' => $tanggal_akhir,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
    ])->render();

    $dompdf->loadHtml($html);

    // Set ukuran kertas dan orientasi
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF
    $dompdf->render();

    // Menambahkan nomor halaman di kanan bawah
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 10;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
        $y = $canvas->get_height() - 15; // 15 pixel dari bawah

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $dompdf->stream('laporan_pemindahan_barangjadi.pdf', ['Attachment' => false]);
}


}


 