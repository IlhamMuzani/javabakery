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

class KlasifikasiController extends Controller
{
    public function index()
    {
        // $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
        // $subs = Subklasifikasi::all();
        // return view('admin.klasifikasi.index', compact('klasifikasis', 'subs'));
        $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
        return view('admin.klasifikasi.index', compact('klasifikasis'));
    }

    public function get_subklasifikasi($klasifikasi_id)
    {
        $klasifikasis = Subklasifikasi::where('klasifikasi_id', $klasifikasi_id)->get();
        return response()->json($klasifikasis);
    }

    public function create()
    {

        $klasifikasis = Klasifikasi::all();
        $subs = Subklasifikasi::all();
        return view('admin.klasifikasi.create', compact('klasifikasis', 'subs'));
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'nama' => 'required',
    //         ],
    //         [
    //             'nama.required' => 'Masukkan kategori',
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     Klasifikasi::create([
    //         'kategori' => $request->kategori,
    //     ]);

    //     return redirect('admin/klasifikasi')->with('success', 'Berhasil menambahkan kategori');

    // }

   

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',     
                // 'kode_klasifikasi' => 'required',     
                // 'qrcode_klasifikasi' => 'required',     
                // 'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'nama.required' => 'Masukan nama kategori',             
                // 'gambar.image' => 'Gambar yang dimasukan salah!',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }

        // if ($request->gambar) {
        //     $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        //     $namaGambar = 'karyawan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
        //     $request->gambar->storeAs('public/uploads/', $namaGambar);
        // } else {
        //     $namaGambar = null;
        // }

        $kode = $this->kode();

        Klasifikasi::create(array_merge(
            $request->all(),
            [
                // 'gambar' => $namaGambar,
                'kode_klasifikasi' => $this->kode(),
                'qrcode_klasifikasi' => 'https://javabakery.id/klasifikasi/' . $kode,
                'tanggal' => Carbon::now('Asia/Jakarta'),

            ]
        ));

        return redirect('admin/addkategori')->with('success', 'Berhasil menambahkan kategori');
    }
    public function kode()
    {
        $klasifikasi = Klasifikasi::all();
        if ($klasifikasi->isEmpty()) {
            $num = "000001";
        } else {
            $id = Klasifikasi::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'JB';
        $kode_klasifikasi = $data . $num;
        return $kode_klasifikasi;
    }
 

    public function add(){
        
        $klasifikasis = Klasifikasi::all();
        // $subs = Subklasifikasi::all();
        return view('admin.klasifikasi.create1', compact('klasifikasis'));

    }

    public function store1(){

    }
 

    public function qrcodeDepartemenExists($number)
    {
        return Klasifikasi::whereQrcodeDepartemen($number)->exists();
    }

    // public function cetakpdf($id)
    // {
    //     $cetakpdf = Departemen::where('id', $id)->first();
    //     $html = view('admin/departemen.cetak_pdf', compact('cetakpdf'));

    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A4', 'landscape');

    //     $dompdf->render();

    //     $dompdf->stream();
    // }

    public function edit($id)
    {

            $departemen = Klasifikasi::where('id', $id)->first();
            return view('admin/departemen.update', compact('departemen'));
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
            'tanggal_awal' => Carbon::now('Asia/Jakarta'), 
        ]);

        return redirect('admin/departemen')->with('success', 'Berhasil memperbarui Departemen');
    }

    // public function show($id)
    // {
    //     $klasifikasis = Klasifikasi::all();
    //     // $subs = Subklasifikasi::all();
    //     return view('admin.klasifikasi.create1', compact('klasifikasis')); 
    // }

    public function destroy($id)
    {
        $departemen = Klasifikasi::find($id);
        $departemen->delete();

        return redirect('admin/klasifikasi')->with('success', 'Berhasil menghapus Klasifikasi');
    }
}