<?php

namespace App\Http\Controllers\Toko_slawi;

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
use App\Models\Detailpenjualanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Metodepembayaran;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Stok_tokoslawi;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class Inquery_penjualanprodukslawiController extends Controller
{

   
    // public function index(Request $request)
    // {
    //     $status = $request->status;
    //     $tanggal_penjualan = $request->tanggal_penjualan;
    //     $tanggal_akhir = $request->tanggal_akhir;
    
    //     $inquery = Penjualanproduk::query() ->where('toko_id', 3);;
    
    //     if ($status) {
    //         $inquery->where('status', $status);
    //     }
    
    //     if ($tanggal_penjualan && $tanggal_akhir) {
    //         $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $inquery->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    //     } elseif ($tanggal_penjualan) {
    //         $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //         $inquery->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    //     } elseif ($tanggal_akhir) {
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $inquery->where('tanggal_penjualan', '<=', $tanggal_akhir);
    //     } else {
    //         // Jika tidak ada filter tanggal, filter berdasarkan hari ini
    //         $inquery->whereDate('tanggal_penjualan', Carbon::today());
    //     }
    
    //     $inquery->orderBy('id', 'DESC');
    //     $inquery = $inquery->get();
    
    //     return view('toko_slawi.inquery_penjualanproduk.index', compact('inquery'));
    // }

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $metode_bayar = $request->metode_bayar; // Ganti dari metode_bayar ke metode_bayar
    
        $metodes = MetodePembayaran::all(); // Ambil semua metode pembayaran
    
        $inquery = Penjualanproduk::with('metodePembayaran')->where('toko_id', 3);
    
        if ($status) {
            $inquery->where('status', $status);
        }
    
        if ($tanggal_penjualan && $tanggal_akhir) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $inquery->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->where('tanggal_penjualan', '<=', $tanggal_akhir);
        } else {
            $inquery->whereDate('tanggal_penjualan', Carbon::today());
        }
    
        if ($metode_bayar) {
            $inquery->where('metode_id', $metode_bayar); // Pastikan kolom ini sesuai dengan database
        }
    
        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();
    
        return view('toko_slawi.inquery_penjualanproduk.index', compact('inquery', 'metodes'));
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

 

    public function show($id)
    {   
      // Retrieve the specific pemesanan by ID along with its details
      $penjualan = Penjualanproduk::with('detailpenjualanproduk', 'toko')->findOrFail($id);
    
      // Retrieve all pelanggans (assuming you need this for the view)
      $pelanggans = Pelanggan::all();
      $tokos = $penjualan->toko;

      // Pass the retrieved data to the view
      return view('toko_slawi.inquery_penjualanproduk.show', compact('penjualan', 'pelanggans', 'tokos'));
    }

    public function cetakPdf($id)
    {
        $penjualan = Penjualanproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();
        
    
        $tokos = $penjualan->toko;
    
        $pdf = FacadePdf::loadView('toko_slawi.inquery_penjualanproduk.cetak-pdf', compact('penjualan', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('penjualan.pdf');
    }

    public function unpost_penjualanproduk($id)
    {
        $item = Penjualanproduk::where('id', $id)->first();
    
        if ($item) {
            $detailPenjualanProduk = Detailpenjualanproduk::where('penjualanproduk_id', $item->id)->get();
    
            foreach ($detailPenjualanProduk as $detail) {
                // Update stok berdasarkan jumlah produk yang dijual
                $stok = Stok_tokoslawi::where('produk_id', $detail->produk_id)->first();
    
                if ($stok) {
                    $stok->update([
                        'jumlah' => $stok->jumlah + $detail->jumlah
                    ]);
                }
    
                // Update status dari detail penjualan produk menjadi 'unpost'
                $detail->update([
                    'status' => 'unpost' // Pastikan kolom status ada dalam tabel detailpenjualanproduk
                ]);
            }
    
            // Update status dari penjualan produk menjadi 'unpost'
            $item->update([
                'status' => 'unpost'
            ]);
    
            return back()->with('success', 'Berhasil unpost, mengembalikan stok, dan mengubah status detail penjualan produk.');
        }
    
        return back()->with('error', 'Gagal, data tidak ditemukan.');
    }

    public function edit($id)
    {
        $produks = Produk::with(['tokoslawi', 'stok_tokoslawi'])->get();
        $metodes = Metodepembayaran::all();

        $penjualan = PenjualanProduk::with('detailPenjualanProduk')->findOrFail($id);
        
        return view('toko_slawi.inquery_penjualanproduk.update', compact('penjualan','produks','metodes'));
    }
   

        public function update(Request $request, $id)
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
        
                    $nominal_diskon = ($harga * ($diskon / 100)) * $jumlah;
        
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
                    ]);
                }
            }
        
            // Cari data penjualan yang ada berdasarkan ID
            $penjualanproduk = Penjualanproduk::find($id);
        
            if (!$penjualanproduk) {
                return redirect()->back()->with('error', 'Data penjualan tidak ditemukan.');
            }
        
            // Update data penjualan
            $penjualanproduk->update([
                'nama_pelanggan' => $request->nama_pelanggan ?? null,
                'kode_pelanggan' => $request->kode_pelanggan ?? null,
                'kode_lama' => $request->kode_lama1 ?? null,
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
                'toko_id' => $request->toko_id,
                'kasir' => $request->kasir,
                'tanggal_penjualan' => $request->tanggal_penjualan ?? null,
                'status' => 'posting',
                'nominal_diskon' => $nominal_diskon, // Simpan total nominal diskon
            ]);
        
            // Hapus detail penjualan lama
            Detailpenjualanproduk::where('penjualanproduk_id', $penjualanproduk->id)->delete();
        
            // Simpan detail pemesanan baru dan kurangi stok
            foreach ($data_pembelians as $data_pesanan) {
                Detailpenjualanproduk::create([
                    'penjualanproduk_id' => $penjualanproduk->id,
                    'produk_id' => $data_pesanan['produk_id'],
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'kode_lama' => $data_pesanan['kode_lama'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                    'totalasli' => $data_pesanan['totalasli'],
                ]);
        
                // Kurangi stok di tabel stok_tokobanjaran
                $stok = Stok_tokoslawi::where('produk_id', $data_pesanan['produk_id'])->first();
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
        
            return redirect()->route('inquery_penjualanprodukslawi.index')->with('success', 'Data penjualan berhasil diperbarui.');
        }


        public function destroy($id)
        {
            try {
                // Temukan data penjualanproduk berdasarkan ID
                $penjualanproduk = Penjualanproduk::findOrFail($id);
        
                // Hapus detail penjualanproduk yang terkait dengan penjualanproduk_id
                Detailpenjualanproduk::where('penjualanproduk_id', $id)->delete();
        
                // Hapus data penjualanproduk
                $penjualanproduk->delete();
        
                return redirect()->route('toko_slawi.inquery_penjualanproduk.index')
                                 ->with('success', 'Data penjualan dan detailnya berhasil dihapus.');
            } catch (\Exception $e) {
                return redirect()->route('toko_slawi.inquery_penjualanproduk.index')
                                 ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            }
        }

        public function metode($id)
        {
            $metode = Metodepembayaran::where('id', $id)->first();
    
            return json_decode($metode);
        }
        
    
}