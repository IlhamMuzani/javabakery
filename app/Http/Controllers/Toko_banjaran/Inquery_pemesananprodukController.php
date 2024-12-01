<?php

namespace App\Http\Controllers\Toko_banjaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use App\Models\Pelanggan;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Barang;
use App\Models\Detailbarangjadi;
use App\Models\Detailpemesananproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Metodepembayaran;
use App\Models\Pemesananproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class Inquery_pemesananprodukController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pemesanan = $request->tanggal_pemesanan;
        $tanggal_akhir = $request->tanggal_akhir;

        $inquery = Pemesananproduk::with('toko')->where('toko_id', 1); // Menambah filter toko_id = 1

        if ($status) {
            $inquery->where('status', $status);
        }

        if ($tanggal_pemesanan && $tanggal_akhir) {
            $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
        } elseif ($tanggal_pemesanan) {
            $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
            $inquery->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->where('tanggal_pemesanan', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $inquery->whereDate('tanggal_pemesanan', Carbon::today());
        }

        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();

        return view('toko_banjaran.inquery_pemesananproduk.index', compact('inquery'));
    }

    public function unpost_pemesananproduk($id)
    {
        $item = Pemesananproduk::where('id', $id)->first();


        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'unpost'
        ]);
        return back()->with('success', 'Berhasil');
    }

    public function posting_pemesananproduk($id)
    {
        $item = Pemesananproduk::where('id', $id)->first();


        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
        return back()->with('success', 'Berhasil');
    }

    public function create() {}



    public function store(Request $request) {}



    public function show($id)
    {
        //
    }

    // public function edit($id)
    //     {
    //         $pelanggans = Pelanggan::all();
    //         $tokoslawis = Tokoslawi::all();
    //         $tokos = Toko::all();

    //         $produks = Produk::with('tokoslawi')->get();
    //         $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
    //         $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

    //         return view('toko_slawi.inquery_pemesananproduk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks' ,'selectedTokoId'));
    //     }
    public function edit($id)
    {
        // Mengambil semua data yang diperlukan
        $pelanggans = Pelanggan::all();
        $tokoslawis = Tokoslawi::all();
        $tokos = Toko::all();
        $produks = Produk::with('tokobanjaran')->get();

        $inquery = Pemesananproduk::where('id', $id)->first();
        $details = Detailpemesananproduk::where('pemesananproduk_id', $inquery->id)->get();

        // ID toko yang dipilih
        $selectedTokoId = $inquery->toko_id;

        $metodes = Metodepembayaran::all();

        // Mengembalikan view dengan data yang diperlukan
        return view('toko_banjaran.inquery_pemesananproduk.update', compact('inquery', 'details', 'tokos', 'pelanggans', 'tokoslawis', 'produks', 'selectedTokoId', 'metodes'));
    }

    public function update(Request $request, $id)
    {
        // Validasi pelanggan
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'nama_pelanggan' => 'required',
                'telp' => 'required',
                'alamat' => 'required',
                'kategori' => 'required',
            ],
            [
                'nama_pelanggan.required' => 'Masukkan nama pelanggan',
                'telp.required' => 'Masukkan telepon',
                'alamat.required' => 'Masukkan alamat',
                'kategori.required' => 'Pilih kategori pelanggan',
            ]
        );

        // Handling errors for pelanggan
        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        // Handling errors for pesanans
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
                    array_push($error_pesanans, "Barang no " . ($i + 1) . " belum dilengkapi!");
                }

                $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
                $kode_produk = is_null($request->kode_produk[$i]) ? '' : $request->kode_produk[$i];
                $nama_produk = is_null($request->nama_produk[$i]) ? '' : $request->nama_produk[$i];
                $kode_lama = is_null($request->kode_lama[$i]) ? '' : $request->kode_lama[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $diskon = is_null($request->diskon[$i]) ? '' : $request->diskon[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'detail_id' => $request->detail_ids[$i] ?? null,
                    'kode_produk' => $kode_produk,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
                    'kode_lama' => $kode_lama,
                    'jumlah' => $jumlah,
                    'diskon' => $diskon,
                    'harga' => $harga,
                    'total' => $total,
                ]);
            }
        }

        // Handling errors for pelanggans or pesanans
        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with(
                    'error_pesanans',
                    $error_pesanans
                )
                ->with('data_pembelians', $data_pembelians);
        }

        // Update pemesanan yang ada
        $pemesanan = Pemesananproduk::find($id);
        $pemesanan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'metode_id' => $request->metode_id,
            'sub_total' => str_replace(',', '.', str_replace('.', '', $request->sub_total)),
            'nama_penerima' => $request->nama_penerima,
            'telp_penerima' => $request->telp_penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'toko_id' => $request->toko,
            'kode_pemesanan' => $request->kode_pemesanan,
            'toko_id' => '1',
            'status' => 'posting',
        ]);


        // Simpan atau perbarui detail pemesanan
        foreach ($data_pembelians as $data_pesanan) {
            $detailId = $data_pesanan['detail_id'];
            if ($detailId) {
                // Jika detail sudah ada, perbarui
                Detailpemesananproduk::where('id', $detailId)->update([
                    'produk_id' => $data_pesanan['produk_id'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'kode_lama' => $data_pesanan['kode_lama'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                ]);
            } else {

                $existingDetail = Detailpemesananproduk::where([
                    'produk_id' => $data_pesanan['produk_id'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'kode_lama' => $data_pesanan['kode_lama'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                ])->first();

                if (!$existingDetail) {
                    Detailpemesananproduk::create([
                        'pemesananproduk_id' => $pemesanan->id,
                        'produk_id' => $data_pesanan['produk_id'],
                        'kode_produk' => $data_pesanan['kode_produk'],
                        'nama_produk' => $data_pesanan['nama_produk'],
                        'kode_lama' => $data_pesanan['kode_lama'],
                        'jumlah' => $data_pesanan['jumlah'],
                        'diskon' => $data_pesanan['diskon'],
                        'harga' => $data_pesanan['harga'],
                        'total' => $data_pesanan['total'],
                    ]);
                }
            }
        }

        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = Detailpemesananproduk::where('pemesananproduk_id', $pemesanan->id)->get();

        // Redirect ke halaman indeks pemesananproduk
        return redirect('toko_banjaran/inquery_pemesananproduk');
    }


    public function deletedetail($id)
    {
        $item = Detailpemesananproduk::find($id);

        $item->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }


    public function destroy($id)
    {
        //
    }
}