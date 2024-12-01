<?php

namespace App\Http\Controllers\Admin;

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

class KartuController extends Controller
{

    
    public function index(Request $request)
    {
        // $pelanggans = Pelanggan::whereNull('nama_pelanggan')->get();
        // return view('admin.kartu.index', compact('pelanggans'));
        $filter = $request->input('filter');

        if ($filter == 'sudah') {
            $pelanggans = Pelanggan::whereNotNull('nama_pelanggan')->whereNotNull('kode_pelanggan')->get();
        } elseif ($filter == 'belum') {
            $pelanggans = Pelanggan::whereNull('nama_pelanggan')->get();
        } else {
            $pelanggans = Pelanggan::whereNotNull('nama_pelanggan')->whereNotNull('kode_pelanggan')->get();        }
     
          return view('admin.kartu.index', compact('pelanggans'));

        
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_pelanggan' => 'required',
            ],
            [
                'kode_pelanggan.required' => 'Masukkan kode pelanggan',
            ]
        );

        $kode = $this->kode();
        $tanggal = Carbon::now()->format('Y-m-d');
        Pelanggan::create(array_merge(
            $request->all(),
            [
      
                'kode_pelanggan' => $this->kode(),
                'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $kode,
                'tanggal_awal' => $tanggal,

            ]
        ));

        $pelanggans = Pelanggan::whereNull('nama_pelanggan')->get(); 
        return view('admin.kartu.index', compact('pelanggans'));
  

    }
    
    
    // public function index()
    // {
    //     $pelanggans = Pelanggan::get();
    //     return view('admin/pelanggan.index', compact('pelanggans'));
    // }

    public function create()
    {
        $pelanggans = Pelanggan::whereNull('nama_pelanggan')->get();
        return view('admin.kartu.index', compact('pelanggans'));
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'kode_member' => '0',
    //             'qrcode_member' => '0',
          
    //         ],
    //         [
    //             'kode_member.required' => 'Masukkan nama lengkap',
    //             'qrcode_member.required' => 'Pilih gender',
         
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         return back()->withInput()->with('error', $errors);
    //     }

    //     if ($request->gambar_ktp) {
    //         $gambar = str_replace(' ', '', $request->gambar_ktp->getClientOriginalName());
    //         $namaGambar = 'gambar_ktp/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
    //         $request->gambar_ktp->storeAs('public/uploads/', $namaGambar);
    //     } else {
    //         $namaGambar = null;
    //     }

    //     $kode = $this->kode();
    //     $tanggal = Carbon::now()->format('Y-m-d');
    //     Member::create(array_merge(
    //         $request->all(),
    //         [
    //             'gambar_ktp' => $namaGambar,
    //             'kode_member' => $this->kode(),
    //             'qrcode_member' => 'https://javabakery.id/member/' . $kode,
    //             'tanggal_awal' => $tanggal,

    //         ]
    //         ));
    //     return redirect('admin/pelanggan/kartu')->with('success', 'Berhasil menambahkan member');
    // }

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


    public function destroy($id)
    {
        $tipe = Pelanggan::find($id);
        $tipe->delete();

        return redirect('admin.kartu.index')->with('success', 'Berhasil menghapus Pelanggan');
    }

    public function getpelanggan($id)
    {
        $items = Pelanggan::where('id', $id)->first();

        return json_decode($items);
    }
}