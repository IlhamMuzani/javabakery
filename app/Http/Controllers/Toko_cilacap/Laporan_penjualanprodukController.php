<?php

namespace App\Http\Controllers\Toko_cilacap;

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
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Laporan_penjualanprodukController extends Controller
{
    public function index(Request $request)
{
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $klasifikasi_id = $request->klasifikasi_id;

    // Query dasar untuk mengambil data penjualan produk dengan toko_id = 6
    $query = Penjualanproduk::where('toko_id', 6);

    // Filter berdasarkan status
    if ($status) {
        $query->where('status', $status);
    }

    // Filter berdasarkan tanggal penjualan
    if ($tanggal_penjualan && $tanggal_akhir) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
        $query->whereDate('tanggal_penjualan', Carbon::today());
    }

    // Filter berdasarkan produk
    if ($produk) {
        $query->whereHas('detailpenjualanproduk', function ($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $query->whereHas('detailpenjualanproduk.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    // Urutkan data berdasarkan ID secara descending
    $query->orderBy('id', 'DESC');

    // Ambil data penjualan produk
    $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

    // Ambil semua data produk untuk dropdown
    $produks = Produk::all();

    // Ambil semua data toko untuk dropdown
    $tokos = Toko::all();

    // Ambil semua klasifikasi untuk dropdown
    $klasifikasis = Klasifikasi::all();

    // Kembalikan view dengan data penjualan produk, produk, toko, dan klasifikasi
    return view('toko_cilacap.laporan_penjualanproduk.index', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
}



    public function indexglobal(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

    
        $query = Penjualanproduk::where('toko_id', 6);
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal penjualan
        if ($tanggal_penjualan && $tanggal_akhir) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_penjualan', Carbon::today());
        }
    
        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailpenjualanproduk', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
        
        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('detailpenjualanproduk.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }
            // Urutkan data berdasarkan ID secara descending
        $query->orderBy('id', 'DESC');
    
        // Ambil data penjualan produk
        $inquery = $query->with('toko', 'detailpenjualanproduk.produk.klasifikasi')->get();
    
        // Ambil semua data produk untuk dropdown
        $produks = Produk::all();
    
        // Ambil semua data toko untuk dropdown
        $tokos = Toko::all();
    
        $klasifikasis = Klasifikasi::all();

        // Kembalikan view dengan data penjualan produk, produk, dan toko
        return view('toko_cilacap.laporan_penjualanproduk.global', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
    }
 

public function printReport(Request $request)
{
    $status = $request->input('status');
    $tanggalPenjualan = $request->input('tanggal_penjualan');
    $tanggalAkhir = $request->input('tanggal_akhir');
    $produk = $request->input('produk');
    $tokoId = $request->input('toko_id');
    $klasifikasiId = $request->input('klasifikasi_id');

    // Initialize the query
    $query = Penjualanproduk::where('toko_id', 6);

    // Apply status filter
    if ($status) {
        $query->where('status', $status);
    }

    // Apply date range filter
    if ($tanggalPenjualan && $tanggalAkhir) {
        $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
        $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
        $query->whereBetween('tanggal_penjualan', [$tanggalPenjualan, $tanggalAkhir]);
    } elseif ($tanggalPenjualan) {
        $tanggalPenjualan = Carbon::parse($tanggalPenjualan)->startOfDay();
        $query->where('tanggal_penjualan', '>=', $tanggalPenjualan);
    } elseif ($tanggalAkhir) {
        $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
        $query->where('tanggal_penjualan', '<=', $tanggalAkhir);
    } else {
        // Default to today's date if no date filter is provided
        $query->whereDate('tanggal_penjualan', Carbon::today());
    }

    // Apply product filter
    if ($produk) {
        $query->whereHas('detailpenjualanproduk', function ($q) use ($produk) {
            $q->where('produk_id', $produk);
        });
    }


    // Apply classification filter
    if ($klasifikasiId) {
        $query->whereHas('detailpenjualanproduk.produk.klasifikasi', function ($q) use ($klasifikasiId) {
            $q->where('id', $klasifikasiId);
        });
    }

    // Order results
    $query->orderBy('id', 'DESC');

    // Load related data
    $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi', 'metodePembayaran'])->get();

    // Format dates for PDF view
    $formattedStartDate = $tanggalPenjualan ? Carbon::parse($tanggalPenjualan)->format('d-m-Y') : 'N/A';
    $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : 'N/A';

    // Generate PDF
    $pdf = FacadePdf::loadView('toko_cilacap.laporan_penjualanproduk.print', [
        'inquery' => $inquery,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
    ]);

    return $pdf->stream('laporan_penjualan_produk.pdf');
}


// global
public function printReportglobal(Request $request)
{
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;

    // Default toko_id ke 1 jika tidak ada yang dipilih
    if (!$toko_id) {
        $toko_id = 6; // ID Toko Banjaran
    }

    // Query dasar untuk mengambil data penjualan produk
    $query = Penjualanproduk::with(['toko', 'detailpenjualanproduk', 'dppemesanan'])->orderBy('id', 'DESC');

    // Filter berdasarkan status
    if ($status) {
        $query->where('status', $status);
    }

    // Filter berdasarkan tanggal penjualan
    if ($tanggal_penjualan && $tanggal_akhir) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    } else {
        $query->whereDate('tanggal_penjualan', Carbon::today());
    }

    // Filter berdasarkan produk
    if ($produk) {
        $query->whereHas('detailpenjualanproduk', function ($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Filter berdasarkan toko
    $query->where('toko_id', $toko_id);
    $toko = Toko::find($toko_id); // Ambil nama toko berdasarkan ID
    $branchName = $toko ? $toko->nama_toko : 'Semua Toko'; // Nama toko atau default jika tidak ditemukan

    $inquery = $query->get();

    // Format tanggal untuk tampilan PDF
    $formattedStartDate = $tanggal_penjualan ? Carbon::parse($tanggal_penjualan)->format('d-m-Y') : null;
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : null;

    // Inisialisasi DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Jika menggunakan URL eksternal untuk gambar atau CSS

    $dompdf = new Dompdf($options);

    // Memuat konten HTML dari view
    $html = view('toko_cilacap.laporan_penjualanproduk.printglobal', [
        'inquery' => $inquery,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
        'branchName' => $branchName, // Sertakan variabel nama cabang toko
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
    return $dompdf->stream('laporan_penjualan_produk.pdf', ['Attachment' => false]);
}

   


public function unpost_penjualanproduk($id)
{
    $item = Penjualanproduk::where('id', $id)->first();

    
        $item->update([
            'status' => 'unpost'
        ]);
    return back()->with('success', 'Berhasil');
}

public function posting_penjualanproduk($id)
{
    $item = Penjualanproduk::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
    return back()->with('success', 'Berhasil');
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
            $pelanggans = Pelanggan::all();
            $tokoslawis = Tokoslawi::all();
            $tokos = Toko::all();
        
            $produks = Produk::with('tokoslawi')->get();
            $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
            $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

            return view('toko_cilacap.inquery_pemesananproduk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks' ,'selectedTokoId'));
        }
        
        public function update(Request $request, $id)
        {
            // Validasi pelanggan
            $validasi_pelanggan = Validator::make(
                $request->all(),
                [
                    'nama_pelanggan' => 'required',
                    'telp' => 'required',
                    'alamat' => 'required',
                    'kategori' => 'required',
                ],
                [
                    'nama_pelanggan.required' => 'Masukkan nama pelanggan',
                    'telp.required' => 'Masukkan telepon',
                    'alamat.required' => 'Masukkan alamat',
                    'kategori.required' => 'Pilih kategori pelanggan',
                ]
            );
        
            // Handling errors for pelanggan
            $error_pelanggans = array();
        
            if ($validasi_pelanggan->fails()) {
                array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
            }
        
            // Handling errors for pesanans
            $error_pesanans = array();
            $data_pembelians = collect();
        
            if ($request->has('produk_id')) {
                for ($i = 0; $i < count($request->produk_id); $i++) {
                    $validasi_produk = Validator::make($request->all(), [
                        'kode_produk.' . $i => 'required',
                        'produk_id.' . $i => 'required',
                        'nama_produk.' . $i => 'required',
                        'harga.' . $i => 'required',
                        'total.' . $i => 'required',
                    ]);
        
                    if ($validasi_produk->fails()) {
                        array_push($error_pesanans, "Barang no " . ($i + 1) . " belum dilengkapi!");
                    }
        
                    $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
                    $kode_produk = is_null($request->kode_produk[$i]) ? '' : $request->kode_produk[$i];
                    $nama_produk = is_null($request->nama_produk[$i]) ? '' : $request->nama_produk[$i];
                    $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                    $diskon = is_null($request->diskon[$i]) ? '' : $request->diskon[$i];
                    $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                    $total = is_null($request->total[$i]) ? '' : $request->total[$i];
        
                    $data_pembelians->push([
                        'kode_produk' => $kode_produk,
                        'produk_id' => $produk_id,
                        'nama_produk' => $nama_produk,
                        'jumlah' => $jumlah,
                        'diskon' => $diskon,
                        'harga' => $harga,
                        'total' => $total,
                    ]);
                }
            }
        
            // Handling errors for pelanggans or pesanans
            if ($error_pelanggans || $error_pesanans) {
                return back()
                    ->withInput()
                    ->with('error_pelanggans', $error_pelanggans)
                    ->with('error_pesanans', $error_pesanans)
                    ->with('data_pembelians', $data_pembelians);
            }
        
            // Update pemesanan yang ada
            $pemesanan = Pemesananproduk::find($id);
            $pemesanan->update([
                'nama_pelanggan' => $request->nama_pelanggan,
                'telp' => $request->telp,
                'alamat' => $request->alamat,
                'kategori' => $request->kategori,
                'sub_total' => $request->sub_total,
                'nama_penerima' => $request->nama_penerima,
                'telp_penerima' => $request->telp_penerima,
                'alamat_penerima' => $request->alamat_penerima,
                'tanggal_kirim' => $request->tanggal_kirim,
                'toko_id' => $request->toko,
                'kode_pemesanan' => $request->kode_pemesanan,
                'qrcode_pemesanan' => 'https://javabakery.id/pemesanan/' . $this->kode(),
                'tanggal_penjualan' => Carbon::now('Asia/Jakarta'),
                'status' => 'posting',
            ]);
        
            // Simpan atau perbarui detail pemesanan
            foreach ($data_pembelians as $data_pesanan) {
                $detail = Detailpemesananproduk::where('pemesananproduk_id', $pemesanan->id)
                    ->where('kode_produk', $data_pesanan['kode_produk'])
                    ->first();
        
                if ($detail) {
                    // Jika detail sudah ada, perbarui
                    $detail->update([
                        'nama_produk' => $data_pesanan['nama_produk'],
                        'jumlah' => $data_pesanan['jumlah'],
                        'diskon' => $data_pesanan['diskon'],
                        'harga' => $data_pesanan['harga'],
                        'total' => $data_pesanan['total'],
                    ]);
                } else {
                    // Jika detail belum ada, buat baru
                    Detailpemesananproduk::create([
                        'pemesananproduk_id' => $pemesanan->id,
                        'kode_produk' => $data_pesanan['kode_produk'],
                        'nama_produk' => $data_pesanan['nama_produk'],
                        'jumlah' => $data_pesanan['jumlah'],
                        'diskon' => $data_pesanan['diskon'],
                        'harga' => $data_pesanan['harga'],
                        'total' => $data_pesanan['total'],
                    ]);
                }
            }
        
            // Ambil detail pemesanan untuk ditampilkan di halaman cetak
            $details = Detailpemesananproduk::where('pemesananproduk_id', $pemesanan->id)->get();
        
            // Redirect ke halaman indeks pemesananproduk
            return redirect('toko_cilacap/inquery_pemesananproduk');

        }
        

    public function destroy($id)
    {
        //
    }

}