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
use App\Models\Detailpenjualanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Metodepembayaran;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokoslawi;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class Inquery_penjualanprodukController extends Controller
{

    public function index(Request $request)

{
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;

    $inquery = Penjualanproduk::query();

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
        // Jika tidak ada filter tanggal hari ini
        $inquery->whereDate('tanggal_penjualan', Carbon::today());
    }

    $inquery->orderBy('id', 'DESC');
    $inquery = $inquery->get();

    return view('admin.inquery_penjualanproduk.index', compact('inquery'));
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


public function unpost_penjualanproduk($id)
{
    $item = Penjualanproduk::where('id', $id)->first();

    if ($item) {
        $detailPenjualanProduk = DetailPenjualanproduk::where('penjualanproduk_id', $item->id)->get();

        foreach ($detailPenjualanProduk as $detail) {
            // Update stok berdasarkan jumlah produk yang dijual
            $stok = Stok_tokobanjaran::where('produk_id', $detail->produk_id)->first();

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
        $produks = Produk::with(['tokobanjaran', 'stok_tokobanjaran'])->get();
        $metodes = Metodepembayaran::all();

        $penjualan = PenjualanProduk::with('detailPenjualanProduk')->findOrFail($id);
        
        return view('admin.inquery_penjualanproduk.update', compact('penjualan','produks','metodes'));
    }

//  public function edit($id)
// {
//     $produks = Produk::with(['tokobanjaran', 'stok_tokobanjaran'])->get();
//     $metodes = Metodepembayaran::all();
//     // Ambil data penjualan
//     $penjualanproduk = Penjualanproduk::findOrFail($id);

//     // Ambil data detail produk yang terkait dengan penjualan
//     $detailProduk = Detailpenjualanproduk::where('penjualanproduk_id', $penjualanproduk->id)->get();

//     // Kirim data ke view
//     return view('admin.inquery_penjualanproduk.update', compact('penjualanproduk', 'detailProduk', 'produks', 'metodes'));
// }

    
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
            'kasir' => ucfirst(auth()->user()->karyawan->nama_lengkap),
            'tanggal_penjualan' => Carbon::now('Asia/Jakarta'),
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
    
        return redirect()->route('inquery_penjualanproduk.index')->with('success', 'Data penjualan berhasil diperbarui.');
    }
    


    public function hapusProduk(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:detailpenjualanproduk,id', // Validasi id
        ]);
    
        $detail = DetailPenjualanProduk::find($request->id); // Cari berdasarkan id
        if ($detail) {
            $detail->delete(); // Hapus data
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
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
    
        
    
    

    public function destroy($id)
    {
        //
    }

}