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
use App\Models\Tokobanjaran;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class Inquery_perubahanhargaController extends Controller
{

     public function index(Request $request)
     {
        $toko = request()->input('toko', 'tokoslawi'); // Ambil input toko dari request, default ke 'tokoslawi'
        $today = Carbon::today(); // Tanggal hari ini
        
        $produk = Produk::with(['tokoslawi', 'tokobenjaran' , 'tokotegal','tokopemalang', 'tokobumiayu', 'tokocilacap'])
            ->where(function ($query) use ($today) {
                $query->whereHas('tokoslawi', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokoslawis.member_harga_slw != tokoslawis.harga_awal')
                                    ->orWhereRaw('tokoslawis.non_harga_slw != tokoslawis.harga_awal')
                                    ->orWhereRaw('tokoslawis.member_diskon_slw != 0')
                                    ->orWhereRaw('tokoslawis.non_diskon_slw != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokobanjaran', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokobanjarans.member_harga_bnjr != tokobanjarans.harga_awal')
                                    ->orWhereRaw('tokobanjarans.non_harga_bnjr != tokobanjarans.harga_awal')
                                    ->orWhereRaw('tokobanjarans.member_diskon_bnjr != 0')
                                    ->orWhereRaw('tokobanjarans.non_diskon_bnjr != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokotegal', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokotegals.member_harga_tgl != tokotegals.harga_awal')
                                    ->orWhereRaw('tokotegals.non_harga_tgl != tokotegals.harga_awal')
                                    ->orWhereRaw('tokotegals.member_diskon_tgl != 0')
                                    ->orWhereRaw('tokotegals.non_diskon_tgl != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokopemalang', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokopemalangs.member_harga_pml != tokopemalangs.harga_awal')
                                    ->orWhereRaw('tokopemalangs.non_harga_pml != tokopemalangs.harga_awal')
                                    ->orWhereRaw('tokopemalangs.member_diskon_pml != 0')
                                    ->orWhereRaw('tokopemalangs.non_diskon_pml != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokobumiayu', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokobumiayus.member_harga_bmy != tokobumiayus.harga_awal')
                                    ->orWhereRaw('tokobumiayus.non_harga_bmy != tokobumiayus.harga_awal')
                                    ->orWhereRaw('tokobumiayus.member_diskon_bmy != 0')
                                    ->orWhereRaw('tokobumiayus.non_diskon_bmy != 0');
                          });
                });
            })->orWhere(function ($query) use ($today) {
                $query->whereHas('tokocilacap', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokocilacaps.member_harga_clc != tokocilacaps.harga_awal')
                                    ->orWhereRaw('tokocilacaps.non_harga_clc != tokocilacaps.harga_awal')
                                    ->orWhereRaw('tokocilacaps.member_diskon_clc != 0')
                                    ->orWhereRaw('tokocilacaps.non_diskon_clc != 0');
                          });
                });
            })
            ->get();
                
                    // Cek apakah ada data yang diperbarui hari ini
                    if ($produk->isEmpty()) {
                        // Jika tidak ada, redirect kembali dengan pesan
                        return redirect()->back()->with('info', 'Tidak ada data yang diperbarui hari ini.');
                    }
                    
                    return view('admin.inquery_perubahanharga.index', compact('produk'));
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
        }

    public function edit($id)
        {
            $pelanggans = Pelanggan::all();
            $tokoslawis = Tokoslawi::all();
            $tokos = Toko::all();
        
            $produks = Produk::with('tokoslawi')->get();
            $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
            $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

            return view('admin.inquery_pemesananproduk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks' ,'selectedTokoId'));
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
            return redirect('admin/inquery_pemesananproduk');

        }
        

    public function destroy($id)
    {
        //
    }

}