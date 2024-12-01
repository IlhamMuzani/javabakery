<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('subsub')->get();
        return view('admin.barang.index', compact('barangs'));
        // $barangs = Barang::all();
        // $klasifikasis = Klasifikasi::all();
        // $subklasifikasis = Subklasifikasi::all();
        // $subsubs = Subsub::all();
        // return view('admin/barang.index',compact('barangs','klasifikasis','subklasifikasis', 'subsubs'));
        // $barangs = Barang::with(['klasifikasi', 'subklasifikasi'])->get();
        // return view('admin.barang.index', compact('barangs'));
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
        
        $klasifikasis = Klasifikasi::all();
        $subs = Subklasifikasi::all();
        $subs1 = Subsub::all();
        return view('admin/barang.create',compact('klasifikasis','subs', 'subs1'));
    }

public function store(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            // 'kode_barang' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'harga' => 'required',
            
            'subsub_id' => 'required',
        ],
        [
            // 'kode_barang.required' => 'Masukkan kode barang',
            'keterangan.required' => 'Masukkan keterangan',
            'jumlah.required' => 'Masukkan jumlah',
            'harga.required' => 'Masukkan harga',
           
            'subsub_id.required' => 'Masukkan subsub',
        ]
    );

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $kode = $this->kode();
    $total = $request->harga - ($request->harga * $request->diskon / 100);

    $barang = new Barang();
    $barang->kode_barang = $kode; // Generate and assign kode_barang
    $barang->qrcode_barang = 'https://javabakery.id/barang/' . $kode; // Generate and assign qrcode_barang
    $barang->keterangan = $request->keterangan;
    $barang->jumlah = $request->jumlah;
    $barang->harga = $request->harga;
    $barang->diskon = $request->diskon;
    $barang->total = $total;
    $barang->subsub_id = $request->subsub_id;

    $barang->save();

    return redirect('admin/barang')->with('success', 'Berhasil menambahkan barang');
}

    public function kode()
    {
        $barang = Barang::all();
        if ($barang->isEmpty()) {
            $num = "000001";
        } else {
            $id = Barang::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'AE';
        $kode_barang = $data . $num;
        return $kode_barang;
    }

    public function edit($id)
    {

        $barang = Barang::where('id', $id)->first();
        return view('admin/barang.update', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
              
                'jumlah' => 'required',
                'diskon' => 'required',
                'harga' => 'required',
            ],
            [
                'jumlah.required' => 'Masukkan ukuran',
                'diskon.required' => 'Masukkan diskon',
                'harga.required' => 'Masukkan harga',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $barang = Barang::findOrFail($id);
        

        Barang::where('id', $id)->update([
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
            'diskon' => $request->diskon,
        ]);

        return redirect('admin/barang')->with('success', 'Berhasil memperbarui barang');
    }


    public function cetakqrcode($id)
    {
        $barangs = Barang::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.barang.cetak_pdf', compact('barangs'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodeBarang.pdf');
    }

    public function show($id)
    {


        $barang = Barang::where('id', $id)->first();
        return view('admin/barang.show', compact('barang'));
    }


    public function destroy($id)
    {
        $tipe = Barang::find($id);
        $tipe->delete();

        return redirect('admin/barang')->with('success', 'Berhasil menghapus barang');
    }
}