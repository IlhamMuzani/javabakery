<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Toko;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TokoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokos = Toko::all();
        return view('admin.toko.index', compact('tokos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tokos = Toko::all();
        return view('admin/toko.create', compact('tokos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_toko' => 'required',
                'alamat' => 'required',

            ],
            [
                'nama_toko.required' => 'Masukan nama toko',
                'alamat.required' => 'Masukkan alamat',

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

        Toko::create(array_merge(
            $request->all(),
            [
                'gambar' => $namaGambar,
                'kode_toko' => $this->kode(),
                'qrcode_toko' => 'https://javabakery.id/toko/' . $kode,
                'tanggal' => Carbon::now('Asia/Jakarta'),

            ]
        ));

        return redirect('admin/toko')->with('success', 'Berhasil menambahkan toko');
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
        $tokos = Toko::where('id', $id)->first();
        return view('admin/toko.update', compact('tokos')); 
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
               'nama_toko' => 'required',
                'alamat' => 'required',
            ],
            [
                'nama_toko.required' => 'Masukan nama toko',
                'alamat.required' => 'Masukkan alamat',

            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $tokos = Toko::findOrFail($id);

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $tokos->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namaGambar = 'tokos/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = $tokos->gambar;
        }

        $tokos->nama_toko = $request->nama_toko;
        $tokos->alamat = $request->alamat;

        $tokos->save();

        return redirect('admin/toko')->with('success', 'Berhasil mengubah toko');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tokos = Toko::find($id);
        $tokos->delete();

        return redirect('admin/toko')->with('success', 'Berhasil menghapus toko');
    }
    

    public function kode()
    {
        $lastBarang = Toko::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_toko;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'TK';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
}
