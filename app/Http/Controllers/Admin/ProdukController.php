<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
use App\Models\Stok_tokobanjaran;
use App\Models\Stokpesanan_tokobanjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProdukImport;
use App\Models\Stok_tokobumiayu;
use App\Models\Stok_tokocilacap;
use App\Models\Stok_tokopemalang;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokotegal;
use App\Models\Stokpesanan_tokobumiayu;
use App\Models\Stokpesanan_tokocilacap;
use App\Models\Stokpesanan_tokopemalang;
use App\Models\Stokpesanan_tokoslawi;
use App\Models\Stokpesanan_tokotegal;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;




class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil input pencarian
        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();
        

        $produks = Produk::when($search, function ($query, $search) {
            return $query->where('nama_produk', 'like', '%' . $search . '%')
                         ->orWhere('kode_lama', 'like', '%' . $search . '%');
        })
        ->paginate(10); // Menampilkan 10 data per halaman
    
        return view('admin.produk.index', compact('produks', 'search', 'klasifikasis','subklasifikasis'));
    }
    


    public function getkategori($id)
    {

        $klasifikasi = Klasifikasi::where('kategori', $id)->get();
        return response()->json($klasifikasi);
    }

    public function get_klasifikasi($klasifikasi_id)
    {
        $klasifikasis = Subklasifikasi::where('klasifikasi_id', $klasifikasi_id)->get();
        return response()->json($klasifikasis);
    }


    public function create()
    {
        $produks = Produk::all();
        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();

        return view('admin/produk.create', compact('produks', 'klasifikasis', 'subklasifikasis'));
    }
    
    public function fetch(Request $request)
    {
        $subklasifikasis = Subklasifikasi::where('klasifikasi_id', $request->klasifikasi_id)->get();
        return response()->json($subklasifikasis);
    }


    public function store(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'nama_produk' => 'required',
            'klasifikasi_id' => 'required',
            'subklasifikasi_id' => 'required',
            'kode_lama' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ],
        [
            'nama_produk.required' => 'Masukan nama produk',
            'klasifikasi_id.required' => 'Masukkan klasifikasi produk',
            'subklasifikasi_id.required' => 'Masukkan subklasifikasi produk',
            'kode_lama.required' => 'Masukkan Kode',
            'satuan.required' => 'Masukkan satuan',
            'harga.required' => 'Masukkan harga',
            'gambar.image' => 'Gambar yang dimasukan salah!',
        ]
    );

    if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return back()->withInput()->with('error', $errors);
    }

    if ($request->gambar) {
        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $namaGambar = 'produk/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $namaGambar);
    } else {
        $namaGambar = null;
    }
    $kode = $this->kode();

    // Simpan data produk
    $produk = Produk::create(array_merge(
        $request->all(),
        [
            'diskon' => 0,
            'gambar' => $namaGambar,
            'kode_produk' => $kode,
            'qrcode_produk' => 'https://javabakery.id/produk/' . $kode,
            'tanggal' => Carbon::now('Asia/Jakarta'),
        ]
    ));

    // Simpan data ke tabel toko
    TokoSlawi::create([
        'produk_id' => $produk->id,
        'member_harga_slw' => $request->harga,
        'harga_awal' => $request->harga,
        'diskon_awal' => 0,
        'member_diskon_slw' => 0,
        'non_harga_slw' => $request->harga,
        'non_diskon_slw' => 0,
    ]);

    Tokobanjaran::create([
        'produk_id' => $produk->id,
        'harga_awal' => $request->harga,
        'diskon_awal' => 0,
        'member_harga_bnjr' => $request->harga,
        'member_diskon_bnjr' => 0,
        'non_harga_bnjr' => $request->harga,
        'non_diskon_bnjr' => 0,
    ]);

    Tokotegal::create([
        'produk_id' => $produk->id,
        'harga_awal' => $request->harga,
        'diskon_awal' => 0,
        'member_harga_tgl' => $request->harga,
        'member_diskon_tgl' => 0,
        'non_harga_tgl' => $request->harga,
        'non_diskon_tgl' => 0,
    ]);

    Tokopemalang::create([
        'produk_id' => $produk->id,
        'harga_awal' => $request->harga,
        'diskon_awal' => 0,
        'member_harga_pml' => $request->harga,
        'member_diskon_pml' => 0,
        'non_harga_pml' => $request->harga,
        'non_diskon_pml' => 0,
    ]);

    Tokobumiayu::create([
        'produk_id' => $produk->id,
        'harga_awal' => $request->harga,
        'diskon_awal' => 0,
        'member_harga_bmy' => $request->harga,
        'member_diskon_bmy' => 0,
        'non_harga_bmy' => $request->harga,
        'non_diskon_bmy' => 0,
    ]);

    Tokocilacap::create([
        'produk_id' => $produk->id,
        'harga_awal' => $request->harga,
        'diskon_awal' => 0,
        'member_harga_clc' => $request->harga,
        'member_diskon_clc' => 0,
        'non_harga_clc' => $request->harga,
        'non_diskon_clc' => 0,
    ]);

    // Simpan data ke tabel stok_tokobanjaran dengan jumlah 0
    Stok_tokobanjaran::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);

    // Simpan data ke tabel stokpesanan_tokobanjaran dengan nilai yang sama seperti stok_tokobanjaran
    Stokpesanan_tokobanjaran::create([
        'produk_id' => $produk->id,
        'jumlah' => 0
    ]);

    // Simpan data ke tabel stok untuk masing-masing toko lainnya
    Stok_tokotegal::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);

    Stokpesanan_tokotegal::create([
        'produk_id' => $produk->id,
        'jumlah' => 0
    ]);
    Stok_tokoslawi::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);

    Stokpesanan_tokoslawi::create([
        'produk_id' => $produk->id,
        'jumlah' => 0
    ]);


    Stok_tokopemalang::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);
    Stokpesanan_tokopemalang::create([
        'produk_id' => $produk->id,
        'jumlah' => 0
    ]);


    Stok_tokobumiayu::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);
    Stokpesanan_tokobumiayu::create([
        'produk_id' => $produk->id,
        'jumlah' => 0
    ]);

    Stok_tokocilacap::create([
        'produk_id' => $produk->id,
        'jumlah' => 0,
    ]);
    Stokpesanan_tokocilacap::create([
        'produk_id' => $produk->id,
        'jumlah' => 0
    ]);

    return redirect('admin/produk')->with('success', 'Berhasil menambahkan produk');
}

    

    public function show($id)
    {

        $produk = Produk::where('id', $id)->first();
        return view('admin/produk.show', compact('produk'));
    }

    public function edit($id)
    {

        $produk = Produk::where('id', $id)->first();
        return view('admin/produk.update', compact('produk'));  
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_produk' => 'required',
                'satuan' => 'required',
                'harga' => 'required',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'nama_produk.required' => 'Masukan nama produk',
                'satuan.required' => 'Masukkan satuan',
                'harga.required' => 'Masukkan harga',
                'gambar.image' => 'Gambar yang dimasukan salah!',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $produk = Produk::findOrFail($id);

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $produk->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namaGambar = 'produk/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = $produk->gambar;
        }

        $produk->nama_produk = $request->nama_produk;
        $produk->satuan = $request->satuan;
        $produk->harga = $request->harga;
        $produk->gambar = $namaGambar;
        $produk->save();

        return redirect('admin/produk')->with('success', 'Berhasil mengubah produk');
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return redirect('admin/produk')->with('success', 'Berhasil menghapus data produk');
    }

    public function kode()
    {
        $lastBarang = Produk::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_produk;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'PR';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    public function formProduk()
    {
        $klasifikasis = Klasifikasi::with('produks')->get();
        $importedData = session('imported_data', []);
        return view('admin.permintaan_produk.form', compact('klasifikasis', 'importedData'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx',
        ]);
    
        Excel::import(new ProdukImport, $request->file('file_excel'));
    
        return redirect('admin/produk')->with('success', 'Berhasil mengimpor produk dari Excel');
    }

    // public function cetak_barcode($id)
    // {
    //     // Mengambil produk berdasarkan ID
    //     $produk = Produk::findOrFail($id); // Menggunakan findOrFail untuk memastikan produk ada
    
    //     // Mengambil semua klasifikasi dan subklasifikasi jika perlu
    //     $klasifikasis = Klasifikasi::all();
    //     $subklasifikasis = Subklasifikasi::all();
    
    //     $pdf = FacadePdf::loadView('admin.produk.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis'));
    //     $pdf->setPaper('a4', 'portrait');
    
    //     return $pdf->stream('penjualan.pdf');
 
    // }
    public function cetak_barcode($id)
    {
        $produk = Produk::findOrFail($id); 
    
        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();
    
        $pdf = FacadePdf::loadView('admin.produk.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis'));
        
        $pdf->setPaper([0, 0, 612, 400], 'portrait'); 
        return $pdf->stream('penjualan.pdf');
    }
    

    

//     public function cetak_barcode($id)
// {
//     $produk = Produk::findOrFail($id); 

//     $klasifikasis = Klasifikasi::all();
//     $subklasifikasis = Subklasifikasi::all();

//     $pdf = FacadePdf::loadView('admin.produk.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis'));

//     $pdf->setPaper([0, 0, 33, 15], 'potrait'); 
    
//     return $pdf->stream('barcode_produk.pdf');
// }

    
    public function print($id)
    {
        $produk = Produk::findOrFail($id); 
    
        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();
    
        $barcodeWidth = 10 * 37.7953; // Menghitung lebar dari unit ke mm (1 unit = 37.7953 mm)
        $barcodeHeight = 10 * 37.7953; // Jika tinggi sama dengan lebar
    
        $customPaper = array(0, 0, $barcodeWidth, $barcodeHeight); // Lebar dan tinggi kertas sesuai barcode
    
        $pdf = FacadePdf::loadView('admin.produk.print', compact('produk', 'klasifikasis', 'subklasifikasis'));
        $pdf->setPaper($customPaper, 'portrait');
    
        return $pdf->stream('penjualan.pdf');
    }

}


