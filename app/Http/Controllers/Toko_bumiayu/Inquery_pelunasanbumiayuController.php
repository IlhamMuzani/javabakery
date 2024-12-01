<?php

namespace App\Http\Controllers\Toko_bumiayu;

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
use App\Models\Pelunasan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class Inquery_pelunasanbumiayuController extends Controller
{

   
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pelunasan = $request->tanggal_pelunasan;
        $tanggal_akhir = $request->tanggal_akhir;
    
        // Modify the query to include relationships
        $inquery = Pelunasan::with(['metodePembayaran', 'dppemesanan.pemesananproduk'])
            ->whereHas('dppemesanan.pemesananproduk', function($query) {
                $query->where('toko_id', 5);
            });
    
        // Filter by status if provided
        if ($status) {
            $inquery->where('status', $status);
        }
    
        // Handle date filtering for pelunasan
        if ($tanggal_pelunasan && $tanggal_akhir) {
            $tanggal_pelunasan = Carbon::parse($tanggal_pelunasan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereBetween('tanggal_pelunasan', [$tanggal_pelunasan, $tanggal_akhir]);
        } elseif ($tanggal_pelunasan) {
            $tanggal_pelunasan = Carbon::parse($tanggal_pelunasan)->startOfDay();
            $inquery->where('tanggal_pelunasan', '>=', $tanggal_pelunasan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->where('tanggal_pelunasan', '<=', $tanggal_akhir);
        } else {
            // Default to today's pelunasan if no date is provided
            $inquery->whereDate('tanggal_pelunasan', Carbon::today());
        }
    
        // Order by id in descending order
        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();
    
        return view('toko_bumiayu.inquery_pelunasanbumiayu.index', compact('inquery'));
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

        
            // Update status deposit_driver menjadi 'posting'
            $item->update([
                'status' => 'unpost'
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
      return view('toko_bumiayu.inquery_pelunasanbumiayu.show', compact('penjualan', 'pelanggans', 'tokos'));
    }

    public function cetakPdf($id)
    {
        $penjualan = Penjualanproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();
        
    
        $tokos = $penjualan->toko;
    
        $pdf = FacadePdf::loadView('toko_bumiayu.inquery_pelunasanbumiayu.cetak-pdf', compact('penjualan', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('penjualan.pdf');
    }

    public function edit($id)
        {
            $pelanggans = Pelanggan::all();
            $tokoslawis = Tokoslawi::all();
            $tokos = Toko::all();
        
            $produks = Produk::with('tokoslawi')->get();
            $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
            $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

            return view('toko_bumiayu.inquery_pelunasanbumiayu.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks' ,'selectedTokoId'));
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
            return redirect('toko_bumiayu/inquery_pelunasanbumiayu');

        }
        

    public function destroy($id)
    {
        //
    }

}