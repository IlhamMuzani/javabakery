<?php

namespace App\Http\Controllers\Toko_bumiayu;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ConditionalRules;
use Barryvdh\DomPDF\Facade ;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
// use Barryvdh\DomPDF\PDF as DomPDFPDF;

class PelangganController extends Controller
{

    public function index(Request $request)
    {
 

    $search = $request->input('search'); // Ambil input pencarian

    // $pelanggans = Pelanggan::all();
    $pelanggans = Pelanggan::when($search, function ($query, $search) {
        return $query->where('nama_pelanggan', 'like', '%' . $search . '%')
                     ->orWhere('kode_pelangganlama', 'like', '%' . $search . '%');
    }) ->paginate(10);

      return view('toko_bumiayu.pelanggan.index', compact('pelanggans', 'search'));
        
    
    }


    public function create()
    {
        $pelanggans = Pelanggan::all();
        return view('toko_bumiayu/pelanggan.create', compact('pelanggans'));
        // tidak memiliki akses
    }
   
    

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_pelangganlama' => 'required',
                'nama_pelanggan' => 'required',
                'alamat' => 'nullable',
                'gender' => 'nullable',
                'telp' => 'nullable',
                'email' => 'nullable',
                'pekerjaan' => 'nullable',
                'tanggal_lahir' => 'nullable',
                'tanggal_awal' => 'nullable',
                'tanggal_akhir' => 'nullable',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
             
                'kode_pelangganlama.required' => 'Masukkan kode lama',
                'nama_pelanggan.required' => 'Masukkan nama pelanggan',
               
