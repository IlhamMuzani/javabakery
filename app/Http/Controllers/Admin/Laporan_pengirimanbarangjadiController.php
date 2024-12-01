<?php

namespace App\Http\Controllers\Admin;

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
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class Laporan_pengirimanbarangjadiController extends Controller
{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_pengiriman = $request->tanggal_pengiriman;
            $tanggal_akhir = $request->tanggal_akhir;
            $toko_id = $request->toko_id;  // Ambil toko_id dari request


            $query = Pengiriman_barangjadi::with(['produk.klasifikasi', 'toko']); // Pastikan toko diload

            if ($status) {
                $query->where('status', $status);
            }

            if ($toko_id) {
                $query->where('toko_id', $toko_id); // Tambahkan filter berdasarkan toko_id
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
            $stokBarangJadi = $query->get()->groupBy('kode_pengiriman');
            $tokos = Toko::all();

            return view('admin.laporan_pengirimanbarangjadi.index', compact('stokBarangJadi', 'tokos'));
    }

  


    // public function printReport(Request $request)
    // {
    //     // Ambil parameter dari request
    //     $tanggalPengiriman = $request->input('tanggal_pengiriman');
    //     $tanggalAkhir = $request->input('tanggal_akhir');
    //     $status = $request->input('status');
    //     $toko_id = $request->input('toko_id'); // Tambahkan toko_id dari request
        
    //     // Buat query untuk ambil data berdasarkan filter
    //     $query = Pengiriman_barangjadi::query();
        
    //     if ($tanggalPengiriman) {
    //         $query->whereDate('tanggal_pengiriman', '>=', $tanggalPengiriman);
    //     }
        
    //     if ($tanggalAkhir) {
    //         $query->whereDate('tanggal_pengiriman', '<=', $tanggalAkhir);
    //     }
        
    //     if ($status) {
    //         $query->where('status', $status);
    //     }
        
    //     if ($toko_id) {
    //         $query->where('toko_id', $toko_id); // Tambahkan filter berdasarkan toko_id
    //     }

    //     $formattedStartDate = $tanggalPengiriman ? Carbon::parse($tanggalPengiriman)->format('d-m-Y') : 'N/A';
    //     $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : 'N/A';

    //     // Ambil data yang telah difilter
    //     $pengirimanBarangJadi = $query->with(['produk.subklasifikasi', 'toko'])->get();
        
    //     $selectedCabang = $toko_id ? $pengirimanBarangJadi->first()->toko->nama_toko : 'Semua Toko';

    //     // Kelompokkan data berdasarkan kode_pengiriman
    //     $groupedData = $pengirimanBarangJadi->groupBy('kode_pengiriman');
        
    //     // Ambil item pertama untuk informasi toko
    //     $firstItem = $pengirimanBarangJadi->first();

    //     // Buat PDF menggunakan Facade PDF
    //     $pdf = FacadePdf::loadView('admin.laporan_pengirimanbarangjadi.print', [
    //         'groupedData'  => $groupedData, 
    //         'firstItem' => $firstItem, 
    //         'tanggalPengiriman' => $tanggalPengiriman,
    //         'tanggalAkhir' => $tanggalAkhir,
    //         'startDate' => $formattedStartDate,
    //         'endDate' => $formattedEndDate,
    //         'selectedCabang' => $selectedCabang // Pass the selected cabang to the view
    //     ]);

    //     // Menambahkan nomor halaman di kanan bawah
    //     $pdf->output();
    //     $dompdf = $pdf->getDomPDF();
    //     $canvas = $dompdf->getCanvas();
    //     $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    //         $text = "Page $pageNumber of $pageCount";
    //         $font = $fontMetrics->getFont('Arial', 'normal');
    //         $size = 10;

    //         // Menghitung lebar teks
    //         $width = $fontMetrics->getTextWidth($text, $font, $size);

    //         // Mengatur koordinat X dan Y
    //         $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
    //         $y = $canvas->get_height() - 15; // 15 pixel dari bawah

    //         // Menambahkan teks ke posisi yang ditentukan
    //         $canvas->text($x, $y, $text, $font, $size);
    //     });

    //     // Output PDF ke browser
    //     return $pdf->stream('laporan_pengiriman_barang_jadi.pdf');
    // }

    public function printReport(Request $request)
    {
        // Ambil parameter dari request
        $tanggalPengiriman = $request->input('tanggal_pengiriman');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $status = $request->input('status');
        $toko_id = $request->input('toko_id'); // Tambahkan toko_id dari request
        
        // Buat query untuk ambil data berdasarkan filter
        $query = Pengiriman_barangjadi::query();
        
        if ($tanggalPengiriman) {
            $query->whereDate('tanggal_pengiriman', '>=', $tanggalPengiriman);
        }
        
        if ($tanggalAkhir) {
            $query->whereDate('tanggal_pengiriman', '<=', $tanggalAkhir);
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($toko_id) {
            $query->where('toko_id', $toko_id); // Tambahkan filter berdasarkan toko_id
        }
    
        $formattedStartDate = $tanggalPengiriman ? Carbon::parse($tanggalPengiriman)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : 'N/A';
    
        // Ambil data yang telah difilter
        $pengirimanBarangJadi = $query->with(['produk.klasifikasi', 'toko'])->get();
        
        $selectedCabang = $toko_id ? $pengirimanBarangJadi->first()->toko->nama_toko : 'Semua Toko';
    
        // Kelompokkan data berdasarkan kode_pengiriman dan klasifikasi produk
        $groupedData = $pengirimanBarangJadi->groupBy(function ($item) {
            return $item->kode_pengiriman .   '|'  . $item->produk->klasifikasi->nama;
        });
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
    
        // Buat PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('admin.laporan_pengirimanbarangjadi.print', [
            'groupedData'  => $groupedData, 
            'firstItem' => $firstItem, 
            'tanggalPengiriman' => $tanggalPengiriman,
            'tanggalAkhir' => $tanggalAkhir,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'selectedCabang' => $selectedCabang // Pass the selected cabang to the view
        ]);
    
        // Menambahkan nomor halaman di kanan bawah
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
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
        return $pdf->stream('laporan_pengiriman_barang_jadi.pdf');
    }
    

    
    
}