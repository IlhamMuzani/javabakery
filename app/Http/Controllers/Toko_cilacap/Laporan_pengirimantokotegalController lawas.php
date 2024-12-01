<?php

namespace App\Http\Controllers\Toko_tegal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
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
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Stok_barangjadi;
use App\Models\Permintaanproduk;
use App\Models\Detailpermintaanproduk;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengiriman_tokotegal;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class Laporan_pengirimantokotegalController extends Controller
{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_input = $request->tanggal_input;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pengiriman_tokotegal::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

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

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->get()->groupBy('kode_pengiriman');

            return view('toko_tegal.laporan_pengirimantokotegal.index', compact('stokBarangJadi'));
    }


    
    public function printReport(Request $request)
{
    // Ambil parameter dari request
    $tanggalPengiriman = $request->input('tanggal_input');
    $tanggalAkhir = $request->input('tanggal_akhir');
    $status = $request->input('status');
    
    // Buat query untuk ambil data berdasarkan filter
    $query = Pengiriman_tokotegal::query();
    
    if ($tanggalPengiriman) {
        $query->whereDate('tanggal_input', '>=', $tanggalPengiriman);
    }
    
    if ($tanggalAkhir) {
        $query->whereDate('tanggal_input', '<=', $tanggalAkhir);
    }
    
    if ($status) {
        $query->where('status', $status);
    }
    
    $formattedStartDate = $tanggalPengiriman ? Carbon::parse($tanggalPengiriman)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : 'N/A';
    // Ambil data yang telah difilter
    $pengirimanBarangJadi = $query->with(['produk.subklasifikasi', 'toko'])->get();
    
    // Kelompokkan data berdasarkan kode_pengiriman
    $groupedData = $pengirimanBarangJadi->groupBy('kode_pengiriman');
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    // Inisialisasi DOMPDF
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Jika menggunakan URL eksternal untuk gambar atau CSS
    
    $dompdf = new \Dompdf\Dompdf($options);
    
    // Memuat konten HTML dari view
    $html = view('toko_tegal.laporan_pengirimantokotegal.print', [
    'groupedData'  => $groupedData, 
    'firstItem' => $firstItem , 
    'tanggalPengiriman', 
    'tanggalAkhir',
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
    return $dompdf->stream('laporan_pengiriman_barang_jadi.pdf', ['Attachment' => false]);
}

    
    
}