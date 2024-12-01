<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Metode_pembayaranController extends Controller
{
    public function index()
    {
        $metodes = Metodepembayaran::all();
        return view('admin.metode_pembayaran.index', compact('metodes'));
        // tidak memiliki akses
    }


    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $karyawans = Karyawan::with('departemen')
            ->where('nama_lengkap', 'like', "%$keyword%")
            ->paginate(10);
        return response()->json($karyawans);
    }


    public function create()
    {
        $metodes = Metodepembayaran::all();
        return view('admin/metode_pembayaran.create', compact('metodes'));
        // tidak memiliki akses
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_metode' => 'required',
                'fee' => 'nullable',
                'keterangan' => 'nullable',
               
            ],
            [
                'nama_metode.required' => 'Masukan nama metode pembayaran',
                // 'diskon.required' => 'Masukkan diskon',
                // 'keterangan.required' => 'Masukkan keterangan',
                
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }else {
            $namaGambar = null;
        }
        $kode = $this->kode();

        Metodepembayaran::create(array_merge(
            $request->all(),
            [
                'kode_metode' => $this->kode(),
                'qrcode_metode' => 'https://javabakery.id/metode/' . $kode,
                    // 'tanggal' => Carbon::now('Asia/Jakarta'),

            ]
        ));

        return redirect('admin/metode_pembayaran')->with('success', 'Berhasil menambahkan metode pembayaran');
    }

    public function kode()
    {
        $lastBarang = Metodepembayaran::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_metode;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'MP';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }

    
    public function show($id)
    {

        $karyawan = Karyawan::where('id', $id)->first();
        return view('admin/karyawan.show', compact('karyawan'));
    }

    public function edit($id)
    {

        $metode = Metodepembayaran::where('id', $id)->first();
        return view('admin/metode_pembayaran.update', compact('metode'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_metode' => 'required',
                
            ],
            [
                'nama_metode.required' => 'masukan nama metode',
                
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $metode = Metodepembayaran::findOrFail($id);

        

        $metode->nama_metode = $request->nama_metode;
        $metode->fee = $request->fee;
        $metode->keterangan = $request->keterangan;
        
        $metode->save();

        return redirect('admin/metode_pembayaran')->with('success', 'Berhasil mengubah metode');
    }

    public function destroy($id)
    {
        $metode = Metodepembayaran::find($id);
        $metode->delete();

        return redirect('admin/metode_pembayaran')->with('success', 'Berhasil menghapus Metode pembayaran');
    }
}
