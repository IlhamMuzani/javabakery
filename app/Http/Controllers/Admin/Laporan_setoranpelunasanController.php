<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelunasan_penjualan;
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use App\Models\Toko;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;


class Laporan_setoranpelunasanController extends Controller
{

    public function index(Request $request)
    {
        // if (auth()->check() && auth()->user()->menu['laporan penerimaan kas kecil']) {
        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $tokos = Toko::all();

        $inquery = Pelunasan_penjualan::orderBy('id', 'DESC');

        if ($status == "posting") {
            $inquery->where('status', $status);
        } else {
            $inquery->where('status', 'posting');
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $inquery->whereDate('tanggal_awal', '>=', $tanggal_awal)
                ->whereDate('tanggal_awal', '<=', $tanggal_akhir);
        }


        $hasSearch = ($tanggal_awal && $tanggal_akhir);
        $inquery = $hasSearch ? $inquery->get() : collect();

        return view('admin.laporan_setoranpelunasan.index', compact('inquery', 'tokos'));
    }

    public function printReportpelunasanToko(Request $request)
    {
        // Ambil parameter dari request
        $tanggalPenjualan = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $tokoId = $request->input('toko_id');

        // Query dasar untuk setoran penjualan
        $query = Pelunasan_penjualan::query();

        // Filter berdasarkan tanggal setoran
        if ($tanggalPenjualan && $tanggalAkhir) {
            $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->whereBetween('tanggal_awal', [$tanggalPenjualan, $tanggalAkhir]);
        } elseif ($tanggalPenjualan) {
            $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
            $query->where('tanggal_awal', '>=', $tanggalPenjualan);
        } elseif ($tanggalAkhir) {
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->where('tanggal_awal', '<=', $tanggalAkhir);
        } else {
            // Jika tidak ada filter tanggal, gunakan hari ini
            $query->whereDate('tanggal_awal', Carbon::today());
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
        $pdf = FacadePdf::loadView('admin.laporan_setoranpelunasan.print', [
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