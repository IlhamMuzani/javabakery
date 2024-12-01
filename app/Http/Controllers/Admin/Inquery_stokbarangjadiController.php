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
use App\Models\Stok_barangjadi;
use App\Models\Detail_stokbarangjadi;
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




class Inquery_stokbarangjadiController extends Controller{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_input = $request->tanggal_input;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Stok_barangjadi::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

            if ($tanggal_input && $tanggal_akhir) {
                $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
            } elseif ($tanggal_input) {
                $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
                $query->where('tanggal_input', '>=', $tanggal_input);
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->where('tanggal_input', '<=', $tanggal_akhir);
            } else {
                // Jika tidak ada filter tanggal, tampilkan data hari ini
                $query->whereDate('tanggal_input', Carbon::today());
            }

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->get()->groupBy('kode_input');

            return view('admin.inquery_stokbarangjadi.index', compact('stokBarangJadi'));
    }


    public function create()
    {
        
        $klasifikasis = Klasifikasi::with('produks')->get();
        
        return view('admin.stok_barangjadi.create', compact('klasifikasis'));    
    }

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'klasifikasi_id' => 'required|exists:klasifikasis,id',
    //             'produk' => 'required|array',
    //             'produk.*.stok' => 'nullable|numeric|min:0',
    //         ],
    //         [
    //             'klasifikasi_id.required' => 'Pilih klasifikasi yang valid',
    //             'produk.required' => 'Pilih produk dan masukkan stok',
    //             'produk.*.stok.numeric' => 'Stok harus berupa angka',
    //             'produk.*.stok.min' => 'Stok tidak boleh kurang dari 0',
    //         ]
    //     );
    
    //     if ($validator->fails()) {
    //         // Mengambil kesalahan validasi
    //         $errors = $validator->errors()->all();
    //         return back()->withInput()->with('error', $errors);
    //     }
    
    //     // Simpan data stok
    //     foreach ($request->input('produk') as $produkId => $data) {
    //         // Pastikan $data['stok'] memiliki nilai sebelum mencoba menyimpannya
    //         if (isset($data['stok']) && $data['stok'] !== '') {
    //             $stokBarangJadi = Stok_barangjadi::where('produk_id', $produkId)
    //                 ->where('klasifikasi_id', $request->input('klasifikasi_id'))
    //                 ->first();
    
    //             if ($stokBarangJadi) {
    //                 // Tambahkan stok yang sudah ada dengan stok yang baru
    //                 $stokBarangJadi->stok += $data['stok'];
    //                 $stokBarangJadi->save();
    //             } else {
    //                 // Buat entri baru jika belum ada
    //                 Stok_barangjadi::create([
    //                     'produk_id' => $produkId,
    //                     'klasifikasi_id' => $request->input('klasifikasi_id'),
    //                     'stok' => $data['stok'],
    //                     'status' => 'unpost',
    //                 ]);
    //             }
    //         }
    //     }
    
    //     return redirect('admin/stok_barangjadi')->with('success', 'Berhasil menambahkan stok barang jadi');
    // }
    
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
        $klasifikasi = $detailStokBarangJadi->first()->produk->klasifikasi->nama ?? 'Tidak Diketahui';

        return view('admin.inquery_stokbarangjadi.show', compact('detailStokBarangJadi', 'klasifikasi'));
    }

        public function unpost_stokbarangjadi($id)
        {
            // Ambil data stok barang berdasarkan ID
            $stok = Stok_barangjadi::where('id', $id)->first();
        
            // Pastikan data ditemukan
            if (!$stok) {
                return back()->with('error', 'Data tidak ditemukan.');
            }
        
            // Ambil kode_input dari stok yang diambil
            $kodeInput = $stok->kode_input;
        
            // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
            Stok_barangjadi::where('kode_input', $kodeInput)->update([
                'status' => 'unpost'
            ]);
            Detail_stokbarangjadi::where('kode_input', $kodeInput)->update([
                'status' => 'unpost'
            ]);

            return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
        }
        
    public function posting_stokbarangjadi($id)
    {
        $stok = Stok_barangjadi::where('id', $id)->first();

        if (!$stok) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $kodeInput = $stok->kode_input;

        Stok_barangjadi::where('kode_input', $kodeInput)->update([
            'status' => 'posting'
        ]);
        Detail_stokbarangjadi::where('kode_input', $kodeInput)->update([
            'status' => 'posting'
        ]);

        return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
    }

    
    public function kode()
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
    
    

    public function edit($id)
    {
        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $klasifikasis = Klasifikasi::all(); // Menyediakan daftar klasifikasi

        return view('admin.inquery_stokbarangjadi.edit', compact('stok_barangjadi', 'klasifikasis'));
    }


    // public function update(Request $request, $id)
    // {
    //     // Temukan record Stok_Barangjadi berdasarkan ID
    //     $stokBarangjadi = Stok_Barangjadi::findOrFail($id);
    
    //     // Loop melalui input produk dan perbarui atau hapus record sesuai kebutuhan
    //     foreach ($request->input('produk', []) as $produkId => $detail) {
    //         $stok = $detail['stok'];
            
    //         // Temukan record existing untuk produk_id yang diberikan pada tabel detail_stokbarangjadi
    //         $existingDetail = Detail_StokBarangjadi::where('stokbarangjadi_id', $stokBarangjadi->id)
    //             ->where('produk_id', $produkId)
    //             ->first();
            
    //         if ($stok == 0) {
    //             // Jika stok 0, hapus record dari Detail_StokBarangjadi jika ada
    //             if ($existingDetail) {
    //                 $existingDetail->delete();
    //             }
    //         } else {
    //             // Jika stok lebih besar dari 0
    //             if ($existingDetail) {
    //                 // Perbarui stok pada record Detail_StokBarangjadi yang ada
    //                 $existingDetail->update(['stok' => $stok]);
    //             } else {
    //                 // Jika tidak ada record yang ditemukan untuk produk ini, buat record baru
    //                 Detail_StokBarangjadi::create([
    //                     'stokbarangjadi_id' => $stokBarangjadi->id,
    //                     'produk_id' => $produkId,
    //                     'stok' => $stok,
    //                 ]);
    //             }
    //         }
    //     }
    
    //     // Update stok total di Stok_Barangjadi jika diperlukan
    //     // Misalnya, jika stok total adalah jumlah dari Detail_StokBarangjadi
    //     $totalStok = Detail_StokBarangjadi::where('stokbarangjadi_id', $stokBarangjadi->id)
    //         ->sum('stok');
            
    //     $stokBarangjadi->update(['stok' => $totalStok]);
    
    //     // Redirect kembali dengan pesan sukses
    //     return redirect()->route('inquery_stokbarangjadi.index')->with('success', 'Permintaan produk berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        // Temukan record Stok_Barangjadi berdasarkan ID
        $stokBarangjadi = Stok_Barangjadi::findOrFail($id);

        // Loop melalui input produk dan perbarui atau hapus record sesuai kebutuhan
        foreach ($request->input('produk', []) as $produkId => $detail) {
            $stok = $detail['stok'];

            // Temukan record existing untuk produk_id yang diberikan pada tabel detail_stokbarangjadi
            $existingDetail = Detail_StokBarangjadi::where('stokbarangjadi_id', $stokBarangjadi->id)
                ->where('produk_id', $produkId)
                ->first();

            if ($stok == 0) {
                // Jika stok 0, hapus record dari Detail_StokBarangjadi jika ada
                if ($existingDetail) {
                    $existingDetail->delete();
                }
            } else {
                // Jika stok lebih besar dari 0
                if ($existingDetail) {
                    // Perbarui stok pada record Detail_StokBarangjadi yang ada
                    $existingDetail->stok = $stok; // Set stok baru
                    $existingDetail->save(); // Simpan perubahan
                } else {
                    // Jika tidak ada record yang ditemukan untuk produk ini, buat record baru
                    Detail_StokBarangjadi::create([
                        'stokbarangjadi_id' => $stokBarangjadi->id,
                        'produk_id' => $produkId,
                        'stok' => $stok,
                    ]);
                }
            }
        }

        // Update stok total di Stok_Barangjadi jika diperlukan
        // Misalnya, jika stok total adalah jumlah dari Detail_StokBarangjadi
        $totalStok = Detail_StokBarangjadi::where('stokbarangjadi_id', $stokBarangjadi->id)
            ->sum('stok');

        $stokBarangjadi->update(['stok' => $totalStok]);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('inquery_stokbarangjadi.index')->with('success', 'Permintaan produk berhasil diperbarui.');
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