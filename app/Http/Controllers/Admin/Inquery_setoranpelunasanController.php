<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Pelunasan_penjualan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Inquery_setoranpelunasanController extends Controller
{


    public function index(Request $request)
    {
        $tokos = Toko::all();

        $status = $request->status;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->input('toko_id');

        $inquery = Pelunasan_penjualan::query();

        if ($status) {
            $inquery->where('status', $status);
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $inquery->whereBetween('tanggal_awal', [$tanggal_awal, $tanggal_akhir]);
        } elseif ($tanggal_awal) {
            $inquery->where('tanggal_awal', '>=', $tanggal_awal);
        } elseif ($tanggal_akhir) {
            $inquery->where('tanggal_awal', '<=', $tanggal_akhir);
        } else {
            $inquery->whereDate('tanggal_awal', Carbon::today());
        }

        if ($toko_id) {
            $inquery->where('toko_id', $toko_id);
        }

        $inquery->orderBy('id', 'DESC');
        $inquery = $inquery->get();
        return view('admin.inquery_setoranpelunasan.index', compact('inquery', 'tokos'));
    }


    public function getdata(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'tanggal_penjualan' => 'required|date',
        ]);

        // Ambil tanggal dari request
        $tanggalPenjualan = $request->input('tanggal_penjualan');

        // Query untuk menghitung penjualan kotor
        $penjualan_kotor = Penjualanproduk::whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)'));

        // Query untuk menghitung diskon penjualan dari detailpenjualanproduks
        $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
        })->get()->sum(function ($detail) {
            // Menghitung total diskon
            $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga); // Hapus "Rp." dan "." dari harga
            $jumlah = $detail->jumlah;
            $diskon = $detail->diskon / 100; // Ubah diskon persen menjadi desimal

            return $harga * $jumlah * $diskon; // Hitung diskon
        });

        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

        // Hitung total deposit keluar
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
        })->sum('dp_pemesanan');

        // Hitung total deposit masuk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggalPenjualan) {
            $q->whereDate('tanggal_pemesanan', $tanggalPenjualan);
        })->sum('dp_pemesanan');

        // Hitung total dari berbagai metode pembayaran
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $qris = Penjualanproduk::where('metode_id', 17)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $transfer = Penjualanproduk::where('metode_id', 3)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        // Hitung total penjualan
        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;

        // Kembalikan hasil dalam format JSON untuk diproses di frontend
        return response()->json([
            'penjualan_kotor' => number_format($penjualan_kotor, 0, ',', '.'),
            'diskon_penjualan' => number_format($diskon_penjualan, 0, ',', '.'),
            'penjualan_bersih' => number_format($penjualan_bersih, 0, ',', '.'),
            'deposit_keluar' => number_format($deposit_keluar, 0, ',', '.'),
            'deposit_masuk' => number_format($deposit_masuk, 0, ',', '.'),
            'mesin_edc' => number_format($mesin_edc, 0, ',', '.'),
            'qris' => number_format($qris, 0, ',', '.'),
            'gobiz' => number_format($gobiz, 0, ',', '.'),
            'transfer' => number_format($transfer, 0, ',', '.'),
            'total_penjualan' => number_format($total_penjualan, 0, ',', '.'),
            'total_metode' => number_format($total_metode, 0, ',', '.'),
            'total_setoran' => number_format($total_setoran, 0, ',', '.'),
        ]);
    }


    public function edit(Request $request, $id)
    {
        $tokos = Toko::all();
        $setoranPenjualans = Setoran_penjualan::orderBy('id', 'DESC')->get();
        $pelunasan = Pelunasan_penjualan::where('id', $id)->first();
        // dd($setoranPenjualans); // Periksa isi variabel sebelum dikirim ke view
        return view('admin.inquery_setoranpelunasan.update', compact('setoranPenjualans', 'tokos', 'pelunasan'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'setoran_penjualan_id' => 'required',
                'mesin_edc1' => 'required',
                'qris1' => 'required',
                'gobiz1' => 'required',
                'transfer1' => 'required',
                'total_setoran1' => 'required',
            ],
            [
                'setoran_penjualan_id.required' => 'Pilih Faktur',
                'mesin_edc1.required' => 'Masukkan nominal mesin edc',
                'qris1.required' => 'Masukkan nominal qris',
                'gobiz1.required' => 'Masukkan nominal gobiz',
                'transfer1.required' => 'Masukkan nominal transfer',
                'total_setoran1.required' => 'Masukkan nominal setoran tunai',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $pelunasan = Pelunasan_penjualan::findOrFail($id);

        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');

        $tanggal = Carbon::now()->format('Y-m-d');
        $pelunasan->update([
            'setoran_penjualan_id' => $request->setoran_penjualan_id,
            'no_fakturpenjualantoko' => $request->no_fakturpenjualantoko,
            'toko_id' => $request->toko_id,
            'mesin_edc1' => str_replace(',', '.', str_replace('.', '', $request->mesin_edc1)),
            'qris1' => str_replace(',', '.', str_replace('.', '', $request->qris1)),
            'gobiz1' => str_replace(',', '.', str_replace('.', '', $request->gobiz1)),
            'transfer1' => str_replace(',', '.', str_replace('.', '', $request->transfer1)),
            'total_setoran1' => str_replace(',', '.', str_replace('.', '', $request->total_setoran1)),
            'mesinedc_selisih' => str_replace(',', '.', str_replace('.', '', $request->mesinedc_selisih)),
            'qris_selisih' => str_replace(',', '.', str_replace('.', '', $request->qris_selisih)),
            'gobiz_selisih' => str_replace(',', '.', str_replace('.', '', $request->gobiz_selisih)),
            'transfer_selisih' => str_replace(',', '.', str_replace('.', '', $request->transfer_selisih)),
            'totalsetoran_selisih' => str_replace(',', '.', str_replace('.', '', $request->totalsetoran_selisih)),
            'tanggal_awal' =>  $tanggal,
            'status' => 'posting',
        ]);

        $cetakpdf = Pelunasan_penjualan::where('id', $id)->first();

        return view('admin.inquery_setoranpelunasan.show', compact('cetakpdf'));
    }


    public function print($id)
    {
        // Ambil data setoran penjualan berdasarkan id yang dipilih
        $setoran = Pelunasan_penjualan::findOrFail($id);

        // Load view untuk PDF dan kirimkan data
        $pdf = FacadePdf::loadView('admin.inquery_setoranpelunasan.print', compact('setoran'));

        // Return PDF stream agar langsung bisa ditampilkan
        return $pdf->stream('setoran_penjualan.pdf');
    }

    public function unpost_setorantunai($id)
    {
        $item = Pelunasan_penjualan::find($id);

        $item->update([
            'status' => 'unpost'
        ]);

        return response()->json(['success' => 'Berhasil unpost']);
    }


    public function posting_setorantunai($id)
    {
        $item = Pelunasan_penjualan::find($id);

        $item->update([
            'status' => 'posting'
        ]);

        return response()->json(['success' => 'Berhasil memposting']);
    }

    public function show($id)
    {
        $cetakpdf = Pelunasan_penjualan::where('id', $id)->first();

        return view('admin.inquery_setoranpelunasan.show', compact('cetakpdf'));
    }

    public function updateStatus(Request $request)
    {
        // Ambil id setoran dari request
        $setoran_id = $request->input('id');

        // Cari setoran_penjualan berdasarkan id
        $setoran = Setoran_penjualan::find($setoran_id);

        if ($setoran) {
            // Update status menjadi 'posting'
            $setoran->status = 'posting';

            // Update nilai nominal_setoran, nominal_setoran2, tanggal_setoran, dan tanggal_setoran2
            $setoran->plusminus = $request->input('plusminus');
            $setoran->nominal_setoran = $request->input('nominal_setoran');
            $setoran->nominal_setoran2 = $request->input('nominal_setoran2');
            $setoran->tanggal_setoran = $request->input('tanggal_setoran');
            $setoran->tanggal_setoran2 = $request->input('tanggal_setoran2');

            // Simpan perubahan ke database
            $setoran->save();

            // Redirect atau response dengan pesan sukses
            return redirect()->route('inquery_setoranpelunasan.index')->with('success', 'Sukses');
        }

        // Jika setoran tidak ditemukan
        return redirect()->back()->with('error', 'Setoran tidak ditemukan');
    }
}
