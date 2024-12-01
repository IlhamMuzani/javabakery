<?php

namespace App\Http\Controllers\Toko_slawi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;



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
    
        return view('toko_slawi.produk.index', compact('produks', 'search', 'klasifikasis','subklasifikasis'));
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
        $subs = Subklasifikasi::all();
        $subs1 = Subsub::all();
        return view('toko_slawi/produk.create', compact('produks', 'klasifikasis', 'subs', 'subs1'));
    }

 
    public function store(Request $request)
 
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_produk' => 'required',
                'klasifikasi_id' => 'required',
                'subklasifikasi_id' => 'required',
                'satuan' => 'required',
                'harga' => 'required|numeric',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'nama_produk.required' => 'Masukan nama produk',
                'klasifikasi_id.required' => 'Masukan nama produk',
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
  
    
        TokoSlawi::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $request->harga,
            'harga_awal' => $request->harga,
            'diskon_awal' =>  0,
            'member_diskon_slw' => 0,
            'non_harga_slw' => $request->harga,
            'non_diskon_slw' => 0,
        ]);
    
        Tokobenjaran::create([
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
    
        return redirect('toko_slawi/produk')->with('success', 'Berhasil menambahkan produk');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $produk = Produk::where('id', $id)->first();
        return view('toko_slawi/produk.update', compact('produk'));  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

        return redirect('toko_slawi/produk')->with('success', 'Berhasil mengubah produk');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return redirect('toko_slawi/produk')->with('success', 'Berhasil menghapus data produk');
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


    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     Excel::import(new ProdukImport, $request->file('file'));

    //     return redirect()->route('form.produk')->with('success', 'Data produk berhasil diimpor.');
    // }

    // public function formProduk()
    // {
    //     $klasifikasis = Klasifikasi::with('produks')->get();
    //     $importedData = session('imported_data', []);
    //     return view('toko_slawi.permintaan_produk.form', compact('klasifikasis', 'importedData'));
    // }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Import data
        $import = new ProdukImport();
        Excel::import($import, $request->file('file'));

        // Save imported data to session
        session()->put('imported_data', $import->getImportedData());

        // Redirect to the form with success message
        return redirect()->route('form.produk')->with('success', 'Data produk berhasil diimpor.');
    }

    public function formProduk()
    {
        $klasifikasis = Klasifikasi::with('produks')->get();
        $importedData = session('imported_data', []);
        return view('toko_slawi.permintaan_produk.form', compact('klasifikasis', 'importedData'));
    }
}
