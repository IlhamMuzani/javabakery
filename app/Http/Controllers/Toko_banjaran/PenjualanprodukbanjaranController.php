<?php

namespace App\Http\Controllers\Toko_banjaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
use App\Models\Stok_tokobanjaran;
use App\Models\Stokpesanan_tokobanjaran;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Barang;
use App\Models\Pelunasan;
use App\Models\Detailbarangjadi;
use App\Models\Detailpemesananproduk;
use App\Models\Detailpenjualanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class PenjualanprodukbanjaranController extends Controller
    {
    
    public function index(Request $request)
    {
        $today = Carbon::today();
        
        $inquery = Penjualanproduk::with('metodePembayaran')
            ->where('toko_id', 1) 
            ->where(function ($query) use ($today) {
                $query->whereDate('created_at', $today)
                      ->orWhere(function ($query) use ($today) {
                          $query->where('status', 'unpost')
                                ->whereDate('created_at', '<', $today);
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('toko_banjaran.penjualan_produk.index', compact('inquery'));
    }
    
    
    public function getProductByKode(Request $request)
    {
        $kode = $request->get('kode');
        $product = Produk::where('kode_produk', $kode)->first();
    
        if ($product) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'nama_produk' => $product->nama_produk,
                    'harga' => $product->harga,
                    'diskon' => $product->diskon,
                ],
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function pelanggan($id)
    {
        $user = Pelanggan::where('id', $id)->first();

        return json_decode($user);
    }

    public function metode($id)
    {
        $metode = Metodepembayaran::where('id', $id)->first();

        return json_decode($metode);
    }

    // create try
    public function create()
    {
        $barangs = Barang::all();
        $details = Detailbarangjadi::all();
        $tokos = Toko::all();
        $dppemesanans = Dppemesanan::all();
        $pemesananproduks = Pemesananproduk::all();
        $metodes = Metodepembayaran::all();

        // Mengambil semua pelanggan yang belum expired
        $today = Carbon::today();
        $pelanggans = Pelanggan::where('tanggal_akhir', '>=', $today)->get();

        // Mengambil produk yang tersedia di toko banjaran
        $produks = Produk::with(['tokobanjaran', 'stok_tokobanjaran'])->get();

        $kategoriPelanggan = 'member';

        return view('toko_banjaran.penjualan_produk.create', compact('barangs', 'tokos', 'produks', 'details', 'pelanggans', 'kategoriPelanggan', 'dppemesanans', 'pemesananproduks', 'metodes'));
    }

    public function getProduks(Request $request)
    {
        // Ambil semua data produk dengan relasi yang diperlukan
        $produks = Produk::with(['tokobanjaran', 'stok_tokobanjaran'])->get();

        return response()->json($produks);
    }


    //store try
    public function store(Request $request)
    {
        // Validasi pelanggan
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'nama_pelanggan' => 'nullable|string',
                'telp' => 'nullable|string',
                'alamat' => 'nullable|string',
                'kategori' => 'nullable|string',
                'metode_id' => 'nullable|exists:metodepembayarans,id',
                'total_fee' => 'nullable|numeric',
                'keterangan' => 'nullable|string'
            ],
            [
                'nama_pelanggan.nullable' => 'Masukkan nama pelanggan',
                'telp.nullable' => 'Masukkan telepon',
                'alamat.nullable' => 'Masukkan alamat',
                'kategori.nullable' => 'Pilih kategori pelanggan',
                'metode_id.nullable' => 'Pilih metode pembayaran',
                'total_fee.numeric' => 'Total fee harus berupa angka',
                'keterangan.string' => 'Keterangan harus berupa string',
            ]
        );
    
        // Handling errors for pelanggan
        $error_pelanggans = [];
        if ($validasi_pelanggan->fails()) {
            $error_pelanggans = $validasi_pelanggan->errors()->all();
        }
    
        // Handling errors for pesanans
        $error_pesanans = [];
        $data_pembelians = collect();
    
        if ($request->has('produk_id')) {
            for ($i = 0; $i < count($request->produk_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'kode_produk.' . $i => 'required',
                    'produk_id.' . $i => 'required',
                    'nama_produk.' . $i => 'required',
                    'harga.' . $i => 'required|numeric',
                    'total.' . $i => 'required|numeric',
                    'totalasli.' . $i => 'required|numeric',
                ]);
    
                if ($validasi_produk->fails()) {
                    $error_pesanans[] = "Barang no " . ($i + 1) . " belum dilengkapi!";
                }
    
                $produk_id = $request->input('produk_id.' . $i, '');
                $kode_produk = $request->input('kode_produk.' . $i, '');
                $kode_lama = $request->input('kode_lama.' . $i, '');
                $nama_produk = $request->input('nama_produk.' . $i, '');
                $jumlah = $request->input('jumlah.' . $i, '');
                $diskon = $request->input('diskon.' . $i, '');
                $harga = $request->input('harga.' . $i, '');
                $total = $request->input('total.' . $i, '');
                $totalasli = $request->input('totalasli.' . $i, '');
    
                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'kode_lama' => $kode_lama,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
                    'jumlah' => $jumlah,
                    'diskon' => $diskon,
                    'harga' => $harga,
                    'total' => $total,
                    'totalasli' => $totalasli,
                    'nominal_diskon' => ($harga * ($diskon / 100)) * $jumlah, // Menyimpan nominal_diskon
                ]);
            }
        }
    
        // Buat pemesanan baru
        $cetakpdf = Penjualanproduk::create([
            'nama_pelanggan' => $request->nama_pelanggan ?? null,
            'kode_pelanggan' => $request->kode_pelanggan ?? null,
            'kode_pelangganlama' => $request->kode_pelangganlama ?? null,
            'telp' => $request->telp ?? null,
            'alamat' => $request->alamat ?? null,
            'kategori' => $request->kategori,
            'sub_total' => $request->sub_total,
            'sub_totalasli' => $request->sub_totalasli,
            'bayar' => $request->bayar,
            'kembali' => $request->kembali,
            'catatan' => $request->catatan,
            'metode_id' => $request->metode_id === "tunai" ? null : $request->metode_id, // Simpan null jika metode adalah tunai
            'total_fee' => $request->metode_id === "tunai" ? 0 : ($request->total_fee ?? 0), // Simpan 0 jika metode adalah tunai atau total_fee kosong
            'keterangan' => $request->metode_id === "tunai" ? null : $request->keterangan, // Simpan null jika metode adalah tunai
            'toko_id' => 1,
            'kasir' => ucfirst(auth()->user()->karyawan->nama_lengkap),
            'kode_penjualan' => $this->kode(),
            'qrcode_penjualan' => 'https://javabakery.id/penjualan/' . $this->kode(),
            'tanggal_penjualan' => Carbon::now('Asia/Jakarta'),
            'status' => 'posting',
        ]);
    
        // Dapatkan ID transaksi baru
        $transaksi_id = $cetakpdf->id;
    
        // Simpan detail pemesanan dan kurangi stok
        foreach ($data_pembelians as $data_pesanan) {
            // Simpan detail pemesanan
            $detailPenjualan = Detailpenjualanproduk::create([
                'penjualanproduk_id' => $transaksi_id, // Ganti cetakpdf->id dengan transaksi_id
                'produk_id' => $data_pesanan['produk_id'],
                'kode_produk' => $data_pesanan['kode_produk'],
                'kode_lama' => $data_pesanan['kode_lama'],
                'nama_produk' => $data_pesanan['nama_produk'],
                'jumlah' => $data_pesanan['jumlah'],
                'diskon' => $data_pesanan['diskon'],
                'harga' => $data_pesanan['harga'],
                'total' => $data_pesanan['total'],
                'totalasli' => $data_pesanan['totalasli'],
                'nominal_diskon' => $data_pesanan['nominal_diskon'], // Menyimpan nominal_diskon
            ]);
    
            // Periksa apakah penyimpanan berhasil
            if (!$detailPenjualan) {
                // Jika gagal, simpan log atau ambil error
                Log::error('Gagal menyimpan detail penjualan produk', $data_pesanan);
            }
    
            // Kurangi stok di tabel stok_tokobanjaran
            $stok = Stok_tokobanjaran::where('produk_id', $data_pesanan['produk_id'])->first();
            if ($stok) {
                // Jika jumlah stok 0, maka kurangi dengan nilai jumlah dari inputan dan buat stok jadi minus
                if ($stok->jumlah == 0) {
                    $stok->jumlah = -$data_pesanan['jumlah'];
                } else {
                    $stok->jumlah -= $data_pesanan['jumlah'];
                }
                $stok->save();
            }
        }
    
        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = Detailpenjualanproduk::where('penjualanproduk_id', $transaksi_id)->get();
    
        // Kirimkan URL untuk tab baru
        $pdfUrl = route('toko_banjaran.penjualan_produk.cetak-pdf', ['id' => $transaksi_id]);
    
        // Return response dengan URL PDF
        return response()->json([
            'success' => 'Transaksi Berhasil',
            'pdfUrl' => $pdfUrl,
        ]);
    }
    
    public function pelunasan()
    {
        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
        $tokobanjarans = Tokobanjaran::all();
        $tokos = Toko::all();
        $metodes = Metodepembayaran::all();
        $dppemesanans = Dppemesanan::all();
        $pemesananproduks = Pemesananproduk::all();
        $produks = Produk::with('tokobanjaran')->get();
        $kategoriPelanggan = 'member';
 
        return view('toko_banjaran.penjualan_produk.pelunasan', compact('barangs','metodes', 'tokos', 'produks', 'details', 'tokoslawis', 'tokobanjarans', 'pelanggans', 'kategoriPelanggan', 'dppemesanans', 'pemesananproduks'));
    }

    public function fetchProductData(Request $request)
    {
        $kodeLama = $request->input('kode_lama');
        $namaProduk = $request->input('nama_produk');
    
        // Cari produk berdasarkan kode_lama jika diisi, jika tidak gunakan nama_produk
        if ($kodeLama) {
            $produk = Produk::where('kode_lama', $kodeLama)->first();
        } elseif ($namaProduk) {
            $produk = Produk::where('nama_produk', 'LIKE', "%{$namaProduk}%")->first();
        } else {
            return response()->json(null, 404);
        }
    
        if ($produk) {
            return response()->json([
                'produk_id'   => $produk->id,
                'kode_produk' => $produk->kode_produk,
                'nama_produk' => $produk->nama_produk,
                'kode_lama'   => $produk->kode_lama,  // Pastikan kode_lama dikirim kembali
                'harga'       => $produk->harga,
            ]);
        } else {
            return response()->json(null, 404);
        }
    }
    
    public function cariProduk(Request $request)
    {
        $query = $request->query('query');
        $produks = Produk::where('nama_produk', 'LIKE', '%' . $query . '%')
                        ->orWhere('kode_lama', 'LIKE', '%' . $query . '%')
                        ->orWhere('qrcode_produk', 'LIKE', '%' . $query . '%')
                        ->with('tokobanjaran', 'stok_tokobanjaran')
                        ->get();
    
        return response()->json(['produks' => $produks]);
    }
    
    
    public function SimpanPelunasan(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'dppemesanan_id' => 'required|string',
            'pelunasan' => 'required|numeric',
            'metode_id' => 'nullable|integer',
            'total_fee' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'kode_produk' => 'nullable|array',
            'kode_produk.*' => 'nullable|string',
            'kode_lama.*' => 'nullable|string',
            'nama_produk' => 'nullable|array',
            'nama_produk.*' => 'nullable|string',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'nullable|integer',
            'harga' => 'nullable|array', 
            'harga.*' => 'nullable|numeric', 
            'total' => 'nullable|array',
            'total.*' => 'nullable|numeric',
            'diskon' => 'nullable|array',
            'diskon.*' => 'nullable|numeric',
            'produk_id' => 'nullable|array',
            'produk_id.*' => 'nullable|numeric',
            'kembali' => 'nullable|numeric',
        ]);
    
        // Jika nilai pelunasan kosong atau 0, set default ke 1
        $validated['pelunasan'] = $validated['pelunasan'] > 0 ? $validated['pelunasan'] : 1;
    
        // Update kolom pelunasan di tabel dppemesanans
        $dppemesanans = Dppemesanan::find($validated['dppemesanan_id']);
        if (!$dppemesanans) {
            return redirect()->back()->withErrors(['error' => 'Data pesanan tidak ditemukan']);
        }
    
        // Update pelunasan di tabel dppemesanans
        $dppemesanans->pelunasan += $validated['pelunasan'];
        $dppemesanans->save();
        
        // Generate kode untuk penjualan
        $kode_penjualan = $this->kode();
    
        // Simpan data ke tabel penjualan_produk
        $penjualan = new PenjualanProduk();
        $penjualan->dppemesanan_id = $validated['dppemesanan_id'];
        $penjualan->nama_pelanggan = $dppemesanans->pemesananproduk->nama_pelanggan;
        $penjualan->kode_pelanggan = $dppemesanans->pemesananproduk->kode_pelanggan;
        $penjualan->telp = $dppemesanans->pemesananproduk->telp;
        $penjualan->alamat = $dppemesanans->pemesananproduk->alamat;
        $penjualan->sub_total = $dppemesanans->pemesananproduk->sub_total;
        $penjualan->sub_totalasli = $dppemesanans->pemesananproduk->sub_totalasli;
        $penjualan->nominal_diskon = $dppemesanans->pemesananproduk->nominal_diskon;
        $penjualan->kasir = ucfirst(auth()->user()->karyawan->nama_lengkap);
        $penjualan->total_fee = $validated['total_fee'];
        $penjualan->keterangan = $validated['keterangan'];
        $penjualan->metode_id = $validated['metode_id'];
        $penjualan->kembali = $validated['kembali'];
        $penjualan->bayar = $validated['pelunasan'];
        $penjualan->status = 'posting';
        $penjualan->toko_id = 1;
        $penjualan->kode_penjualan = $kode_penjualan;
        $penjualan->tanggal_penjualan = Carbon::now('Asia/Jakarta');
        $penjualan->qrcode_penjualan = 'https://javabakery.id/penjualan/' . $kode_penjualan;
        $penjualan->save();
    
        // Simpan data ke tabel pelunasan
        $pelunasan = new Pelunasan();
        $pelunasan->dppemesanan_id = $validated['dppemesanan_id'];
        $pelunasan->penjualanproduk_id = $penjualan->id;
        $pelunasan->pelunasan = $validated['pelunasan'];
        $pelunasan->metode_id = $validated['metode_id'];
        $pelunasan->total_fee = $validated['total_fee'];
        $pelunasan->keterangan = $validated['keterangan'];
        $pelunasan->kembali = $validated['kembali'];
        $pelunasan->tanggal_pelunasan = Carbon::now('Asia/Jakarta');
        $pelunasan->kasir = ucfirst(auth()->user()->karyawan->nama_lengkap);
        $pelunasan->status = 'posting';
        $pelunasan->toko_id = '1'; 
        $pelunasan->kode_penjualan = $penjualan->kode_penjualan; // Menggunakan kode_penjualan dari penjualan
        $pelunasan->save();
    
        // Simpan data ke tabel detailpenjualanproduk dan kurangi stok
        foreach ($validated['kode_produk'] as $index => $kode_produk) {
            $detail = new DetailPenjualanProduk();
            $detail->penjualanproduk_id = $penjualan->id;
            $detail->kode_produk = $kode_produk;
            $detail->kode_lama = $validated['kode_lama'][$index];
            $detail->produk_id = $validated['produk_id'][$index];
            $detail->nama_produk = $validated['nama_produk'][$index];
            $detail->jumlah = $validated['jumlah'][$index];
            $detail->harga = $validated['harga'][$index];
            $detail->diskon = $validated['diskon'][$index];
            $detail->total = $validated['total'][$index];
            $detail->save();
    
            // Ambil klasifikasi produk
            $produk = Produk::find($detail->produk_id);
    

            // Kurangi stok berdasarkan klasifikasi_id atau kode_lama
        if ($produk) {
            if (in_array($produk->klasifikasi_id, [15, 16]) || 
                ($produk->klasifikasi_id == 13 && in_array($produk->kode_lama, ['KU001', 'M0002']))
            ) {
                // Pengurangan stok untuk stok_tokobanjaran
                $stok = Stok_tokobanjaran::where('produk_id', $detail->produk_id)->first();
            } else {
                // Jika tidak, kurangi stok dari stokpesanan_tokobanjaran
                $stok = Stokpesanan_tokobanjaran::where('produk_id', $detail->produk_id)->first();
            }

            if ($stok) {
                // Kurangi stok tanpa memeriksa apakah stok mencukupi
                $stok->jumlah -= $detail->jumlah;
                $stok->save();
            } else {
                // Jika stok tidak ditemukan, buat stok baru dengan nilai negatif
                if (in_array($produk->klasifikasi_id, [15, 16]) || 
                    ($produk->klasifikasi_id == 13 && in_array($detail->kode_lama, ['KU001', 'M0002']))
                ) {
                    Stok_tokobanjaran::create([
                        'produk_id' => $detail->produk_id,
                        'jumlah' => -$detail->jumlah,
                    ]);
                } else {
                    Stokpesanan_tokobanjaran::create([
                        'produk_id' => $detail->produk_id,
                        'jumlah' => -$detail->jumlah,
                    ]);
                }
            }
        }

        }
    
        // Ambil detail pelunasan untuk ditampilkan di halaman cetak
        $details = DetailPenjualanProduk::where('penjualanproduk_id', $penjualan->id)->get();
    
        // Kirimkan URL untuk tab baru
        $pdfUrl = route('toko_banjaran.pelunasan_pemesanan.cetak-pdf', ['id' => $pelunasan->id]);
    
        // Return response dengan URL PDF
        return response()->json([
            'success' => 'Transaksi Berhasil',
            'pelunasan' => $pelunasan,
            'penjualan' => $penjualan,
            'details' => $details,
            'pdfUrl' => $pdfUrl,
        ]);
    }
    

    public function getCustomerByKode($kode)
    {
        $customer = Pelanggan::where('kode_pelanggan', $kode)->first();
        if ($customer) {
            return response()->json($customer);
        }
        return response()->json(['message' => 'Customer not found'], 404);
    }

    public function getCustomerData(Request $request)
    {
        $qrcode_pelanggan = $request->qrcode_pelanggan;

        // Query untuk mengambil data pelanggan berdasarkan qrcode_pelanggan
        $customer = Pelanggan::where('qrcode_pelanggan', $qrcode_pelanggan)->first();

        if ($customer) {
            // Jika data ditemukan, kembalikan data dalam bentuk JSON
            return response()->json([
                'nama_pelanggan' => $customer->nama_pelanggan,
                'telp' => $customer->telp,
                'alamat' => $customer->alamat,
            ]);
        } else {
            // Jika data tidak ditemukan, kembalikan respons kosong atau sesuaikan dengan kebutuhan
            return response()->json([
                'error' => 'Data pelanggan tidak ditemukan.',
            ], 404);
        }
    }
   
    //function utk menampilkan data yang diambil
    public function fetchDataByKode(Request $request)
    {
        $kode = $request->input('kode_pemesanan');
        $data = Dppemesanan::whereHas('pemesananproduk', function($query) use ($kode) {
            $query->where('kode_pemesanan', $kode);
        })->with('pemesananproduk', 'detailpemesananproduk')->first();

        if ($data) {
            return response()->json([
                'id' => $data->id,
                'kode_pemesanan' => $data->pemesananproduk->kode_pemesanan ?? '',
                'dp_pemesanan' => $data->dp_pemesanan,
                'nama_pelanggan' => $data->pemesananproduk->nama_pelanggan ?? '',
                'telp' => $data->pemesananproduk->telp ?? '',
                'alamat' => $data->pemesananproduk->alamat ?? '',
                'tanggal_kirim' => $data->pemesananproduk->tanggal_kirim ?? '',
                'nama_penerima' => $data->pemesananproduk->nama_penerima ?? '',
                'telp_penerima' => $data->pemesananproduk->telp_penerima ?? '',
                'alamat_penerima' => $data->pemesananproduk->alamat_penerima ?? '',
                'sub_total' => $data->pemesananproduk->sub_total ?? 0,
                'sub_totalasli' => $data->pemesananproduk->sub_totalasli ?? 0,
                'nominal_diskon' => $data->pemesananproduk->nominal_diskon ?? 0,
                'dp_pemesanan' => $data->dp_pemesanan,
                'kekurangan_pemesanan' => $data->kekurangan_pemesanan,
                'products' => $data->detailpemesananproduk->map(function ($item) {
                    return [
                        'produk_id' => $item->produk_id,
                        'kode_produk' => $item->kode_produk,
                        'kode_lama' => $item->kode_lama,
                        'harga' => $item->harga,
                        'diskon' => $item->diskon,
                        'nama_produk' => $item->nama_produk,
                        'jumlah' => $item->jumlah,
                        'total' => $item->total,
                    ];
                })
            ]);
        } else {
            return response()->json([], 404);
        }
    }
 
   
    public function kode()
    {
        $prefix = 'FPC';
        $year = date('y'); // Dua digit terakhir dari tahun
        $monthDay = date('dm'); // Format bulan dan hari: MMDD

        // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix PBNJ
        $lastBarang = Penjualanproduk::where('kode_penjualan', 'LIKE', $prefix . '%')
                                    ->whereDate('tanggal_penjualan', Carbon::today())
                                    ->orderBy('kode_penjualan', 'desc')
                                    ->first();

        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_penjualan;
            $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }

        $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
        $newCode = $prefix . $monthDay . $year . $formattedNum;
        return $newCode;
    }

    
    public function cetak($id)
    {
        // Retrieve the specific pemesanan by ID along with its details
        $penjualan = Penjualanproduk::with('detailpenjualanproduk', 'toko')->findOrFail($id);
    
        // Retrieve all pelanggans (assuming you need this for the view)
        $pelanggans = Pelanggan::all();
        $tokos = $penjualan->toko;

        // Pass the retrieved data to the view
        return view('toko_banjaran.penjualan_produk.cetak', compact('penjualan', 'pelanggans', 'tokos'));
    }

    public function cetakpelunasan($id)
    {
        // Retrieve the specific pemesanan by ID along with its details
        $penjualan = Pelunasan::with('dppemesanan', 'toko')->findOrFail($id);
    
        // Retrieve all pelanggans (assuming you need this for the view)
        $pelanggans = Pelanggan::all();
        $tokos = $penjualan->toko;

        // Pass the retrieved data to the view
        return view('toko_banjaran.penjualan_produk.cetakpelunasan', compact('penjualan', 'pelanggans', 'tokos'));
    }
    
    public function cetakPdf($id)
    {
        $penjualan = Penjualanproduk::with('toko', 'metodepembayaran')->findOrFail($id); // Eager load relasi
        $pelanggans = Pelanggan::all();
        $tokos = $penjualan->toko;
    
        $pdf = FacadePdf::loadView('toko_banjaran.penjualan_produk.cetak-pdf', compact('penjualan', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('penjualan.pdf');
    }
    
    public function show($id)
    {   
      // Retrieve the specific pemesanan by ID along with its details
      $penjualan = Penjualanproduk::with('detailpenjualanproduk', 'toko')->findOrFail($id);
    
      // Retrieve all pelanggans (assuming you need this for the view)
      $pelanggans = Pelanggan::all();
      $tokos = $penjualan->toko;

      // Pass the retrieved data to the view
      return view('toko_banjaran.penjualan_produk.cetak', compact('penjualan', 'pelanggans', 'tokos'));
    }
    

        public function edit($id)
        {
            $pelanggans = Pelanggan::all();
            $tokoslawis = Tokoslawi::all();
            $tokos = Toko::all();
        
            $produks = Produk::with('tokoslawi')->get();
            $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
            $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

            return view('toko_banjaran.pemesanan_produk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks' ,'selectedTokoId'));
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
                // 'kode_pemesanan' => $request->kode_pemesanan,
                // 'qrcode_pemesanan' => 'https://javabakery.id/pemesanan/' . $this->kode(),
                // 'tanggal_pemesanan' => Carbon::now('Asia/Jakarta'),
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
            return redirect('toko_banjaran/pemesanan_produk');

        }
        
        public function getProductsByToko($tokoId)
        {
            $products = Produk::with(['tokoslawi', 'tokobenjaran'])->whereHas('tokoslawi', function($query) use ($tokoId) {
                $query->where('id', $tokoId);
            })->orWhereHas('tokobenjaran', function($query) use ($tokoId) {
                $query->where('id', $tokoId);
            })->get();
    
            return response()->json($products);
        }

        public function destroy($id)
        {
            DB::transaction(function () use ($id) {
                $penjualan = Penjualanproduk::findOrFail($id);
                
                // Ambil semua detail pemesanan terkait
                $detailPenjualanProduks = Detailpenjualanproduk::where('penjualanproduk_id', $id)->get();

                // Mengembalikan stok untuk setiap produk yang dipesan
                foreach ($detailPenjualanProduks as $detail) {
                    DB::table('stok_tokobanjarans')
                        ->where('produk_id', $detail->produk_id)
                        ->increment('jumlah', $detail->jumlah);
                }

                // Menghapus (soft delete) detail pemesanan terkait
                Detailpenjualanproduk::where('penjualanproduk_id', $id)->delete();

                // Menghapus (soft delete) data pemesanan
                $penjualan->delete();
            });

            return redirect('toko_banjaran/penjualan_produk')->with('success', 'Berhasil menghapus data penjualan');
        }

        public function getProduk(Request $request)
        {
            $search = $request->input('search', '');

            // Ambil data berdasarkan pencarian
            $produks = Produk::with('tokobanjaran') // Gunakan eager loading jika perlu
                ->where('nama_produk', 'LIKE', '%' . $search . '%')
                ->orWhere('kode_lama', 'LIKE', '%' . $search . '%')
                ->get();

            return response()->json($produks);
        }

        public function searchProduct(Request $request)
        {
            $query = $request->input('query');
            
            // Cari produk berdasarkan query
            $products = Produk::where('nama_produk', 'LIKE', "%$query%")
                                ->orWhere('kode_produk', 'LIKE', "%$query%")
                                ->get();
            
            // Kembalikan hasil dalam format JSON
            return response()->json($products);
        }

       

       
}