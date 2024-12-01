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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class PenjualanprodukController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $inquery = Penjualanproduk::with('metodePembayaran')
            ->whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('admin.penjualan_produk.index', compact('inquery'));
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


    public function create()
    {

        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
        $tokos = Toko::all();
        $dppemesanans = Dppemesanan::all();
        $pemesananproduks = Pemesananproduk::all();
        $metodes = Metodepembayaran::all();
    
        $produks = Produk::with('tokoslawi')->get();

        $kategoriPelanggan = 'member';
    
        return view('admin.penjualan_produk.create', compact('barangs', 'tokos', 'produks', 'details', 'tokoslawis', 'pelanggans', 'kategoriPelanggan','dppemesanans','pemesananproduks','metodes'));
    }
    

    public function pelunasan()
    {
        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
        $tokos = Toko::all();
        $dppemesanans = Dppemesanan::all();
        $pemesananproduks = Pemesananproduk::all();
        $produks = Produk::with('tokoslawi')->get();
        $kategoriPelanggan = 'member';
 
        return view('admin.penjualan_produk.pelunasan', compact('barangs', 'tokos', 'produks', 'details', 'tokoslawis', 'pelanggans', 'kategoriPelanggan', 'dppemesanans', 'pemesananproduks'));
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
                'dp_pemesanan' => $data->dp_pemesanan,
                'kekurangan_pemesanan' => $data->kekurangan_pemesanan,
                'products' => $data->detailpemesananproduk->map(function ($item) {
                    return [
                        'kode_produk' => $item->kode_produk,
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
        $lastPemesanan = Penjualanproduk::latest()->first();
        if (!$lastPemesanan) {
            $num = 1;
        } else {
            $lastCode = $lastPemesanan->kode_penjualan;
            $num = (int) substr($lastCode, 3) + 1; // Mengambil angka setelah prefix 'SPP'
        }
        
        $formattedNum = sprintf("%06s", $num); // Mengformat nomor urut menjadi 6 digit
        $prefix = 'PP';
        $newCode = $prefix . $formattedNum; // Gabungkan prefix dengan nomor urut yang diformat
    
        return $newCode;
    }
    
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
                'metode_id' => 'nullable|exists:metodepembayarans,id', // Validasi metode pembayaran
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
                $nama_produk = $request->input('nama_produk.' . $i, '');
                $jumlah = $request->input('jumlah.' . $i, '');
                $diskon = $request->input('diskon.' . $i, '');
                $harga = $request->input('harga.' . $i, '');
                $total = $request->input('total.' . $i, '');
                $totalasli = $request->input('totalasli.' . $i, '');

                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
                    'jumlah' => $jumlah,
                    'diskon' => $diskon,
                    'harga' => $harga,
                    'total' => $total,
                    'totalasli' => $totalasli,
                ]);
            }
        }

        //Handling errors for pelanggans or pesanans
        // if ($error_pelanggans || $error_pesanans) {
        //     return back()
        //         ->withInput()
        //         ->withErrors([
        //             'pelanggans' => $error_pelanggans,
        //             'pesanans' => $error_pesanans,
        //         ])
        //         ->with('data_pembelians', $data_pembelians);
        // }


        $kode = $this->kode();
        // Buat pemesanan baru
        $cetakpdf = Penjualanproduk::create([
            'nama_pelanggan' => $request->nama_pelanggan ?? null,
            'kode_pelanggan' => $request->kode_pelanggan ?? null,
            'telp' => $request->telp ?? null,
            'alamat' => $request->alamat ?? null,
            'kategori' => $request->kategori,
            'sub_total' => $request->sub_total,
            'sub_totalasli' => $request->sub_totalasli,
            'bayar' => $request->bayar,
            'kembali' => $request->kembali,
            'catatan' => $request->catatan,
            'metode_id' => $request->metode_id, 
            'total_fee' => $request->total_fee, 
            'keterangan' => $request->keterangan, 
            'toko_id' => 1,
            'kasir' => ucfirst(auth()->user()->karyawan->nama_lengkap),
            'kode_penjualan' => $this->kode(),
            'qrcode_penjualan' => 'https://javabakery.id/penjualan/' . $kode,
            'tanggal_penjualan' => Carbon::now('Asia/Jakarta'),
            'status' => 'posting',
        ]);

        // Dapatkan ID transaksi baru
        $transaksi_id = $cetakpdf->id;

        // Simpan detail pemesanan
        foreach ($data_pembelians as $data_pesanan) {
            Detailpenjualanproduk::create([
                'penjualanproduk_id' => $cetakpdf->id,
                'produk_id' => $data_pesanan['produk_id'],
                'kode_produk' => $data_pesanan['kode_produk'],
                'nama_produk' => $data_pesanan['nama_produk'],
                'jumlah' => $data_pesanan['jumlah'],
                'diskon' => $data_pesanan['diskon'],
                'harga' => $data_pesanan['harga'],
                'total' => $data_pesanan['total'],
                'totalasli' => $data_pesanan['totalasli'],
            ]);
        }

        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = Detailpenjualanproduk::where('penjualanproduk_id', $cetakpdf->id)->get();

        // Redirect ke halaman cetak dengan menyertakan data sukses dan detail pemesanan
        return redirect()->route('admin.penjualan_produk.cetak', ['id' => $cetakpdf->id])->with([
            'success' => 'Berhasil menambahkan barang jadi',
            'penjualan' => $cetakpdf,
            'details' => $details,
        ]);
    }

    

    public function cetak($id)
    {
        // Retrieve the specific pemesanan by ID along with its details
        $penjualan = Penjualanproduk::with('detailpenjualanproduk', 'toko')->findOrFail($id);
    
        // Retrieve all pelanggans (assuming you need this for the view)
        $pelanggans = Pelanggan::all();
        $tokos = $penjualan->toko;

        // Pass the retrieved data to the view
        return view('admin.penjualan_produk.cetak', compact('penjualan', 'pelanggans', 'tokos'));
    }
    
    public function cetakPdf($id)
    {
        $penjualan = Penjualanproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();
        
    
        $tokos = $penjualan->toko;
    
        $pdf = FacadePdf::loadView('admin.penjualan_produk.cetak-pdf', compact('penjualan', 'tokos', 'pelanggans'));
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
      return view('admin.penjualan_produk.cetak', compact('penjualan', 'pelanggans', 'tokos'));
    }
    

    public function edit($id)
    {
            $pelanggans = Pelanggan::all();
            $tokoslawis = Tokoslawi::all();
            $tokos = Toko::all();
        
            $produks = Produk::with('tokoslawi')->get();
            $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
            $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

            return view('admin.pemesanan_produk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks' ,'selectedTokoId'));
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
            return redirect('admin/pemesanan_produk');

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
                $pemesanan = Pemesananproduk::findOrFail($id);
        
                // Menghapus (soft delete) detail pemesanan terkait
                DetailPemesananProduk::where('pemesananproduk_id', $id)->delete();
        
                // Menghapus (soft delete) data pemesanan
                $pemesanan->delete();
            });
        
            return redirect('admin/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
    }
        

}