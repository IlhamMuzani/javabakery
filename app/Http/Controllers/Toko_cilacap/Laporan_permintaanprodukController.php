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
use App\Models\Permintaanproduk;
use App\Models\Detailpermintaanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Laporan_permintaanprodukController extends Controller
{

public function index(Request $request)
{
    $status = $request->status;
    $tanggal_permintaan = $request->tanggal_permintaan;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    $query = PermintaanProduk::with(['detailpermintaanproduks.produk.klasifikasi', 'detailpermintaanproduks.toko']);

    // Filter berdasarkan status
    if ($status) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($status) {
            $query->where('status', $status);
        });
    }

    // Filter berdasarkan tanggal permintaan
    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $query->orderBy('id', 'DESC');

    $inquery = $query->get();

    $produks = Produk::all();
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();

    return view('toko_cilacap.laporan_permintaanproduk.index', compact('inquery', 'produks', 'tokos', 'klasifikasis', 'klasifikasi_id'));
}

public function indexrinci(Request $request)
{
    $status = $request->status;
    $tanggal_permintaan = $request->tanggal_permintaan;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    $query = PermintaanProduk::with(['detailpermintaanproduks.produk.klasifikasi', 'detailpermintaanproduks.toko']);

    // Filter berdasarkan status
    if ($status) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($status) {
            $query->where('status', $status);
        });
    }

    // Filter berdasarkan tanggal permintaan
    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $query->orderBy('id', 'DESC');

    $inquery = $query->get();

    $produks = Produk::all();
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();

    return view('toko_cilacap.laporan_permintaanproduk.indexrinci', compact('inquery', 'produks', 'tokos', 'klasifikasis', 'klasifikasi_id'));
}

public function printReport(Request $request)
{
    $klasifikasi_id = $request->get('klasifikasi_id');
    $toko_id = $request->get('toko_id');
    $tanggal_permintaan = $request->get('tanggal_permintaan');
    $tanggal_akhir = $request->get('tanggal_akhir');

    $query = PermintaanProduk::with(['detailpermintaanproduks.produk.klasifikasi.subklasifikasi', 'detailpermintaanproduks.toko']);

    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $permintaanProduk = $query->get();
    $tokoData = Toko::all();

    $filteredKlasifikasi = null;
    if ($klasifikasi_id) {
        $filteredKlasifikasi = Klasifikasi::find($klasifikasi_id);
    }

    $pdf = FacadePdf::loadView('toko_cilacap.laporan_permintaanproduk.print', compact('permintaanProduk', 'tokoData', 'klasifikasi_id', 'tanggal_permintaan', 'tanggal_akhir', 'filteredKlasifikasi'));
    return $pdf->stream('laporan_permintaan_produk.pdf');
}

public function printReportRinci(Request $request)
{
    $klasifikasi_id = $request->get('klasifikasi_id');
    $toko_id = $request->get('toko_id');
    $tanggal_permintaan = $request->get('tanggal_permintaan');
    $tanggal_akhir = $request->get('tanggal_akhir');

    $query = PermintaanProduk::with([
        'detailpermintaanproduks.produk.klasifikasi.subklasifikasi',
        'detailpermintaanproduks.toko'
    ]);

    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $permintaanProduk = $query->get();
    $tokoData = Toko::all();

    // Group products by division and store
    $produkByTokoAndDivisi = [];
    foreach ($permintaanProduk as $permintaan) {
        foreach ($permintaan->detailpermintaanproduks as $detail) {
            $toko = $detail->toko->nama_toko;
            $divisi = $detail->produk->klasifikasi->nama;
            $subklasifikasi = $detail->produk->klasifikasi->subklasifikasi->first()->nama ?? '-';
            $kodeProduk = $detail->produk->kode_produk;
            $namaProduk = $detail->produk->nama_produk;

            if (!isset($produkByTokoAndDivisi[$toko][$divisi])) {
                $produkByTokoAndDivisi[$toko][$divisi] = collect();
            }

            $produkByTokoAndDivisi[$toko][$divisi]->push([
                'kode_produk' => $kodeProduk,
                'nama_produk' => $namaProduk,
                'subklasifikasi' => $subklasifikasi,
                'jumlah' => $detail->jumlah
            ]);
        }
    }

    // Pass 'permintaan' as well to the view
    $pdf = FacadePdf::loadView('toko_cilacap.laporan_permintaanproduk.printrinci', compact('permintaanProduk', 'produkByTokoAndDivisi', 'tokoData', 'klasifikasi_id', 'tanggal_permintaan', 'tanggal_akhir'));
    return $pdf->stream('laporan_permintaan_produk.pdf');
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