<?php

namespace App\Http\Controllers\Toko_slawi;

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
use App\Models\Detailpenjualanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Dppemesanan;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Metodepembayaran;
use App\Models\Pelunasan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Stok_tokoslawi;
use App\Models\Stokpesanan_tokoslawi;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class Inquery_pelunasanslawiController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pelunasan = $request->tanggal_pelunasan;
        $tanggal_akhir = $request->tanggal_akhir;

        // Modify the query to include relationships
        $inquery = Pelunasan::with(['metodePembayaran', 'dppemesanan.pemesananproduk'])
            ->whereHas('dppemesanan.pemesananproduk', function ($query) {
                $query->where('toko_id', 3);
            });

        // Filter by status if provided
        if ($status) {
            $inquery->where('status', $status);
        }

        // Handle date filtering for pelunasan
        if ($tanggal_pelunasan && $tanggal_akhir) {
            $tanggal_pelunasan = Carbon::parse($tanggal_pelunasan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereBetween('tanggal_pelunasan', [$tanggal_pelunasan, $tanggal_akhir]);
        } elseif ($tanggal_pelunasan) {
            $tanggal_pelunasan = Carbon::parse($tanggal_pelunasan)->startOfDay();
            $inquery->where('tanggal_pelunasan', '>=', $tanggal_pelunasan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->where('tanggal_pelunasan', '<=', $tanggal_akhir);
        } else {
            // Default to today's pelunasan if no date is provided
            $inquery->whereDate('tanggal_pelunasan', Carbon::today());
        }

        // Order by id in descending order
        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();

        return view('toko_slawi.inquery_pelunasanslawi.index', compact('inquery'));
    }


    public function edit(Request $request, $id)
    {
        $inquery = Pelunasan::where('id', $id)->first();
        $penjualan = PenjualanProduk::where('id', $inquery->penjualanproduk_id)->first();
        $detail_penjualans = DetailPenjualanProduk::where('penjualanproduk_id', $penjualan->id)->get();
        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
        $tokos = Toko::all();
        $pemesananproduks = Pemesananproduk::all();
        $metodes = Metodepembayaran::all();

        // Filter produk berdasarkan nama klasifikasi
        $produks = Produk::with(['tokotegal', 'klasifikasi'])
            ->whereHas('klasifikasi', function ($query) {
                $query->whereIn('nama', ['FREE MAINAN', 'FREE PACKAGING', 'BAKERY']);
            })
            ->get();

        $dppemesanans = Dppemesanan::whereHas('pemesananproduk', function ($query) {
            $query->where('toko_id', 3);
        })->get();

        $kategoriPelanggan = 'member';


        return view('toko_slawi.inquery_pelunasanslawi.update', compact(
            'inquery',
            'barangs',
            'tokos',
            'produks',
            'detail_penjualans',
            'details',
            'tokoslawis',
            'pelanggans',
            'kategoriPelanggan',
            'dppemesanans',
            'pemesananproduks',
            'metodes'
        ));
    }


    public function posting_penjualanproduk($id)
    {
        $item = Pelunasan::find($id);


        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
        return back()->with('success', 'Berhasil');
    }

    public function unpost_penjualanproduk($id)
    {
        $item = Pelunasan::find($id);

        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'unpost'
        ]);
        return back()->with('success', 'Berhasil');
    }

    public function show($id)
    {
        // Retrieve the specific pemesanan by ID along with its details
        $penjualan = Penjualanproduk::with('detailpenjualanproduk', 'toko')->findOrFail($id);

        // Retrieve all pelanggans (assuming you need this for the view)
        $pelanggans = Pelanggan::all();
        $tokos = $penjualan->toko;

        // Pass the retrieved data to the view
        return view('toko_slawi.inquery_pelunasanslawi.show', compact('penjualan', 'pelanggans', 'tokos'));
    }

    public function cetakPdf($id)
    {
        $penjualan = Penjualanproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();


        $tokos = $penjualan->toko;

        $pdf = FacadePdf::loadView('toko_slawi.inquery_pelunasanslawi.cetak-pdf', compact('penjualan', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('penjualan.pdf');
    }

    // public function edit($id)
    // {
    //     $pelanggans = Pelanggan::all();
    //     $tokoslawis = Tokoslawi::all();
    //     $tokos = Toko::all();

    //     $produks = Produk::with('tokoslawi')->get();
    //     $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();
    //     $selectedTokoId = $inquery->toko_id; // ID toko yang dipilih

    //     return view('toko_slawi.inquery_pemesananproduk.update', compact('inquery', 'tokos', 'pelanggans', 'tokoslawis', 'produks', 'selectedTokoId'));
    // }

    public function update(Request $request, $id)
    {
        // Validasi pelanggan
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'dppemesanan_id' => 'required',
                'pelunasan' => 'required',
            ],
            [
                'dppemesanan_id.required' => 'pilih deposit',
                'pelunasan.required' => 'masukan pembayaran',
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
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        // Update pemesanan yang ada
        $pemesanan = Pelunasan::find($id);
        $pemesanan->update([
            'dppemesanan_id' => $request->dppemesanan_id,
            'penjualanproduk_id' =>  $request->penjualanproduk_id,
            'pelunasan' => str_replace(',', '.', str_replace('.', '', $request->pelunasan)),
            'metode_id' => $request->metode_id,
            'total_fee' => str_replace(',', '.', str_replace('.', '', $request->total_fee)),
            'keterangan' => $request->keterangan,
            'kembali' => str_replace(',', '.', str_replace('.', '', $request->kembali)),
            'dp_pemesanan' => str_replace(',', '.', str_replace('.', '', $request->dp_pemesanan)),
            'total_fee' => str_replace(',', '.', str_replace('.', '', $request->total_fee)),
            'toko_id' => '1',
            'status' => 'posting',
        ]);

        $id_penjualan = $request->penjualanproduk_id;
        $penjualan = PenjualanProduk::where('id', $id_penjualan)->first();

        $penjualan->update([
            'dppemesanan_id' => $request->dppemesanan_id,
            'nama_pelanggan' =>  $request->nama_pelanggan,
            'kode_pelanggan' => $request->kode_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'sub_total' => str_replace(',', '.', str_replace('.', '', $request->sub_total)),
            'sub_totalasli' => str_replace(',', '.', str_replace('.', '', $request->grand_total)),
            'nominal_diskon' => str_replace(',', '.', str_replace('.', '', $request->nominal_diskon)),
            'total_fee' => str_replace(',', '.', str_replace('.', '', $request->total_fee)),
            'keterangan' => $request->keterangan,
            'metode_id' => $request->metode_id,
            'kembali' => str_replace(',', '.', str_replace('.', '', $request->kembali)),
            'bayar' => str_replace(',', '.', str_replace('.', '', $request->bayar)),
            'toko_id' => '1',
            'status' => 'posting',
        ]);


        $transaksi_id = $penjualan->id;
        $detailIds = $request->input('detail_ids');

        foreach ($data_pembelians as $data_pesanan) {
            $detailId = $data_pesanan['detail_id'];

            if ($detailId) {
                $detail = DetailPenjualanProduk::where('penjualanproduk_id', $penjualan->id)
                    ->where('kode_produk', $data_pesanan['kode_produk'])
                    ->first();

                $produk = Produk::find($detail->produk_id);

                if (
                    in_array($produk->klasifikasi_id, [15, 16]) ||
                    ($produk->klasifikasi_id == 13 && in_array($produk->kode_lama, ['KU001', 'M0003']))
                ) {
                    // Pengurangan stok untuk stok_tokoslawi
                    $stok = Stok_tokoslawi::where('produk_id', $detail->produk_id)->first();
                } else {
                    // Jika tidak, kurangi stok dari stokpesanan_tokoslawi
                    $stok = Stokpesanan_tokoslawi::where('produk_id', $detail->produk_id)->first();
                }

                if ($stok) {
                    // Kurangi stok tanpa memeriksa apakah stok mencukupi
                    $stok->jumlah -= $detail->jumlah;
                    $stok->save();
                } else {
                    // Jika stok tidak ditemukan, buat stok baru dengan nilai negatif
                    if (
                        in_array($produk->klasifikasi_id, [15, 16]) ||
                        ($produk->klasifikasi_id == 13 && in_array($detail->kode_lama, ['KU001', 'M0003']))
                    ) {
                        Stok_tokoslawi::create([
                            'produk_id' => $detail->produk_id,
                            'jumlah' => -$detail->jumlah,
                        ]);
                    } else {
                        Stokpesanan_tokoslawi::create([
                            'produk_id' => $detail->produk_id,
                            'jumlah' => -$detail->jumlah,
                        ]);
                    }
                }

                DetailPenjualanProduk::where('id', $detailId)->update([
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
                $existingDetail = DetailPenjualanProduk::where([
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
                    DetailPenjualanProduk::create([
                        'penjualanproduk_id' => $penjualan->id,
                        'produk_id' => $data_pesanan['produk_id'],
                        'kode_produk' => $data_pesanan['kode_produk'],
                        'nama_produk' => $data_pesanan['nama_produk'],
                        'kode_lama' => $data_pesanan['kode_lama'],
                        'jumlah' => $data_pesanan['jumlah'],
                        'diskon' => $data_pesanan['diskon'],
                        'harga' => $data_pesanan['harga'],
                        'total' => $data_pesanan['total'],
                    ]);

                    $detail = DetailPenjualanProduk::where('penjualanproduk_id', $penjualan->id)
                        ->where('kode_produk', $data_pesanan['kode_produk'])
                        ->first();

                    // return;
                    $produk = Produk::find($detail->produk_id);

                    if (
                        in_array($produk->klasifikasi_id, [15, 16]) ||
                        ($produk->klasifikasi_id == 13 && in_array($produk->kode_lama, ['KU001', 'M0003']))
                    ) {
                        // Pengurangan stok untuk stok_tokoslawi
                        $stok = Stok_tokoslawi::where('produk_id', $detail->produk_id)->first();
                    } else {
                        // Jika tidak, kurangi stok dari stokpesanan_tokoslawi
                        $stok = Stokpesanan_tokoslawi::where('produk_id', $detail->produk_id)->first();
                    }

                    if ($stok) {
                        // Kurangi stok tanpa memeriksa apakah stok mencukupi
                        $stok->jumlah -= $detail->jumlah;
                        $stok->save();
                    } else {
                        // Jika stok tidak ditemukan, buat stok baru dengan nilai negatif
                        if (
                            in_array($produk->klasifikasi_id, [15, 16]) ||
                            ($produk->klasifikasi_id == 13 && in_array($detail->kode_lama, ['KU001', 'M0003']))
                        ) {
                            Stok_tokoslawi::create([
                                'produk_id' => $detail->produk_id,
                                'jumlah' => -$detail->jumlah,
                            ]);
                        } else {
                            Stokpesanan_tokoslawi::create([
                                'produk_id' => $detail->produk_id,
                                'jumlah' => -$detail->jumlah,
                            ]);
                        }
                    }
                }
            }
        }

        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = DetailPenjualanProduk::where('penjualanproduk_id', $pemesanan->id)->get();

        // Redirect ke halaman indeks pemesananproduk
        return redirect('toko_slawi/inquery_pelunasanslawi');
    }


    public function deletedetail($id)
    {
        $item = DetailPenjualanProduk::find($id);

        $item->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }

    public function destroy($id)
    {
        //
    }
}