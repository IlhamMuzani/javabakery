<?php

namespace App\Http\Controllers\Toko_slawi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AddpelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::get();
        return view('toko_slawi/pelanggan/add.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('toko_slawi/pelanggan/add/index');
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'kode_pelanggan' =>'0',
                // 'qrcode_pelanggan' =>'0',
                'nama_pelanggan' => 'required',
                'gender' => 'required',
                'email' => 'required',
                'pekerjaan' => 'required',
                'telp' => 'required',
                'tanggal_lahir' => 'required',
                'alamat' => 'required',
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
            ],
            [
                // 'kode_pelanggan' =>'masukan kode pelanggan',
                'nama_pelanggan' => 'masukan nama pelanggan',
                'gender' => 'masukan gender',
                'email' => 'masukan email',
                'pekerjaan' => 'masukan pekerjaan',
                'telp' => 'masukan no telpon',
                'tanggal_lahir' => 'masukan tangal lahir',
                'alamat' => 'masukan alamat',
                'tanggal_awal' => 'masukan tanggal gabung',
                'tanggal_akhir' => 'masukan tanggal expired',
            ]
        );

        $pelanggan = new Pelanggan();
        
        $pelanggan->kode_lama = $request->kode_lama;
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->gender = $request->gender;
        $pelanggan->email = $request->email;
        $pelanggan->pekerjaan = $request->pekerjaan;
        $pelanggan->telp = $request->telp;
        $pelanggan->tanggal_lahir = $request->tanggal_lahir;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->tanggal_awal = $request->tanggal_awal;
        $pelanggan->tanggal_akhir = $request->tanggal_akhir;
        $pelanggan->qrcode_pelanggan = $request->qrcode_pelanggan;
        $pelanggan->kode_pelanggan = $request->kode_pelanggan;
        

        $pelanggan->save();

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }  
    
        return redirect('toko_slawi/pelanggan/')->with('success', 'Berhasil menambahkan pelanggan');
    }

    // public function kode()
    // {
    //     $barang = Barang::all();
    //     if ($barang->isEmpty()) {
    //         $num = "000001";
    //     } else {
    //         $id = Barang::getId();
    //         foreach ($id as $value);
    //         $idlm = $value->id;
    //         $idbr = $idlm + 1;
    //         $num = sprintf("%06s", $idbr);
    //     }

    //     $data = 'AE';
    //     $kode_barang = $data . $num;
    //     return $kode_barang;
    // }

    // public function edit($id)
    // {

    //     $barang = Barang::where('id', $id)->first();
    //     return view('toko_slawi/barang.update', compact('barang'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'nama_barang' => 'required',
    //             'jumlah' => 'required',
    //             'spesifikasi' => 'required',
    //             'keterangan' => 'required',
    //             'harga' => 'required',
    //         ],
    //         [
    //             'nama_barang.required' => 'Masukkan nama barang',
    //             'jumlah.required' => 'Masukkan ukuran',
    //             'spesifikasi.required' => 'Masukkan spesifikasi',
    //             'keterangan.required' => 'Masukkan keterangan',
    //             'harga.required' => 'Masukkan harga',
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         $error = $validator->errors()->all();
    //         return back()->withInput()->with('error', $error);
    //     }

    //     $barang = Barang::findOrFail($id);

    //     Barang::where('id', $id)->update([
    //         'nama_barang' => $request->nama_barang,
    //         'jumlah' => $request->jumlah,
    //         'spesifikasi' => $request->spesifikasi,
    //         'keterangan' => $request->keterangan,
    //         'harga' => $request->harga,
    //     ]);

    //     return redirect('toko_slawi/barang')->with('success', 'Berhasil memperbarui barang');
    // }


    // public function cetakqrcode($id)
    // {
    //     $barangs = Barang::find($id);
    //     $pdf = app('dompdf.wrapper');
    //     $pdf->loadView('toko_slawi.barang.cetak_pdf', compact('barangs'));
    //     $pdf->setPaper('letter', 'portrait');
    //     return $pdf->stream('QrCodeBarang.pdf');
    // }

    // public function show($id)
    // {


    //     $barang = Barang::where('id', $id)->first();
    //     return view('toko_slawi/barang.show', compact('barang'));
    // }


    public function destroy($id)
    {
        $tipe = Pelanggan::find($id);
        $tipe->delete();

        return redirect('toko_slawi/pelanggan')->with('success', 'Berhasil menghapus barang');
    }
}