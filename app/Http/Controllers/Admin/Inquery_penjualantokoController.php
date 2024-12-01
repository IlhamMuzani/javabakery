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


class Inquery_penjualantokoController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter tanggal dari request
        $tanggalPenjualan = $request->input('tanggal_setoran');
        $tanggalAkhir = $request->input('tanggal_akhir');

        // Ambil semua data setoran penjualan dengan filter tanggal jika ada
        $setoranPenjualans = Setoran_penjualan::when($tanggalPenjualan, function ($query) use ($tanggalPenjualan, $tanggalAkhir) {
            return $query->whereDate('tanggal_setoran', '>=', $tanggalPenjualan)
                ->whereDate('tanggal_setoran', '<=', $tanggalAkhir ?? $tanggalPenjualan);
        })
            ->orderBy('id', 'DESC')
            ->get();

        // Kirim data ke view
        return view('admin.inquery_penjualantoko.index', compact('setoranPenjualans'));
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

    public function print($id)
    {
        // Ambil data setoran penjualan berdasarkan id yang dipilih
        $setoran = Setoran_penjualan::with('toko')->findOrFail($id);

        // Pastikan data toko terkait tersedia
        $cabang = $setoran->toko->nama_toko ?? 'Cabang Tidak Diketahui';
        $alamat = $setoran->toko->alamat ?? 'Cabang Tidak Diketahui';

        // Load view untuk PDF dan kirimkan data
        $pdf = FacadePdf::loadView('admin.inquery_penjualantoko.print', compact('setoran', 'cabang', 'alamat'));

        // Return PDF stream agar langsung bisa ditampilkan
        return $pdf->stream('setoran_penjualan.pdf');
    }

    public function edit(Request $request, $id)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $kasir = $request->kasir;

        // Ambil semua data produk, toko, kasir, klasifikasi untuk dropdown
        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $kasirs = Penjualanproduk::select('kasir')->distinct()->get();

        // Buat query dasar untuk menghitung total penjualan kotor
        $query = Penjualanproduk::query();

        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan tanggal penjualan
        if ($tanggal_penjualan && $tanggal_akhir) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }

        // Filter berdasarkan kasir
        if ($kasir) {
            $query->where('kasir', $kasir);
        }

        // Hitung total penjualan kotor
        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp", ""), ".", "") AS UNSIGNED)'));

        // Hitung total diskon penjualan (nominal_diskon)
        $diskon_penjualan = $query->sum('nominal_diskon');

        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

        // Query terpisah untuk menghitung total deposit masuk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
            if ($tanggal_penjualan && $tanggal_akhir) {
                $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            }
            if ($kasir) {
                $q->where('kasir', $kasir);
            }
        })->sum('dp_pemesanan');

        // Hitung total deposit keluar
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($kasir, $tanggal_penjualan, $tanggal_akhir) {
            if ($tanggal_penjualan && $tanggal_akhir) {
                $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
            }
            if ($kasir) {
                $q->where('kasir', $kasir);
            }
        })->sum('dp_pemesanan');

        // Hitung total dari berbagai metode pembayaran
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));

        $qris = Penjualanproduk::where('metode_id', 17)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));

        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));

        $transfer = Penjualanproduk::where('metode_id', 3)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));

        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;


        $penjualan = Setoran_penjualan::where('id', $id)->first();

        return view('admin.inquery_penjualantoko.update', compact(
            'produks',
            'tokos',
            'klasifikasis',
            'kasirs',
            'penjualan_kotor',
            'diskon_penjualan',
            'penjualan_bersih',
            'deposit_masuk',
            'total_penjualan',
            'mesin_edc',
            'qris',
            'gobiz',
            'transfer',
            'total_setoran',
            'deposit_keluar',
            'penjualan'
        ));
    }


    public function update(Request $request, $id)
    {
        // Validasi input dengan custom error messages
        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'total_setoran' => 'required',
            'toko_id' => 'required|exists:tokos,id', // Validasi bahwa toko_id harus ada di tabel tokos
        ], [
            // Custom error messages
            'tanggal_penjualan.required' => 'Tanggal penjualan tidak boleh kosong.',
            'total_setoran.required' => 'Total setoran tidak boleh kosong.',
            'toko_id.required' => 'Toko harus dipilih.',
            'toko_id.exists' => 'Toko yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Fungsi untuk menghilangkan format angka
        $removeFormat = function ($value) {
            return (int)str_replace(['.', ','], '', $value); // Hilangkan titik dan koma
        };

        $penjualan = Setoran_penjualan::findOrFail($id);
        // Simpan data ke database
        $penjualan->update([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'penjualan_kotor' => $removeFormat($request->penjualan_kotor),
            'diskon_penjualan' => $removeFormat($request->diskon_penjualan),
            'penjualan_bersih' => $removeFormat($request->penjualan_bersih),
            'deposit_keluar' => $removeFormat($request->deposit_keluar),
            'deposit_masuk' => $removeFormat($request->deposit_masuk),
            'total_penjualan' => $removeFormat($request->total_penjualan),
            'mesin_edc' => $removeFormat($request->mesin_edc),
            'qris' => $removeFormat($request->qris),
            'gobiz' => $removeFormat($request->gobiz),
            'transfer' => $removeFormat($request->transfer),
            'total_setoran' => $removeFormat($request->total_setoran),
            'toko_id' => $request->toko_id, // Ambil nilai toko_id dari request
            'status' => 'posting',
        ]);

        // Update status penjualanproduk menjadi 'selesai' berdasarkan toko_id dan tanggal_penjualan
        Penjualanproduk::where('toko_id', $request->toko_id)
            ->whereDate('tanggal_penjualan', $request->tanggal_penjualan)
            ->update(['status' => 'selesai']);

        // Redirect ke halaman show dengan pesan sukses
        return redirect()->route('penjualan_toko.show', $penjualan->id)
            ->with('success', 'Data berhasil disimpan dan status penjualan berhasil diperbarui!');
    }


    public function show($id)
    {
        $setoran = Setoran_penjualan::findOrFail($id);
        return view('admin.inquery_penjualantoko.show', compact('setoran'));
    }


    public function unpost_penjualantoko($id)
    {
        // Ambil data setoran_penjualan berdasarkan ID
        $setoranPenjualan = Setoran_penjualan::where('id', $id)->first();

        if ($setoranPenjualan) {
            // Update status pada setoran_penjualan menjadi 'unpost'
            $setoranPenjualan->update([
                'status' => 'unpost',
            ]);

            // Update status pada penjualanproduk
            $affectedRows = Penjualanproduk::where('toko_id', $setoranPenjualan->toko_id)
                ->whereDate('tanggal_penjualan', $setoranPenjualan->tanggal_penjualan) // Gunakan whereDate
                ->update(['status' => 'posting']);

            // Periksa apakah data berhasil diperbarui
            if ($affectedRows > 0) {
                return back()->with('success', 'Status berhasil diubah menjadi unpost, dan status penjualanproduk diubah menjadi posting.');
            } else {
                return back()->with('error', 'Tidak ada data penjualanproduk yang sesuai untuk diperbarui.');
            }
        }

        return back()->with('error', 'Data setoran tidak ditemukan.');
    }

    public function posting_penjualantoko($id)
    {
        // Ambil data setoran_penjualan berdasarkan ID
        $setoranPenjualan = Setoran_penjualan::where('id', $id)->first();

        if ($setoranPenjualan) {
            // Update status pada setoran_penjualan menjadi 'unpost'
            $setoranPenjualan->update([
                'status' => 'posting',
            ]);

            // Update status pada penjualanproduk
            $affectedRows = Penjualanproduk::where('toko_id', $setoranPenjualan->toko_id)
                ->whereDate('tanggal_penjualan', $setoranPenjualan->tanggal_penjualan) // Gunakan whereDate
                ->update(['status' => 'selesai']);

            // Periksa apakah data berhasil diperbarui
            if ($affectedRows > 0) {
                return back()->with('success', 'Status berhasil diubah menjadi unpost, dan status penjualanproduk diubah menjadi posting.');
            } else {
                return back()->with('error', 'Tidak ada data penjualanproduk yang sesuai untuk diperbarui.');
            }
        }

        return back()->with('error', 'Data setoran tidak ditemukan.');
    }
}