                'pekerjaan.nullable' => 'masukan pekerjaan',
                'gender.nullable' => 'peilih gender',
                'email.nullable' => 'Masukkan email',
                // 'jabatan.required' => 'Pilih jabatan',
                'telp.required' => 'Masukkan no telepon',
                'alamat.required' => 'Masukkan alamat',
                'tanggal_lahir.nullable' => 'Masukkan tanggal lahir',
                'tanggal_awal.nullable' => 'Masukkan tanggal gabung',
                'tanggal_akhir.required' => 'Masukkan tanggal expired',
                'gambar.image' => 'Gambar yang dimasukan salah!',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }

        if ($request->gambar) {
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namaGambar = 'karyawan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = null;
        }
        $kode = $this->kode();

        Pelanggan::create(array_merge(
            $request->all(),
            [
                'gambar' => $namaGambar,
                'status' => 'null',
                'kode_pelanggan' => $this->kode(),
                'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $kode,
                'tanggal' => Carbon::now('Asia/Jakarta'),

            ]
        ));

        return redirect('toko_bumiayu/pelanggan')->with('success', 'Berhasil menambahkan karyawan');
    }

    public function kode()
    {
        $pelanggan = Pelanggan::all();
        if ($pelanggan->isEmpty()) {
            $num = "000001";
        } else {
            $id = Pelanggan::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'JB';
        $kode_pelanggan = $data . $num;
        return $kode_pelanggan;
    }

    public function edit($id)
    {

        $pelangganfirst = Pelanggan::where('id', $id)->first();

        $pelanggans = Pelanggan::where('kode_pelanggan', null)->get();
        return view('toko_bumiayu/pelanggan.update', compact('pelanggans', 'pelangganfirst'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_pelanggan' => 'required',
                'gender' => 'nullable',
                'tanggal_lahir' => 'nullable',
                'tanggal_awal' => 'nullable',
                'tanggal_akhir' => 'nullable',
                'telp' => 'nullable',
                'email' => 'nullable',
                'pekerjaan' => 'nullable',
                'alamat' => 'nullable',
                // 'gambar_ktp' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'nama_pelanggan.nullable' => 'Masukkan nama lengkap',
                'gender.nullable' => 'Pilih gender',
                // 'umur.nullable' => 'Masukkan umur',
                'alamat.nullable' => 'Masukkan alamat',
                'tanggal_lahir.nullable' => 'Masukkan tanggal lahir',
                'tanggal_awal.nullable' => 'Masukkan tanggal gabung',
                'tanggal_akhir.nullable' => 'Masukkan tanggal expired',
                'telp.nullable' => 'Masukkan no telepon',
                'email.nullable' => 'Masukkan email',
                'pekerjaan.nullable' => 'Masukkan pekerjaan',
                // 'gambar_ktp.image' => 'Gambar yang dimasukan salah!',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $pelanggans = Pelanggan::findOrFail($id);

        if ($request->gambar_ktp) {
            Storage::disk('local')->delete('public/uploads/' . $pelanggans->gambar_ktp);
            $gambar = str_replace(' ', '', $request->gambar_ktp->getClientOriginalName());
            $namaGambar = 'gambar_ktp/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar_ktp->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = $pelanggans->gambar_ktp;
        }

        if ($pelanggans->kode_lama == null) {

            Pelanggan::where('id', $id)->update([
                // 'gambar_ktp'=> $namaGambar,
                'nama_pelanggan' => $request->nama_pelanggan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'kode_pelangganlama' => $request->kode_lama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'gender' => $request->gender,
                // 'umur' => $request->umur,
                'telp' => $request->telp,
                'email' => $request->email,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
            ]);
    
        }else{

            Pelanggan::where('id', $id)->update([
                // 'gambar_ktp'=> $namaGambar,
                'nama_pelanggan' => $request->nama_pelanggan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $pelanggans->kode_pelanggan,

                'kode_pelangganlama' => $request->kode_pelangganlama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'gender' => $request->gender,
                // 'umur' => $request->umur,
                'telp' => $request->telp,
                'email' => $request->email,
                'pekerjaan' => $request->pekerjaan,
                'alamat' => $request->alamat,
            ]);
        }

        Pelanggan::where('id', $id)->update([
            // 'gambar_ktp'=> $namaGambar,
            'nama_pelanggan' => $request->nama_pelanggan,
            'kode_pelanggan' => $request->kode_pelanggan,
            'kode_pelangganlama' => $request->kode_pelangganlama,
            // 'qrcode_pelanggan' => $request->qrcode_pelanggan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_gabung' => $request->tanggal_gabung,
            'gender' => $request->gender,
            // 'umur' => $request->umur,
            'telp' => $request->telp,
            'email' => $request->email,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
        ]);

        return redirect('toko_bumiayu/pelanggan')->with('success', 'Berhasil memperbarui Pelanggan');
    }


    public function cetakqrcode($id)
    {
        $pelanggans = Pelanggan::find($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('toko_bumiayu.pelanggan.cetak_pdf', compact('pelanggans'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('QrCodePelanggan.pdf');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::where('id', $id)->first();
        return view('toko_bumiayu/pelanggan.show', compact('pelanggan'));
    }

    public function getpelanggan($id)
    {
        $items = Pelanggan::where('id', $id)->first();

        return json_decode($items);
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);
        $pelanggan->delete();

        return redirect('toko_bumiayu/pelanggan')->with('success', 'Berhasil menghapus data pelanggan');
    }
    
    // public function cetak_pdf($id)
    // {
    //     $pelanggan = Pelanggan::findOrFail($id);

    //     $pdf = FacadePdf::loadView('toko_bumiayu.pelanggan.cetak_pdf', compact('pelanggan'));
    //     return $pdf->download('kartu_member.pdf');
    // }
    public function cetak_pdf($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Membuat PDF dan menetapkan ukuran kertas kustom
        $pdf = FacadePdf::loadView('toko_bumiayu.pelanggan.cetak_pdf', compact('pelanggan'))
                        ->setPaper([0, 0, 500, 270]); // [left, top, width, height]

        // Mengirimkan view PDF sebagai respons
        return $pdf->stream('kartu_member.pdf');

    }
}
