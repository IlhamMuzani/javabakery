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
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;



class Laporan_pemesananprController extends Controller
{
 
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pemesanan = $request->tanggal_pemesanan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id; // Ambil nilai toko_id dari request
    
        $inquery = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($tanggal_pemesanan && $tanggal_akhir, function ($query) use ($tanggal_pemesanan, $tanggal_akhir) {
                $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
            })
            ->when($tanggal_pemesanan, function ($query, $tanggal_pemesanan) {
                $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
                return $query->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
            })
            ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            })
            ->when(!$tanggal_pemesanan && !$tanggal_akhir, function ($query) {
                return $query->whereDate('tanggal_pemesanan', Carbon::today());
            })
            ->when($toko_id && $toko_id != '0', function ($query) use ($toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->orderBy('id', 'DESC')
            ->get();
    
        $groupedData = [];
        foreach ($inquery as $item) {
            foreach ($item->detailpemesananproduk as $detail) {
                $key = $item->kode_pemesanan . '-' . $detail->kode_produk . '-' . ($detail->produk->klasifikasi->id ?? 'no-klasifikasi');
                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        'klasifikasi' => $detail->produk->klasifikasi->nama ?? 'Tidak ada',
                        // 'tanggal_pemesanan' => $item->tanggal_pemesanan,
                        'tanggal_pemesanan' => Carbon::parse($item->tanggal_pemesanan)->format('d-m-Y H:i'), // Format tanggal dan jam
                        'tanggal_kirim' => $item->tanggal_kirim,
                        'kode_pemesanan' => $item->kode_pemesanan,
                        'kode_produk' => $detail->kode_produk,
                        'nama_produk' => $detail->nama_produk,
                        'catatanproduk' => $detail->catatanproduk,
                        'benjaran' => 0,
                        'tegal' => 0,
                        'slawi' => 0,
                        'pemalang' => 0,
                        'bumiayu' => 0,
                        'cilacap' => 0,
                    ];
                }
                $tokoFieldMap = [
                    1 => 'benjaran',
                    2 => 'tegal',
                    3 => 'slawi',
                    4 => 'pemalang',
                    5 => 'bumiayu',
                    6 => 'cilacap',
                ];
                $tokoField = $tokoFieldMap[$item->toko_id] ?? null;
                if ($tokoField) {
                    $groupedData[$key][$tokoField] += $detail->jumlah;
                }
            }
        }
        $formattedStartDate = $tanggal_pemesanan ? Carbon::parse($tanggal_pemesanan)->format('d-m-Y') : null;
        // Convert $groupedData to a collection and sort by 'nama_produk'
        $groupedData = collect($groupedData)->sortBy('nama_produk')->values()->all();
    
        return view('admin.laporan_pemesananproduk.index', [
            'groupedData' => $groupedData,
            'totalSubtotal' => array_sum(array_column($groupedData, 'subtotal')),
            'toko_id' => $toko_id, // Kirimkan toko_id ke view
            'tanggal_pemesanan' => $formattedStartDate,
        ]);
    }


    public function print_pemesanan(Request $request)
    {
        // Tangkap data dari request
        $status = htmlspecialchars($request->input('status'));
        $tanggal_pemesanan = $request->input('start_date');
        $tanggal_akhir = $request->input('end_date');
        $toko_id = htmlspecialchars($request->input('toko_id'));
    
        // Query untuk mendapatkan data pemesanan produk
        $query = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($tanggal_pemesanan && $tanggal_akhir, function ($query) use ($tanggal_pemesanan, $tanggal_akhir) {
                $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
            })
            ->when($tanggal_pemesanan && !$tanggal_akhir, function ($query) use ($tanggal_pemesanan) {
                $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
                return $query->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
            })
            ->when(!$tanggal_pemesanan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            })
            ->when(!$tanggal_pemesanan && !$tanggal_akhir, function ($query) {
                return $query->whereDate('tanggal_pemesanan', Carbon::today());
            })
            ->when($toko_id && $toko_id != '0', function ($query) use ($toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->orderBy('id', 'DESC')
            ->get();
    
        // Pengelompokan data berdasarkan klasifikasi
        $groupedData = [];
        foreach ($query as $item) {
            foreach ($item->detailpemesananproduk as $detail) {
                $klasifikasi = $detail->produk->klasifikasi->nama ?? 'Tidak ada';
                $key = $detail->kode_produk . '-' . ($detail->produk->klasifikasi->id ?? 'no-klasifikasi');
    
                if (!isset($groupedData[$klasifikasi])) {
                    $groupedData[$klasifikasi] = [];
                }
                if (!isset($groupedData[$klasifikasi][$key])) {
                    $groupedData[$klasifikasi][$key] = [
                        'klasifikasi' => $klasifikasi,
                        'tanggal_pemesanan' => Carbon::parse($item->tanggal_pemesanan)->format('d-m-Y H:i'),
                        'tanggal_kirim' => Carbon::parse($item->tanggal_kirim)->format('d-m-Y'),
                        'kode_produk' => $detail->kode_produk ?? 'Tidak ada',
                        'nama_produk' => $detail->nama_produk ?? 'Tidak ada',
                        'kode_pemesanan' => $item->kode_pemesanan ?? 'Tidak ada',
                        'catatanproduk' => $detail->catatanproduk ?? 'Tidak ada',
                        'benjaran' => 0,
                        'tegal' => 0,
                        'slawi' => 0,
                        'pemalang' => 0,
                        'bumiayu' => 0,
                        'cilacap' => 0,
                        'subtotal' => 0,
                    ];
                }
                $tokoFieldMap = [
                    1 => 'benjaran',
                    2 => 'tegal',
                    3 => 'slawi',
                    4 => 'pemalang',
                    5 => 'bumiayu',
                    6 => 'cilacap',
                ];
                $tokoField = $tokoFieldMap[$item->toko_id] ?? null;
                if ($tokoField) {
                    $groupedData[$klasifikasi][$key][$tokoField] += $detail->jumlah;
                    $groupedData[$klasifikasi][$key]['subtotal'] += $detail->jumlah;
                }
            }
        }
    
        // Format tanggal untuk tampilan PDF
        $formattedStartDate = $tanggal_pemesanan ? Carbon::parse($tanggal_pemesanan)->format('d-m-Y') : null;
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : null;
    
        // Buat PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('admin.laporan_pemesananproduk.print', [
            'groupedData' => $groupedData,
            'totalSubtotal' => array_sum(array_map(function($items) {
                return array_sum(array_column($items, 'subtotal'));
            }, $groupedData)),
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'toko_id' => $toko_id,
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
            $x = $canvas->get_width() - $width - 10;
            $y = $canvas->get_height() - 15;
    
            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });
    
        // Output PDF ke browser
        return $pdf->stream('Laporan_Pemesanan_Produk.pdf');
    }
    
    
    public function create()
    {

       
    }
    
 
    
    public function store(Request $request)
{

}



    public function show($id)
    {
        //
    }

  
    public function edit($id)
    {


    }

 
    public function update(Request $request, $id)
    {
       
    }


    public function destroy($id)
    {
        //
    }

}