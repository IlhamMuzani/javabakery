<?php

namespace App\Http\Controllers\Admin;

use App\Console\Kernel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subklasifikasi;
use Illuminate\Support\Facades\Validator;

class SubklasifikasiController extends Controller
{
    // public function index()
    // {
    //     $subklasifikasis = Subklasifikasi::all();
    //     $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
    //     return view('admin/klasifikasi.subklasifikasi.index', compact('klasifikasis', 'subklasifikasis'));
      
            
    // }

    public function index()
    {
        
        $subklasifikasis = Subklasifikasi::with('klasifikasi')->get();
    
        return view('admin/klasifikasi.subklasifikasi.index', compact('subklasifikasis'));
    }
    

    public function getSubklasifikasi($klasifikasi_id)
    {
        $subklasifikasis = Subklasifikasi::where('klasifikasi_id', $klasifikasi_id)->get();
        return response()->json($subklasifikasis);
    }
    // public function create()
    // {

    //         $klasifikasis = Klasifikasi::all();
    //         $subs = Subklasifikasi::all();
          
    //         return view('admin/klasifikasi.create', compact('klasifikasis', 'subs'));
            
    // }
    public function create()
    {

        $klasifikasis = Klasifikasi::all();

        return view('admin.klasifikasi.addkategori.create', compact('klasifikasis'));
    }

// public function get_karyawans($departemen_id)
// {
//     $karyawans = Karyawan::where('departemen_id', $departemen_id)->get();
//     return response()->json($karyawans);
// }

public function edit($id)
    {

            $subklasifikasis = Subklasifikasi::where('id', $id)->first();
            return view('admin.klasifikasi.subklasifikasi.update', compact('subklasifikasis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama tidak boleh Kosong',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Subklasifikasi::where('id', $id)->update([
            'nama' => $request->nama,
        ]);

        return redirect('admin/subklasifikasi')->with('success', 'Berhasil memperbarui subkategori');
    }

    public function destroy($id)
    {
        $subklasifikasis = Subklasifikasi::find($id);
        $subklasifikasis->delete();

        return redirect('admin/subklasifikasi')->with('success', 'Berhasil menghapus subkategori');
    }
}
