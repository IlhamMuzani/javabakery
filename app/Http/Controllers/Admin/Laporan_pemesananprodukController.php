<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Toko;
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
use Illuminate\Support\Facades\Facade;

class Laporan_pemesananprodukController extends Controller
{
 
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_kirim = $request->tanggal_kirim;
        $tanggal_akhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        // Query dasar untuk mengambil data pemesanan produk
        $query = Pemesananproduk::query();
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal pemesanan
        if ($tanggal_kirim && $tanggal_akhir) {
            $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
        } elseif ($tanggal_kirim) {
            $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
            $query->where('tanggal_kirim', '>=', $tanggal_kirim);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_kirim', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_kirim', Carbon::today());
        }
    
        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailpemesananproduk', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('detailpemesananproduk.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }
    
        // Urutkan data berdasarkan ID secara descending 
        // $query->orderBy('id', 'DESC');
    
        // Ambil data pemesanan produk
        $inquery = $query->with(['toko', 'detailpemesananproduk.produk.klasifikasi'])->get();
    
        // Ambil semua data produk untuk dropdown
        $produks = Produk::all();
    
        // Ambil semua data toko untuk dropdown
        $tokos = Toko::all();
    
        // Ambil semua klasifikasi untuk dropdown
        $klasifikasis = Klasifikasi::all();
    
        // Kembalikan view dengan data pemesanan produk, produk, toko, dan klasifikasi
        return view('admin.laporan_pemesananproduk.index', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
    }

    public function indexpemesananglobal(Request $request)
    {
        $status = $request->status;
        $tanggal_kirim = $request->tanggal_kirim;
        $tanggal_akhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        // Query dasar untuk mengambil data pemesanan produk
        $query = Pemesananproduk::query();
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal pemesanan
        if ($tanggal_kirim && $tanggal_akhir) {
            $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
        } elseif ($tanggal_kirim) {
            $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
            $query->where('tanggal_kirim', '>=', $tanggal_kirim);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_kirim', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_kirim', Carbon::today());
        }
    
        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailpemesananproduk', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('detailpemesananproduk.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }
    
        // Urutkan data berdasarkan ID secara descending 
        // $query->orderBy('id', 'DESC');
    
        // Ambil data pemesanan produk
        $inquery = $query->with(['toko', 'detailpemesananproduk.produk.klasifikasi'])->get();
    
        // Ambil semua data produk untuk dropdown
        $produks = Produk::all();
    
        // Ambil semua data toko untuk dropdown
        $tokos = Toko::all();
    
        // Ambil semua klasifikasi untuk dropdown
        $klasifikasis = Klasifikasi::all();
    
        // Kembalikan view dengan data pemesanan produk, produk, toko, dan klasifikasi
        return view('admin.laporan_pemesananproduk.indexglobal', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
    }
    
    public function printReportPemesanan(Request $request)
    {
        $status = $request->status;
        $tanggalPemesanan = $request->tanggal_kirim;
        $tanggalAkhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $tokoId = $request->toko_id;
        $klasifikasiId = $request->klasifikasi_id;
    
        // Query dasar untuk mengambil data pemesanan produk
        $query = Pemesananproduk::query();
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal pemesanan
        if ($tanggalPemesanan && $tanggalAkhir) {
            $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->whereBetween('tanggal_kirim', [$tanggalPemesanan, $tanggalAkhir]);
        } elseif ($tanggalPemesanan) {
            $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
            $query->where('tanggal_kirim', '>=', $tanggalPemesanan);
        } elseif ($tanggalAkhir) {
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $query->where('tanggal_kirim', '<=', $tanggalAkhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_kirim', Carbon::today());
        }
    
        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailpemesananproduk', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }
    
        // Filter berdasarkan toko
        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }
    
        // Filter berdasarkan klasifikasi
        if ($klasifikasiId) {
            $query->whereHas('detailpemesananproduk.produk.klasifikasi', function ($query) use ($klasifikasiId) {
                $query->where('id', $klasifikasiId);
            });
        }
    
        // Ambil data pemesanan produk dengan eager loading
        $inquery = $query->with([
            'toko',
            'detailpemesananproduk.produk.klasifikasi.subklasifikasi' // Pastikan eager load subklasifikasi jika ada
        ])->get();
    
        $selectedCabang = $tokoId ? $inquery->first()->toko->nama_toko : 'Semua Toko';
    
        // Kelompokkan data berdasarkan klasifikasi
       // Kelompokkan data berdasarkan klasifikasi
$groupedByKlasifikasi = $inquery->groupBy(function($item) {
    return $item->detailpemesananproduk->first()->produk->klasifikasi->nama ?? 'Tidak Diketahui';
});

// Untuk setiap klasifikasi, kelompokkan berdasarkan produk dan pelanggan
$finalGrouped = $groupedByKlasifikasi->map(function ($items) {
    return $items->flatMap(function ($item) {
        return $item->detailpemesananproduk->map(function ($detail) use ($item) {
            return [
                'kode_pemesanan' => $item->kode_pemesanan,
                'nama_pelanggan' => $item->nama_pelanggan,
                'kode_pelanggan' => $item->kode_pelanggan,
                'catatan' => $item->catatan,
                'produk' => $detail->produk,
                'jumlah' => $detail->jumlah,
            ];
        });
    })->groupBy('produk.id');
});


        // Generate PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('admin.laporan_pemesananproduk.print', [
            'groupedByKlasifikasi' => $groupedByKlasifikasi,
            'startDate' => $tanggalPemesanan,
            'finalGrouped' => $finalGrouped,
            'endDate' => $tanggalAkhir,
            'branchName' => $tokoId ? $inquery->first()->toko->nama_toko : 'Semua Cabang',
            'selectedCabang' => $selectedCabang // Pass the selected cabang to the view
        ]);
    
        // Menambahkan nomor halaman di footer
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
        return $pdf->stream('laporan_pemesanan_produk.pdf');
    }
    
    
    public function printReportPemesananglobal(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'status' => 'nullable|string',
            'tanggal_kirim' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'toko_id' => 'nullable|integer',
        ]);

        // Tangkap data dari request
        $status = htmlspecialchars($validatedData['status'] ?? null);
        $tanggalPemesanan = $validatedData['tanggal_kirim'] ?? null;
        $tanggalAkhir = $validatedData['tanggal_akhir'] ?? null;
        $tokoId = htmlspecialchars($validatedData['toko_id'] ?? '0');

        // Definisikan daftar toko
        $tokoList = [
            1 => 'Banjaran',
            2 => 'Tegal',
            3 => 'Slawi',
            4 => 'Pemalang',
            5 => 'Bumiayu',
            6 => 'Cilacap',
        ];

        // Tentukan toko yang akan ditampilkan berdasarkan filter
        if ($tokoId && $tokoId != '0') {
            // Jika toko tertentu dipilih
            $tokoFieldMap = [
                $tokoId => strtolower($tokoList[$tokoId] ?? 'unknown'),
            ];
        } else {
            // Jika "Semua Toko" dipilih
            $tokoFieldMap = [];
            foreach ($tokoList as $id => $name) {
                $tokoFieldMap[$id] = strtolower($name);
            }
        }

        // Query untuk mendapatkan data pemesanan produk
        $query = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalPemesanan, $tanggalAkhir) {
                $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
                $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
                return $query->whereBetween('tanggal_kirim', [$tanggalPemesanan, $tanggalAkhir]);
            })
            ->when($tanggalPemesanan && !$tanggalAkhir, function ($query) use ($tanggalPemesanan) {
                $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
                return $query->where('tanggal_kirim', '>=', $tanggalPemesanan);
            })
            ->when(!$tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalAkhir) {
                $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
                return $query->where('tanggal_kirim', '<=', $tanggalAkhir);
            })
            ->when(!$tanggalPemesanan && !$tanggalAkhir, function ($query) {
                return $query->whereDate('tanggal_kirim', Carbon::today());
            })
            ->when($tokoId && $tokoId != '0', function ($query) use ($tokoId) {
                return $query->where('toko_id', $tokoId);
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
                    // Inisialisasi data produk
                    $dataArray = [
                        'klasifikasi' => $klasifikasi,
                        'kode_produk' => $detail->kode_produk ?? 'Tidak ada',
                        'kode_lama' => $detail->kode_lama ?? 'Tidak ada',
                        'nama_produk' => $detail->nama_produk ?? 'Tidak ada',
                        'subtotal' => 0,
                    ];
                    // Inisialisasi field toko sesuai dengan toko yang dipilih
                    foreach ($tokoFieldMap as $field) {
                        $dataArray[$field] = 0;
                    }
                    $groupedData[$klasifikasi][$key] = $dataArray;
                }
                // Map toko_id ke field toko yang sesuai
                $tokoField = $tokoFieldMap[$item->toko_id] ?? null;
                if ($tokoField) {
                    $groupedData[$klasifikasi][$key][$tokoField] += (int) $detail->jumlah;
                    $groupedData[$klasifikasi][$key]['subtotal'] += (int) $detail->jumlah;

                }
            }
        }

        foreach ($groupedData as $klasifikasi => &$items) {
            // Urutkan berdasarkan kode_lama (asumsikan kode_lama adalah string atau integer)
            uasort($items, function ($a, $b) {
                return strcmp($a['kode_lama'], $b['kode_lama']); // Menggunakan strcmp untuk sorting string
            });
        }
        // Format tanggal untuk tampilan PDF
        $formattedStartDate = $tanggalPemesanan ? Carbon::parse($tanggalPemesanan)->format('d-m-Y') : null;
        $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : null;

        // Hitung total subtotal
        $totalSubtotal = 0;
        foreach ($groupedData as $klasifikasi => $items) {
            foreach ($items as $data) {
                $totalSubtotal += $data['subtotal'];
            }
        }

        // Buat PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('admin.laporan_pemesananproduk.printglobal', [
            'groupedData' => $groupedData,
            'totalSubtotal' => $totalSubtotal,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'toko_id' => $tokoId,
            'tokoFieldMap' => $tokoFieldMap,
            'tokoList' => $tokoList, 
            'selectedCabang' => $tokoList[$tokoId] ?? 'Semua Toko', // Tambahkan variabel ini
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
            $x = $canvas->get_width() - $width - 10;
            $y = $canvas->get_height() - 15;

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('Laporan_Pemesanan_Produk.pdf');
    }
    
    public function printReportPemesananglobal1(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'status' => 'nullable|string',
            'tanggal_kirim' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'toko_id' => 'nullable|integer',
        ]);

        // Tangkap data dari request
        $status = htmlspecialchars($validatedData['status'] ?? null);
        $tanggalPemesanan = $validatedData['tanggal_kirim'] ?? null;
        $tanggalAkhir = $validatedData['tanggal_akhir'] ?? null;
        $tokoId = htmlspecialchars($validatedData['toko_id'] ?? '0');

        // Definisikan daftar toko
        $tokoList = [
            1 => 'Banjaran',
            2 => 'Tegal',
            3 => 'Slawi',
            4 => 'Pemalang',
            5 => 'Bumiayu',
            6 => 'Cilacap',
        ];

        // Tentukan toko yang akan ditampilkan berdasarkan filter
        if ($tokoId && $tokoId != '0') {
            // Jika toko tertentu dipilih
            $tokoFieldMap = [
                $tokoId => strtolower($tokoList[$tokoId] ?? 'unknown'),
            ];
        } else {
            // Jika "Semua Toko" dipilih
            $tokoFieldMap = [];
            foreach ($tokoList as $id => $name) {
                $tokoFieldMap[$id] = strtolower($name);
            }
        }

        // Query untuk mendapatkan data pemesanan produk
        $query = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalPemesanan, $tanggalAkhir) {
                $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
                $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
                return $query->whereBetween('tanggal_kirim', [$tanggalPemesanan, $tanggalAkhir]);
            })
            ->when($tanggalPemesanan && !$tanggalAkhir, function ($query) use ($tanggalPemesanan) {
                $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
                return $query->where('tanggal_kirim', '>=', $tanggalPemesanan);
            })
            ->when(!$tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalAkhir) {
                $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
                return $query->where('tanggal_kirim', '<=', $tanggalAkhir);
            })
            ->when(!$tanggalPemesanan && !$tanggalAkhir, function ($query) {
                return $query->whereDate('tanggal_kirim', Carbon::today());
            })
            ->when($tokoId && $tokoId != '0', function ($query) use ($tokoId) {
                return $query->where('toko_id', $tokoId);
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
                    // Inisialisasi data produk
                    $dataArray = [
                        'klasifikasi' => $klasifikasi,
                        'kode_produk' => $detail->kode_produk ?? 'Tidak ada',
                        'kode_lama' => $detail->kode_lama ?? 'Tidak ada',
                        'nama_produk' => $detail->nama_produk ?? 'Tidak ada',
                        'subtotal' => 0,
                    ];
                    // Inisialisasi field toko sesuai dengan toko yang dipilih
                    foreach ($tokoFieldMap as $field) {
                        $dataArray[$field] = 0;
                    }
                    $groupedData[$klasifikasi][$key] = $dataArray;
                }
                // Map toko_id ke field toko yang sesuai
                $tokoField = $tokoFieldMap[$item->toko_id] ?? null;
                if ($tokoField) {
                    $groupedData[$klasifikasi][$key][$tokoField] += (int) $detail->jumlah;
                    $groupedData[$klasifikasi][$key]['subtotal'] += (int) $detail->jumlah;

                }
            }
        }

        foreach ($groupedData as $klasifikasi => &$items) {
            // Urutkan berdasarkan kode_lama (asumsikan kode_lama adalah string atau integer)
            uasort($items, function ($a, $b) {
                return strcmp($a['kode_lama'], $b['kode_lama']); // Menggunakan strcmp untuk sorting string
            });
        }
        // Format tanggal untuk tampilan PDF
        $formattedStartDate = $tanggalPemesanan ? Carbon::parse($tanggalPemesanan)->format('d-m-Y') : null;
        $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : null;

        // Hitung total subtotal
        $totalSubtotal = 0;
        foreach ($groupedData as $klasifikasi => $items) {
            foreach ($items as $data) {
                $totalSubtotal += $data['subtotal'];
            }
        }

        // Buat PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('admin.laporan_pemesananproduk.printglobaltoko', [
            'groupedData' => $groupedData,
            'totalSubtotal' => $totalSubtotal,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'toko_id' => $tokoId,
            'tokoFieldMap' => $tokoFieldMap,
            'tokoList' => $tokoList, 
            'selectedCabang' => $tokoList[$tokoId] ?? 'Semua Toko', // Tambahkan variabel ini
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
            $x = $canvas->get_width() - $width - 10;
            $y = $canvas->get_height() - 15;

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('Laporan_Pemesanan_Produk.pdf');
    }

    

}