<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang; // Import model Barang untuk mengambil data barang
use App\Models\Detailbarangjadi;
use App\Models\Klasifikasi;
use App\Models\Produk;
use App\Models\Subsub; // Import model Subsub untuk mengambil data subsub
use App\Models\Tokoslawi;

class InputController extends Controller
{
    public function index()
    {
        
        $barangs = Barang::all();
        $produks = Produk::all();
        $subs1 = Subsub::all();
        $details = Detailbarangjadi::all();
        $klasifikasi = Klasifikasi::all();
        $tokoslawis = Tokoslawi::all();
        $inputs = Input::with('barang')->get();
        return view('admin/input.index', compact('inputs', 'barangs', 'produks', 'subs1', 'details','klasifikasi', 'tokoslawis'));
    }

    // show as data
    public function show(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $barangs = Barang::all();
        $subs1 = Subsub::all();
        $inputs = Input::with('details.subsub.subklasifikasi.klasifikasi')
        ->whereDate('tanggal', $tanggal)
        ->get();
        return view('admin.input.data', compact('inputs', 'barangs', 'subs1'));
    }
   
   
    public function kode()
    {
        $faktur = Input::all();
        if ($faktur->isEmpty()) {
            $num = "000001";
        } else {
            $id = Input::getId();
            foreach ($id as $value);
            $idlm = $value->id;
            $idbr = $idlm + 1;
            $num = sprintf("%06s", $idbr);
        }

        $data = 'JB';
        $kode_faktur = $data . $num;
        return $kode_faktur;
    }
    
    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                // 'kode_faktur' => 'required',
                'tanggal' => 'required',
                'cabang' => 'required',
                'sub_total' => 'required',
                'catatan' => 'required',
                // 'tanggal_pengiriman' => 'required',
            ],
            [
                'kode_faktur.required' => 'Pilih no_faktur',
                'tanggal.required' => 'Pilih Pelanggan',
                'cabang.required' => 'Masukkan grand total',
            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('produk_id')) {
            for ($i = 0; $i < count($request->produk_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'kode_produk.' . $i => 'required',
                    'produk_id.' . $i => 'required',
                    'nama_produk.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'total.' . $i => 'required',
                 
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang no " . ($i + 1) . " belum dilengkapi!"); // Corrected the syntax for concatenation and indexing
                }

                $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
                $kode_produk = is_null($request->kode_produk[$i]) ? '' : $request->kode_produk[$i];
                $nama_produk = is_null($request->nama_produk[$i]) ? '' : $request->nama_produk[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];
             

                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'total' => $total,
             
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

      
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');
        $kode = $this->kode();
        $tanggal = Carbon::now()->format('Y-m-d');

        $cetakpdf = Input::create([
            // 'user_id' => auth()->user()->id,
            // 'kode_faktur' => $kode,
            'tanggal' => $request->tanggal,
            'cabang' => $request->cabang,
            'sub_total' => $request->sub_total,
            'catatan' => $request->catatan,
            // 'tanggal_pengiriman' => $request->tanggal_pengiriman,
           
        ]);

        $transaksi_id = $cetakpdf->id;

        if ($cetakpdf) {
            foreach ($data_pembelians as $data_pesanan) {
                $detailTagihan = Detailbarangjadi::create([
                    'input_id' => $cetakpdf->id,
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'produk_id' => $data_pesanan['produk_id'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'harga' => $data_pesanan['harga'],
                    // 'no_po' => $data_pesanan['no_po'],
                    'total' => $data_pesanan['total'],
            
                  
                ]);

                Input::where('id', $detailTagihan->input_id);
            }
        }

        $details = Detailbarangjadi::where('input_id', $cetakpdf->id)->get();
        return back()->with('success', 'Berhasil menambahkan barang jadi');;
    }





//     public function kode()
// {
//     $input = Input::all();

//     if ($input->isEmpty()) {
//         $num = 1; // Jika tidak ada data, mulai dari nomor 1
//     } else {
//         // Ambil ID terakhir dari input
//         $lastId = $input->last()->id;
//         $num = $lastId + 1; // Nomor urut berikutnya
//     }

//     // Mendapatkan tahun saat ini
//     $tahun = date('Y');

//     // Format kode pelanggan
//     $prefix = 'JB'; // Prefix untuk kode pelanggan
//     $formattedNum = sprintf("%06d", $num); // Format nomor urut dengan panjang 6 digit angka
//     $kode_pelanggan = $prefix . $tahun . $formattedNum;

//     return $kode_pelanggan;
// }

// public function store(Request $request)
// {
//     $validasi_pelanggan = Validator::make(
//         $request->all(),
//         [
//             'no_faktur' => 'required',
//             'tanggal' => 'required',
//             'cabang' => 'required',
//             'sub_total' => 'required',
//             'catatan' => 'required',
//             'tanggal_pengiriman' => 'required',
//         ],
//         [
//             'no_faktur.required' => 'Pilih no_faktur',
//             'tanggal.required' => 'Pilih Pelanggan',
//             'cabang.required' => 'Masukkan grand total',
//         ]
//     );

//     if ($validasi_pelanggan->fails()) {
//         return back()
//             ->withInput()
//             ->withErrors($validasi_pelanggan)
//             ->with('error_pesanans', []);
//     }

//     $data_pembelians = collect();

//     if ($request->has('produk_id')) {
//         for ($i = 0; $i < count($request->barang_id); $i++) {
//             $validasi_produk = Validator::make($request->all(), [
//                 'kode_produk.' . $i => 'required',
//                 'barang_id.' . $i => 'required',
//                 'nama_barang.' . $i => 'required',
//                 'harga.' . $i => 'required',
//                 'total.' . $i => 'required',
//             ]);

//             if ($validasi_produk->fails()) {
//                 return back()
//                     ->withInput()
//                     ->withErrors($validasi_produk)
//                     ->with('error_pesanans', ["Barang no " . ($i + 1) . " belum dilengkapi!"]);
//             }

//             $data_pembelians->push([
//                 'kode_produk' => $request->kode_produk[$i],
//                 'barang_id' => $request->barang_id[$i],
//                 'nama_barang' => $request->nama_barang[$i],
//                 'jumlah' => $request->jumlah[$i],
//                 'harga' => $request->harga[$i],
//                 'total' => $request->total[$i],
//             ]);
//         }
//     }

//     // Simpan data input
//     $input = Input::create([
//         'no_faktur' => $request->no_faktur,
//         'tanggal' => $request->tanggal,
//         'cabang' => $request->cabang,
//         'sub_total' => $request->sub_total,
//         'catatan' => $request->catatan,
//         'tanggal_pengiriman' => $request->tanggal_pengiriman,
//     ]);

//     // Simpan detail barang jadi
//     if ($input) {
//         foreach ($data_pembelians as $data_pesanan) {
//             Detailbarangjadi::create([
//                 'input_id' => $input->id,
//                 'kode_produk' => $data_pesanan['kode_produk'],
//                 'barang_id' => $data_pesanan['barang_id'],
//                 'nama_barang' => $data_pesanan['nama_barang'],
//                 'jumlah' => $data_pesanan['jumlah'],
//                 'harga' => $data_pesanan['harga'],
//                 'total' => $data_pesanan['total'],
//             ]);
//         }
//     }

//     // Redirect back with success message or errors
//     return back()->with('success', 'Data berhasil disimpan.');
// }

}