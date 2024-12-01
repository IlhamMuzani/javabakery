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

class AddkategoriController extends Controller
{
    public function index()
    {
        $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
        return view('admin.klasifikasi.addkategori.index', compact('klasifikasis'));
    }

    public function create()
    {
        $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
        return view('admin.klasifikasi.addkategori.create', compact('klasifikasis'));
        // $klasifikasis = Klasifikasi::all();

        // return view('admin.klasifikasi.addkategori.create', compact('klasifikasis'));
    }

    public function tambahkategori(Request $request)
    {
        $error_pelanggans = array();
        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('klasifikasi_id')) {
            for ($i = 0; $i < count($request->klasifikasi_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'klasifikasi_id.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                  
                 
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang nomor " . $i + 1 . " belum dilengkapi!");
                }


                $klasifikasi_id = is_null($request->klasifikasi_id[$i]) ? '' : $request->klasifikasi_id[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
       

                $data_pembelians->push([
                    'klasifikasi_id' => $klasifikasi_id, 'nama_barang' => $nama_barang
                ]);
            }
        } else {
        }
        

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

            foreach ($data_pembelians as $data_pesanan) {
                // Create a new Detailpembelian
                Subklasifikasi::create([
                    'klasifikasi_id' => $data_pesanan['klasifikasi_id'],
                    'nama' => $data_pesanan['nama_barang'],
                 
                ]);
        }

        return redirect()->route('subklasifikasi.index')->with('success', 'Berhasil Menambahkan subklasifikasi');
    }

    public function kode()
    {
        $lastBarang = Klasifikasi::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_klasifikasi;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'AA';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
 

 
    public function qrcodeDepartemenExists($number)
    {
        return Klasifikasi::whereQrcodeDepartemen($number)->exists();
    }



    public function edit($id)
    {

            $klasifikasis = Klasifikasi::where('id', $id)->first();
            return view('admin.klasifikasi.addkategori.update', compact('klasifikasis'));
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

        Klasifikasi::where('id', $id)->update([
            'nama' => $request->nama,
        ]);

        return redirect('admin/addkategori')->with('success', 'Berhasil memperbarui Kategori');
    }

    public function destroy($id)
    {
        $klasifikasis = Klasifikasi::find($id);
        $klasifikasis->delete();

        return redirect('admin/addkategori')->with('success', 'Berhasil menghapus Kategori');
    }
}