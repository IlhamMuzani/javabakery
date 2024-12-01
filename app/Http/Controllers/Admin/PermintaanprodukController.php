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
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
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
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;




class PermintaanprodukController extends Controller{

    public function index()
{
    $today = \Carbon\Carbon::today();

    $permintaanProduks = PermintaanProduk::with('detailpermintaanproduks')
        ->whereDate('created_at', $today)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.permintaan_produk.index', compact('permintaanProduks'));
}

    public function create()
    {
        $klasifikasis = Klasifikasi::with('produks')->get();
        
        return view('admin.permintaan_produk.create', compact('klasifikasis'));
    }

    public function store(Request $request)
    {
        // Generate a new kode_permintaan
        $kode = $this->kode();

        // Create the main PermintaanProduk entry
        $permintaanProduk = PermintaanProduk::create([
            'kode_permintaan' => $kode,
            'qrcode_permintaan' => 'https://javabakery.id/permintaan_produk/' . $kode,
        ]);

        $produkData = $request->input('produk', []);

        foreach ($produkData as $produkId => $data) {
            $jumlah = $data['jumlah'] ?? null;

            if (!is_null($jumlah) && $jumlah !== '') {
                Detailpermintaanproduk::create([
                    'permintaanproduk_id' => $permintaanProduk->id,
                    'produk_id' => $produkId,
                    'toko_id' => '1', 
                    'jumlah' => $jumlah,
                    'status' => 'posting',
                    'tanggal_permintaan' => Carbon::now('Asia/Jakarta'),
                ]);
            }
        }

        return redirect()->route('permintaan_produk.show', $permintaanProduk->id)->with('success', 'Berhasil menambahkan permintaan produk');
    }

    
    public function kode1()
    {
        $lastBarang = PermintaanProduk::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_permintaan;
            $num = (int) substr($lastCode, strlen('PB')) + 1; // Updated the prefix
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'PB';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function kode()
    {
        $prefix = 'JKp';
        $year = date('y'); // Dua digit terakhir dari tahun
        $monthDay = date('dm'); // Format bulan dan hari: MMDD
    
        // Mengambil kode terakhir yang dibuat pada hari yang sama dengan prefix PBNJ
        $lastBarang = PermintaanProduk::where('kode_permintaan', 'LIKE', $prefix . '%')
                                      ->whereDate('tanggal_permintaan', Carbon::today())
                                      ->orderBy('kode_permintaan', 'desc')
                                      ->first();
    
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_permintaan;
            $lastNum = (int) substr($lastCode, strlen($prefix . $monthDay . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }
    
        $formattedNum = sprintf("%02d", $num); 
        $newCode = $prefix . $monthDay . $year . $formattedNum;
        return $newCode;
    }

public function show($id)
{
    $permintaanProduk = PermintaanProduk::find($id);
    $detailPermintaanProduks = DetailPermintaanProduk::with('toko')->where('permintaanproduk_id', $id)->get();

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Menghitung total jumlah per klasifikasi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data Subklasifikasi berdasarkan Klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    // Mengambil nama toko dari salah satu detail permintaan produk
    $toko = $detailPermintaanProduks->first()->toko;

    return view('admin.permintaan_produk.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi', 'toko'));
}


    public function print($id)
    {
        // $permintaanProduk = PermintaanProduk::where('id', $id)->firstOrFail();
        
        // $detailPermintaanProduks = $permintaanProduk->detailpermintaanproduks;
        $permintaanProduk = PermintaanProduk::find($id);
        $detailPermintaanProduks = DetailPermintaanProduk::where('permintaanproduk_id', $id)->get();
    
        // Mengelompokkan produk berdasarkan divisi
        $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
            return $item->produk->klasifikasi->nama; // Ganti dengan nama divisi jika diperlukan
        });
    
        // Menghitung total jumlah per divisi
        $totalPerDivisi = $produkByDivisi->map(function($produks) {
            return $produks->sum('jumlah');
        });
        $toko = $detailPermintaanProduks->first()->toko;

        $pdf = FacadePdf::loadView('admin.permintaan_produk.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi','toko'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }

    public function unpost(Request $request, $id)
    {
        $permintaan = Detailpermintaanproduk::find($id);
    
        if ($permintaan) {
            $permintaan->status = 'posting';
            $permintaan->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false], 404);
    }
    
    
    public function unpost_permintaanproduk($id)
{
    $item = Permintaanproduk::where('id', $id)->first();

    
        $item->update([
            'status' => 'unpost'
        ]);
    return back()->with('success', 'Berhasil');
}

public function posting_permintaanproduk($id)
{
    $item = Permintaanproduk::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
    return back()->with('success', 'Berhasil');
}

public function edit($id)
{
    $permintaanProduk = PermintaanProduk::findOrFail($id);
    $klasifikasis = Klasifikasi::with('produks')->get();
    $detailPermintaanProduk = $permintaanProduk->detailpermintaanproduks()->get();

    return view('admin.permintaan_produk.update', compact('permintaanProduk', 'klasifikasis', 'detailPermintaanProduk'));
}


public function update(Request $request, $id)
{
    $permintaanProduk = PermintaanProduk::findOrFail($id);

    // Loop through the produk input and update the jumlah accordingly
    foreach ($request->produk as $produkId => $detail) {
        $jumlah = $detail['jumlah'];
        $detailPermintaan = $permintaanProduk->detailPermintaanProduks()->where('produk_id', $produkId)->first();

        if ($detailPermintaan) {
            if ($jumlah == 0) {
                $detailPermintaan->delete();
            } else {
                $detailPermintaan->update(['jumlah' => $jumlah]);
            }
        } else if ($jumlah > 0) {
            $permintaanProduk->detailPermintaanProduks()->create([
                'produk_id' => $produkId,
                'jumlah' => $jumlah,
            ]);
        }
    }

    return redirect()->route('permintaan_produk.index')->with('success', 'Permintaan produk berhasil diperbarui.');
}

public function destroy($id)
{
    $permintaanProduk = PermintaanProduk::findOrFail($id);

    // Hapus detail permintaan produk terkait
    $permintaanProduk->detailPermintaanProduks()->delete();

    // Hapus permintaan produk itu sendiri
    $permintaanProduk->delete();

    return redirect()->route('permintaan_produk.index')->with('success', 'Permintaan produk dan detail terkait berhasil dihapus.');
}
        
        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
    
            Excel::import(new ProdukImport, $request->file('file'));
    
            // Redirect to the form with success message
            return redirect()->route('form.produk')->with('success', 'Data produk berhasil diimpor.');
        }
    
        public function formProduk()
        {
            $klasifikasis = Klasifikasi::with('produks')->get();
            $importedData = session('imported_data', []);
            return view('admin.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}