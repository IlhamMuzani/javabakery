<?php

namespace App\Http\Controllers\Admin;

use App\Console\Kernel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class AddsubController extends Controller
{
    public function index()
    {
        $subsubs = Subsub::with('subklasifikasi')->get();
        return view('admin.klasifikasi.addsub.index', compact('subsubs'));
    }

    public function get_subklasifikasi($klasifikasi_id)
    {
        $klasifikasis = Subklasifikasi::where('klasifikasi_id', $klasifikasi_id)->get();
        return response()->json($klasifikasis);
    }

    public function create()
    {

        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();
        $subsubs = Subsub::all();

        return view('admin.klasifikasi.addsub.create', compact('klasifikasis', 'subklasifikasis', 'subsubs'));
    }


public function store(Request $request)
{
    $error_pelanggans = array();
    $error_pesanans = array();
    $data_pembelians = collect();

    if ($request->has('klasifikasi_id')) {
        for ($i = 0; $i < count($request->klasifikasi_id); $i++) {
            $validasi_produk = Validator::make($request->all(), [
                'klasifikasi_id.' . $i => 'required',
                'subklasifikasi_id.' . $i => 'required',
                'nama_barang.' . $i => 'required',
            ]);

            if ($validasi_produk->fails()) {
                array_push($error_pesanans, "Barang nomor " . ($i + 1) . " belum dilengkapi!");
            }

            $klasifikasi_id = $request->klasifikasi_id[$i] ?? null;
            $subklasifikasi_id = $request->subklasifikasi_id[$i] ?? null;
            $nama_barang = $request->nama_barang[$i] ?? null;

            $data_pembelians->push([
                'klasifikasi_id' => $klasifikasi_id,
                'subklasifikasi_id' => $subklasifikasi_id,
                'nama_barang' => $nama_barang
            ]);
        }
    }

    if ($error_pelanggans || $error_pesanans) {
        return back()
            ->withInput()
            ->with('error_pelanggans', $error_pelanggans)
            ->with('error_pesanans', $error_pesanans)
            ->with('data_pembelians', $data_pembelians);
    }

    // Simpan data ke dalam database
    foreach ($data_pembelians as $data_pesanan) {
        Subsub::create([
            'subklasifikasi_id' => $data_pesanan['subklasifikasi_id'],
            'nama' => $data_pesanan['nama_barang'],
        ]);
    }

    return redirect()->route('addsub.index')->with('success', 'Berhasil menambahkan Kategori');
}


public function destroy($id)
{
    $subsubs = Subsub::find($id);
    $subsubs->delete();
    return redirect()->route('addsub.index')->with('success', 'Berhasil menghapus Kategori');

}

}
