<?php

namespace App\Http\Controllers\Toko_banjaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
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
use App\Models\Pemesananproduk;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use App\Models\Toko;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class PemesananprodukbanjaranController extends Controller
{

    public function index(Request $request)
    {
        $today = Carbon::today();

        $inquery = Pemesananproduk::with('dppemesanan') // Memuat relasi dppemesanan
            ->where('toko_id', 1) // Hanya tampilkan data dengan toko_id = 1
            ->where(function ($query) use ($today) {
                $query->whereDate('created_at', $today)
                    ->orWhere(function ($query) use ($today) {
                        $query->where('status', 'unpost')
                            ->whereDate('created_at', '<', $today);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('toko_banjaran.pemesanan_produk.index', compact('inquery'));
    }

    public function pelanggan($id)
    {
        $user = Pelanggan::where('id', $id)->first();

        return json_decode($user);
    }

    public function create(Request $request)
    {
        $search = $request->input('search'); // Ambil input pencarian
        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
        $tokobanjaran = Tokobanjaran::all();
        $tokos = Toko::all();
        $metodes = Metodepembayaran::all();

        $produks = Produk::with(['tokobanjaran', 'stokpesanan_tokobanjaran'])->get();

        $kategoriPelanggan = 'member';

        return view('toko_banjaran.pemesanan_produk.create', compact(
            'barangs',
            'metodes',
            'tokos',
            'produks',
            'details',
            'tokoslawis',
            'pelanggans',
            'kategoriPelanggan',
            'tokobanjaran',
            'search'
        ));
    }

    public function getCustomerByKode($kode)
    {
        $customer = Pelanggan::where('kode_pelanggan', $kode)->first();
        if ($customer) {
            return response()->json($customer);
        }
        return response()->json(['message' => 'Customer not found'], 404);
    }

    public function getCustomerData(Request $request)
    {
        $qrcode_pelanggan = $request->qrcode_pelanggan;

        // Query untuk mengambil data pelanggan berdasarkan qrcode_pelanggan
        $customer = Pelanggan::where('qrcode_pelanggan', $qrcode_pelanggan)->first();

        if ($customer) {
            // Jika data ditemukan, kembalikan data dalam bentuk JSON
            return response()->json([
                'nama_pelanggan' => $customer->nama_pelanggan,
                'telp' => $customer->telp,
                'alamat' => $customer->alamat,
            ]);
        } else {
            // Jika data tidak ditemukan, kembalikan respons kosong atau sesuaikan dengan kebutuhan
            return response()->json([
                'error' => 'Data pelanggan tidak ditemukan.',
            ], 404);
        }
    }



    public function store(Request $request)
    {
        // Validasi pelanggan
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'nama_pelanggan' => 'required',
                'telp' => 'nullable',
                'alamat' => 'nullable',
                'kategori' => 'required',
                'tanggal_kirim' => 'required', // Tambahkan ini
            ],
            [
                'nama_pelanggan.required' => 'Masukkan nama pelanggan',
                'telp.nullable' => 'Masukkan telepon',
                'alamat.required' => 'Masukkan alamat',
                'kategori.required' => 'Pilih kategori pelanggan',
                'tanggal_kirim.required' => 'Tanggal pengambilan harus diisi', // Tambahkan ini
            ]
        );

        // Handling errors for pelanggan
        $error_pelanggans = [];
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
                $produk_id = $request->produk_id[$i] ?? '';
                $catatanproduk = $request->catatanproduk[$i] ?? '';
                $kode_produk = $request->kode_produk[$i] ?? '';
                $kode_lama = $request->kode_lama[$i] ?? '';
                $nama_produk = $request->nama_produk[$i] ?? '';
                $jumlah = $request->jumlah[$i] ?? '';
                $diskon = $request->diskon[$i] ?? '';
                $harga = $request->harga[$i] ?? '';
                $total = $request->total[$i] ?? '';

                $nominal_diskon = ($harga * ($diskon / 100)) * $jumlah;

                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'kode_lama' => $kode_lama,
                    'produk_id' => $produk_id,
                    'catatanproduk' => $catatanproduk,
                    'nama_produk' => $nama_produk,
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
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }


        $kode = $this->kode();
        $tanggal_kirim = $request->tanggal_kirim . ' ' . $request->waktu_kirim;
        $format = 'd/m/Y H:i';
        $tanggal_kirim = Carbon::createFromFormat($format, $request->tanggal_kirim)->format('Y-m-d H:i:s');

        // Buat pemesanan baru
        $cetakpdf = Pemesananproduk::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'kode_pelanggan' => $request->kode_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'sub_total' => preg_replace('/[^0-9]/', '', $request->sub_total),
            'catatan' => $request->catatan,
            'nama_penerima' => $request->nama_penerima,
            'telp_penerima' => $request->telp_penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'tanggal_kirim' => $tanggal_kirim,
            'toko_id' => '1',
            // 'toko_id' =>$request->toko_id,
            'kasir' => ucfirst(auth()->user()->karyawan->nama_lengkap),
            'metode_id' => $request->metode_id,
            'sub_totalasli' => $request->sub_totalasli,
            'total_fee' => $request->total_fee,
            'keterangan' => $request->keterangan,
            'kode_pemesanan' => $kode,
            'qrcode_pemesanan' => 'https://javabakery.id/pemesanan/' . $kode,
            'tanggal_pemesanan' => Carbon::now('Asia/Jakarta'),
            'status' => 'posting',
            'nominal_diskon' => $nominal_diskon, // Simpan total nominal diskon
        ]);

        // Simpan detail pemesanan
        if ($cetakpdf) {
            foreach ($data_pembelians as $data_pesanan) {
                Detailpemesananproduk::create([
                    'pemesananproduk_id' => $cetakpdf->id,
                    'produk_id' => $data_pesanan['produk_id'],
                    'catatanproduk' => $data_pesanan['catatanproduk'],
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'kode_lama' => $data_pesanan['kode_lama'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                ]);
            }

            // Simpan DP dan Kekurangan ke tabel dppemesanans
            Dppemesanan::create([
                'pemesananproduk_id' => $cetakpdf->id,
                'kode_dppemesanan' => $this->kodedp(), // Panggil fungsi kode untuk mendapatkan kode_dppemesanan
                'dp_pemesanan' => preg_replace('/[^0-9]/', '', $request->dp_pemesanan),
                'kekurangan_pemesanan' => preg_replace('/[^0-9]/', '', $request->kekurangan_pemesanan),
                'tanggal_dp' => Carbon::now('Asia/Jakarta'),
            ]);
        }

        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = Detailpemesananproduk::where('pemesananproduk_id', $cetakpdf->id)->get();

        // Kirimkan URL untuk tab baru
        $pdfUrl = route('toko_banjaran.pemesanan_produk.cetak-pdf', ['id' => $cetakpdf->id]);

        // Return response dengan URL PDF
        return response()->json([
            'success' => 'Transaksi Berhasil',
            'pdfUrl' => $pdfUrl,
        ]);
    }



    public function cetak($id)
    {
        // Mengambil pemesanan tertentu berdasarkan ID beserta detailnya
        $pemesanan = Pemesananproduk::with('detailpemesananproduk', 'toko', 'dppemesanan')->findOrFail($id);

        // Mengambil semua pelanggans (diasumsikan Anda memerlukan ini untuk view)
        $pelanggans = Pelanggan::all();
        $tokos = $pemesanan->toko;
        $dp = $pemesanan->dppemesanan;

        // Mengirim data yang diambil ke view
        return view('toko_banjaran.pemesanan_produk.cetak', compact('pemesanan', 'pelanggans', 'tokos', 'dp'));
    }

    public function cetakPdf($id)
    {
        $pemesanan = Pemesananproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();

        $dp = $pemesanan->dppemesanan;
        $tokos = $pemesanan->toko;

        $pdf = FacadePdf::loadView('toko_banjaran.pemesanan_produk.cetak-pdf', compact('pemesanan', 'tokos', 'pelanggans', 'dp'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('pemesanan.pdf');
    }

    public function kode()
    {
        $prefix = 'PC';
        $year = date('y'); // Dua digit terakhir dari tahun
        $date = date('dm'); // Format bulan dan hari: MMDD

        // Mengambil kode retur terakhir yang dibuat pada hari yang sama
        $lastBarang = Pemesananproduk::where('kode_pemesanan', 'LIKE', $prefix . '%')
            ->whereDate('tanggal_pemesanan', Carbon::today())
            ->orderBy('kode_pemesanan', 'desc')
            ->first();

        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_pemesanan;
            $lastNum = (int) substr($lastCode, strlen($prefix . $date . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }

        $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
        $newCode = $prefix . $date  . $year . $formattedNum;
        return $newCode;
    }

    public function kodedp()
    {
        $prefix = 'DPC';
        $year = date('y'); // Dua digit terakhir dari tahun
        $date = date('dm'); // Format bulan dan hari: MMDD

        // Mengambil kode retur terakhir yang dibuat pada hari yang sama
        $lastBarang = Dppemesanan::whereDate('tanggal_dp', Carbon::today())
            ->orderBy('kode_dppemesanan', 'desc')
            ->first();

        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_dppemesanan;
            $lastNum = (int) substr($lastCode, strlen($prefix . $date . $year)); // Mengambil urutan terakhir
            $num = $lastNum + 1;
        }

        $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
        $newCode = $prefix . $date . $year  . $formattedNum;
        return $newCode;
    }

    public function show($id)
    {
        // Mengambil pemesanan tertentu berdasarkan ID beserta detailnya
        $pemesanan = Pemesananproduk::with('detailpemesananproduk', 'toko', 'dppemesanan')->findOrFail($id);

        // Mengambil semua pelanggans (diasumsikan Anda memerlukan ini untuk view)
        $pelanggans = Pelanggan::all();
        $tokos = $pemesanan->toko;
        $dp = $pemesanan->dppemesanan;

        // Mengirim data yang diambil ke view
        return view('toko_banjaran.pemesanan_produk.cetak', compact('pemesanan', 'pelanggans', 'tokos', 'dp'));
    }

    public function edit($id)
    {
        // Mengambil semua data yang diperlukan
        $pelanggans = Pelanggan::all();
        $tokoslawis = Tokoslawi::all();
        $tokos = Toko::all();
        $produks = Produk::with('tokoslawi')->get();

        // Mengambil data pemesananproduk dengan detailpemesananproduk dan dppemesanans yang terkait
        $inquery = Pemesananproduk::with(['detailpemesananproduk', 'dppemesanans'])->findOrFail($id);


        // ID toko yang dipilih
        $selectedTokoId = $inquery->toko_id;

        $metodes = Metodepembayaran::all();

        // Mengembalikan view dengan data yang diperlukan
        return view('toko_banjaran.pemesanan_produk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks', 'selectedTokoId', 'metodes'));
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
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $diskon = is_null($request->diskon[$i]) ? '' : $request->diskon[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
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
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        // Update pemesanan yang ada
        $pemesanan = Pemesananproduk::find($id);
        $pemesanan->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'sub_total' => $request->sub_total,
            'nama_penerima' => $request->nama_penerima,
            'telp_penerima' => $request->telp_penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'tanggal_kirim' => $request->tanggal_kirim,
            'toko_id' => $request->toko,
            // 'kode_pemesanan' => $request->kode_pemesanan,
            // 'qrcode_pemesanan' => 'https://javabakery.id/pemesanan/' . $this->kode(),
            // 'tanggal_pemesanan' => Carbon::now('Asia/Jakarta'),
            'status' => 'posting',
        ]);

        // Simpan atau perbarui detail pemesanan
        foreach ($data_pembelians as $data_pesanan) {
            $detail = Detailpemesananproduk::where('pemesananproduk_id', $pemesanan->id)
                ->where('kode_produk', $data_pesanan['kode_produk'])
                ->first();

            if ($detail) {
                // Jika detail sudah ada, perbarui
                $detail->update([
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                ]);
            } else {
                // Jika detail belum ada, buat baru
                Detailpemesananproduk::create([
                    'pemesananproduk_id' => $pemesanan->id,
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                ]);
            }
        }

        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = Detailpemesananproduk::where('pemesananproduk_id', $pemesanan->id)->get();

        // Redirect ke halaman indeks pemesananproduk
        return redirect('toko_banjaran/pemesanan_produk');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pemesanan = Pemesananproduk::findOrFail($id);

            // Menghapus (soft delete) detail pemesanan terkait
            DetailPemesananProduk::where('pemesananproduk_id', $id)->delete();

            // Menghapus (soft delete) data pemesanan
            $pemesanan->delete();
        });

        return redirect('toko_banjaran/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
    }
}
