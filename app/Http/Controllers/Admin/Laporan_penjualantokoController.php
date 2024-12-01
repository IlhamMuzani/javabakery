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
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;






class Laporan_penjualantokoController extends Controller
{

    public function index(Request $request)
    {
        // Ambil parameter tanggal dari request
        $tanggalPenjualan = $request->input('tanggal_setoran');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $toko_id = $request->input('toko_id');

        // Query dasar untuk setoran penjualan
        $query = Setoran_penjualan::query();

        // Filter berdasarkan tanggal setoran
        if ($tanggalPenjualan && $tanggalAkhir) {
            $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->whereBetween('tanggal_setoran', [$tanggalPenjualan, $tanggalAkhir]);
        } elseif ($tanggalPenjualan) {
            $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
            $query->where('tanggal_setoran', '>=', $tanggalPenjualan);
        } elseif ($tanggalAkhir) {
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->where('tanggal_setoran', '<=', $tanggalAkhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_setoran', Carbon::today());
        }

        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }

        // Ambil data setoran penjualan
        $setoranPenjualans = $query->orderBy('id', 'DESC')->get();

        // Ambil data toko untuk dropdown
        $tokos = Toko::all();

        // Kirim data ke view
        return view('admin.laporan_penjualantoko.index', compact('setoranPenjualans', 'tokos'));
    }

    public function printReportpenjualanToko(Request $request)
    {
        // Ambil parameter dari request
        $tanggalPenjualan = $request->input('tanggal_setoran');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $tokoId = $request->input('toko_id');

        // Query dasar untuk setoran penjualan
        $query = Setoran_penjualan::query();

        // Filter berdasarkan tanggal setoran
        if ($tanggalPenjualan && $tanggalAkhir) {
            $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->whereBetween('tanggal_setoran', [$tanggalPenjualan, $tanggalAkhir]);
        } elseif ($tanggalPenjualan) {
            $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
            $query->where('tanggal_setoran', '>=', $tanggalPenjualan);
        } elseif ($tanggalAkhir) {
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->where('tanggal_setoran', '<=', $tanggalAkhir);
        } else {
            // Jika tidak ada filter tanggal, gunakan hari ini
            $query->whereDate('tanggal_setoran', Carbon::today());
        }

        // Filter berdasarkan toko
        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }

        // Ambil data setoran penjualan dengan relasi yang dibutuhkan
        $setoranPenjualans = $query->with('toko')->orderBy('id', 'DESC')->get();

        // Menentukan nama toko
        if ($tokoId) {
            $toko = Toko::find($tokoId); // Ambil nama toko berdasarkan ID
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko'; // Nama toko atau default jika tidak ditemukan
        } else {
            $branchName = 'Semua Toko'; // Default jika tidak ada filter toko
        }

        // Format tanggal untuk tampilan di PDF
        $formattedStartDate = $tanggalPenjualan ? Carbon::parse($tanggalPenjualan)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : 'N/A';

        // Buat PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('admin.laporan_penjualantoko.print', [
            'setoranPenjualans' => $setoranPenjualans,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'branchName' => $branchName,
        ]);

        // Menambahkan nomor halaman di kanan bawah
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Page $pageNumber of $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 8;

            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);

            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('laporan_setoran_penjualan.pdf');
    }
}