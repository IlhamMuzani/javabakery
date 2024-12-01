<?php

namespace App\Http\Controllers\Toko_banjaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use App\Models\Toko;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class Laporan_depositbanjaranController extends Controller
{
 

    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_kirim = $request->tanggal_kirim;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_pelunasan = $request->status_pelunasan;

        // Ambil daftar toko untuk filter (jika diperlukan untuk tujuan lain)
        $tokos = Toko::all();
        
        // Query dasar untuk mengambil data Dppemesanan
        $inquery = Dppemesanan::with(['pemesananproduk.toko'])
            ->whereHas('pemesananproduk', function ($query) {
                $query->where('toko_id', 1); // Secara default ambil toko_id = 1
            })
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filter_tanggal == 'tanggal_kirim') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_kirim', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_kirim', '<=', $tanggal_akhir);
                });
            }
        } elseif ($request->filter_tanggal == 'tanggal_pemesanan') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_pemesanan', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_pemesanan', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
                });
            }
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $inquery->whereHas('pemesananproduk', function ($query) {
                $query->whereDate('tanggal_kirim', Carbon::today());
            });
        }

        // Filter berdasarkan status pelunasan
        if ($status_pelunasan == 'diambil') {
            $inquery->where(function ($query) {
                $query->whereNotNull('pelunasan')
                    ->where('pelunasan', '>', 0);
            });
        } elseif ($status_pelunasan == 'belum_diambil') {
            $inquery->where(function ($query) {
                $query->whereNull('pelunasan')
                    ->orWhere('pelunasan', 0);
            });
        }

        // Eksekusi query dan dapatkan hasilnya
        $inquery = $inquery->get();
        
        // Kirim data ke view
        return view('toko_banjaran.laporan_depositbanjaran.index', compact('inquery', 'tokos'));
    }

    public function indexrinci(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_kirim = $request->tanggal_kirim;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_pelunasan = $request->status_pelunasan;

        // Ambil daftar toko untuk filter (jika diperlukan untuk tujuan lain)
        $tokos = Toko::all();
        
        // Query dasar untuk mengambil data Dppemesanan
        $inquery = Dppemesanan::with(['pemesananproduk.toko'])
            ->whereHas('pemesananproduk', function ($query) {
                $query->where('toko_id', 1); // Secara default ambil toko_id = 1
            })
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filter_tanggal == 'tanggal_kirim') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_kirim', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_kirim', '<=', $tanggal_akhir);
                });
            }
        } elseif ($request->filter_tanggal == 'tanggal_pemesanan') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_pemesanan', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_pemesanan', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
                });
            }
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $inquery->whereHas('pemesananproduk', function ($query) {
                $query->whereDate('tanggal_kirim', Carbon::today());
            });
        }

        // Filter berdasarkan status pelunasan
        if ($status_pelunasan == 'diambil') {
            $inquery->where(function ($query) {
                $query->whereNotNull('pelunasan')
                    ->where('pelunasan', '>', 0);
            });
        } elseif ($status_pelunasan == 'belum_diambil') {
            $inquery->where(function ($query) {
                $query->whereNull('pelunasan')
                    ->orWhere('pelunasan', 0);
            });
        }

        // Eksekusi query dan dapatkan hasilnya
        $inquery = $inquery->get();
        
        // Kirim data ke view
        return view('toko_banjaran.laporan_depositbanjaran.indexrinci', compact('inquery', 'tokos'));
    }

    public function indexsaldo(Request $request)
    {
        // Ambil parameter filter dari request
        $toko_id = $request->toko_id;

        // Ambil daftar toko untuk filter
        $tokos = Toko::all();

        // Query dasar untuk mengambil data Dppemesanan
        $inquery = Dppemesanan::with(['pemesananproduk.toko'])
        ->whereHas('pemesananproduk', function ($query) {
            $query->where('toko_id', 1); 
        })
        ->orderBy('created_at', 'desc');

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
                $query->where('toko_id', $toko_id);
            });
        }

        // Ambil data dppemesanan berdasarkan toko yang dipilih dan status_pelunasan NULL
        $inquery = $inquery->get()->groupBy('pemesananproduk.toko_id');

        // Hitung saldo untuk setiap toko (jumlah dp_pemesanan di mana status_pelunasan NULL)
        $saldoPerToko = [];
        foreach ($inquery as $tokoId => $dpPemesanan) {
            $totalSaldo = $dpPemesanan->whereNull('pelunasan')->sum('dp_pemesanan');
            $saldoPerToko[$tokoId] = $totalSaldo;
        }

        // Kirim data ke view
        return view('toko_banjaran.laporan_depositbanjaran.indexsaldo', compact('saldoPerToko', 'tokos', 'toko_id'));
    }


    public function printReportdeposit(Request $request)
    {
        // Set default toko_id untuk Banjaran
        $banjaranTokoId = 1;
    
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_kirim = $request->tanggal_kirim;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_pelunasan = $request->status_pelunasan;
        $toko_id = $request->toko_id ?: $banjaranTokoId; // Jika toko_id tidak diisi, set default ke Banjaran
        $filter_tanggal = $request->filter_tanggal; // Ambil filter tanggal dari request
    
        // Ambil daftar toko untuk filter
        $tokos = Toko::all();
    
        // Query dasar untuk mengambil data Dppemesanan
        $inquery = Dppemesanan::with(['pemesananproduk.toko']);
    
        // Filter berdasarkan status
        if ($status) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }
    
        // Filter berdasarkan toko_id (default ke Banjaran jika tidak ada toko_id lain)
        $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    
        // Filter berdasarkan tanggal
        if ($filter_tanggal == 'tanggal_kirim') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_kirim', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_kirim', '<=', $tanggal_akhir);
                });
            }
        } elseif ($filter_tanggal == 'tanggal_pemesanan') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_pemesanan', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_pemesanan', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
                });
            }
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $inquery->whereHas('pemesananproduk', function ($query) {
                $query->whereDate('tanggal_kirim', Carbon::today());
            });
        }
    
        // Filter berdasarkan status pelunasan
        if ($status_pelunasan == 'diambil') {
            $inquery->whereNotNull('pelunasan');
        } elseif ($status_pelunasan == 'belum_diambil') {
            $inquery->whereNull('pelunasan');
        }
    
        // Eksekusi query dan dapatkan hasilnya
        $inquery = $inquery->get();
    
        // Hitung total deposit, fee deposit, dan sub total
        $totalDeposit = $inquery->sum(function ($deposit) {
            return (int)$deposit->dp_pemesanan; // Pastikan nilai numerik
        });
        $totalFee = $inquery->sum(function ($deposit) {
            return (int)($deposit->pemesananproduk->total_fee ?? 0); // Pastikan nilai numerik
        });
        $subTotal = $totalDeposit + $totalFee;
    
        // Format tanggal untuk tampilan PDF
        $formattedStartDate = $tanggal_kirim ? Carbon::parse($tanggal_kirim)->format('d-m-Y') : null;
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : null;
    
        // Ambil nama toko berdasarkan ID, default ke Banjaran
        $branchName = Toko::find($toko_id)->nama_toko ?? 'Toko Banjaran';
    
        // Buat PDF menggunakan Facade PDF
        $pdf = FacadePdf::loadView('toko_banjaran.laporan_depositbanjaran.print', [
            'inquery' => $inquery,
            'tokos' => $tokos,
            'status' => $status,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'status_pelunasan' => $status_pelunasan,
            'toko_id' => $toko_id,
            'totalDeposit' => $totalDeposit,
            'totalFee' => $totalFee,
            'subTotal' => $subTotal,
            'branchName' => $branchName
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
        return $pdf->stream('laporan_deposit.pdf');
    }
    
    
    // public function printReportdepositrinci(Request $request)
    // {
    //     // Set default toko_id untuk Banjaran
    //     $banjaranTokoId = 1;
    
    //     // Ambil parameter filter dari request
    //     $status = $request->status;
    //     $tanggal_kirim = $request->tanggal_kirim;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $status_pelunasan = $request->status_pelunasan;
    //     $toko_id = $request->toko_id ?: $banjaranTokoId; // Jika toko_id tidak diisi, set default ke Banjaran
    //     $filter_tanggal = $request->filter_tanggal; // Ambil filter tanggal dari request
    
    //     // Ambil daftar toko untuk filter
    //     $tokos = Toko::all();
    
    //     $branchName = $toko_id ? Toko::find($toko_id)->nama_toko : 'Semua Cabang';
    //     // Query dasar untuk mengambil data Dppemesanan dan relasi pemesananproduk
    //     $inquery = Dppemesanan::with(['pemesananproduk' => function($query) {
    //         // Eager load detail pemesanan produk untuk akses tanggal dan pelanggan
    //         $query->with(['detailpemesananproduk', 'pelanggan']);
    //     }])
    //     ->orderBy('created_at', 'asc');

    //     // Format tanggal untuk ditampilkan di view
    //     $formattedStartDate = $tanggal_kirim ? Carbon::parse($tanggal_kirim)->format('d-m-Y') : 'N/A';
    //     $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';
    
    //     // Filter berdasarkan status
    //     if ($status) {
    //         $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
    //             $query->where('status', $status);
    //         });
    //     }
    
    //     // Filter berdasarkan toko_id (default ke Banjaran jika tidak ada toko_id lain)
    //     $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
    //         $query->where('toko_id', $toko_id);
    //     });
    
    //     // Filter berdasarkan tanggal
    //     if ($filter_tanggal == 'tanggal_kirim') {
    //         if ($tanggal_kirim && $tanggal_akhir) {
    //             $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
    //                 $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
    //             });
    //         } elseif ($tanggal_kirim) {
    //             $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
    //             $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
    //                 $query->where('tanggal_kirim', '>=', $tanggal_kirim);
    //             });
    //         } elseif ($tanggal_akhir) {
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
    //                 $query->where('tanggal_kirim', '<=', $tanggal_akhir);
    //             });
    //         }
    //     } elseif ($filter_tanggal == 'tanggal_pemesanan') {
    //         if ($tanggal_kirim && $tanggal_akhir) {
    //             $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
    //                 $query->whereBetween('tanggal_pemesanan', [$tanggal_kirim, $tanggal_akhir]);
    //             });
    //         } elseif ($tanggal_kirim) {
    //             $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
    //             $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
    //                 $query->where('tanggal_pemesanan', '>=', $tanggal_kirim);
    //             });
    //         } elseif ($tanggal_akhir) {
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
    //                 $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
    //             });
    //         }
    //     } else {
    //         // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
    //         $inquery->whereHas('pemesananproduk', function ($query) {
    //             $query->whereDate('tanggal_kirim', Carbon::today());
    //         });
    //     }
    
    //     // Filter berdasarkan status pelunasan
    //     if ($status_pelunasan == 'diambil') {
    //         $inquery->whereNotNull('pelunasan');
    //     } elseif ($status_pelunasan == 'belum_diambil') {
    //         $inquery->whereNull('pelunasan');
    //     }
    
    //     // Eksekusi query dan dapatkan hasilnya
    //     $inquery = $inquery->get();
    
    //     // Hitung total deposit, fee deposit, dan sub total
    //     $totalDeposit = $inquery->sum(function ($deposit) {
    //         return (int)$deposit->dp_pemesanan; // Pastikan nilai numerik
    //     });
    //     $totalFee = $inquery->sum(function ($deposit) {
    //         return (int)($deposit->pemesananproduk->total_fee ?? 0); // Pastikan nilai numerik
    //     });
    //     $subTotal = $totalDeposit + $totalFee;

    
    //     // Buat PDF menggunakan Facade PDF
    //     $pdf = FacadePdf::loadView('toko_banjaran.laporan_depositbanjaran.printrinci', [
    //         'inquery' => $inquery,
    //         'tokos' => $tokos,
    //         'status' => $status,
    //         'startDate' => $formattedStartDate,
    //         'endDate' => $formattedEndDate,
    //         'status_pelunasan' => $status_pelunasan,
    //         'toko_id' => $toko_id,
    //         'totalDeposit' => $totalDeposit,
    //         'totalFee' => $totalFee,
    //         'subTotal' => $subTotal,
    //         'branchName' => $branchName
    //     ]);
    
    //     // Menambahkan nomor halaman di kanan bawah
    //     $pdf->output();
    //     $dompdf = $pdf->getDomPDF();
    //     $canvas = $dompdf->getCanvas();
    //     $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    //         $text = "Page $pageNumber of $pageCount";
    //         $font = $fontMetrics->getFont('Arial', 'normal');
    //         $size = 8;
    
    //         // Menghitung lebar teks
    //         $width = $fontMetrics->getTextWidth($text, $font, $size);
    
    //         // Mengatur koordinat X dan Y
    //         $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
    //         $y = $canvas->get_height() - 15; // 15 pixel dari bawah
    
    //         // Menambahkan teks ke posisi yang ditentukan
    //         $canvas->text($x, $y, $text, $font, $size);
    //     });
    
    //     // Output PDF ke browser
    //     return $pdf->stream('laporan_deposit.pdf');
    // }
    
    public function printReportdepositrinci(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_kirim = $request->tanggal_kirim;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_pelunasan = $request->status_pelunasan;
        $toko_id = $request->toko_id;
        $filter_tanggal = $request->filter_tanggal; // Ambil filter tanggal dari request

        // Ambil daftar toko untuk filter
        $tokos = Toko::all();

        // Dapatkan nama toko berdasarkan toko_id
        $branchName = $toko_id ? Toko::find($toko_id)->nama_toko : 'Semua Cabang';

        // Query dasar untuk mengambil data Dppemesanan dan relasi pemesananproduk
        $inquery = Dppemesanan::with(['pemesananproduk' => function($query) {
            // Eager load detail pemesanan produk untuk akses tanggal dan pelanggan
            $query->with(['detailpemesananproduk', 'pelanggan']);
        }])
        ->orderBy('created_at', 'asc');

        // Format tanggal untuk ditampilkan di view
        $formattedStartDate = $tanggal_kirim ? Carbon::parse($tanggal_kirim)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

        // Filter berdasarkan status
        if ($status) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
                $query->where('toko_id', $toko_id);
            });
        }

        // Filter berdasarkan tanggal kirim atau pemesanan
        if ($filter_tanggal == 'tanggal_kirim') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_kirim', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_kirim', '<=', $tanggal_akhir);
                });
            }
        } elseif ($filter_tanggal == 'tanggal_pemesanan') {
            if ($tanggal_kirim && $tanggal_akhir) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim, $tanggal_akhir) {
                    $query->whereBetween('tanggal_pemesanan', [$tanggal_kirim, $tanggal_akhir]);
                });
            } elseif ($tanggal_kirim) {
                $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_kirim) {
                    $query->where('tanggal_pemesanan', '>=', $tanggal_kirim);
                });
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                    $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
                });
            }
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $inquery->whereHas('pemesananproduk', function ($query) {
                $query->whereDate('tanggal_kirim', Carbon::today());
            });
        }

        // Filter berdasarkan status pelunasan
        if ($status_pelunasan == 'diambil') {
            $inquery->whereNotNull('pelunasan');
        } elseif ($status_pelunasan == 'belum_diambil') {
            $inquery->whereNull('pelunasan');
        }

        // Eksekusi query dan dapatkan hasilnya
        $inquery = $inquery->get();

        // Hitung total deposit, fee deposit, dan sub total
        $totalDeposit = $inquery->sum(function ($deposit) {
            return (int)$deposit->dp_pemesanan; // Pastikan nilai numerik
        });
        $totalFee = $inquery->sum(function ($deposit) {
            return (int)($deposit->pemesananproduk->sum('total_fee') ?? 0); // Pastikan nilai numerik
        });
        $subTotal = $inquery->sum(function ($deposit) {
            return $deposit->pemesananproduk->sum('sub_totalasli'); // Pastikan nilai numerik
        });

        // Kirim data ke view cetak
        $pdf = FacadePdf::loadView('toko_banjaran.laporan_depositbanjaran.printrinci', compact(
            'inquery', 
            'tokos', 
            'status', 
            'tanggal_kirim', 
            'tanggal_akhir', 
            'status_pelunasan', 
            'toko_id', 
            'totalDeposit', 
            'totalFee', 
            'subTotal', 
            'formattedStartDate', 
            'formattedEndDate',
            'branchName'
        ));

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
        return $pdf->stream('laporan_deposit.pdf');
    }
   

    public function printReportsaldo(Request $request)
    {
        // Set toko_id ke 5
        $toko_id = 1;
    
        // Ambil daftar toko untuk filter
        $tokos = Toko::all();
    
        // Dapatkan nama toko berdasarkan toko_id
        $branchName = Toko::find($toko_id)->nama_toko;
    
        // Dapatkan alamat toko berdasarkan toko_id
        $branchAddress = Toko::find($toko_id)->alamat;
    
        // Query dasar untuk mengambil data Dppemesanan dan relasi pemesananproduk
        $inquery = Dppemesanan::with(['pemesananproduk.toko'])
            ->orderBy('created_at', 'desc');
    
        // Filter berdasarkan toko_id
        $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    
        // Eksekusi query dan group by toko
        $inquery = $inquery->get()->groupBy('pemesananproduk.toko_id');
    
        // Hitung saldo untuk setiap toko (jumlah dp_pemesanan di mana status_pelunasan NULL)
        $saldoPerToko = [];
        foreach ($inquery as $tokoId => $dpPemesanan) {
            $totalSaldo = $dpPemesanan->whereNull('pelunasan')->sum('dp_pemesanan');
            $saldoPerToko[$tokoId] = $totalSaldo;
        }
    
        // Kirim data ke view cetak
        $pdf = FacadePdf::loadView('toko_banjaran.laporan_depositbanjaran.printsaldo', compact(
            'saldoPerToko',
            'tokos',
            'toko_id',
            'branchName',
            'branchAddress'
        ));
    
        // Output PDF ke browser
        return $pdf->stream('laporan_deposit.pdf');
    }
    



    
}