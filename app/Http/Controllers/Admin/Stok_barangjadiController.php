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
use App\Models\Detail_stokbarangjadi;
use App\Models\Stok_barangjadi;
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
use Dompdf\Options;
use StokBarangJadiImport;

class Stok_barangjadiController extends Controller{

    public function index()
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();
    
        $stokBarangJadi = Stok_barangjadi::with('detail_stokbarangjadi.produk.klasifikasi')
            ->whereDate('tanggal_input', $today)
            ->orWhere('status', 'unpost')
            ->orderBy('tanggal_input', 'desc')  
            ->get()
            ->groupBy('kode_input');
    
        return view('admin.stok_barangjadi.index', compact('stokBarangJadi'));
    }
    
    public function create()
    {
        
        $klasifikasis = Klasifikasi::with('produks')->get();
        
        return view('admin.stok_barangjadi.create', compact('klasifikasis'));    
    }

public function store(Request $request)
{
    $kode = $this->kode();
    $produkData = $request->input('produk', []);
    $detailData = [];

    foreach ($produkData as $produkId => $data) {
        $stok = $data['stok'] ?? null;

        if (!is_null($stok) && $stok !== '') {
            // Create a new record in Stok_barangjadi
            $stokBarangJadi = Stok_barangjadi::create([
                'produk_id' => $produkId,
                'klasifikasi_id' => $request->input('klasifikasi_id'),
                'stok' => $stok,
                'status' => 'posting',
                'kode_input' => $kode,
                'tanggal_input' => Carbon::now('Asia/Jakarta'),
            ]);

            // Check if a record with the same produk_id and klasifikasi_id exists in Detail_stokbarangjadi
            $existingDetail = Detail_stokbarangjadi::where('produk_id', $produkId)
                ->where('klasifikasi_id', $request->input('klasifikasi_id'))
                ->where('status', 'posting')
                ->first();

            if ($existingDetail) {
                // Update existing record
                $existingDetail->stok += $stok;
                $existingDetail->save();
            } else {
                // Create a new record in Detail_stokbarangjadi
                $detailData[] = [
                    'stokbarangjadi_id' => $stokBarangJadi->id,
                    'produk_id' => $produkId,
                    'klasifikasi_id' => $request->input('klasifikasi_id'),
                    'stok' => $stok,
                    'status' => 'posting',
                    'kode_input' => $kode,
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ];
            }
        }
    }

    if (!empty($detailData)) {
        Detail_stokbarangjadi::insert($detailData);
    }

    return redirect('admin/stok_barangjadi')->with('success', 'Berhasil menambahkan stok barang jadi');
}


    public function kode()
    {
        $lastBarang = Stok_barangjadi::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_input;
            $num = (int) substr($lastCode, strlen('SB')) + 1; 
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'SB';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
    
    public function show($id)
    {
        // Ambil kode_input dari detail_stokbarangjadi berdasarkan id
        $kodeInput = Stok_barangjadi::where('id', $id)->value('kode_input');
        
        // Jika kode_input tidak ditemukan, tampilkan pesan error
        if (!$kodeInput) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_input yang sama
        $detailStokBarangJadi = Stok_barangjadi::with(['produk.subklasifikasi'])->where('kode_input', $kodeInput)->get();
        
        return view('admin.stok_barangjadi.show', compact('detailStokBarangJadi'));
    }
    


//     public function print($id)
// {
//     $kodeInput = Stok_barangjadi::where('id', $id)->value('kode_input');

//     // Jika kode_input tidak ditemukan, tampilkan pesan error
//     if (!$kodeInput) {
//         return redirect()->back()->with('error', 'Data tidak ditemukan.');
//     }

//     // Ambil semua data dengan kode_input yang sama, dikelompokkan berdasarkan klasifikasi produk
//     $detailStokBarangJadi = Stok_barangjadi::with(['produk.subklasifikasi', 'produk.klasifikasi'])
//         ->where('kode_input', $kodeInput)
//         ->get()
//         ->groupBy('produk.klasifikasi.nama');  // Mengelompokkan data berdasarkan nama klasifikasi

//     // Inisialisasi DOMPDF
//     $options = new Options();
//     $options->set('isHtml5ParserEnabled', true);
//     $options->set('isRemoteEnabled', true); // Jika menggunakan URL eksternal untuk gambar atau CSS

//     $dompdf = new Dompdf($options);

//     // Memuat konten HTML dari view
//     $html = view('admin.stok_barangjadi.print', [
//         'detailStokBarangJadi' => $detailStokBarangJadi,
//         'kodeInput' => $kodeInput,
//     ])->render();

//     $dompdf->loadHtml($html);

//     // Set ukuran kertas dan orientasi
//     $dompdf->setPaper('A4', 'portrait');

//     // Render PDF
//     $dompdf->render();

//     // Menambahkan nomor halaman di kanan bawah
//     $canvas = $dompdf->getCanvas();
//     $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
//         $text = "Page $pageNumber of $pageCount";
//         $font = $fontMetrics->getFont('Arial', 'normal');
//         $size = 10;

//         // Menghitung lebar teks
//         $width = $fontMetrics->getTextWidth($text, $font, $size);

//         // Mengatur koordinat X dan Y
//         $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
//         $y = $canvas->get_height() - 15; // 15 pixel dari bawah

//         // Menambahkan teks ke posisi yang ditentukan
//         $canvas->text($x, $y, $text, $font, $size);
//     });

//     // Output PDF ke browser
//     return $dompdf->stream('laporan_stok_barangjadi.pdf', ['Attachment' => false]);
// }

public function print($id)
{
    // Ambil kode_input berdasarkan id stok_barangjadi
    $stokBarang = Stok_barangjadi::where('id', $id)->first();

    // Jika data tidak ditemukan
    if (!$stokBarang) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // Ambil semua data dengan kode_input yang sama, dikelompokkan berdasarkan klasifikasi produk
    $detailStokBarangJadi = Stok_barangjadi::with(['produk.subklasifikasi', 'produk.klasifikasi'])
        ->where('kode_input', $stokBarang->kode_input)
        ->get()
        ->groupBy('produk.klasifikasi.nama');  // Mengelompokkan data berdasarkan nama klasifikasi

    // Ambil klasifikasi dari produk pertama, jika ada
    $klasifikasi = optional($detailStokBarangJadi->first())->produk->klasifikasi->nama ?? 'Tidak ada klasifikasi';

    // Inisialisasi DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // Jika menggunakan URL eksternal untuk gambar atau CSS

    $dompdf = new Dompdf($options);

    // Memuat konten HTML dari view
    $html = view('admin.stok_barangjadi.print', [
        'detailStokBarangJadi' => $detailStokBarangJadi,
        'kodeInput' => $stokBarang->kode_input,
        'tanggalInput' => $stokBarang->tanggal_input,  // Mengirimkan tanggal_input ke view
        'klasifikasi' => $klasifikasi,
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
    return $dompdf->stream('laporan_stok_barangjadi.pdf', ['Attachment' => false]);
}








public function unpost_stokbarangjadi($id)
{
    $item = Stok_barangjadi::where('id', $id)->first();

    
        $item->update([
            'status' => 'unpost'
        ]);
    return back()->with('success', 'Berhasil');
}

public function posting_stokbarangjadi($id)
{
    $item = Stok_barangjadi::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
    return back()->with('success', 'Berhasil');
}
    

    public function edit($id)
    {
           
    }
        
    

    public function update(Request $request, $id)
    {
           
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
        
        public function import(Request $request)
        {
            Excel::import(new StokBarangJadiImport, $request->file('file'));
            return redirect('admin/stok_barangjadi')->with('success', 'Data berhasil diimpor');
        }
    
        public function formProduk()
        {
            $klasifikasis = Klasifikasi::with('produks')->get();
            $importedData = session('imported_data', []);
            return view('admin.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}